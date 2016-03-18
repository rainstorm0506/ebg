<?php

/**
 * 商品标签
 *
 * @author gl
 */
class GoodsTagController extends SController
{
	public function actionIndex()
	{
		$this->checkUserPurview('gt.ix');
		
		$this->render('list' , array(
			'list' => GlobalGoodsTag::getTags(-1)
		));
	}
	
	public function actionUpdatet()
	{
		$this->checkUserPurview('gt.set');
		
		$model	= ClassLoad::Only('GoodsTag');/* @var $model GoodsTag */
		$id		= $this->getQuery('id');
		$val	= $this->getQuery('val');

		if(empty($id) || $id <= 0)
			$this->jsonOutput(1 , 'ID错误');
		
		if($val < 0 || $val > 1)
			$this->jsonOutput(2 , '参数错误!');
		
		if($model->updateTag($id , $val))
		{
			GlobalGoodsTag::flush();
			$this->jsonOutput(0);
		}else{
			$this->jsonOutput(3 , '未知错误!');
		}
	}
}
