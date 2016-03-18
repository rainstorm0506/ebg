<?php

/**
 * 商品订单 模型类
 * @author jeson.Q 
 * 
 * @table orders
 */
class Store extends ApiModels {
	
	/**
	 * 获得 商家商品订单信息
	 * return array|boolean
	 * @param int $uid 
	 */
	public function getStoreOrderData($uid) {
		$orderInfo = array ();
		//判断是否是会员操作
		if ($uid) {
			$orderInfo['todayOrders'] 		= $this->selectOrderData($uid,1);
			$orderInfo['waitSendOrders'] 	= $this->selectOrderData($uid,2);
			$orderInfo['backMoney'] 		= $this->selectOrderData($uid,3);
			//$orderInfo['todayVisits'] 		= $this->getStoreVisit($uid); //暂无统计表
			$orderInfo['todayVisits'] 		= 0;
			
			return $orderInfo;
		} else {
			return false;
		}
	}

	/**
	 * 按条件统计订单数
	 * return array|boolean
	 * @param int $type 
	 */
	public function selectOrderData($uid, $type = 1) {
		$orderInfo = array ();$where = '';
		//判断是否是会员操作
		if ($uid) {
			switch($type){
				case 1:	$where .= " AND create_time > ".strtotime(date('Y-m-d').' 00:00:00')." AND create_time < ".strtotime(date('Y-m-d').' 23:59:59'); break;
				case 2: $where .= " AND order_status_id = 103 "; break;
				case 3: $where .= " AND order_status_id = 108 "; break;
			}
			$sql = "SELECT count(*) as cnt FROM orders WHERE merchant_id = {$uid} {$where}";
			$orderInfo = $this->queryRow($sql);

			return empty($orderInfo) ? 0 : $orderInfo['cnt'];
		} else {
			return false;
		}
	}

	/**
	 * 按统计商家店铺商品今日访客
	 * return array|boolean
	 * @param int $uid 
	 */
	public function getStoreVisit($uid) {
		$userVisitInfo = array ();
		//判断是否是会员操作
		if ($uid) {
			$sql = "SELECT count(*) as cnt FROM user_history WHERE user_id = {$uid}";
			$userVisitInfo = $this->queryRow($sql);

			return empty($userVisitInfo) ? 0 : $userVisitInfo['cnt'];
		} else {
			return false;
		}
	}
}
