<?php
class Pay extends WebModels
{
	public function getChildOrders($mainOSN)
	{
		return $this->queryAll("SELECT order_sn,order_money FROM orders WHERE parent_order_sn={$this->quoteValue($mainOSN)}");
	}
	
	public function getOrders($osn)
	{
		if (!$osn)
			return array();
	
		$temp = $this->queryAll("SELECT o.*,oe.cons_name,oe.cons_phone,oe.cons_address,oe.delivery_way,oe.order_cover,og.goods_title,og.goods_cover
				FROM orders AS o
				INNER JOIN orders_extend AS oe ON o.order_sn=oe.order_sn
				LEFT JOIN order_goods AS og ON o.order_sn=og.order_sn
				WHERE o.order_sn={$this->quoteValue($osn)}");
		if ($temp)
		{
			$_x = array();
			foreach ($temp as $tx)
			{
				$_x[$tx['order_sn']] = $tx;
				if ($tx['goods_title'] == null && $tx['order_cover'])
				{
					$_x[$tx['order_sn']]['goods'] = $this->jsonDnCode($tx['order_cover']);
				}else{
					$_x[$tx['order_sn']]['goods'][] = $tx['goods_title'];
				}
				$_x[$tx['order_sn']]['cover'][] = $tx['goods_cover'];
			}
			
			if (isset($_x[$osn]))
			{
				unset($_x[$osn]['goods_title'] , $_x[$osn]['goods_cover']);
				#print_r($_x[$osn]);exit;
				return $_x[$osn];
			}
		}
		return array();
	}
	
	/**
	 * 支付成功
	 *
	 * @param		$osn		$osn			订单编号
	 * @param		double		$money			实际收到的钱
	 * @param		string		$trade_no		交易号
	 * @param		int			$payPort		支付接口 [1=支付宝 , 2=银联 , 3=财付通 , 4=货到付款，5=邮政汇款]
	 * @param		array		$callBack		回调信息
	 */
	public function success($osn , $money , $trade_no , $payPort , array $callBack = array())
	{
		if (!$osn || !$money || !$trade_no)
			return false;
		
		if (!$orders = $this->getOrders($osn))
			return false;
		
		//如果是父订单
		#if ($orders['parent_order_sn'] == 1)
		#	return false;
		
		//如果已支付
		if ((int)$orders['order_status_id'] !== 101)
			return $this->recordLog($osn , $money , $trade_no , $payPort , 1 , $callBack);
		
		//如果实际收到的钱少于应收的钱,则失败
		if ($money < $orders['order_money'])
			return $this->recordLog($osn , $money , $trade_no , $payPort , 0 , $callBack);
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$time = time();
			//如果是父订单
			if ($orders['parent_order_sn'] == 1)
			{
				$moneyTotal = 0;
				
				//子订单
				foreach ($this->getChildOrders($osn) as $vs)
				{
					$moneyTotal += $vs['order_money'];
					
					$this->update('orders' , array('order_status_id'=>103 , 'pay_type'=>1 , 'is_pay'=>1 , 'receive_money'=>$vs['order_money']) , "order_sn={$this->quoteValue($vs['order_sn'])} AND order_status_id=101");
					$this->insert('order_log' , array(
						'order_sn'				=> $vs['order_sn'],
						'operate_type'			=> 3,
						'operate_id'			=> 0,
						'pre_order_status_id'	=> 101,
						'now_order_status_id'	=> 103,
						'logs'					=> "您已经成功支付订单 ({$vs['order_sn']}) {$vs['order_money']}元",
						'memo'					=> '',
						'time'					=> $time,
					));
					$this->recordLog($vs['order_sn'] , $vs['order_money'] , $trade_no , $payPort , 1 , $callBack);
				}
				
				//子订单支付金额不等总订单金额
				if ($moneyTotal != $money)
				{
					$transaction->rollBack();
					return false;
				}
				
				//总订单
				$this->update('orders' , array('order_status_id'=>103 , 'pay_type'=>1 , 'is_pay'=>1 , 'receive_money'=>$money) , "order_sn={$this->quoteValue($osn)} AND order_status_id=101");
				
			}else{
				$this->update('orders' , array('order_status_id'=>103 , 'pay_type'=>1 , 'is_pay'=>1 , 'receive_money'=>$money) , "order_sn={$this->quoteValue($osn)} AND order_status_id=101");
				$this->insert('order_log' , array(
					'order_sn'				=> $osn,
					'operate_type'			=> 3,
					'operate_id'			=> 0,
					'pre_order_status_id'	=> 101,
					'now_order_status_id'	=> 103,
					'logs'					=> "您已经成功支付订单 ({$osn}) {$money}元",
					'memo'					=> '',
					'time'					=> $time,
				));
				$this->recordLog($osn , $money , $trade_no , $payPort , 1 , $callBack);
			}
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	/**
	 * 支付失败
	 *
	 * @param		string		$osn			订单编号
	 * @param		float		$money			实际收到的钱
	 * @param		string		$trade_no		交易号
	 * @param		int			$payPort		支付接口 [1=支付宝 , 2=银联 , 3=财付通 , 4=货到付款，5=邮政汇款]
	 * @param		array		$callBack		回调信息
	 */
	public function failure($osn , $money , $trade_no , $payPort , array $callBack = array())
	{
		return $this->recordLog($osn , $money , $trade_no , $payPort , 0 , $callBack);
	}
	
	/**
	 * 记录支付日志
	 * 
	 * @param		string		$osn			订单编号
	 * @param		float		$money			实际收到的钱
	 * @param		string		$trade_no		交易号
	 * @param		int			$payPort		支付接口 [1=支付宝 , 2=银联 , 3=财付通 , 4=货到付款，5=邮政汇款]
	 * @param		int			$status			支付状态 , 1=成功 , 其他=失败
	 * @param		array		$callBack		回调信息
	 */
	private function recordLog($osn , $money , $trade_no , $payPort , $status , array $callBack = array())
	{
		if (!$osn)
			return false;
		
		$this->insert('order_pay_log' , array(
			'order_sn'		=> $osn,
			'trade_no'		=> $trade_no,
			'pay_money'		=> (double)$money,
			'pay_time'		=> time(),
			'pay_port'		=> (int)$payPort,
			'pay_status'	=> (int)$status,
			'pay_verify'	=> 1,
			'call_back'		=> json_encode($callBack),
		));
		return true;
	}
}