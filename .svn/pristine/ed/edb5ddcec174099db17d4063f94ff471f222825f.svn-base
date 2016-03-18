<?php
class PurchaseController extends WebController
{
	public function init()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('purchase' , 0));
	}
	
	public function actionIndex()
	{
		$userInfo = $this->getUser();
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$searchPost = isset($_GET) ? $_GET : array();
		
		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		//查询订单列表
		$purchaseList = $model->getList($searchPost, $offset, $page->pageSize);
		
		$this->render('index',array(
			'page' => $page,
			'form' => $form,
			'purchaseList' => $purchaseList,
			'searchPost' => $searchPost,
			'user_type' => isset($userInfo['user_type']) ? $userInfo['user_type'] : 0,
			'uid'=> isset($userInfo['id']) ? $userInfo['id'] : 0,
		));
	}

	//商家集采详细页
	public function actionShowDetail()
	{
		$pid = $this->getParam('pid' , 0);

		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$this->exitAjaxPost($form , 'formBox');
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getGoodsTotalNumber($pid);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 6;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		$purchaseInfo = $model->getActiveInfo($pid, $offset, $page->pageSize);
		
		if(isset($_POST['PurchaseForm']))
		{
			$form->attributes = $_POST['PurchaseForm'];
			if($form->validate())
			{
				$model->createWithdrawal($_POST['PurchaseForm'], $uid);
				$this->redirect(array('list'));
			}
		}
		//查询列表并 渲染试图
		$this->render('showDetail' , array(
			'form'=>$form,
			'page' => $page,
			'goodsTree' => GlobalGoodsClass::getTree(),
			'purchaseData' => $purchaseInfo
		));
	}

	//发布采购单详细页
	public function actionPublic()
	{
		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$this->exitAjaxPost($form , 'formBox');
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */

		if(isset($_POST['PurchaseForm']))
		{
			$form->attributes = $_POST['PurchaseForm'];
			if($form->validate())
			{
				$model->createPurchase($_POST['PurchaseForm']);
				$this->redirect(array('success','phone'=>$_POST['PurchaseForm']['phone']));
			}
		}
		//查询列表并 渲染试图
		$this->render('publish' , array(
			'form'=>$form
		));
	}
	
	//发布采购单详细页
	public function actionSuccess()
	{
		$phone = $this->getParam('phone' , 0);
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */

		//查询列表并 渲染试图
		$this->render('success',array('phone'=>$phone));
	}
	
// 	//发布采购单详用户登录操作
// 	public function actionPhoneLogin()
// 	{
// 		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
// 		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
// 		if(isset($_POST['PurchaseForm']))
// 		{
// 			$form->attributes = $_POST['PurchaseForm'];
// 			if($form->validate())
// 			{
// 				$session = Yii::app()->session;
// 				$session['userInfo'] = array('phone'=>$_POST['PurchaseForm']['phone'],'verCode'=>$_POST['PurchaseForm']['codeNum'],'sendTime'=>time());
// 				//$model->addUserInfo($_POST['PurchaseForm']);
// 			}
// 		}
// 		//查询列表并 渲染试图
// 		$this->redirect(array('index'));
// 	}
}