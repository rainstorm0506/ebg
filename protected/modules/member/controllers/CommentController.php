<?php
/**
 * 个人中心--我的评论 - 控制器
 * 
 * @author Jeson.Q
 */
class CommentController extends MemberController
{
	//我的评论首页
	public function actionIndex()
	{
		$this->leftNavType = 'comment';
		$commentList = array();
		$model = ClassLoad::Only('Comment'); /* @var $model Comment */
		$uid = $this->getUid();
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($uid);
		$page = new CPagination();
		$page->pageSize = 5;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		// 查询未评论商品列表并 渲染试图
		$allCommentList['commentList']  = $model->getList($uid, $offset , $page->pageSize);
		$allCommentList['page'] = $page;
		$this->render('index' , $allCommentList);
	}

	//跳转到单个商品 评论页
	public function actionCommentPage()
	{
		$this->showLeftNav = false;
		// 加载类 、检查数据	
		$order_sn = (string)$this->getQuery('oid');
		$goods_id = (int)$this->getQuery('gid');
		$model = ClassLoad::Only('Comment'); /* @var $model Comment */
		if (!$info = $model->getActiveInfo($order_sn, $goods_id))
			$this->error('订单不存在!');
		//查询当前商品的评论总数及平均分
		$goodsInfo = $model->getGoodsNums($goods_id);
		// 查询列表并 渲染试图
		$this->render('myComments' , array(
			'info' => $info,
			'goodsInfo' => $goodsInfo
		));
	}
	//单个商品评论数据提交处理
	public function actionSubmitComment()
	{
		$this->showLeftNav = false;
		$userModel = ClassLoad::Only('User');/* @var $model User */
		$model = ClassLoad::Only('Comment');/* @var $model Comment */
		$id = $this->getUid();
		if(!$info=$userModel->getPersonInfo($id))
			$this->error('无该用户信息');

		$this->exitAjaxPost($_POST);
		if (isset ( $_POST )) {
			$model->addUserComment ( $_POST, $id);
			$this->redirect ( array (
				'comment/index'
			) );
		}
	}
}