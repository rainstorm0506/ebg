<?php
/**
 * 用户评论管理 控制器
 * @author jeson.Q
 */
class OrderCommentController extends SController {
	// 首页订单列表------(暂时不用)
	public function actionOrderList() {
		$keyword = $this->getParam ( 'keyword' );
		
		if ($keyword) {
			$orderComment = Order::model ()->searchAllByCondition ( $keyword );
		} else {
			$orderComment = Order::model ()->findAll ();
		}
		$this->render ( 'order_list', array (
			'orderComment' => $orderComment 
		) );
	}
	
	// 显示评论列表
	public function actionCommentList() {
			// 设置访问权限
		$this->checkUserPurview('comment:l');
		$keyword = $this->getParam ( 'keyword' );
		$model = ClassLoad::Only ( 'OrderComment' ); /* @var $model OrderComment */
		// 设置分页
		$pageNow = $this->getParam ( 'pagenum', 0 );
		$count = $model->getTotalNumber ( $keyword );
		$page = new CPagination ();
		$page->pageSize = 20;
		$page->pageVar = 'pagenum';
		$page->setItemCount ( $count );
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$orderComment = $model->getList ( $keyword, $offset, $page->pageSize );
		$this->render ( 'list', array (
			'orderComment' => $orderComment,
			'page' => $page,
			'keyword'=> $keyword
		) );
	}
	// 查看或回复 评论
	public function actionEdit() {
		//设置访问权限
		$this->checkUserPurview('comment:r');
		// 加载类 、检查数据
		$form = ClassLoad::Only('OrderCommentForm');/* @var $form OrderCommentForm */
		$this->exitAjaxPost($form);
		
		if (! $id = ( int ) $this->getQuery ( 'id'))
			$this->error ( '错误的ID' );
		
		$model = ClassLoad::Only('OrderComment');/* @var $model OrderComment */
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		
		// 判断是否是 新回复
		if (isset ( $_POST ['OrderCommentForm'] )) {
			$form->attributes = $_POST ['OrderCommentForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['OrderCommentForm'], $id );
				// $this->redirect(array('goodsComment/commentList'),array('id'=>$info['order_sn']));
			}
			$this->redirect ( array (
				'orderComment/commentList' 
			) );
		} else {
			// 渲染试图
			$this->render ( 'edit', array (
				'form' => $form,
				'info' => $info 
			) );
		}
	}
	
	// 改变状态的ajax（操作 是否 屏蔽）
	public function actionChangeShow() {
		//设置访问权限
		$this->checkUserPurview('contentType:d');
		$cid = $_POST ['cid'];
		$status = $_POST ['is_show'];
		if ($cid) {
			$model = ClassLoad::Only ( 'OrderComment' ); /* @var $model OrderComment */
			$flag = $model->updateCommentShow ( $cid, $status );
			echo $flag;
		} else {
			echo 0;
		}
	}
}
