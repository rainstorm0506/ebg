<?php
class OrdersController extends ScheController
{
	/**
	 * 30分钟自动取消订单
	 * 建议每分钟执行一次
	 * 
	 * http://www.ebangon.com/schedule/orders.autoCloseOrders
	 * http://loc.ebg.com/schedule/orders.autoCloseOrders
	 */
	public function actionAutoCloseOrders()
	{
		$key = 'autoCloseOrders';
		$this->setScriptTimeout(0);
		//排程开始
		$this->scheduleStart($key);

		#-------- 业务逻辑
		$model = ClassLoad::Only('Orders');/* @var $model Orders */
		$model->autoCloseOrders();

		//排程结束
		$this->scheduleEnd($key);
		exit('ok');
	}
	
	/**
	 * 订单自动确认收货     或   7天确认收货
	 * 建议每10分钟执行一次      
	 * 
	 * http://www.ebangon.com/schedule/orders.autoFinish
	 * http://loc.ebg.com/schedule/orders.autoFinish
	 */
	public function actionAutoFinish()
	{
		$key = 'autoFinish';
		$this->setScriptTimeout(0);
		
		//排程开始
		$this->scheduleStart($key);

		#-------- 业务逻辑
		$model = ClassLoad::Only('Orders');/* @var $model Orders */
		$model->autoFinishOrders();

		//排程结束
		$this->scheduleEnd($key);
		exit('ok');
	}
}