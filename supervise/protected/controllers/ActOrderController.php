<?php
/**
 * 活动订单
 * 
 * @author yancl
 *
 */
class ActOrderController extends SController
{
	/**
	 * 订单列表
	 * 
	 * @return void
	 */
	public function actionList()
	{
		// $this->checkUserPurview(ao.lt');
		
		$model	 = ClassLoad::Only('ActOrder');
		$where	 = isset($_POST['OrderForm']) ? $_POST['OrderForm'] : array();
		$total	 = $model->getCount($where);
		$page	 = new CPagination($total);
		$pageNow = $this->getParam('page', 1);
		$offset	 = $pageNow > 1 ? ($pageNow -1) * $page->pageSize : 0;
		$list	 = $model->getList($where, $offset, $page->pageSize);
		
		$this->render('list', array(
				'model'	=> $model,
				'list'	=> $list,
				'page'	=> $page
		));
	}
	
	/**
	 * 订单详情
	 * 
	 * @return void
	 */
	public function actionInfo()
	{
		// $this->checkUserPurview('ao.lt');
	}
}