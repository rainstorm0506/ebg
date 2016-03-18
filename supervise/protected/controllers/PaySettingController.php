<?php
/**
 * 支付设置 控制器
 * @author simon
 */
class PaySettingController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('payset.l');
		
		$model = ClassLoad::Only('PaySetting');/* @var $model PaySetting */
		$this->render('list' , array(
			'list' => $model->getList(),
		));
	}
	
	//开通 & 关闭
	public function actionSetting()
	{
		$this->checkUserPurview('payset.e');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$code = (int)$this->getQuery('code');
		
		$model = ClassLoad::Only('PaySetting');/* @var $model PaySetting */
		$model->setting($id , $code ? 1 : 0);
		$this->redirect(array('paySetting/list'));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('payset.m');
		
		$form = ClassLoad::Only('PaySettingForm');/* @var $form PaySettingForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('PaySetting');/* @var $model PaySetting */
		if (!$info = $model->getInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['PaySettingForm']) ? $_POST['PaySettingForm'] : array();
		if(isset($_POST['PaySettingForm']) && $form->validate())
		{
			$model->modify($_POST['PaySettingForm'] , $id);
			$this->redirect(array('paySetting/list'));
		}
		
		$this->render('append' , array(
			'form' => $form,
			'info' => $info
		));
	}
}