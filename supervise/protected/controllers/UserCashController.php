<?php
	/**
	 * 用户提现管理控制器
	 *
	 * @author 夏勇高
	 */
	class UserCashController extends SController {

		/**
		 * 0提现申请，1审核成功，2审核失败，3提现成功
		 */
		public $withState = array(
			0=>'待提现',
			1=>'审核成功',
			2=>'已提现',
			3=>'审核失败'
		);
		
		public function actionAccount()
		{
			$this->checkUserPurview("cash.c");
			$form=ClassLoad::Only("UserCashAccountForm");
			$this->exitAjaxPost($form);
			$uid=$this->getQuery("id");
			if(isset($_POST['UserCashAccountForm'])) {
				$form->attributes=$_POST['UserCashAccountForm'];

				if($form->validate()) {
					$model=ClassLoad::Only('UserCash');/* @var $model UserCash */
					$model->addAccount($uid, $_POST['UserCashAccountForm']);
					$this->redirect(array('userCash/apply?id='.$uid));
				}
			}
			
			$this->render("account",array(
				'form'=>$form
				)
			);
		}
		
		// 提现记录列表
		public function actionList() {
			$this -> checkUserPurview("cash.l");
			$keyword = $this->getQuery("keyword");
			$type=$this->getQuery("type");
			$uid=$this->getQuery("id");
			$model = ClassLoad::Only("UserCash");
			
			$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
			$page->pageVar = 'p';
			$page->setItemCount($model->getCount($uid, $type, $keyword));
			$page->pageSize = 20;
			
			$this -> render("list", array(
				'page'		=>	$page,
				'list'		=>	$model->getList($uid, $type, $keyword , $page->getOffset() , $page->getLimit() , $page->getItemCount())
			));
		}
		
		// 提现记录日志列表
		public function actionLogs() {
			$this -> checkUserPurview("cash.l");
			$rid = (int)$this->getQuery("id");
			$model= ClassLoad::Only("UserCash");
			$model->getCashLogs($rid);
			$this -> render("logList", array(
				"info"	=>$model->getCashRecord($rid),
				"logs" => $model->getCashLogs($rid)));
		}
		
		// 申请提现
		public function actionApply()
		{
			$this->checkUserPurview('cash.c');
			
			$form=ClassLoad::Only('UserCashRecordForm');/* @var $form UserCashRecordForm */
			$this->exitAjaxPost($form);
			
			$model = ClassLoad::Only("UserCash");
			// $suid = (int)$this->getUid();// Session User's ID
			$suid = $this->getQuery("id");
			if(isset($_POST['UserCashRecordForm'])) {
				$form->attributes=$_POST['UserCashRecordForm'];
				if($form->validate()) {
					$model->supplyCash($suid, $_POST['UserCashRecordForm']);
					$this->redirect(array('userCash/list'));
				}
			}
			
			$this->render("apply",array(
				"uid"	=>$suid,
				"accounts"=>$this->userAccounts($suid),
				"form"=>$form)
			);
		}
		
		public function actionVerify()
		{
			$model=ClassLoad::Only("UserCash");
			$form=ClassLoad::Only('UserCashRecordForm');/* @var $form UserCashRecordForm */
			$rid=$this->getQuery("id");
			
			if(isset($_POST['UserCashRecordForm'])){
				$state=$_POST['UserCashRecordForm']['state'];
				$remark=$_POST['UserCashRecordForm']['remark'];
				$tradeSN=$_POST['UserCashRecordForm']['sn'];
				$tradeTime=$_POST['UserCashRecordForm']['time'];
				$model=ClassLoad::Only("UserCash");
				
				if($state=='Y'){
					$model->verifyPass($rid, $remark,$tradeSN,$tradeTime);
				} else if($state=='N'){
					$record=$model->getCashRecord($rid);
					$model->verifyUnpass($rid, $remark,$record['amount'],$record['uid'],$tradeSN,$tradeTime);
				}
				$this->redirect(array('userCash/list'));
			}
			
			$this->render("verify", array(
				'info'	=>$model->getCashRecord($rid),
				"form"=>$form
			));
		}
		
		/**
		 * 确认提现操作
		 */
		public function actionConfirm()
		{
			$this->checkUserPurview("cash.l");
			$model=ClassLoad::Only("UserCash");
			$rid=(int)$this->getQuery("id");
			$model->confirmCash($rid);
			$this->redirect(array('userCash/list'));
		}
		
		public $accounts=array();
		/**
		 * 获取某会员用户的提现账户信息
		 */
		public function userAccounts($uid)
		{
			$model = ClassLoad::Only("UserCash");
			$accounts = $model->getAccounts($uid);
			foreach ($accounts as $key => $val) {
				$this->accounts[$val['id']] = $val['account'].'('.$val['bank'].')';
			}
			return $this->accounts;
		}
		
	}
?>