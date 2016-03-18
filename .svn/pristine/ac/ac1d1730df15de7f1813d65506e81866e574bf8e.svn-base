<?php
//date_default_timezone_set('PRC');
/**
 * 集采管理-控制器 - 控制器
 * 
 * @author Jeson.Q
 */
class PurchaseController extends MerchantController
{
	//商家集采未报价--首页
	public function actionIndex()
	{
		$this->leftNavType = 'purchase';
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$id = (int)$this->getMerchantID();
		$searchPost = isset($_POST['PurchaseForm']) ? $_POST['PurchaseForm'] : array();

		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost,1);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//查询订单列表
		$purchaseDataAll['noPurchase'] = $model->getList($searchPost, $id, $offset, $page->pageSize, 1);
		$purchaseDataAll['page'] = $page;
		$purchaseDataAll['form'] = $form;
		$purchaseDataAll['searchPost'] = $searchPost;
		//渲染试图
		$this->render('index' , $purchaseDataAll);
	}

	//商家集采已报价--首页
	public function actionYesOffer()
	{
		$this->leftNavType = 'purchase';
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$id = (int)$this->getMerchantID();
		$searchPost = isset($_POST['PurchaseForm']) ? $_POST['PurchaseForm'] : array();
	
		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost,2);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
	
		//查询订单列表
		$purchaseDataAll['yesPurchase'] = $model->getList($searchPost, $id, $offset , $page->pageSize, 2);
		$purchaseDataAll['page'] = $page;
		$purchaseDataAll['form'] = $form;
		$purchaseDataAll['searchPost'] = $searchPost;
		//渲染试图
		$this->render('yesOffer' , $purchaseDataAll);
	}
	
	//商家集采详细页
	public function actionShowDetail()
	{
		$this->showLeftNav = false;
		$pid = $this->getParam('pid' , 0);

		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$this->exitAjaxPost($form , 'formBox');
		
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$uid = (int)$this->getMerchantID();
		$purchaseInfo = $model->getActiveInfo($pid, $uid);
		
		if(isset($_POST['PurchaseForm']))
		{	//商家报价操作
			$form->attributes = $_POST['PurchaseForm'];
			if($form->validate())
			{
				$model->setGoodsPrice($_POST['PurchaseForm'], $uid, $pid);
				$this->redirect(array('showDetail','pid'=>$pid));
			}
		}
		//查询列表并 渲染试图
		$this->render('waitOffer' , array(
			'form'=>$form,
			'goodsTree' => GlobalGoodsClass::getTree(),
			'purchaseInfo' => $purchaseInfo
		));
	}
	
	//ajax获取集采商品数据
	public function actionGoodsInfo()
	{
		$gid = $this->getParam('gid' , 0);
		if($gid){
			$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
			$purchaseInfo = $model->getPurchaseGoodsInfo($gid);
		
			echo $purchaseInfo ? json_encode($purchaseInfo) : 0;
		}else{
			echo 0;
		}
	}
	
}