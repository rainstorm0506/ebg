<?php
class SubscibeController extends ApiController{
/**
 * @param		int		apt		APP抛数据的时间
 * @param		array	cid		ID
 * @param		array	type	识别类型
 */
	public function actiongetInsert()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = $this->getPost('apt');
		$form->collect= $this->getPost('collect');
		if($form->validatorInsert())
		{
			$model = ClassLoad::Only('Subscibe');/* @var $model Subscibe */
			if($info = $model->getRestgz($form))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'数据错误');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 我的关注列表
	 * @param		int			APP抛数据的时间
	 */
	public function actionFocus()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = $this->getPost('apt');
		if ($form->validateBrand())
		{
			$model = ClassLoad::Only('Subscibe');/* @var $model Subscibe */
			if($info = $model->getTestFocus())
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'数据错误');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 关注列表
	 * 
	 */
	public function actionList()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = $this->getPost('apt');
		if ($form->validateBrand())
		{
			$model = ClassLoad::Only('Subscibe');/* @var $model Subscibe */
			if($info = $model->getList($form->apt))
			{
				$this->jsonOutput(0 , $info);
			}else{
				$this->jsonOutput(2 , '数据错误');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 删除选中关注
	 * @param		int			apt			APP抛数据的时间
	 * @param		array		collect		接收数组
	 */
	public function actionDelete()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = $this->getPost('apt');
		$form->collect= $this->getPost('collect');
		if($form->validatorInsert())
		{
			$model = ClassLoad::Only('Subscibe');/* @var $model Subscibe */
			if($info = $model->getDelete($form))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到数据');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 *显示包含选中 
	 *@param		int		apt			APP抛数据的时间
	 */
	public function actionValid()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = $this->getPost('apt');
		if($form->validateInfo())
		{
			$model = ClassLoad::Only('Subscibe');/* @var $model Subscibe */
			if($info = $model->getValid())
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'数据错误');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
}