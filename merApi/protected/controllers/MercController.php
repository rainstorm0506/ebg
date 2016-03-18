<?php
/*
 * 获取商家保证金
 *
 *   */
class MercController extends ApiController
{
	public function actionGetMerMargin()
	{
	
		$form = ClassLoad::Only('MerInfoForm');/* @var $form MerInfoForm */
		$form->attributes = empty($_POST) ? array() : $_POST;
		//echo'111'; exit;
		if($form->validate())
		{
			$model= ClassLoad::Only('Merchants');/* @var $model Merchants */
			if($info = $model->getMerMargin($this->getUid()))
			{
				$this->jsonOutput(0,$info);
	
			}else{
				$this->jsonOutput(2,'未找到保证金信息！') ;
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	
	}
	
	public function actionGetMerIntegral()
	{
		
		$form = ClassLoad::Only('MercIntegralForm');/* @var $form MercIntegralFrom */
		$form->attributes = empty($_POST) ? array() : $_POST;
		
		if($form->validate())
		{
			$model= ClassLoad::Only('Merc');/* @var $model Merc */
			if($info = $model->getMerIntegral($this->getUid()))
			{
				$this->jsonOutput(0,$info);
			}else
			{
				$this->jsonOutput(2,'未找到积分信息') ;
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
}