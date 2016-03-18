<?php
class OrderForm extends SForm
{
	public $express_send_time,$starttime,$endtime,$create_time, $receive_money, $is_pay, $order_money, $edit_money, $discount_money, $freight_money, $goods_money, $order_status_id, $merchant_id, $user_id, $parent_order_sn, $order_sn, $id;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			array('order_sn' ,'required') 
		);
	}

}