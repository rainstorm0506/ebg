<?php
/**
 * 商家中心--我的评论 - 控制器
 * 
 * @author Jeson.Q
 */
class CommentController extends MerchantController
{
	//商家中心首页
	public function actionIndex()
	{
		$this->leftNavType = 'comment';
		$commentList = array();
		$model = ClassLoad::Only('Comment'); /* @var $model Comment */
		$searchPost = isset($_POST) ? $_POST : array();
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost);
		$page = new CPagination();
		$page->pageSize = 20;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		// 查询未评论商品列表并 渲染试图
		$commentList = $model->getList($searchPost, $offset , $page->pageSize);
		$this->render('index' , array(
			'commentList'	=>	$commentList,
			'searchPost'	=>	$searchPost,
			'page'=>$page
		));
	}

	//提交评论回复--操作页
	public function actionSubmitComment()
	{
		$this->showLeftNav = false;
		$model = ClassLoad::Only('Comment');/* @var $model Comment */
		$id = (int)$this->getMerchantID();

		$this->exitAjaxPost($_POST);
		if (isset ( $_POST )) {
			$model->replyUserComment ( $_POST, $id);
			$this->redirect ( array (
				'comment/index'
			) );
		}
	}

	//商家回复评价用户评价--页面
	public function actionGetCommentInfo()
	{
		// 加载类
		$cid = (int)$this->getParam('cid');
		if($cid){
			$model = ClassLoad::Only('Comment'); /* @var $model Comment */
			$commentData = $model->getActiveInfo($cid);
			$commentData['public_time'] = date('Y-m-d',$commentData['public_time']);

			echo empty($commentData) ? 0 : json_encode($commentData);
		}else
			echo 0;
	}
}