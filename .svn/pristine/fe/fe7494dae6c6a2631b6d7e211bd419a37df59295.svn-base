<?php
/**
 * 个人中心--我的收藏- 控制器
 * 
 * @author Jeson.Q
 */
class MyCollectionController extends MemberController
{
	//用户收藏店铺首页
	public function actionIndex()
	{
		$this->leftNavType = 'collection';
		$keyword = (string)$this->getQuery('keyword');
		$model = ClassLoad::Only('Collection'); /* @var $model Collection */
		$id = $this->getUid();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($id, 1 , $keyword);
		$page = new CPagination();
		$page->pageSize = 8;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//获取用户收藏店铺数据
		$storeData = $model->getStoreList($id, $offset , $page->pageSize, $keyword);
		// 查询列表并 渲染试图
		$this->render('collectionStore' , array(
			'storeData'=>$storeData,
			'page'=>$page,
			'keyword'=>$keyword ? $keyword : ''
		));
	}

	/**
	 * 个人收藏商品列表页
	 **/
	public function actionShowGoods()
	{
		$this->leftNavType = 'collection';
		$keyword = (string)$this->getQuery('keyword');
		$model = ClassLoad::Only('Collection');/* @var $model Collection */
		$id = $this->getUid();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($id, 2 , $keyword);
		$page = new CPagination();
		$page->pageSize = 8;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		$this->exitAjaxPost($_POST);
		//获取用户收藏店铺数据
		$goodsData = $model->getGoodsList($id, $offset , $page->pageSize, $keyword);
		
		// 查询列表并 渲染试图
		$this->render('collectionGoods' , array(
			'goodsData'=>$goodsData,
			'page'=>$page,
			'keyword'=>$keyword ? $keyword : ''
		) );
	}

	/**
	 * 个人用户取消收藏 操作
	 **/
	public function actionDelete()
	{
		$this->leftNavType = 'collection';
		$collection_id = (int)$this->getQuery('mid');
		$typename = (string)$this->getQuery('typename');
		$model = ClassLoad::Only('Collection');/* @var $model Collection */
		$id = $this->getUid();
		if(!empty($typename) && $collection_id){
			$url = $typename == 'store' ? 'member/myCollection/index' : 'member/myCollection/showGoods';
			$type = $typename == 'store' ? 2 : 1;
			$model->deteleCollect ( $collection_id, $id, $type);
			$this->redirect($url);
		}
		$this->redirect('member/myCollection/index');
	}
}