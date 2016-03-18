<?php
class CartController extends WebController
{
	//购物车
	public function actionIndex()
	{
		$this->mainLayoutNav = false;
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('cart' , 0));
		
		#用户的等级数据
		if ($user = $this->getUser())
			$user = GlobalUser::getUserLayer($user['exp'] , $user['user_type']);
		
		$cartData = empty(Yii::app()->session[self::CARTKEY]) ? array() : Yii::app()->session[self::CARTKEY];
		$model = ClassLoad::Only('Cart');/* @var $model Cart */
		$goods = $model->getCartList($cartData);
		# $cartData 是传址模式 , 剔除了无库存的商品 , 更新 session
		Yii::app()->session[self::CARTKEY] = $cartData;
		$this->initUserInfo();
		
		$this->render('index' , array(
			'goods'			=> $goods,
			'select'		=> empty($cartData['select']) ? array() : $cartData['select'],
			'privilege'		=> GlobalActivities::getUserPrivilege($this->getUid()),
			'reduction'		=> GlobalActivities::getReduction(),
			'freeFreight'	=> isset($user['free_freight']) ? $user['free_freight'] : 0,
		));
	}
	
	//立即结算
	public function actionSettle()
	{
		//选中商品
		if ($goods = (array)$this->getPost('goods'))
		{
			$seArray = array();
			if (empty(Yii::app()->session[self::CARTKEY]))
			{
				$this->redirect($this->createUrl('cart/index'));
			}else{
				$seArray = new ArrayObject(Yii::app()->session[self::CARTKEY]);
				$seArray = $seArray->getArrayCopy();
			}
				
			$seArray['select'] = array();
			foreach ($goods as $k => $v)
				$seArray['select'][$k] = 1;
			Yii::app()->session[self::CARTKEY] = $seArray;
			$this->redirect($this->createUrl('cart/closing'));
		}else{
			$this->error('请选择商品后在结算!');
		}
	}

	//结算
	public function actionClosing()
	{
		$this->mainLayoutNav = false;
		
		$form		= ClassLoad::Only('CartForm');/* @var $form CartForm */
		$cartData	= empty(Yii::app()->session[self::CARTKEY]) ? array() : Yii::app()->session[self::CARTKEY];
		$select		= empty($cartData['select']) ? array() : $cartData['select'];	# 选中的商品
		$carts		= empty($cartData['goods']) ? array() : $cartData['goods'];		# 购物车主数据
		
		foreach ($carts as $mid => $merVal)
		{
			foreach ($merVal as $k => $v)
			{
				if (empty($select[$k]))
					unset($cartData['goods'][$mid][$k]);
				
				if (empty($cartData['goods'][$mid]))
					unset($cartData['goods'][$mid]);
			}
		}
		
		$totals			= 0;
		$model			= ClassLoad::Only('Cart');/* @var $model Cart */
		$goods			= $model->getCartList($cartData , $totals);
		$changeLock		= md5(serialize($goods));
		//无商品返回购物车首页
		if (!$goods)
			$this->redirect('cart/index');
		
		//提交订单
		if($this->isPost() && isset($_POST['CartForm']))
		{
			$form->attributes = $_POST['CartForm'];
			
			//商品已改变
			if ($changeLock != $form->changeLock)
				$this->redirect('cart/closing');
			
			if($form->validate())
			{
				//创建订单
				if ($orderSN = $model->createOrders($_POST['CartForm'] , $goods , $totals))
				{
					$seArray = new ArrayObject(Yii::app()->session[self::CARTKEY]);
					$seArray = $seArray->getArrayCopy();
					foreach ($seArray['select'] as $k => $v)
					{
						foreach ($seArray['goods'] as $mid => $merVal)
						{
							if (isset($merVal[$k]))
								unset($seArray['goods'][$mid][$k]);
							
							if (empty($seArray['goods'][$mid]))
								unset($seArray['goods'][$mid]);
						}
					}
					
					$seArray['cartNum'] -= count($seArray['select']);
					unset($seArray['select']);
					
					Yii::app()->session[self::CARTKEY] = $seArray;
					
					if ($form->payType == 1)
					{
						$this->redirect(array('pay/index' , 'osn'=>$orderSN));
					}else{
						$this->redirect(array('pay/finish' , 'osn'=>$orderSN));
					}
				}else{
					$this->error('订单生成失败!');
				}
			}
		}
		
		Yii::app()->getClientScript()->registerCoreScript('layer');
		$this->render('closing' , array(
			'goods'				=> $goods,
			'totals'			=> $totals,
			'privilege'			=> $model->getUsablePrivilege($totals),
			'reduction'			=> $model->getUsableReduction($totals),
			'form'				=> $form,
			'changeLock'		=> $changeLock,
		));
	}
	
	/**
	 * 计算预生成订单商品应该的运费
	 * 
	 * @param		string		dict		地址ID , 用|间隔
	 * @param		string		weight		重量 , 用|间隔
	 */
	public function actionOrderFreight()
	{
		if (!$dict = (string)$this->getQuery('dict'))
			$this->jsonOutput(1 , '参数有误');
		
		if (!$changeLock = (string)$this->getQuery('changeLock'))
			$this->jsonOutput(2 , '参数有误');
		
		$dict = explode('|' , $dict);
		if (empty($dict[0]) || empty($dict[1]))
			$this->jsonOutput(3 , '参数有误');
		
		$goods = array();
		if (!$this->verifyChangeLock($changeLock , $goods))
			$this->jsonOutput(-1 , $this->createUrl('cart/closing'));
		
		$model = ClassLoad::Only('Cart');/* @var $model Cart */
		$money = $model->getDictFreight($goods , $dict);
		$this->jsonOutput(0 , array('money'=>$money));
	}
	
	private function verifyChangeLock($changeLock , &$goods)
	{
		$cartData	= empty(Yii::app()->session[self::CARTKEY]) ? array() : Yii::app()->session[self::CARTKEY];
		$select		= empty($cartData['select']) ? array() : $cartData['select'];	# 选中的商品
		$carts		= empty($cartData['goods']) ? array() : $cartData['goods'];		# 购物车主数据
		foreach ($carts as $mid => $merVal)
		{
			foreach ($merVal as $k => $v)
			{
				if (empty($select[$k]))
					unset($cartData['goods'][$mid][$k]);
		
				if (empty($cartData['goods'][$mid]))
					unset($cartData['goods'][$mid]);
			}
		}
		$model = ClassLoad::Only('Cart');/* @var $model Cart */
		$goods = $model->getCartList($cartData);
		
		return md5(serialize($goods)) == $changeLock;
	}
	
	//修改数量
	public function actionChangeAmount()
	{
		if (!$key = (string)$this->getQuery('key'))
			$this->jsonOutput(1 , '异常!');
		
		if (($amount = (int)$this->getQuery('amount')) < 1)
			$this->jsonOutput(2 , '数量错误');
		
		if (!empty(Yii::app()->session[self::CARTKEY]['goods']))
		{
			$seArray = new ArrayObject(Yii::app()->session[self::CARTKEY]);
			$seArray = $seArray->getArrayCopy();
			
			$currentMID = 0;
			foreach ($seArray['goods'] as $mid => $merVal)
			{
				if (isset($merVal[$key]['amount']))
				{
					$currentMID = $mid;
					$seArray['goods'][$mid][$key]['amount'] = $amount;
					break;
				}
			}
			Yii::app()->session[self::CARTKEY] = $seArray;
			
			$model = ClassLoad::Only('Cart');/* @var $model Cart */
			if ($list = $model->getCartList($seArray))
			{
				if (isset($list[$currentMID][$key]))
				{
					$this->jsonOutput(0 , array(
						'final_price'	=> $list[$currentMID][$key]['final_price'],
						'final_total'	=> $list[$currentMID][$key]['final_total'],
						'final_stock'	=> $list[$currentMID][$key]['final_stock'],
						'final_weight'	=> $list[$currentMID][$key]['final_weight'],
					));
				}
			}
		}
		
		$this->jsonOutput(3 , '异常!');
	}
	
	//删除商品
	public function actionDelete()
	{
		$key = (string)$this->getQuery('key');
		$goods = (array)$this->getPost('goods');
		if (!$key && !$goods)
			$this->error('key 错误!');
		
		if (isset(Yii::app()->session[self::CARTKEY]['goods']))
		{
			$seArray = new ArrayObject(Yii::app()->session[self::CARTKEY]);
			$seArray = $seArray->getArrayCopy();
			
			if ($key)
				$goods = array($key => 1);
			
			foreach ($seArray['goods'] as $mid => $merVal)
			{
				foreach ($goods as $k => $v)
				{
					if (isset($merVal[$k]))
					{
						$seArray['cartNum']--;
						unset($seArray['goods'][$mid][$k] , $seArray['select'][$k]);
						
						if (empty($seArray['goods'][$mid]))
							unset($seArray['goods'][$mid]);
					}
				}
			}
			Yii::app()->session[self::CARTKEY] = $seArray;
		}
		$this->redirect('cart/index');
	}
	
	/**
	 * 商品页 - 立即购买
	 * 
	 * @param	GET		int			amount					数量
	 * @param	GET		int			type					商品类型 , 1=新品 , 2=二手商品
	 * @param	GET		int			gid						商品ID
	 * @param	GET		string		attrs_1_unite_code		商品1属性ID
	 * @param	GET		string		attrs_2_unite_code		商品2属性ID
	 * @param	GET		string		attrs_3_unite_code		商品3属性ID
	 */
	public function actionPromptly()
	{
		$this->_cartJoin(true);
		$this->jsonOutput(0 , array('src'=>$this->createUrl('cart/closing')));
	}
	
	/**
	 * 商品页 - 加入购物车
	 * 
	 * @param	GET		int			amount					数量
	 * @param	GET		int			type					商品类型 , 1=新品 , 2=二手商品
	 * @param	GET		int			gid						商品ID
	 * @param	GET		string		attrs_1_unite_code		商品1属性ID
	 * @param	GET		string		attrs_2_unite_code		商品2属性ID
	 * @param	GET		string		attrs_3_unite_code		商品3属性ID
	 */
	public function actionJoin()
	{
		$count = $this->_cartJoin(false);
		$this->jsonOutput(0 , array('total'=>$count));
	}
	
	private function _cartJoin($select = true)
	{
		if (!$this->getUid())
			$this->jsonOutput(1 , '请登录后在购买!');
		
		$ut = $this->getUserType();
		if ($ut === 3)
			$this->jsonOutput(2 , '商家会员不能购买商品!');
		if ($ut < 1 || $ut > 2)
			$this->jsonOutput(3 , '会员类型不正确!');
		
		$type = (int)$this->getQuery('type');
		if ($type < 1 || $type > 2)
			$this->jsonOutput(4 , '商品类型错误');
		
		if (($gid = (int)$this->getQuery('gid')) <= 0)
			$this->jsonOutput(5 , '商品ID错误');
		
		if (($amount = (int)$this->getQuery('amount')) < 1)
			$this->jsonOutput(6 , '商品数量错误');
		
		$pars = array(
			'amount'				=> $amount,
			'type'					=> $type,
			'gid'					=> $gid,
			'attrs_1_unite_code'	=> (string)$this->getQuery('attrs_1_unite_code'),
			'attrs_2_unite_code'	=> (string)$this->getQuery('attrs_2_unite_code'),
			'attrs_3_unite_code'	=> (string)$this->getQuery('attrs_3_unite_code'),
		);
		
		$mid = 0;
		if ($type == 1)
		{
			//全新商品
			$model = ClassLoad::Only('Cart');/* @var $model Cart */
			if (!$goods = $model->getGoodsList(array($gid)))
				$this->jsonOutput(7 , '商品不存在,或者商品状态异常');
			
			if (empty($goods[$gid]['merchant_id']) || ($mid = $goods[$gid]['merchant_id']) < 0)
				$this->jsonOutput(8 , '商品数据有误!');
		}else{
			//二手商品
			$model = ClassLoad::Only('Used'); /* @var $model Used */
			if(!$intro = $model->intro($gid , false))
				$this->jsonOutput(9 , '商品不存在,或者商品状态异常');
			
			if (empty($intro['merchant_id']) || ($mid = $intro['merchant_id']) < 0)
				$this->jsonOutput(10 , '商品数据有误!');
		}
		
		$seArray = array('cartNum' => 0);
		$session = Yii::app()->session;
		if ($session[self::CARTKEY])
		{
			$seArray = new ArrayObject($session[self::CARTKEY]);
			$seArray = $seArray->getArrayCopy();
			$seArray['cartNum'] = isset($seArray['cartNum']) ? $seArray['cartNum'] : 0;
		}
		
		$key = md5(serialize(array_slice($pars , 1)));
		//购物车数量
		$seArray['cartNum'] += isset($seArray['goods'][$mid][$key]) ? 0 : 1;
		$seArray['goods'][$mid][$key] = $pars;
		if ($select)
		{
			$seArray['select'] = array();
			$seArray['select'][$key] = 1;
		}
		
		$session[self::CARTKEY] = $seArray;
		return $seArray['cartNum'];
	}
}