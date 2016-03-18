<?php
/**
 * 企业中心--我的提现 - 控制器
 * 
 * @author Jeson.Q
 */
class MyWithdrawalController extends EnterpriseController
{
	//企业用户提现信息列表首页
	public function actionIndex()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'withdrawal';
		$model = ClassLoad::Only('Withdrawal'); /* @var $model Withdrawal */
		$id = $this->getUid();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($id);
		$page = new CPagination();
		$page->pageSize = 8;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//获取企业用户提现列表数据
		$myWithData = $model->getList($id, $offset , $page->pageSize);
		// 查询列表并 渲染试图
		$this->render('index' , array(
			'page' => $page,
			'myWithData'=>$myWithData,
		));
	}
	
	//企业用户提现操作页
	public function actionShowWithdrawal()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'withdrawal';

		$user = $this->getUser();
		$myMoney = empty($user['money']) ? 0 : (float)$user['money'];
		
		$form = ClassLoad::Only('WithdrawalForm'); /* @var $model WithdrawalForm */
		$form->myMoney = $myMoney;
		$this->exitAjaxPost($form , 'formBox');
		
		$model = ClassLoad::Only('Withdrawal'); /* @var $model Withdrawal */
		$uid = $this->getUid();
		
		if(isset($_POST['WithdrawalForm']))
		{
			$form->attributes = $_POST['WithdrawalForm'];
			if($form->validate())
			{
				$model->createWithdrawal($_POST['WithdrawalForm'], $uid);
				GlobalUser::setReflushUser($user , 1);
				$this->redirect(array('index'));
			}
		}
		// 查询列表并 渲染试图
		$this->render('selectBank' , array(
			'form'=>$form,
			'myMoney'=>$myMoney
		));
	}

}