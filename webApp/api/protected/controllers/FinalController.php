<?php
/**
 * Description of FinalController
 *
 * @author Administrator
 */
class FinalController extends CController{
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getParam($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getParam($name , $defaultValue);
	}
        //执行支付
        public function actionExPay()
        {
                $osn    = (string)$this->getParam('osn');
		$pay	= (string)$this->getParam('pay');
		$pay = explode('-' , $pay);
		$bank = isset($pay[1]) ? $pay[1] : '';
		$pay = isset($pay[0]) ? $pay[0] : '';
	
		$soary = array('alipay'=>1 , 'tenpay'=>1); 
                $model = ClassLoad::Only('Pay');/* @var $model Pay */ 
                $orders = $model->getOrders($osn);
                
                $subject = empty($orders['goods'][0]) ? '' : $orders['goods'][0].'...';
		switch ($pay)
		{
			case 'alipay' :
				Yii::import('system.extensions.payment.alipay.Alipay');
				echo Alipay::init(array(
					'out_trade_no'	=> $orders['order_sn'],
					'total_fee'		=> $orders['order_money'],
					'bank'			=> $bank,
					'subject'		=> $subject,
				));
			break;
			
			case 'tenpay' :
				Yii::import('system.extensions.payment.tenpay.Tenpay');
				echo Tenpay::init($orders['order_sn'] , $orders['order_money'] , $subject);
			break;
		}            
        }        
}
