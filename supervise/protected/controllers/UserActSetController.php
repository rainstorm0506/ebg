<?php
/**
 * 会员(积分&成长值&资金)基础设置 控制器
 * @author simon
 */
class UserActSetController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('uas.l');
		$type = (int)$this->getQuery('type');
		
		$model = ClassLoad::Only('UserActSet');/* @var $model UserActSet */
		$this->render('list' , array(
			'list' => $model->getList($type),
		));
	}
	
	//设置
	public function actionSetting()
	{
		$this->checkUserPurview('uas.s');
		
		$form = ClassLoad::Only('UserActSetForm');/* @var $form UserActSetForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('UserActSet');/* @var $model UserActSet */
		if (!$info = $model->getInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['UserActSetForm']) ? $_POST['UserActSetForm'] : array();
		if(isset($_POST['UserActSetForm']) && $form->validate())
		{
			$model->setting($_POST['UserActSetForm'] , $id , (bool)$info['money_ratio']);
			UserAction::getActionSetting(0 , true);
			$this->redirect(array('userActSet/list'));
		}
		
		$this->render('append' , array(
			'form' => $form,
			'info' => $info
		));
	}
}