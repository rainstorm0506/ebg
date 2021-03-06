<?php

/**
 * 商品订单 模型类
 * @author jeson.Q 
 * 
 * @table orders
 */
class Order extends ApiModels {
	
	/**
	 * 获得 商品订单信息
	 * return array|boolean
	 * @param int $id 
	 */
	public function getActiveInfo($uid, $order_sn) {
		$orderInfo = array ();
		if ($uid && $order_sn) {
			$sql = "SELECT o.order_sn, o.order_money, o.freight_money, opl.pay_port, opl.pay_time, opl.pay_money, s.merchant_title, oe.cons_phone, oe.cons_name, oe.cons_address, oe.user_remark, oe.delivery_way, u.nickname FROM orders o 
				lEFT JOIN order_pay_log opl ON o.order_sn = opl.order_sn
				lEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
				lEFT JOIN status s ON o.order_status_id = s.id
				lEFT JOIN user u ON o.user_id = u.id
				WHERE o.order_sn = '{$order_sn}' AND o.merchant_id = {$uid}";
			$orderInfo = $this->queryRow($sql);
			if(!empty($orderInfo))
				$orderInfo['goods'] = $this->getGoodsList($orderInfo['order_sn']);
			
			return $orderInfo;
		} else {
			return false;
		}
	}
	
	/**
	 * 查询 订单列表
	 * 
	 * @param int $uid
	 * @param int $status
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid, $status = 0, $offset = 0, $limit = 10) {
		$allOrder = array();
		$sql = $where = $orderBy = $limits ='';
		if($uid){
			// 判断是否条件搜索
			if($status)
			{
				$where .= $status == 1071 ? ' AND oe.is_evaluate = 1 ' : ' AND o.order_status_id='.(int)$status;
			}
			// 组装sql 语句
			$orderBy = " ORDER BY o.create_time DESC ";
			$limits = " limit {$offset},{$limit} ";
			// 组装sql 语句
			$sql = "SELECT o.order_sn,o.order_status_id,o.create_time,o.order_money,o.is_pay,s.merchant_title,oe.is_evaluate FROM orders o 
					lEFT JOIN status s ON o.order_status_id = s.id
					lEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
					WHERE o.parent_order_sn <> 1 AND o.merchant_id = {$uid} AND o.merchant_delete = 0 {$where} {$orderBy} {$limits}";
			$allOrder = $this->queryAll($sql);

			if($allOrder)
			{
				foreach ($allOrder as $key => $val)
				{
					$allOrder[$key] = $val;
					$allOrder[$key]['goods'] = $this->getGoodsList($val['order_sn']);
				}
			}
			$orderData['orderList'] 	= $allOrder;
			$orderData['orderTotal'] 	= $this->getTotalNumber($uid, $status = 0);
		}
		return $orderData;
	}

	/**
	 * 查询 订单所有状态管理列表
	 * @param string $type
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getStatusList($type = 0)
	{
		$statusList = array();
		if ($type) {
			//组装sql 语句
			$sql = "SELECT id,user_title from status WHERE type = {$type}";
			$statusList = $this->queryAll($sql);
		}
		return $statusList;
	}
	/**
	 *
	 * 统计 订单总数
	 * @param int $uid
	 * @param int $status
	 * @return int
	 * @throws Exception
	 */
	public function getTotalNumber($uid, $status = 0) {
		$sql = $where = '';
		// 判断是否条件搜索
		if($status)
		{
			$where .= ' AND order_status_id='.(int)$status;
		}
		//$uid = (int)$this->getUid();
		if($uid){
			// 组装sql 语句
			$sql = "SELECT count(*) as totalNums,sum(order_money) as totalMoney FROM orders WHERE parent_order_sn <> 1 AND merchant_id = {$uid} AND merchant_delete = 0 {$where}";
			$totalData = $this->queryRow($sql);
			return $totalData;
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
					WHERE ol.order_sn = '{$order_sn}' ORDER BY time asc";
			
			$logInfo = $this->queryAll($sql);
		}
		return $logInfo;
	}
	
	/**
	 * 查询 所有物流公司 列表
	 *
	 * @param string $order_sn
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getExpressList($uid) {
		$expressInfo = array();$sql = '';
		if($uid)
		{
			$sql = "SELECT id,firm_name FROM express WHERE usable = 1 ORDER BY rank asc";
			$expressInfo = $this->queryAll($sql);
		}
		return $expressInfo;
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
		$logInfo = array();$sql = $where = '';
		if($order_sn)
		{
			$where = " WHERE og.order_sn = '{$order_sn}'";
			// 组装sql 语句
			$sql = "SELECT og.id,og.goods_cover,og.goods_attrs,og.num,og.unit_price, g.title, um.uid, um.store_name, um.store_address FROM order_goods og
					lEFT JOIN goods g ON og.goods_id = g.id
					lEFT JOIN user_merchant um ON g.merchant_id = um.uid {$where} ";

			$orderInfo = $this->queryAll($sql);
			if(!empty($orderInfo)){
				foreach ($orderInfo as $key => $val){
					$attrs = '';
					$goods_attrs = empty($val['goods_attrs']) ? array() : json_decode($val['goods_attrs'],true);
					if(!empty($goods_attrs)){
						foreach ($goods_attrs as $k => $v){
							$attrs .= $v[1].':'.$v[2].",";
						}
					}
					$orderInfo[$key]['goods_attrs'] = $attrs;
				}
			}
		}
		return $orderInfo;
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
	 * 检查给定的密码是否正确
	 * @param		string		$writePassword		密码(明文字符串)
	 * @param		string		$dbPassword			数据库中存储加密后的密码值
	 * @return		boolean
	 */
	public function validatePassword($writePassword , $dbPassword)
	{
		return GlobalUser::validatePassword($writePassword, $dbPassword);
	}
	
	/**
	 * 操作后台订单管理 集合
	 *	return boolean
	 * @param int $id
	 */
	public function setOrderInfo($post) {
		$logData = $orderExtend = $orderData = $orderPayLogData = $orderInfo = $orderGoods = $orderComment = array ();
		$memo = $typename = $datastr = $logstr = $now_status_id = $pre_status_id = $payport = $tradeno = $goodsId = $system_memo = $smsLogs = $codeNums = '';
		$currentUser = $this->getUser();
		$merchant = $this->getMerchantInfo($currentUser['id']);
		//整理提交来的数据集合
		foreach ($post as $key => $val)
		{
			switch ($key){
				case 'typename':			$typename 	=	$val;	break;
				case 'express_id':			$expressId 	=	$val;	break;
				case 'oid':			$order_sn 	=	$val;	break;
				case 'express_no':			$expressNo 	=	$val;	break;
				case 'reason':				$reason 	=	$val;	break;

				default:					$orderData[$key] = $val;break;
			}
		}
		//判断是否存在该订单
		if($order_sn)
		{
			$orderInfo = $this->getExpressName($order_sn);
		}
		//判断用户操作 类型
		switch ($typename)
		{
			case 'send_goods':
				$pre_status_id = 105;
				$now_status_id = 106;
				if($orderInfo['delivery_way'] == 2){
					$codeNums = $this->getRandChar(8);
					$phone = $this->getUserPhone($orderInfo['user_id']);
					$smsLogs = "尊敬的会员用户您好，您在e办公商城购买的商品，商家 [ ".$merchant['store_name']." ] 已经将商品送到指定网点，请凭兑取码【{$codeNums}】前往领取。谢谢！---e办公商城.";
					$this->sendPhoneSms($phone, $smsLogs);
				}
				$logstr = $orderInfo['delivery_way'] == 2 ? "商家 [ ".$merchant['store_name']." ] 已经将商品送到指定网点，请凭兑取码【{$codeNums}】前往领取。" : "商家 [ ".$merchant['store_name']." ] 通过商家管理后台进行了 [ 发货 ] 操作";
				break;
// 			case 'refuse_reback':
// 				$pre_status_id = 106;
// 				$now_status_id = 107;
// 				$logstr = "商家 [ ".$currentUser['true_name']." ] 通过商家管理后台拒绝了用户的退货申请 并注明了拒绝原因,原订单状态已完成";
// 				break;
// 			case 'agree_reback':
// 				$pre_status_id = 106;
// 				$now_status_id = 107;
// 				$logstr = "商家 [ ".$currentUser['true_name']." ] 通过商家管理后台同意了用户的退货申请 并进入退款流程阶段,原订单状态已完成";
// 				break;
			case 'edit_money':
				$pre_status_id = 101;
				$now_status_id = 101;
				$logstr = "商家 [ ".$merchant['store_name']." ] 通过商家管理后台进行了 [ 修改订单金额 ] 操作,将原订单金额 [".$orderInfo['order_money']."]修改为 [".$orderData['order_money'].".00]元";
				break;
			case 'option_abolish_yes':
				$pre_status_id = 104;
				$now_status_id = 108;
				$logstr = "商家 [ ".$merchant['store_name']." ] 通过商家管理后台进行了 [ 同意取消订单 ] 操作审核通过了 用户取消该订单申请，并且进入财务退款阶段。";
				break;
			case 'option_abolish_no':
				$pre_status_id = 104;
				$now_status_id = 103;
				$logstr = "商家 [ ".$merchant['store_name']." ] 通过商家管理后台 [ 拒绝取消订单 ] 操作拒绝了 用户取消该订单申请,并进入备货阶段";
				break;
			case 'received_goods':
				$pre_status_id = 106;
				$now_status_id = 107;
				$logstr = $orderInfo['pay_type'] == 2 ? "商家 [ ".$merchant['store_name']." ] 通过商家管理后台 确认用户收货并付款 " : "商家 [".$merchant['store_name']."] 通过商家管理后台确认用户收货, 该订单操作已完成！";
				break;
			case 'reply_content':
				$pre_status_id = 107;
				$now_status_id = 107;
				$logstr = "管理员 [ ".$merchant['store_name']." ] 通过 [ 回复用户评价 ] 操作回复了用户商品评价";
				break;
				
			default:	break;
		}
		$logData = array(
			'order_sn' => $order_sn,
			'operate_type' => 2,
			'operate_id' => $currentUser['id'],
			'pre_order_status_id' => $pre_status_id,
			'now_order_status_id' => $now_status_id,
			'logs' => $logstr,
			'memo' => $memo,
			'time' => time(),
		);
		
		//开始事务操作
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$flag = $this->insert('order_log' , $logData);
			if($order_sn)
			{
				//判断是否为发货操作
				if($typename == 'send_goods')
				{
					$orderData['order_status_id'] = $now_status_id;
					if(!empty($expressId)){
						$orderExtend['express_id'] = $expressId;
						$orderExtend['express_no'] = $expressNo;
						$orderExtend['express_send_time'] = time();
						$this->update ( 'orders_extend', $orderExtend, "order_sn='{$order_sn}'" );
					}
					$this->update ( 'orders', $orderData, "order_sn='{$order_sn}'" );
//				}//商家同意退货操作
// 				elseif($typename == 'agree_reback')
// 				{
// 					$orderData['order_status_id'] = $now_status_id;
// 					$this->update ( 'orders', $orderData, "order_sn='".$orderData['order_sn']."'" );
// 				}//商家拒绝退货操作
// 				elseif($typename == 'refuse_reback')
// 				{
// 					$orderData['order_status_id'] = $now_status_id;
// 					$this->update ( 'orders', $orderData, "order_sn='".$orderData['order_sn']."'" );
				}//回复用户商品评价操作
				elseif($typename == 'reply_content')
				{
					$orderComment = array(
						'reply_content' => $orderData['reply_content'],
						'reply_time' => time()
					);
					$orderGoods['is_reply_evaluate'] = 1;
					$this->update ( 'order_comment', $orderComment, "order_sn='{$order_sn}' AND goods_id = {$goodsId} " );
					$this->update ( 'order_goods', $orderGoods, "order_sn='{$order_sn}' AND goods_id = {$goodsId} " );
				}//判断是否为修改订单价格  操作
				elseif($typename == 'edit_money')
				{
					$editMoney = $orderInfo['order_money']-$orderData['order_money'];
					$orderData['edit_money'] = $orderInfo['edit_money'] + $editMoney;
					$orderData['discount_money'] = $orderInfo['discount_money'] + $orderData['edit_money'];
					$this->update ( 'orders', $orderData, "order_sn='".$order_sn."'" );
					if($orderInfo['parent_order_sn'] && $orderInfo['parent_order_sn'] != 1){
						$parentOrder = $this->getActiveInfo($orderInfo['parent_order_sn'], 1);
						$orderParentData['edit_money'] = $parentOrder['edit_money'] + $editMoney;
						$orderParentData['order_money'] = $parentOrder['order_money'] - $editMoney;
						$this->update ( 'orders', $orderParentData, "order_sn='".$orderInfo['parent_order_sn']."'" );
					}
				}//判断是否为发货操作
				elseif($typename == 'back_goods')
				{
					$orderGoods['return_sn'] = $orderData['return_sn'];	
					$this->update ( 'order_goods', $orderGoods, "order_sn='{$order_sn}' AND goods_id = {$goodsId}" );
					$goodsArr = $this->queryRow("SELECT * FROM order_goods WHERE order_sn = '{$order_sn}' limit 1");
					//是否该订单已经全部商品退货完成，是则改变订单状态
					if(!$goodsArr['id'])
					{
						$this->update ( 'orders', array('is_back_goods'=>1), "order_sn='{$order_sn}'" );
					}
				}//确认用户已收到商品
				elseif($typename == 'received_goods')
				{
					$orderData['order_status_id'] = $now_status_id;
					$this->update ( 'orders', $orderData, "order_sn='{$order_sn}'" );
				}else//其他操作
				{

				}

				//取消订单---恢复库存
				//if($typename == 'abolish' || $typename == 'option_abolish_yes')
				//{
					
					//GlobalOrders::setGoodsNum($orderData['order_sn']);
				//}
			}
			$transaction->commit();
			return $flag;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
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
			$sql = "SELECT s.user_title, oe.express_name, oe.system_remark,oe.delivery_way, o.order_money, o.edit_money, o.parent_order_sn, o.pay_type, o.is_pay, o.user_id, o.discount_money FROM orders o 
					LEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn 
					LEFT JOIN status s ON o.cancel_status_id = s.id 
					WHERE o.order_sn = '{$order_sn}'";
			$expressArr = $this->queryRow($sql);
			return $expressArr;
		}
		return '';
	}

	/**
	 * 商家删除订单操作
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
				$orderData['merchant_delete'] = 1;
				$flag = $this->update ( 'orders', $orderData, "order_sn='".$post['order_sn']."' AND user_id={$uid}" );
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
	}
	
	/**
	 * 取消订单修改商品数量
	 * @param		return		boolean
	 * @param		string		$order_sn	订单号
	 */
	//取消订单修改商品数量
	public function setAbolishInfo($post)
	{
		$abolishArr = array();$flag = 0;
		if ($post)
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$userInfo = $this->getUser();echo "<pre>";var_dump($userInfo);exit;
				if($post['option_ordersn'] && $userInfo['id'])
				{
					$orderInfo = $this->getActiveInfo($post['option_ordersn'],1);
					//判断是否为发货操作
					$abolishArr['cancel_status_id'] = $post['cancel_status_id'];
					$abolishArr['order_status_id'] = $orderInfo['is_pay'] ? 104 : 102;
					$flag = $this->update ( 'orders', $abolishArr, "order_sn='".$post['option_ordersn']."' AND user_id = ".$userInfo['id'] );

					if($flag){
						$logData = array(
							'order_sn' => $post['option_ordersn'],
							'operate_type' => 1,
							'operate_id' => $userInfo['id'],
							'pre_order_status_id' => $orderInfo['order_status_id'],
							'now_order_status_id' => $abolishArr['order_status_id'],
							'logs' => "个人会员 [ ".$userInfo['nickname']." ] 通过个人中心平台取消了此订单。",
							'memo' => '',
							'time' => time(),
						);
						$this->insert('order_log' , $logData);
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
	 * 查询 店铺首页所有数据
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getAllData($uid) {
		$allData = $orderInfo = $allGoodsData = $allOrderData = array();
		$noPay = $noSend = $returnGoods = $reimburse = $noEvaluate = 0;
		$uid = (int)$uid;
		if($uid){
			// 查询当前商家所有订单状态
			$sql = "SELECT o.id, o.order_status_id, o.is_pay, oe.is_evaluate FROM orders o 
					LEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn 
					WHERE o.merchant_id = {$uid} AND o.parent_order_sn <>1 ";
			$orderInfo = $this->queryAll($sql);
			if($orderInfo)
			{
				foreach ($orderInfo as $key => $val)
				{
					$noPay 			+= 		$val['is_pay'] == 0 ? 1 : 0;
					$noSend 		+= 		$val['order_status_id'] == 103 ? 1 : 0;
					$noEvaluate 	+= 		$val['order_status_id'] == 107 && $val['is_evaluate'] == 0 ? 1 : 0;
					$reimburse 		+= 		$val['order_status_id'] == 104 ? 1 : 0;
					$returnGoods 	+= 		$val['order_status_id'] == 105 ? 1 : 0;
				}
				$allOrderData = array(
					'noPay' 		=>	$noPay,
					'noSend' 		=>	$noSend,
					'noEvaluate'	=>	$noEvaluate,
					'reimburse' 	=>	$reimburse,
					'returnGoods' 	=>	$returnGoods
				);
			}
			//查询商家所有商品及状态
			$allGoodsData = $this->getAllGoods($uid);
			$allData = array_merge($allOrderData, $allGoodsData);
		}
		return $allData;
	}

	/**
	 * 查询 查询商家所有商品
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getAllGoods($uid) {
		$allData = $goodsList = array();
		$saleNums = $waitSalesNums = $waitAuditNums = 0;
		$uid = (int)$uid;
		if($uid){
			// 查询当前商家所有订单状态
			$sql = "SELECT id, shelf_id, status_id FROM goods WHERE merchant_id = {$uid}";
			$goodsList = $this->queryAll($sql);
			if($goodsList)
			{
				foreach ($goodsList as $key => $val)
				{
					$saleNums 		+= 	$val['shelf_id'] == 410 ? 1 : 0;
					$waitAuditNums 	+= 	$val['status_id'] == 400 ? 1 : 0;
					$waitSalesNums 	+= 	$val['status_id'] == 401 && $val['shelf_id'] == 0 ? 1 : 0;
				}
				$allData = array(
					'saleNums' 		=>	$saleNums,
					'waitAuditNums'	=>	$waitAuditNums,
					'waitSalesNums'	=>	$waitSalesNums,
				);
			}
		}
		return $allData;
	}

	/**
	 * 上面自提 商家发货后向用户发送短信
	 * @param int $length
	 * @return string|NULL
	 * @throws Exception
	 */
	function sendPhoneSms($phone, $content)
	{
		//PHP短信接口 --
		if ($returned = SmsNote::sendOne($phone , $content))
		{
			if (isset($returned['code']) && $returned['code'] == 0)
				return true;
		}
		return false;
	}
	
	/**
	 * 随机生成字符串
	 * @param int $length
	 * @return string|NULL
	 * @throws Exception
	 */
	function getRandChar($length){
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol)-1;

		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];
		}

		return $str;
	}

	//查询用户的手机号
	function getUserPhone($id){
		$userData = array();
		$model = ClassLoad::Only('User');/* @var $model User */
		$userData = $model->getPersonInfo($id);
		
		return !empty($userData) ? $userData['phone'] : '';
	}

	/**
	 * 得到商家的详细信息
	 * @param		int		$mid		用户名称
	 * @return		array
	 */
	public function getMerchantInfo($mid)
	{
		if (!$mid) return array();
		return $this->queryRow("SELECT * FROM user_merchant WHERE uid ={$mid}");
	}
}
