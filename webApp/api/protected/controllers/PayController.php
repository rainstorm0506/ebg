<?php
/**
 * Description of PayController
 * 确认 购物车订单==>支付模块
 * @author Administrator
 */
class PayController extends WebApiController{
    //检验 当前订单的 
    public function actionCheckOsn()
    {
		$uid			= $this->getUid();
		$osn			= (string)$this->getParam('osn');
		$pay			= (string)$this->getParam('pay');
		$orderShowURL	= $this->createUrl('pay/finish' , array('osn'=>$osn));
		if (!$osn)
                    $this->jsonOutput (1,"未传入订单编号!");
	
		$pay = explode('-' , $pay);
		$bank = isset($pay[1]) ? $pay[1] : '';
		$pay = isset($pay[0]) ? $pay[0] : '';
	
		$soary = array('alipay'=>1 , 'tenpay'=>1);
		if (!$pay || empty($soary[$pay]))
                    $this->jsonOutput(1,"未选择支付方式!");
	
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		if (!$orders = $model->getOrders($osn))
                    $this->jsonOutput (1,"当前订单无效!");;
		//如果已支付 , 跳转
		if ((int)$orders['order_status_id'] !== 101 || (int)$orders['is_pay'] == 1 || (int)$orders['pay_type'] !== 1)
                    $this->jsonOutput(2,"当前订单已支付!");
		$this->jsonOutput(0,array('url'=>$_SERVER['HTTP_HOST'].'/pay.dispose','param'=>array('osn'=>$osn,'pay'=>$pay)));

	} 
}
