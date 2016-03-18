<?php
class Merchants extends ApiModels
{
	public function getMerInfo()
	{
		if ($merID = $this->getMerchantID())
			return $this->queryRow("SELECT m.*,u.status_id,u.user_code FROM user_merchant AS m INNER JOIN `user` AS u ON m.uid = u.id WHERE m.uid={$merID}");
		return array();
	}
	
	public function getUserInfoByID($uid)
	{
	    if(!$uid)return array();
	    
	    return $this->queryRow("SELECT * FROM `user` WHERE `id`={$this->quoteValue($uid)} AND user_type=3");
	}
	
	/**
	 * 得到的信息
	 * @param		string		$phone		用户手机号码
	 * @return		array
	 */
	public function getUserInfo($phone)
	{
		if (!$phone) return array();
		return $this->queryRow("SELECT * FROM `user` WHERE `phone`={$this->quoteValue($phone)} AND user_type=3");
	}
	
	/**
	 * 更新用户的登录时间
	 * @param	int		$uid		用户ID
	 */
	public function userLoginTime($uid)
	{
		return $this->update('user' , array('last_time'=>time()) , "id={$uid}");
	}
	
	public function findPassword(array $post)
	{
		$this->update('user' , array('password'=>GlobalUser::hashPassword($post['password'])) , "user_type=3 AND phone={$this->quoteValue($post['phone'])}");
		return true;
	}
	
	public function sign(array $post)
	{
		$time = time();
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('user' , array(
				'source'		=> 2,
				'user_type'		=> 3,
				'phone'			=> (string)$post['phone'],
				'password'		=> GlobalUser::hashPassword($post['password']),
				'reg_time'		=> $time,
				'last_time'		=> 0,
				'status_id'		=> 713,
			));
			$userID = $this->getInsertId();
			$this->update('user' , array('user_code'=>GlobalUser::getReCode($userID , $time)) , "id={$userID}");
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	//修改头像
	public function modifyFace($id,$face){
		$face=$this->getPhotos($face,'face',$id);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('user_merchant',array('store_avatar'=>$face),'uid='.$id);
			$this->update('user',array('face'=>$face),'id='.$id);
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}

	public function checkUserPhone($phone)
	{
		if (!$phone) return false;
		return (boolean)$this->queryRow("SELECT id FROM `user` WHERE `phone`={$this->quoteValue($phone)} AND user_type=3");
	}
}