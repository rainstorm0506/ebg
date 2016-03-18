<?php
class EsEnterprise extends SModels
{
	public function signEnterprise(array $post, $uid)
	{
		$time = time();$userInfo = array();
		if($uid){
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$userInfo['user_type'] = 2;
				$userInfo['status_id'] = 613;
				$this->update('user' , $userInfo , "id={$uid}");

				$this->insert('user_company' , array(
					'uid'					=> $uid,
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
				return false;
			}
		}
	}
	
	public function checkCompanyName($companyName , $id = 0)
	{
		if (!$companyName) return false;
		$SQL = $id ? "AND uid!={$id}" : '';
		return (boolean)$this->queryRow("SELECT uid FROM user_company WHERE `com_name`={$this->quoteValue($companyName)} {$SQL}");
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
}