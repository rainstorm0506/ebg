<?php

/**
 * 逛一逛
 */
class GuideController extends WebApiController
{
	/**
	 * 位置
	 */
	public function actionSite()
	{
		if(!$this->getPost('store')){
			$this->jsonOutput(-1,'参数错误');
		}

		$stores = array(
			'store'		=>	$this->getPost('store'),
			'proviso'	=>	$this->getPost('proviso'),
		);

		$model=ClassLoad::Only('Guide');/* @var $model Guide */
		$model->getListStore($stores);



	}
}