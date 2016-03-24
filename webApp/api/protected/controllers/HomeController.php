<?php
/**
 * 首页
 * @author simon
 */
class HomeController extends WebApiController
{
	/**
	 * 限时抢购
	 */
	public function actionFlashSale()
	{
		$data = array('限时抢购！');
		$this->jsonOutput(0,$data);
	}

	/**
	 * 今日推荐
	 */
	public function actionRecommend()
	{
		$data = array('今日推荐！');
		$this->jsonOutput(0,$data);
	}

	/**
	 * 办公用品
	 */
	public function actionCommodities()
	{
		if(!($this->getPost('oneid') == '2135'))
		{
			$this->jsonOutput(2,'参数有误');
		}

		$oneid = (int)$this->getPost('oneid');

		$model=ClassLoad::Only('Goods');/* @var $model Goods */
		$data = $model->getHomeList();

		$this->jsonOutput(0,$data);

	}


}
