<?php
/**
 * 收藏  - 控制器
 */
class CollectController extends WebApiController
{
	/**
	 * 添加收藏
	 */
	public function actionCreate()
	{
		$form=ClassLoad::Only('CollectForm'); /* @var $form CollectForm */
		$form->type= $this->getPost('type');
		$form->collect_id= $this->getPost('collect_id');
		$form->user_id= $this->getUid();
		$model=ClassLoad::Only('Collect'); /* @var $model Collect */
		if($row=$model->create($form))
		{
			$this->jsonOutput(0 , '已成功收藏');
		}
		else
		{
			$this->jsonOutput(2 , '收藏失败');
		}
	}
}