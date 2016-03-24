<?php
/**
 * Description of Pay
 * 支付模型
 * @author Administrator
 */
class Pay extends WebApiModels{
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
}
