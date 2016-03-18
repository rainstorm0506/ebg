<?php

/**
 * 收货地址模型类
 * @author jeson.Q 
 * 
 * @table user_address
 */
class MyAddress extends SModels
{
	/**
	 * 查询 当前用户下所有收货地址-列表
	 *
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid)
	{
		$addressList = array();
		$uid = (int)$uid;
		
		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT ua.*, 
				(SELECT name FROM dict WHERE id = ua.dict_one_id) as provice, 
				(SELECT name FROM dict WHERE id = ua.dict_two_id) as city,
				(SELECT name FROM dict WHERE id = ua.dict_three_id) as county 
				FROM user_address ua WHERE ua.user_id = {$uid} ORDER BY ua.id DESC";
			$addressList = $this->queryAll($sql);

		}
		return $addressList;
	}

	/**
	 * 统计当前用户提现总数
	 * @param int $uid
	 * @return int
	 * @throws Exception
	 */
	public function getTotalNumber($uid)
	{
		$sql = '';
		// 判断是否关键字收索
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT count(wr.id) FROM withdraw_record wr
					LEFT JOIN withdraw_account wa ON wr.aid = wa.id
					WHERE wa.uid = {$uid}";
		}
		return (int)$this->queryScalar($sql);
	}

	/**
	 * 增加用户收货地址
	 * @param array $post
	 * @param int $uid
	 * @return boolean|static[]
	 * @throws Exception
	 */
	public function addAddress(array $post,$uid)
	{
		$field = array();
		if ($post && $uid)
		{
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$aid = $post['aid'] ? (int)$post['aid'] : '';
				$is_default = isset($post['is_default']) ? (int)$post['is_default'] : '';
				if($is_default){
					$this->execute("UPDATE user_address SET is_default = 0");
				}
				// 组装数据
				$field['is_default'] = $is_default ? $is_default : 0;
				$field['user_id'] = (int)$uid;
				$field['phone'] = trim($post['phone']);
				$field['consignee'] = trim($post['consignee']);
				$field['dict_one_id'] = (int)$post['dict_one_id'];
				$field['dict_two_id'] = (int)$post['dict_two_id'];
				$field['dict_three_id'] = (int)$post['dict_three_id'];
				$field['address'] = trim($post['address']);
				if($aid){
					$flag = $this->update('user_address' , $field , " id = {$aid}");
				}else{
					$flag = $this->insert('user_address' , $field );
				}
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
			
		}else{
			return false;
		}
	}

	/**
	 * 统计当前用户提现总数
	 * @param int $uid
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function setAddressDef(array $post, $uid)
	{
		// 判断是否为用户
		if ($uid)
		{
			$is_default = $aid = 0;
			// 执行事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$is_default = $post['type'] ? (int)$post['type'] : 0;
				$aid = $post['aid'] ? (int)$post['aid'] : 0;
				if($is_default){
					$this->execute("UPDATE user_address SET is_default = 0");
					$flag = $this->update('user_address' , array('is_default' => $is_default), " id = {$aid}");
				}
				$transaction->commit();
				return true;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		return (int)$this->queryScalar($sql);
	}

	/**
	 * 查询 当前用户下单个收货地址-详细信息
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getAddressData(array $post, $uid)
	{
		$addressInfo = array();
		$uid = (int)$uid;
		
		// 判断是否为用户
		if ($uid)
		{
			$aid = $post['aid'] ? (int)$post['aid'] : 0;
			// 组装sql 语句
			$sql = "SELECT ua.*, 
				(SELECT name FROM dict WHERE id = ua.dict_one_id) as provice, 
				(SELECT name FROM dict WHERE id = ua.dict_two_id) as city,
				(SELECT name FROM dict WHERE id = ua.dict_three_id) as county 
				FROM user_address ua WHERE ua.user_id = {$uid} AND ua.id = {$aid}";
			$addressInfo = $this->queryRow($sql);

		}
		return $addressInfo;
	}
}
