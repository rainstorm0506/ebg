<?php
/**
 * 促销管理 - 满减栏目 控制器
 * @author 刘军军
 */
class ReductionController extends SController {
	public $active_starttime = '';
	public $active_endtime = '';
	
	// 满减栏目列表
	public function actionList() {
		$keyword = $this->getParam ('keyword');
		$model = ClassLoad::Only ('Reduction');/* @var $model Reduction */
		$pageNow = $this->getParam ( 'pagenum', 0 );
		$count = $model->getTotalNumber ($keyword,null);
		$page = new CPagination ();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount ( $count );
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;	
		$reduction = $model->getList($keyword ? $keyword : '',$offset, $page->pageSize);
		$this->render ( 'list', array (
				"reduction" => $reduction,
				"keyword" => $keyword,
				"page" => $page
		) );
	}
	
	// 编辑满减活动
	public function actionEdit() {
		// $this->checkUserPurview('nf.my');
		// 加载类 、检查数据
		$model = ClassLoad::Only ( 'Reduction' ); /* @var $model Reduction */
		$from = ClassLoad::Only ( 'ReductionForm' ); /* @var $from ReductionForm */
		$this->exitAjaxPost ( $from );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );		
			// 是否提交数据
		if (isset ( $_POST ['ReductionForm'] )) {
			$from->attributes = $_POST ['ReductionForm'];
			if ($from->validate ()) {
				$model->modify ( $_POST ['ReductionForm'], $id );
				$this->redirect ( array (
						'reduction/list' 
				) );
			}
		}
		// 查询列表并 渲染试图
		$this->render ( 'edit', array (
				'form' => $from,
				'info' => $info 
		) );
	}
	
	// 添加 满减活动
	public function actionCreate() {
		$form = ClassLoad::Only ( 'ReductionForm' ); /* @var $form Reductionform */
		$model = ClassLoad::Only ( 'Reduction' );/* @var $model Reduction */
		$this->exitAjaxPost ( $form );
		if (isset ( $_POST ['ReductionForm'] )) {
			$form->attributes = $_POST ['ReductionForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['ReductionForm'], null );
				$this->redirect ( array (
						'reduction/list' 
				) );
			}
		}
		// 查询列表并 渲染试图
		$this->render ( 'edit', array (
				'form' => $form 
		) );
	}
	
	// 删除 满减活动
	public function actionClear() {
		// $this->checkUserPurview('promotion.d');
		// 检查ID是否正确
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );	
		$model = ClassLoad::Only ( 'Reduction' ); /* @var $model Reduction */
		$model->clear ( $id );
		$this->redirect ( array (
				'reduction/list' 
		) );
	}
	
	//显示满减活动页面列表弹窗
	public function actionShowOption()
	{
		$reductionInfo = $reductionList = array();
		$rid = isset($_GET['rid']) ? $_GET['rid'] : '';
		//查询当前满减活动列表
		$model = ClassLoad::Only ( 'Reduction' ); /* @var $model Reduction */
		$reductionInfo = $model->getActiveInfo($rid);
		// 判断是否存在当前数据
		if ($reductionInfo['actList']) {
			foreach ( $reductionInfo['actList'] as $keys => $vals ) {
				$reductionList[$keys]['id'] = $keys + 1;
				$reductionList[$keys]['expire'] = $vals['expire'];
				$reductionList[$keys]['minus'] = $vals['minus'];
			}
		}
		// 查询列表并 渲染试图

		$this->render('show_detail', array('reductionList' => $reductionList, 'reduction_name' => $reductionInfo['title']));
	}
}
