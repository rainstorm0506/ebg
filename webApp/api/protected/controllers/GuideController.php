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
		if(!($this->getPost('position') && $this->getPost('store'))){
			$this->jsonOutput(-1,'参数错误');
		}

		$stores = array(
			'position'	=>	$this->getPost('position'),
			'store'		=>	$this->getPost('store'),
			'proviso'	=>	$this->getPost('proviso'),
			'coords'	=>	$this->getPost('coords'),
		);

		$model=ClassLoad::Only('Guide');/* @var $model Guide */
		$data = $model->getListStore($stores);

		$this->jsonOutput(0,$data);
	}
}