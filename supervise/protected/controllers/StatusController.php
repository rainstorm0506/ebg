<?php
/**
 * 全局状态管理
 * @author 刘军军
 */
class StatusController extends SController {
	// 全局状态管理列表
	public function actionList() {
		$type = Yii::app ()->request->getParam ( 'type' );
		$model = ClassLoad::Only ( 'Status' ); /* @var $model Promotion */
		
		// 设置分页
		$pageNow = Yii::app ()->request->getParam ( 'pagenum', 0 );
		$count = $model->getTotalNumber ( $type );
		$page = new CPagination ();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount ( $count );
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$globalStatus = $model->getList ( $type, $offset, $page->pageSize );
		$this->render ( 'list', array (
				'globalStatus' => $globalStatus,
				'page' => $page 
		) );
	}
	
	// 编辑全局状态管理
	public function actionEdit() {
		$this->checkUserPurview ( 'nf.my' );
		// 加载类 、检查数据
		$form = ClassLoad::Only ( 'StatusForm' ); /* @var $model Status  */
		$model = ClassLoad::Only ( 'Status' );/* @var $form StatusForm */
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
			// 是否提交数据
		if (isset ( $_POST ['StatusForm'] )) {
			$form->attributes = $_POST ['StatusForm'];
			if ($form->validate ()) {
				$model->modifys ( $_POST ['StatusForm'], $id );
				$this->redirect ( array (
						'Status/list' 
				) );
			}
		}
		// 渲染试图
		$this->render ( 'edit', array (
				'form' => $form,
				'info' => $info 
		) );
	}
}