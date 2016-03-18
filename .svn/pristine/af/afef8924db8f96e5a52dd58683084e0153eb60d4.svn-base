<?php
/**
 * 集采管理-控制器 - 控制器
 * 
 * @author Jeson.Q
 */
class PurchaseController extends ApiController
{
	//商家集采未报价--首页
	public function actionPurchaseList()
	{
		$model = ClassLoad::Only('Purchase'); 		/* @var $model Purchase */
		$form = ClassLoad::Only('PurchaseForm'); 	/* @var $form PurchaseForm */
		
		$apt = (int)$this->getPost('apt');
		$type = (int)$this->getPost('type');		//筛选订单状态ID
		$pageNow = (int)$this->getPost('pageNow');	//查询开始位置
		$pageSize = (int)$this->getPost('pageSize');//查询条数
		$uid = (int)$this->getMerchantID();

		$parms = array(
			'apt' => $apt,
			'type' => $type
		);
		$form->optionType = 'list';
		$form->attributes = $parms;
		if($form->validate()){
			//查询订单列表
			$purchaseList = $model->getList($uid, $type, $pageNow, empty($pageSize) ? 10 : $pageSize);
			$this->jsonOutput(0, $purchaseList);
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1, $this->getFormError($form));
	}

	//商家集采详细页
	public function actionShowDetail()
	{
		$apt = (int)$this->getPost('apt');				//APP抛数据的时间
		$purchase_sn = (string)$this->getPost('pid');	//订单号
		$uid = (int)$this->getMerchantID();
		$form = ClassLoad::Only('PurchaseForm'); 		/* @var $form PurchaseForm */
		$model = ClassLoad::Only('Purchase'); 			/* @var $model Purchase */
		$param = array(
			'pid' => $purchase_sn,
			'apt' => $apt
		);
		$form->optionType = 'detail';
		$form->attributes = $param;
		if ($form->validate()){
			$purchaseDetail = $model->getActiveInfo($purchase_sn, $uid);
			if(empty($purchaseDetail)){
				$this->jsonOutput(1,'无该集采订单号数据！');
			}else{
				$this->jsonOutput(0, $purchaseDetail);
			}
		}

		// 接口传入数据验证有误
		$this->jsonOutput(1, $this->getFormError($form));
	}

	//商家报价操作
	public function actionMerchantPrice()
	{
		if(isset($_GET))
		{
			$apt = (int)$this->getPost('apt');				//APP抛数据的时间
			$pid = (string)$this->getPost('pid');			//集采订单号
			$gid = (int)$this->getPost('gid');				//集采订单报价商品ID
			$goodsNum = (string)$this->getPost('goodsNum');				//集采订单报价商品ID
			$price = (string)$this->getPost('price');		//操作类型名称
			
			$form = ClassLoad::Only('PurchaseForm');		/* @var $form PurchaseForm */
			$model = ClassLoad::Only('Purchase'); 			/* @var $model Purchase */
			$param = array(
				'apt' => $apt,
				'pid' => $pid,
				'gid' => $gid,
				'goodsNum' => $goodsNum,
				'price' => $price
			);
			//商家报价操作
			$form->optionType = 'option';
			$form->attributes = $param;
			if($form->validate())
			{
				$info['isPrice'] = $model->setGoodsPrice($param, $pid, $gid);
				$this->jsonOutput(0, $info);
			}
			// 接口传入数据验证有误
			$this->jsonOutput(1, $this->getFormError($form));
		}
	}
	
	//查询当前商家所有商品操作
	public function actionMerchantGoods()
	{
		if(isset($_POST))
		{
			$apt = (int)$this->getPost('apt');			//APP抛数据的时间
			$uid = (int)$this->getMerchantID();			//商家ID
			$form = ClassLoad::Only('PurchaseForm');	/* @var $form PurchaseForm */
			$model = ClassLoad::Only('Purchase'); 		/* @var $model Purchase */
			$param = array(
				'apt' => $apt
			);
			//商家报价操作
			$form->optionType = 'express';
			$form->attributes = $param;
			if($form->validate())
			{
				$info = $model->selectGoodsList($uid);
				$this->jsonOutput(0, $info);
			}
			// 接口传入数据验证有误
			$this->jsonOutput(1, $this->getFormError($form));
		}
	}

	//查询单个商品详情操作
	public function actionGoodsDetail()
	{
		if(isset($_POST))
		{
			$apt = (int)$this->getPost('apt');			//APP抛数据的时间
			$uid = (int)$this->getMerchantID();			//商家ID
			$gid = (int)$this->getQuery('gid');			//商品ID
			$form = ClassLoad::Only('PurchaseForm');	/* @var $form PurchaseForm */
			$model = ClassLoad::Only('Purchase'); 		/* @var $model Purchase */
			$param = array(
				'apt' => $apt,
				'gid' => $gid
			);
			//商家报价操作
			$form->optionType = 'select';
			$form->attributes = $param;
			if($form->validate())
			{
				$info = $model->selectGoodsDetail($gid, $uid);
				$this->jsonOutput(0, $info);
			}
			// 接口传入数据验证有误
			$this->jsonOutput(1, $this->getFormError($form));
		}
	}
}