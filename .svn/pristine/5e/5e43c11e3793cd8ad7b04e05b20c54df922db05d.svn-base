<?php
/**
 * 个人中心--用户兑换 - 控制器
 * 
 * @author Jeson.Q
 */
class MyExchangeController extends MemberController
{
	//用户收藏首页
	public function actionIndex()
	{
		$this->leftNavType = 'exchange';
		$model = ClassLoad::Only('Exchange'); /* @var $model Exchange */
		$id = $this->getUid();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($id);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//查询订单列表、渲染试图
		$exchangeData = $model->getList($id, $offset , $page->pageSize);
		$this->render('index' , array(
			'exchangeData'=>$exchangeData,
			'page'=>$page
		));
	}

	//用户确认收货
	public function actionReceiveGoods()
	{
		$model = ClassLoad::Only('Exchange'); /* @var $model Exchange */
		$uid = $this->getUid();
		$pid = $this->getParam('pid' , 0);
		
		if($pid && $uid)
		{
			$model->setConfirmGoods($pid);
			$this->redirect(array('index'));
		}
	}
}