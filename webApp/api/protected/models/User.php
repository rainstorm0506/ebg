<?php
class User extends WebApiModels
{
	public function find(UserForm $form)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('user' , array('password'=>GlobalUser::hashPassword($form->password_1)) , "`phone`={$this->quoteValue($form->phone)} AND user_type<3");
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	public function sign(UserForm $form)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$reCode = trim($form->reCode);
			$reUid = GlobalUser::getReUid($reCode);
			
			$this->insert('user' , array(
				'source'		=> 3,
				'user_type'		=> $form->type,
				'phone'			=> $form->phone,
				'password'		=> GlobalUser::hashPassword($form->password_1),
				'user_code'		=> $form->phone,
				're_code'		=> $reCode,
				're_uid'		=> $reUid,
				'reg_time'		=> time(),
				'status_id'		=> $form->type == 1 ? 510 : 613,
				'audit_time'	=> 0,
				'nickname'		=> '',
				'realname'		=> '',
				'face'			=> '',
				'fraction'		=> 0,
				'exp'			=> 0,
				'money'			=> 0,
				'merchant_id'	=> 0,
				'remark'		=> 0
			));
			$uid = $this->getInsertId();
			
			if ($form->type == 2)
			{
				$this->insert('user_company' , array(
					'uid'					=> $uid,
					'com_name'				=> trim($form->com_name),
					'dict_one_id'			=> (int)$form->one_id,
					'dict_two_id'			=> (int)$form->two_id,
					'dict_three_id'			=> (int)$form->three_id,
					'com_address'			=> trim($form->com_address),
					'com_num'				=> trim($form->com_num),
					'com_property'			=> trim($form->com_property),
					'com_license'			=> trim($form->com_license),
					'com_tax'				=> trim($form->com_tax),
					'com_org'				=> trim($form->com_org),
					'com_license_timeout'	=> strtotime($form->com_license_timeout),
				));
			}
			
			$this->insert('act_recommended_recode' , array(
				'user_id'		=> $reUid,
				'register_id'	=> $uid,
				'time'			=> time()
			));
			
			$transaction->commit();
			//注册成功后发送短信
			SmsNote::sendOne($form->phone , '恭喜您成为e办公会员：服务连接你我，精彩办公生活。e办公中小企业一站式办公采购平台，我们专注同城一小时送达，慢必赔！就比某东少1元，你懂得！更多详情请登陆 www.ebangon.com');
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
}