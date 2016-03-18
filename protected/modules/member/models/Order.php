<?php

/**
 * 商品订单 模型类
 * @author jeson.Q 
 * 
 * @table content
 */
class Order extends SModels {
	
	/**
	 * 获得 商品订单信息
	 * return array|boolean
	 * @param int $id 
	 */
	public function getActiveInfo($order_sn,$isOrderList = 0) {
		$orderInfo = array ();
		if ($order_sn) {
			$sql = "SELECT o.*, opl.pay_port, opl.pay_time, opl.pay_money, s.pre_status, s.user_title, s.back_title, s.merchant_title, oe.user_shoot, oe.addressee_shoot, oe.cons_address, oe.express_name, oe.express_no, oe.system_remark, oe.user_remark,oe.delivery_way, ex.id as ex_id, ex.firm_name, um.uid, um.store_name, um.store_address,u.id as users_id,u.re_uid FROM orders o 
				lEFT JOIN order_pay_log opl ON o.order_sn = opl.order_sn
				lEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
				lEFT JOIN express ex ON oe.express_id = ex.id
				lEFT JOIN status s ON o.order_status_id = s.id
				lEFT JOIN user_merchant um ON o.merchant_id = um.uid
				lEFT JOIN user u ON o.user_id = u.id
				WHERE o.order_sn = '{$order_sn}' ";
			$orderInfo = $this->queryRow($sql);
			if(!$isOrderList){
				$orderInfo['goods'] = $this->getGoodsList($orderInfo['order_sn']);
				$orderInfo['logs'] 	= $this->getLogList($orderInfo['order_sn']);
				$orderInfo['pay_logs'] 	= $this->getPayLogList($orderInfo['order_sn']);
			}
			return $orderInfo;
		} else {
			return false;
		}
	}

	/**
	 * 查询 订单列表
	 * 
	 * @param int $status
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($status = 0, $offset = 0, $limit = 10)
	{
		$allOrder = $orderInfo = $orderDataAll = array();
		$sql = $where = $orderBy = $limits ='';
		// 判断是否条件搜索
		$where = $status ? ($status == 108 ? ' AND ( o.order_status_id = 102 OR o.order_status_id = '.(int)$status.')' : ' AND o.order_status_id='.(int)$status): '';
		$uid = (int)$this->getUid();
		if($uid){
			// 组装sql 语句
			$limits = " limit {$offset},{$limit} ";
			$orderBy = " ORDER BY o.create_time DESC";
			$sql = "SELECT o.id,o.order_sn,o.order_status_id,o.parent_order_sn,o.create_time,o.order_money,o.freight_money,o.is_pay,o.pay_type,opl.pay_port, opl.pay_time, opl.pay_money, s.pre_status,s.user_title, s.merchant_title , oe.user_shoot, oe.addressee_shoot, oe.is_evaluate, oe.delivery_way, oe.cons_name, um.uid, um.store_name, um.store_address
					FROM orders o 
					lEFT JOIN status s ON o.order_status_id = s.id
					lEFT JOIN order_pay_log opl ON o.order_sn = opl.order_sn
					lEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
					lEFT JOIN user_merchant um ON o.merchant_id = um.uid
					WHERE 1=1 AND o.user_id = {$uid} AND o.user_delete = 0 {$where} {$orderBy} {$limits}";
			$orderInfo = $this->queryAll($sql);
			
			if($orderInfo)
			{
				foreach ($orderInfo as $key => $val)
				{
					$time = 0;
					if($val['parent_order_sn'] && $val['parent_order_sn'] != 1){
						$allOrder[$val['parent_order_sn']]['info'] =$this->getActiveInfo($val['parent_order_sn'],1);
						$allOrder[$val['parent_order_sn']]['childrenOrder'][$val['order_sn']] = $val;
						$allOrder[$val['parent_order_sn']]['childrenOrder'][$val['order_sn']]['goods'] = $this->getGoodsList($val['order_sn']);
					}elseif($val['parent_order_sn'] == 1){
						$allOrder[$val['order_sn']]['info'] = $val;
					}else{
						$allOrder[$val['order_sn']]['info'] = $val;
						$allOrder[$val['order_sn']]['goods'] = $this->getGoodsList($val['order_sn']);
					}
				}
				$orderDataAll['orderList'] 		= 	$allOrder;
			}
			$orderDataAll['orderStatus'] 	= 	$this->getOrderStatusList($uid);
		}
		return $orderDataAll;
	}

	/**
	 * 查询 订单列表
	 *
	 * @param int $status
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getOrderStatusList($uid) {
		$statusInfo = $orderStatus = array();$sql = '';
		if($uid){
			$sql = " SELECT parent_order_sn,order_status_id FROM orders WHERE user_id = {$uid} AND user_delete = 0 AND parent_order_sn <> 1 ";
			$statusInfo = $this->queryAll($sql);
			
			if($statusInfo)
			{
				foreach ($statusInfo as $key => $val)
				{
					if($val['parent_order_sn'] != 1){
						$orderStatus[$val['order_status_id']] = (isset($orderStatus[$val['order_status_id']])?(int)$orderStatus[$val['order_status_id']]:0)+1;
					}
				}
				$orderStatus['allCount'] = count($statusInfo);
			}
		}
		return $orderStatus;
	}

	/**
	 * 查询  子订单列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getChildrenList($order_sn) {
		$allOrder = $orderInfo = array();
		$sql = $where ='';
		if($order_sn)
		{
			$where = " WHERE o.parent_order_sn = '{$order_sn}' AND o.user_delete = 0 ";
			// 组装sql 语句
			$sql = "SELECT o.id,o.order_sn,o.parent_order_sn,o.create_time,o.order_money,o.is_pay,o.freight_money,opl.pay_port, opl.pay_time, opl.pay_money,s.pre_status,s.back_title,s.merchant_title,oe.user_shoot,oe.addressee_shoot,um.uid, um.store_name, um.store_address FROM orders o
			lEFT JOIN status s ON o.order_status_id = s.id
			lEFT JOIN order_pay_log opl ON o.order_sn = opl.order_sn
			lEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
			lEFT JOIN user_merchant um ON o.merchant_id = um.uid
			{$where}";
			$orderInfo = $this->queryAll($sql);
			if($orderInfo)
			{
				foreach ($orderInfo as $key => $val)
				{
					$allOrder[$val['order_sn']] = $val;
					$allOrder[$val['order_sn']]['goods'] = $this->getGoodsList($val['order_sn']);
				}
			}
		}
		return $allOrder;
	}

	/**
	 *
	 * 统计 订单总数
	 * 
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($status = array()) {
		$sql = $where = '';
		// 判断是否条件搜索
		$where = $status ? ($status == 108 ? ' AND ( order_status_id = 102 OR order_status_id = '.(int)$status.')' : ' AND order_status_id='.(int)$status): '';
		$uid = (int)$this->getUid();
		if($uid){
			// 组装sql 语句
			$sql = "SELECT count(*) FROM orders WHERE 1=1 AND user_id = {$uid} AND user_delete = 0 {$where}";
			return (int)$this->queryScalar($sql);
		}
		return;
	}
	
	/**
	 * 查询 订单操作日志 列表
	 *
	 * @param string $order_sn
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getLogList($order_sn) {
		$logInfo = array();$sql = '';
		if($order_sn)
		{
			// 组装sql 语句
			$sql = "SELECT ol.id, ol.operate_type, ol.logs, ol.time, ol.memo, bg.true_name FROM order_log ol
					lEFT JOIN back_governor bg ON ol.operate_id = bg.id 
					WHERE ol.order_sn = '{$order_sn}' ORDER BY time DESC";
			
			$logInfo = $this->queryAll($sql);
		}
		return $logInfo;
	}
	
	/**
	 * 查询 订单操作日志 列表
	 *
	 * @param string $order_sn
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getPayLogList($order_sn) {
		$payLogInfo = array();$sql = '';
		if($order_sn)
		{
			// 组装sql 语句
			$sql = "SELECT oal.id, oal.trade_no, oal.pay_money, oal.pay_time, oal.pay_verify, oal.pay_port FROM order_pay_log oal 
			WHERE oal.order_sn = '{$order_sn}' ORDER BY pay_time DESC";

			$payLogInfo = $this->queryAll($sql);
		}
		return $payLogInfo;
	}
	
	/**
	 * 查询 订单商品 列表
	 * @param string $order_sn
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getGoodsList($order_sn) {
		$goodData = array();$sql = $where = '';
		if($order_sn)
		{
			$where = " WHERE order_sn = '{$order_sn}'";
			// 组装sql 语句
			$sql = "SELECT id, goods_cover, goods_title, goods_attrs, goods_type, goods_vers_num, num, unit_price, goods_id, return_sn, is_evaluate, is_reply_evaluate FROM order_goods {$where} ";
			$goodData = $this->queryAll($sql);
		}
		return $goodData;
	}
	
	public function getMerchantInfo($merchant_id) {
		$merchantInfo = array();$sql = '';
		if($merchant_id)
		{
			// 组装sql 语句
			$sql = "SELECT uid, store_name, store_address FROM user_merchant WHERE uid = {$merchant_id} ";
			$merchantInfo = $this->queryRow($sql);
		}
		return $merchantInfo;
	}
	
	/**
	 * 获得有评论的订单号 列表
	 * return array
	 * @param int $id
	 */
	public function getOrderList() {
		$info = array ();
		
		$sql = "SELECT o.* FROM orders o LEFT JOIN goods_comment c ON o.order_sn = c.order_sn WHERE c.id <> '' OR c.id is not null";
		$info = Yii::app ()->getDb ()->createCommand ( $sql )->queryRow ( true, array () );
		
		return $info;
	}
	
	/**
	 * 用户确认收货操作
	 *	return boolean
	 * @param int $id
	 */
	public function setOrderInfo($post) {
		$logData = $orderData = array ();$isTrue = false;
		$currentUser = $this->getUser();
		//判断是否存在该订单
		if($post['order_sn'])
		{
			$logData = array(
				'order_sn' => $post['order_sn'],
				'operate_type' => 1,
				'operate_id' => $currentUser['id'],
				'pre_order_status_id' => 106,
				'now_order_status_id' => 107,
				'logs' => '您通过会员中心确认了已经收到货，该订单操作已完成。',
				'memo' => '',
				'time' => time(),
			);
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$flag = $this->insert('order_log' , $logData);
				$orderData['order_status_id'] = 107;
				$isTrue = $this->update ( 'orders', $orderData, "order_sn='".$post['order_sn']."'" );
				$transaction->commit();
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
			
			if($isTrue){
				$orderInfo = $this->getActiveInfo($post['order_sn'],1);
				GlobalOrders::sendOrderPrivilege($post['order_sn'], $orderInfo['order_money'], $orderInfo['create_time']);
				
				// 订单 行为处理
				UserAction::orderAction(
						$orderInfo['users_id'] ,
						$orderInfo['merchant_id'] ,
						empty($orderInfo['re_uid']) ? 0 : $orderInfo['re_uid'] ,
						trim($post['order_sn']) ,
						$orderInfo['order_money']
				);
			}
			return $flag;
		}
	}

	/**
	 * 用户删除操作
	 *	return boolean
	 * @param int $id
	 */
	public function deleteOrder($post) {
		$orderData = array ();
		$uid = (int)$this->getUid();
		//判断是否存在该订单
		if($post['order_sn'])
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				//修改订单删除状态
				$orderData['user_delete'] = 1;
				$flag = $this->update ( 'orders', $orderData, "order_sn='".$post['order_sn']."' AND user_id={$uid}" );
				//查询父订单信息并修改总运费
				$sql = "SELECT o.parent_order_sn, o.freight_money, (select freight_money from orders where order_sn = o.parent_order_sn) as freight_moneys FROM orders o WHERE o.order_sn = '".$post['order_sn']."'";
				$orderInfo = $this->queryRow($sql);
				if($orderInfo['parent_order_sn'] && $orderInfo['parent_order_sn'] !=1){
					$fliedData['freight_money'] = (int)$orderInfo['freight_moneys']-(int)$orderInfo['freight_money'];
					//判断父订单下子订单是否  已经都删除完毕
					$flag = $this->getOrderDeleteStatus($orderInfo['parent_order_sn']);
					if($flag){
						$fliedData['user_delete'] = 1;
					}
					$this->update ( 'orders', $fliedData, "order_sn='".$orderInfo['parent_order_sn']."'" );
				}
	
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
	}
	
	/**
	 * 查询 父订单下的 子订单是否都已经删除
	 * @param string $order_sn
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getOrderDeleteStatus($order_sn) {
		$orderInfo = array();$sql = $where = '';$flag = true;
		if($order_sn)
		{
			$sql = "SELECT user_delete FROM orders  WHERE order_sn = '{$order_sn}' ";
			$orderInfo = $this->queryAll($sql);
			foreach ($orderInfo as $key => $val){
				if($val['user_delete'] == 0){
					$flag = false;
					break;
				}
			}
		}
		return $flag;
	}

	/**
	 * 订单 快递公司名称信息查询
	 * @param		array		$post		post
	 * @param		string		$order_sn	订单号
	 */
	public function getExpressName($order_sn)
	{
		$expressArr = array();$sql = '';
		if ($order_sn)
		{
			$sql = "SELECT s.user_title, oe.express_name, oe.system_remark, o.order_money, o.pay_type, o.is_pay, o.discount_money FROM orders o 
					LEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn 
					LEFT JOIN status s ON o.cancel_status_id = s.id 
					WHERE o.order_sn = '{$order_sn}'";
			$expressArr = $this->queryRow($sql);
			return $expressArr;
		}
		return '';
	}

	/**
	 * 个人用户取消订单操作
	 * @param		return		boolean
	 * @param		string		$order_sn	订单号
	 */
	public function setAbolishInfo($post)
	{
		$abolishArr = array();$flag = 0;
		if ($post)
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$userInfo = $this->getUser();
				if($post['option_ordersn'] && $userInfo['id'])
				{
					$orderInfo = $this->getActiveInfo($post['option_ordersn'],1);
					//判断是否为发货操作
					$abolishArr['cancel_status_id'] = isset($post['cancel_status_id']) ? $post['cancel_status_id'] : 0;
					$abolishArr['order_status_id'] = $orderInfo['is_pay'] ? 104 : 102;
					$flag = $this->update ( 'orders', $abolishArr, "order_sn='".$post['option_ordersn']."' AND user_id = ".$userInfo['id'] );
					
					if($flag){
						$userName = empty($userInfo['nickname']) ? (empty($userInfo['phone']) ? '' : $userInfo['phone']) : $userInfo['nickname'];
						$logData = array(
							'order_sn' => $post['option_ordersn'],
							'operate_type' => isset($post['system']) ? 3 : 1,
							'operate_id' => isset($post['system']) ? 1: $userInfo['id'],
							'pre_order_status_id' => $orderInfo['order_status_id'],
							'now_order_status_id' => $abolishArr['order_status_id'],
							'logs' => isset($post['system']) ? "会员超过时限未支付，系统自动取消了此订单。" : ($orderInfo['is_pay'] ? "个人会员 [ {$userName} ] 通过个人中心平台申请取消该订单，进入等待商家审核退款阶段。" : "个人会员 [ {$userName} ] 通过个人中心平台取消了此订单。"),
							'memo' => '',
							'time' => time(),
						);
						$this->insert('order_log' , $logData);
						//回复商品库存
						if($orderInfo['is_pay'] == 0)
							GlobalOrders::setGoodsNum($post['option_ordersn']);
					}
				}
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		return 0;
	}
	
	/**
	 * 获得 商品订单日志 时间
	 * @param string	$order_sn
	 * @param int 		$status
	 */
	public function getOrderLogTime($status, $order_sn = '') {
		$status = ( int ) $status;
		$orderInfo = array ();
		if ($status) {
			$sql = "SELECT time FROM order_log WHERE now_order_status_id = {$status} AND order_sn = '{$order_sn}' ORDER BY time desc limit 1";
			$orderInfo = $this->queryRow($sql);
			return isset($orderInfo['time']) ? date('Y-m-d H:i:s',$orderInfo['time']) : '';
		} else {
			return false;
		}
	}
	
	/**
	 * 获得 商品订单 评论列表
	 * @param string $order_sn
	 * @param int $status
	 * @param return array|boolean
	 */
	public function getOrderComment($goods_id,$order_sn = '') {
		$commentInfo = array ();
		$goodId = (int)$goods_id;
		if ($order_sn) {
			$sql = "SELECT oc.content, g.title FROM order_comment oc 
					LEFT JOIN goods g ON oc.goods_id = g.id 
					WHERE oc.order_sn = '{$order_sn}' AND oc.goods_id = {$goodId}";
			$commentInfo = $this->queryRow($sql);
			return $commentInfo;
		} else {
			return false;
		}
	}
	
	/**
	 * 获得 特定分类全局状态 列表
	 * @param int $type
	 * @param return array|boolean
	 */
	public function getStatusList($type) {
		$statusList = array();
		$type = (int)$type;
		if ($type) {
			$sql = "SELECT id, user_title FROM status WHERE type = {$type}";
			$statusList = $this->queryAll($sql);
			return $statusList;
		} else {
			return false;
		}
	}
}
