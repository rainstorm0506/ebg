<?php
class Home extends WebModels
{
	public function findPassword(array $post , array $userType)
	{
		$_x = 0;
		foreach ($userType as $k => $v)
			$userType[$k] = (++$_x);
		
		$ut = empty($userType[$post['ut']]) ? 0 : (int)$userType[$post['ut']];
		
		return $this->update(
			'user' ,
			array('password'=>GlobalUser::hashPassword($post['password'])) ,
			"user_type={$ut} AND phone={$post['phone']}"
		);
	}
	
	public function signMerchant(array $post)
	{
		$time = time();
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$phone = trim($post['phone']);
			$this->insert('user' , array(
				'source'		=> 1,
				'user_type'		=> 3,
				'phone'			=> $phone,
				'password'		=> GlobalUser::hashPassword($post['password']),
				'reg_time'		=> $time,
				'last_time'		=> $time,
				'status_id'		=> 713,
				'user_code'		=> 'S'.$phone,
				're_code'		=> '',
				're_uid'		=> 0,
			));
			$userID = $this->getInsertId();
			
			$this->insert('user_merchant' , array(
				'uid'				=> $userID,
				'mer_no'			=> GlobalUser::getReCode($userID , $time),
				'mer_name'			=> trim($post['mer_name']),
				'mer_card'			=> trim($post['mer_card']),
				'mer_card_front'	=> $this->getPhotos($post['mer_card_front'] , 'mer_card'),
				'mer_card_back'		=> $this->getPhotos($post['mer_card_back'] , 'mer_card'),
			));
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	public function signMember(array $post)
	{
		$time = time();
		$phone = trim($post['phone']);
		$reCode = trim($post['reCode']);
		$reUid = GlobalUser::getReUid($reCode);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('user' , array(
				'source'		=> 1,
				'user_type'		=> 1,
				'phone'			=> $phone,
				'password'		=> GlobalUser::hashPassword($post['password']),
				'reg_time'		=> $time,
				'last_time'		=> $time,
				'status_id'		=> 510,
				'user_code'		=> $phone,
				're_code'		=> $reCode,
				're_uid'		=> $reUid,
			));
			
			$userID = $this->getInsertId();
			
			# 行为处理 - 个人注册(被推荐) & 个人推荐
			UserAction::signAction($userID, $reUid);
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	public function signEnterprise(array $post)
	{
		$time = time();
		$phone = trim($post['phone']);
		$reCode = trim($post['reCode']);
		$reUid = GlobalUser::getReUid($reCode);
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('user' , array(
				'source'		=> 1,
				'user_type'		=> 2,
				'phone'			=> $phone,
				'password'		=> GlobalUser::hashPassword($post['password']),
				'reg_time'		=> $time,
				'last_time'		=> $time,
				'status_id'		=> 613,
				'user_code'		=> $phone,
				're_code'		=> $reCode,
				're_uid'		=> $reUid,
			));
			$userID = $this->getInsertId();
			
			# 行为处理 - 企业注册(被推荐) & 推荐
			UserAction::signAction($userID , $reUid);
			
			$this->insert('user_company' , array(
				'uid'					=> $userID,
				'com_name'				=> trim($post['companyName']),
				'dict_one_id'			=> intval($post['dictOneId']),
				'dict_two_id'			=> intval($post['dictTwoId']),
				'dict_three_id'			=> intval($post['dictThreeId']),
				'com_address'			=> trim($post['companyAddress']),
				'com_num'				=> trim($post['companyNumber']),
				'com_property'			=> trim($post['companyType']),
				'com_license'			=> $this->getPhotos($post['com_license'] , 'user_company'),
				'com_tax'				=> $this->getPhotos($post['com_tax'] , 'user_company'),
				'com_org'				=> $this->getPhotos($post['com_org'] , 'user_company'),
				'com_license_timeout'	=> strtotime($post['expireTime']),
			));
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	public function checkUserPhone($phone , $isMerchant)
	{
		return (boolean)$this->getUserInfo($phone, $isMerchant);
	}
	
	public function checkCompanyName($companyName , $id = 0)
	{
		if (!$companyName) return false;
		$SQL = $id ? "AND uid!={$id}" : '';
		return (boolean)$this->queryRow("SELECT uid FROM user_company WHERE `com_name`={$this->quoteValue($companyName)} {$SQL}");
	}
	
	/**
	 * 更新用户的登录时间
	 * @param	int		$uid		用户ID
	 */
	public function userLoginTime($uid)
	{
		return $this->update('user' , array('last_time'=>time()) , "id={$uid}");
	}
	
	/**
	 * 得到管理员的信息
	 * @param		string		$phone			手机号码
	 * @param		int			$isMerchant		是否是商家
	 * @return		array
	 */
	public function getUserInfo($phone , $isMerchant)
	{
		if (!$phone) return array();
		$SQL = $isMerchant ? "AND user_type=3" : "AND user_type!=3";
		return $this->queryRow("SELECT * FROM user WHERE `phone`={$this->quoteValue($phone)} {$SQL}");
	}
	
	/**
	 * 获取推荐我的人
	 *
	 * @return string
	 */
	public function getReCode($phone) 
	{
		$sql = "SELECT `re_code` FROM `user` WHERE `phone`=:phone";
		$res = $this->queryRow($sql, true, array(':phone' => $phone));
		
		return isset($res['re_code']) ? $res['re_code'] : '';
	}
}