<?php
class Orders extends ScheModels
{
	/**
	 * 判断和取消超时未支付订单操作
	 * @param		return		boolean
	 */
	public function autoCloseOrders()
	{
		$sql = "SELECT order_sn FROM orders WHERE order_status_id=101 AND pay_type=1 AND parent_order_sn!=1 AND is_pay=0 AND create_time<".(time()-1800);
		foreach ($this->queryAll($sql) as $val)
			$this->setAbolishOrder($val['order_sn']);
		return true;
	}

	/**
	 * 系统自动取消订单操作
	 * @param		return		boolean
	 * @param		string		$order_sn	订单号
	 */
	public function setAbolishOrder($order_sn)
	{
		$abolishArr = array();$flag = 0;
		if ($order_sn)
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$abolishArr['cancel_status_id'] = 911;
				$abolishArr['order_status_id'] = 102;
				$flag = $this->update ( 'orders', $abolishArr, "order_sn='{$order_sn}'" );
				//写入操作日志记录
				if($flag){
					$logData = array(
						'order_sn' => $order_sn,
						'operate_type' => 3,
						'operate_id' => 1,
						'pre_order_status_id' => 101,
						'now_order_status_id' => 102,
						'logs' => "该会员订单超过时限未支付，系统自动取消了此订单。",
						'memo' => '',
						'time' => time(),
					);
					$this->insert('order_log' , $logData);
					//恢复商品库存
					GlobalOrders::setGoodsNum($order_sn);
				}

				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
			}
		}
		return false;
	}

	/**
	 * 订单自动确认收货     或   7天确认收货
	 * @param		return		boolean
	 */
	//取消订单修改商品数量
	public function autoFinishOrders()
	{
		$sql = "SELECT o.order_sn, o.merchant_id, o.create_time, o.order_money, oe.express_send_time, u.id as users_id, u.re_uid
				FROM orders o 
				LEFT JOIN orders_extend oe ON o.order_sn = oe.order_sn
				lEFT JOIN user u ON o.user_id = u.id
				WHERE o.order_status_id = 106 AND o.parent_order_sn <> 1 AND o.pay_type = 1";
		$orderList = $this->queryAll($sql);
		if(!empty($orderList)){
			foreach ($orderList as $val)
			{
				//检查订单是否超过规定时间还未确认收货，超过这设置为已收货状态
				$time = time()-(int)$val['express_send_time'];
				if($time > 7*24*3600){
					$userInfo = array(
						'merchant_id' => $val['merchant_id'],
						'users_id' => $val['users_id'],
						're_uid' => $val['re_uid'],
						'order_money' => $val['order_money']
					);
					$this->setOrderStatus($val['order_sn'], $userInfo);
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * 系统自动确认订单已收货操作
	 * @param		return		boolean
	 * @param		string		$order_sn	订单号
	 */
	public function setOrderStatus($order_sn, $userInfo = array())
	{
		$orderData = array();$flag = 0;
		if ($order_sn && !empty($userInfo))
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$orderData['order_status_id'] = 107;
				$flag = $this->update ( 'orders', $orderData, "order_sn='".$order_sn."'" );
				//写入操作日志记录
				if($flag){
					$logData = array(
						'order_sn' => $order_sn,
						'operate_type' => 3,
						'operate_id' => 1,
						'pre_order_status_id' => 106,
						'now_order_status_id' => 107,
						'logs' => "会员超过7天未确认收货，系统自动默认了该订单已收货，该订单操作已完成。",
						'memo' => '',
						'time' => time(),
					);
					$this->insert('order_log' , $logData);
					
					// 订单 行为处理
					UserAction::orderAction(
							$userInfo['users_id'] ,
							$userInfo['merchant_id'] ,
							empty($userInfo['re_uid']) ? 0 : $userInfo['re_uid'],
							trim($order_sn) ,
							$userInfo['order_money']
					);
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
}