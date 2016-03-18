<?php
/**
 * 个人中心--我的优惠券 - 控制器
 * 
 * @author Jeson.Q
 */
class MyPreferentialController extends MemberController
{
	//我的优惠券首页
	public function actionIndex()
	{
		$this->leftNavType = 'preferential';
		$model = ClassLoad::Only('Reduction'); /* @var $model Reduction */
		$uid = $this->getUid();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($uid);
		$page = new CPagination();
		$page->pageSize = 9;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		//获取用户所有优惠券列表
		$preferentData = $model->getList($uid, $offset , $page->pageSize);

		// 查询列表并 渲染试图
		$this->render('index' , array(
			'preferentData'=>$preferentData,
			'page'=>$page
		));
	}
}