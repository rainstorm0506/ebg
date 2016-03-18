<?php
class DiscountController extends SController {
	public $active_starttime = '';
	public $active_endtime = '';
	// 折扣栏目列表
	public function actionList() {
		$keyword = $this->getQuery('keyword');
		$model = ClassLoad::Only ('Discount');/* var model Discount*/
		$pageNow = $this->getQuery ( 'pagenum', 0 );
		$count = $model->getTotalNumber ($keyword,$type=2);
		$page = new CPagination ();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount ( $count );
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;	
		if ($keyword) {
			$Discount = $model->getList($keyword,$offset, $page->pageSize, $typeId = 2);
		} else {
			$Discount = $model->getList($keyword=null,$offset, $page->pageSize, $typeId = 2);
		}
		$this->render ( 'list', array (
				"Discount" => $Discount,
				"keyword" => $keyword,
				"page" => $page
		) );
	}
	// 添加折扣
	public function actionCreate() {
		// $this->checkUserPurview('nf.ce');
		$form = ClassLoad::Only ( 'DiscountForm' );/* var Form DiscountForm*/
		$model = ClassLoad::Only ( 'Discount' );/* var Model Discount*/
		$this->exitAjaxPost ( $form );
		
		// 是否提交数据
		if (isset ( $_POST ['DiscountForm'] )) {
			$form->attributes = $_POST ['DiscountForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['DiscountForm'], null );
				$this->redirect ( array (
						'discount/list' 
				) );
			}
		}
		$brand = $model->getGoodsBrand ();
		$types = $model->getGoodsType ();
		// 查询列表并 渲染试图
		$this->render ( 'edit', array (
				'form' => $form,
				'types' => $types,
				'brand' => $brand 
		) );
	}
	
	// ajax请求返回商品列表
	public function actionGoodlist() {
		$model = ClassLoad::Only ( 'Discount' ); /* var Model Discount*/
		if (! empty ( $_POST ['id'] ) && ! empty ( $_POST ['type'] )) {
			$parent_id = $_POST ['id'];
			$type = $_POST ['type'];
		} else {
			$this->error ( '空的类型id或者品牌' );
		}
		$data = $model->getGoodList ( $parent_id, $type );
		echo json_encode ( array (
				'code' => 1,
				'data' => $data 
		) );
	}
	
	// ajax请求返回商品列表
	public function actionGoodsearch() {
		if (! empty ( $_POST ['search'] )) {
			$search = $_POST ['search'];
		} else {
			$this->error ( '空的类型id或者品牌' );
		}
		$model = ClassLoad::Only ( 'Discount' ); /* var Model Discount*/
		$data = $model->getGoodByKeywords ( $search );
		echo json_encode ( array (
				'code' => 1,
				'data' => $data 
		) );
	}
	
	// 编辑
	public function actionEdit() {
		// $this->checkUserPurview('nf.my');
		$model = ClassLoad::Only ( 'Discount' ); /* var Model Discount*/
		$form = ClassLoad::Only ( 'DiscountForm' );/* var Form DiscountForm*/
		$this->exitAjaxPost ( $model );
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		if (! $info = $model->getActiveInfo ( $id ))
			$this->error ( '栏位不存在!' );
		
		if (isset ( $_POST ['DiscountForm'] )) {
			$form->attributes = $_POST ['DiscountForm'];
			if ($form->validate ()) {
				$model->modify ( $_POST ['DiscountForm'], $id );
				$this->redirect ( array (
						'discount/list' 
				) );
			}
		} else {
			$types = $model->getGoodsType ();
			$brand = $model->getGoodsBrand ();
			$this->render ( 'edit', array (
					'form' => $form,
					'info' => $info,
					'types' => $types,
					'brand' => $brand 
			) );
		}
	}
	// 删除 折扣活动
	public function actionClear() {	
		if (! $id = ( int ) $this->getQuery ( 'id', 0 ))
			$this->error ( '错误的ID' );
		$model = ClassLoad::Only ( 'Discount' ); /* @var $model Discount */
		$model->clear ( $id );
		$this->redirect ( array (
				'discount/list'
		) );
	}
}
