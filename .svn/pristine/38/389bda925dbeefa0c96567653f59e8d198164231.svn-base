<?php
class PrivilegeController extends SController {
	/**
	 * 优惠券列表
	 */
	public function actionList()
	{   
		$keyword = $this->getParam('keyword');
		$model = ClassLoad::Only('Privilege');/* @var $model Privilege */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getCount($keyword));
		GlobalOrders::sendPrivilegeBySche();
		$page->pageSize = 20;
		$this->render('list' , array(
			'page' => $page,
			'privilege' => $model->getList($keyword,$page->getOffset() , $page->getLimit()),
		));
	}
	
	/**
	 * 优惠券删除
	 */
	public function actionClear() {
		// 检查ID是否正确
		if (! $id = ( int ) Yii::app ()->getRequest ()->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		$model = ClassLoad::Only ( 'Privilege' ); /* @var $model privilege */
		$model->clear ( $id );
		$this->redirect ( array (
				'privilege/list' 
		) );
	}
	
	/**
	 * 添加优惠券(订单)
	 */
	public function actionCreateOrder() {
		$model = ClassLoad::Only ('Privilege' );/* @var $model privilege */
		$form = ClassLoad::Only ('PrivilegeForm' );/* @var $form PrivilegeForm */
		$this->exitAjaxPost ( $model );
		if (isset ( $_POST ['PrivilegeForm'] )) {
			$form->attributes = $_POST ['PrivilegeForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['PrivilegeForm'], null );
				$this->redirect ( array (
						'privilege/list'
				) );
			}
		}
		$this->render ( 'editOrder', array (
				'form' => $form 
		) );
	}
	
	/**
	 * 添加优惠券(用户)
	 */
	public function actionCreateUser() {
		$model = ClassLoad::Only ('Privilege' );/* @var $model privilege */
		$form = ClassLoad::Only ('PrivilegeForm' );/* @var $form PrivilegeForm */
		$this->exitAjaxPost ( $model );
		if (isset ( $_POST ['PrivilegeForm'] )) {
			
			$form->attributes = $_POST ['PrivilegeForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['PrivilegeForm'], null );
				$this->redirect ( array (
						'privilege/list'
				) );
			}
		}
		$this->render ( 'editUser', array (
				'form' => $form
		) );
	}
	
	/**
	 * 编辑 优惠券 按用户
	 *   */
	public function actionEditUser() {
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$this->exitAjaxPost ( $model );
		$form = ClassLoad::Only ( 'PrivilegeForm' );/* @var $form PrivilegeForm */
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		if (isset ( $_POST ['PrivilegeForm'] )) {
			$form->attributes = $_POST ['PrivilegeForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['PrivilegeForm'], $id );
				$this->redirect ( array (
						'privilege/list'
				) );
			}
		}
		// 查询列表并 渲染试图
		$this->render ( 'editUser', array (
				'form' => $form,
				'info' => $info
		) );
	}
	/**
	 * 编辑 优惠券 按订单
	 *   */
	public function actionEditOrder() {
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$this->exitAjaxPost ( $model );
		$form = ClassLoad::Only ( 'PrivilegeForm' );/* @var $form PrivilegeForm */
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		if (isset ( $_POST ['PrivilegeForm'] )) {
			$form->attributes = $_POST ['PrivilegeForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['PrivilegeForm'], $id );
				$this->redirect ( array (
						'privilege/list'
				) );
			}
		}
		// 查询列表并 渲染试图
		$this->render ( 'editOrder', array (
				'form' => $form,
				'info' => $info
		) );
	}
	/****
	 按订单发放不需要发短信  编辑好直接发放
	 param @ $id  优惠券id
	 *   */
	// 发送 优惠券
	public function actionSendOrder() {
	
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$form = ClassLoad::Only ( 'PrivilegeForm' );
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		$info['sum'] = $model->getOrderSum($id);
		if (isset ( $_POST ['PrivilegeForm'] )) {
			if((int)$info['sum']<=0){
				$this->error ( '并无符合条件的订单需要发放，请重新编辑!' );
			}
			$model->addPrivilegeByOrder($id);
			$this->redirect ( array (
					'privilege/list'
			) );
		}
		// 查询列表并 渲染试图
		$this->render ( 'sendOrder', array (
				'form' => $form,
				'info' => $info
		) );
	}


	
	/*****
	 * 设置用户
	 *  */
	public function actionSettingUser() {
	
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$form = ClassLoad::Only ( 'PrivilegeForm' );
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		if (isset ( $_POST ['PrivilegeForm'] )) 
		{	
			
			if(!$_POST ['PrivilegeForm']['ids']){
				$this->error ( '未选择用户,请先设置用户!' );
			}
			if(!json_decode($_POST ['PrivilegeForm']['ids'],true)){
				$this->error ( '未选择用户,请先设置用户!' );
			}
			$ids = $model->addPrivilege( $_POST ['PrivilegeForm'],$id);
			$this->redirect ( array (
					'privilege/list'
			) );
	
		}
		// 查询列表并 渲染试图
		$this->render ( 'settingUser', array (
				'form' => $form,
				'info' => $info,
				'users' => $model->getUserSendMes($id)
		) );
	}
	
	/***
	 * 发送用户
	 * */
	public function actionSendUser() {
	
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$form = ClassLoad::Only ( 'PrivilegeForm' );
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		if (! $list = $model->getUserSendMes ( $id ))
			$this->error ( '请先设置用户!' );
	
		// 查询列表并 渲染试图
		$send['success'] = 0;
		$send['faliue'] = 0;
		foreach ($list as $key=>$row){
			if($row['send_time']>1){
				$send['success']++;
			}else{
				$send['faliue']++;
			}
		}
		$this->render ( 'sendUser', array (
				'form' => $form,
				'info' => $info,
				'list' => $list,
				'send' => $send
		) );
	}
	
	/**
	 *发送短信 
	 *  */
	public function actionSendNote()
	{
		$model = ClassLoad::Only ( 'Privilege' );/* @var $model Privilege */
		$this->exitAjaxPost ( $model );
		if (! $act_id = ( int ) $this->getQuery ( 'act_id', 0 ))
			$this->error ( '错误的奖券id' );
		$id = $this->getQuery ( 'id', 0 );
 		$model->sendNote($act_id,$id);
		// 查询列表并 渲染试图
		$this->redirect ( array (
			'privilege/SendUser','id'=>$act_id
		) );
	
	}
	

	// 通过手机号 用户名匹配用户
	public function ActionFindUsers() {
		$model = ClassLoad::Only ( 'Privilege' ); /* @var $model Privilege */
		if (empty ( $_POST ['p'] )) {
			$p = 1;
		} else {
			$p = intval ( $_POST ['p'] );
		}
		$count = 20;
		$limit = " limit ".(($p - 1) * $count) . "," . $count;
		$info = $model->findUserstype ( $_POST, $limit );
		if (empty ( $info ['data'] )) {
			echo json_encode ( array (
					'code' => 3,
					'data' => array (),
					'mes' => '未找到你查找的用户或者' 
			) );
			exit ();
		}
		$ids = array ();
		$sum = 0;
		foreach ( $info ['all'] as $k => $row ) {
			$ids [] = $row ['id'];
			$sum ++;
		}
		$page_str = '';
		if ($sum > $count) {
			$page_str = $this->getPage ( $sum, $count, $p, $search = 'search2' );
		}
		echo json_encode ( array (
				'code' => 1,
				'data' => $info ['data'],
				'count' => $sum,
				'page' => $page_str,
				'limit' => $limit,
		) );
		exit ();
	}
	
	/***
	 * ajax分页
	 *   */
	public function getPage($sum, $count, $p, $search) {
		$lastpage = intval ( $sum / $count ) + 1;
		$page_str = '';
		$page_str = '<ul class="linkx">';
		$page_str .= '<li><div>共'.$sum.'条记录</div></li>';
		if ($p != 1) {
			$page_str .= '<li><div onclick="' . $search . '(1);">首页</div></li>';
			$page_str .= '<li><div onclick="' . $search . '(' . ($p - 1) . ');">上页</div></li>';
		}
		if ($p != $lastpage) {
			$page_str .= '<li><div onclick="' . $search . '(' . ($p + 1) . ');">下页</div></li>';
			$page_str .= '<li><div onclick="' . $search . '(' . $lastpage . ');">尾页</div></li>';
		}
		$page_str .= '</ul>';
		return $page_str;
	}
}
