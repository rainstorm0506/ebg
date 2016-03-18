<?php
class PayController extends WebController
{
	public function actionIndex()
	{
		$this->mainLayoutNav = false;
	
		if (!$osn = (string)$this->getQuery('osn'))
			$this->error('参数错误!');
	
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		if (!$orders = $model->getOrders($osn))
			$this->error('查询不到订单!');
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('pay', 0));
		
		//如果已支付 , 跳转
		if ((int)$orders['order_status_id'] !== 101 || (int)$orders['is_pay'] == 1 || (int)$orders['pay_type'] !== 1)
			$this->redirect(array('pay/finish' , 'osn'=>$osn));
		
		$this->render('index' , array(
			'orders'	=> $orders,
		));
	}
	
	//跳转到支付接口
	public function actionDispose()
	{
		$uid			= $this->getUid();
		$osn			= (string)$this->getParam('osn');
		$pay			= (string)$this->getParam('pay');
		$orderShowURL	= $this->createUrl('pay/finish' , array('osn'=>$osn));
		if (!$osn)
			$this->redirect($orderShowURL);
	
		$pay = explode('-' , $pay);
		$bank = isset($pay[1]) ? $pay[1] : '';
		$pay = isset($pay[0]) ? $pay[0] : '';
	
		$soary = array('alipay'=>1 , 'tenpay'=>1);
		if (!$pay || empty($soary[$pay]))
			$this->redirect($orderShowURL);
	
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		if (!$orders = $model->getOrders($osn))
			$this->redirect($orderShowURL);
	
		//如果已支付 , 跳转
		if ((int)$orders['order_status_id'] !== 101 || (int)$orders['is_pay'] == 1 || (int)$orders['pay_type'] !== 1)
			$this->redirect($orderShowURL);
		
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
	
	//支付完成 , 成功 & 失败
	public function actionFinish()
	{
		$this->mainLayoutNav = false;
		
		if (!$osn = (string)$this->getQuery('osn'))
			$this->error('参数错误!');
		
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		if (!$orders = $model->getOrders($osn))
			$this->error('查询不到订单!');
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('pay', 0));
		
		if ((int)$orders['pay_type'] === 1)
		{
			if ((int)$orders['is_pay'] === 1 && $orders['order_money'] == $orders['receive_money'])
				$this->render('finish_pay_success' , array('orders' => $orders , 'osn'=>$osn));
			else
				$this->render('finish_pay_defeated' , array('orders' => $orders , 'osn'=>$osn));
		}else{
			if ((int)$orders['delivery_way'] === 1)//配送
				$this->render('finish_delivery' , array('orders' => $orders , 'osn'=>$osn));
			else//上门自提
				$this->render('finish_self' , array('orders' => $orders , 'osn'=>$osn));
		}
	}
	
	//支付宝异步回调
	public function actionAlipayNotify()
	{
		Yii::import('system.extensions.payment.alipay.Alipay' , true);
		
		$osn			= (string)$this->getPost('out_trade_no');			# 商户订单号
		$trade_no		= (string)$this->getPost('trade_no');				# 支付宝交易号
		$trade_status	= (string)$this->getPost('trade_status');			# 交易状态
		$money			= (double)$this->getPost('total_fee');				# 实际收到的钱
		
		/**
		 * 	成功!
		 * --------------------------------------------------------------------
		 * 	TRADE_FINISHED
		 * 	该种交易状态只在两种情况下出现
		 * 	1、开通了普通即时到账，买家付款成功后。
		 * 	2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
		 *
		 * 	TRADE_SUCCESS
		 * 	该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
		 * --------------------------------------------------------------------
		*/
		$alipayNotify = new AlipayNotify();
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		if ($alipayNotify->verifyNotify() && ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'))
		{
			//成功
			if ($model->success($osn , $money , $trade_no , 1 , $this->getCallBack()))
				Yii::app()->end('success');
		}
		
		//失败
		$model->failure($osn , $money , $trade_no , 1 , $this->getCallBack());
		Yii::app()->end('fail');
	}
	
	//支付宝同步回调
	public function actionAlipayReturn()
	{
		Yii::import('system.extensions.payment.alipay.Alipay' , true);
		
		$osn			= (string)$this->getQuery('out_trade_no');			# 商户订单号
		$trade_no		= (string)$this->getQuery('trade_no');				# 支付宝交易号
		$trade_status	= (string)$this->getQuery('trade_status');			# 交易状态
		$money			= (double)$this->getQuery('total_fee');				# 实际收到的钱
		$model			= ClassLoad::Only('Pay');/* @var $model Pay */
		
		$alipayNotify = new AlipayNotify();
		if ($alipayNotify->verifyReturn() && ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'))
		{
			//成功
			if ($model->success($osn , $money , $trade_no , 1 , $this->getCallBack()))
				$this->redirect(array('pay/finish' , 'osn'=>$osn));
		}
		
		//失败
		$model->failure($osn , $money , $trade_no , 1 , $this->getCallBack());
		$this->redirect(array('pay/finish' , 'osn'=>$osn));
	}
	
	public function actionAwait()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('pay', 0));
		
		$this->renderPartial(
			'await' ,
			array('osn' => (string)$this->getQuery('osn')) ,
			false ,
			true
		);
	}
	
	//财付通 异步回调
	public function actionTenpayNotify()
	{
		Yii::import('system.extensions.payment.tenpay.Tenpay' , true);
		$config = Tenpay::getConfig();
		
		$resHandler = new ResponseHandler();
		$resHandler->setKey($config['key']);
		
		//判断签名
		if($resHandler->isTenpaySign())
		{
			//通过通知ID查询，确保通知来至财付通
			//通知id
			$notify_id = $resHandler->getParameter("notify_id");
			
			$queryReq = new RequestHandler();
			$queryReq->init();
			$queryReq->setKey($config['key']);
			$queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
			$queryReq->setParameter("partner", $config['partner']);
			$queryReq->setParameter("notify_id", $notify_id);
			$httpClient = new TenpayHttpClient();
			$httpClient->setTimeOut(5);
			$httpClient->setReqContent($queryReq->getRequestURL());
			
			$osn			= (string)$resHandler->getParameter('out_trade_no');	#商户订单号
			$trade_no		= (string)$resHandler->getParameter('transaction_id');	#财付通订单号
			$money			= (double)$resHandler->getParameter('total_fee');		#金额,以分为单位
			$discount		= (double)$resHandler->getParameter('discount');		#如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$money			= $money + $discount;
			
			if($httpClient->call())
			{
				$queryRes = new ClientResponseHandler();
				$queryRes->setContent($httpClient->getResContent());
				$queryRes->setKey($config['key']);
		
				if(
					$resHandler->getParameter("trade_mode") == "1" &&
					$queryRes->isTenpaySign() &&
					$queryRes->getParameter("retcode")=="0" &&
					$resHandler->getParameter("trade_state")=="0")
				{
					if ($model->success($osn , $money , $trade_no , 3 , $this->getCallBack()))
						Yii::app()->end('success');
				}
			}
		}
		//失败
		$model->failure($osn , $money , $trade_no , 3 , $this->getCallBack());
		Yii::app()->end('fail');
	}
	
	//财付通 同步回调
	public function actionTenpayReturn()
	{
		Yii::import('system.extensions.payment.tenpay.Tenpay' , true);
		
		$config = Tenpay::getConfig();
		$resHandler = new ResponseHandler();
		$resHandler->setKey($config['key']);
		
		#$notify_id		= $resHandler->getParameter("notify_id");		# 通知id
		$osn			= (string)$resHandler->getParameter("out_trade_no");	# 商户订单号
		$trade_no		= (string)$resHandler->getParameter("transaction_id");	# 财付通订单号
		$money			= (double)$resHandler->getParameter("total_fee");		# 金额,以分为单位
		$discount		= (double)$resHandler->getParameter("discount");		# 如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
		$trade_state	= (int)$resHandler->getParameter("trade_state");		# 支付结果
		$trade_mode		= (int)$resHandler->getParameter("trade_mode");			# 交易模式,1即时到账
		$money			= ($money + $discount) / 100;
		
		$model = ClassLoad::Only('Pay');/* @var $model Pay */
		//判断签名
		if($resHandler->isTenpaySign())
		{
			//成功
			if(1 === $trade_mode && 0 === $trade_state)
			{
				if ($model->success($osn , $money , $trade_no , 3 , $this->getCallBack()))
					$this->redirect(array('pay/finish' , 'osn'=>$osn));
			}
		}
		//失败
		$model->failure($osn , $money , $trade_no , 3 , $this->getCallBack());
		$this->redirect(array('pay/finish' , 'osn'=>$osn));
	}
	
	private function getCallBack()
	{
		$request = Yii::app()->getRequest();
		return array(
			'url'		=> $request->getHostInfo() . $request->getUrl(),
			'get'		=> $_GET,
			'post'		=> $_POST,
			'cookie'	=> $_COOKIE,
		);
	}
}