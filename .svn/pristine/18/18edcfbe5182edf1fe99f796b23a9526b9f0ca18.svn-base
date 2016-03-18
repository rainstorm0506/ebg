<?php
/**
 * 订单管理 - 退货列表 控制器
 * @author jeson.Q
 */
class SalesReturnController extends SController {
	// 退货列表
	public function actionList() {
		// $this->checkUserPurview('nf.my');
		$keyword = $this->getParam ( 'keyword' );
		$model = ClassLoad::Only ( 'GoodsReturn' ); /* @var $model GoodsReturn */
		// 设置分页
		$pageNow = $this->getParam ( 'pagenum', 0 );
		$count = $model->getTotalNumber ( $keyword, 1 );
		$page = new CPagination ();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount ( $count );
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$goodsReturn = $model->getList ( $keyword, $offset, $page->pageSize, 1 );
		$this->render ( 'list', array (
			'goodsReturn' => $goodsReturn,
			'page' => $page 
		) );
	}
	
	// 编辑退货
	public function actionEdit() {
		// $this->checkUserPurview('nf.my');
		// 加载类 、检查数据
		$model = ClassLoad::Only ( 'GoodsReturn' ); /* @var $model GoodsReturn */
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id'))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
			
		// 是否提交数据
		if (isset ( $_POST ['GoodsReturn'] )) {
			$model->attributes = $_POST ['GoodsReturn'];
			if ($model->validate ()) {
				$model->modifys ( $_POST ['GoodsReturn'], $id );
				$this->redirect ( array (
					'goodsReturn/list' 
				) );
			}
		}
		// 渲染试图
		$this->render ( 'edit', array (
			'form' => $model,
			'info' => $info 
		) );
	}
	
	// 删除 广告
	public function actionClear() {
		// $this->checkUserPurview('goodsReturn.d');
		// 检查ID是否正确
		if (! $id = ( int ) $this->getQuery ( 'id'))
			$this->error ( '错误的ID' );
		
		$model = ClassLoad::Only ( 'GoodsReturn' ); /* @var $model GoodsReturn */
		$model->clear ( $id );
		$this->redirect ( array (
			'goodsReturn/list' 
		) );
	}
}
