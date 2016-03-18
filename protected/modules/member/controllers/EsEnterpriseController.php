<?php
/**
 * 默认控制器 - 控制器
 * 
 * @author simon
 */
class EsEnterpriseController extends MemberController
{
	public function init()
	{
		$this->layout = 'main';
	}
	
	//升级为企业用户
	public function actionIndex()
	{
		$this->showLeftNav = false;
		$form = ClassLoad::Only('EsEnterpriseForm');/* @var $form EsEnterpriseForm */
		$formError = array();
		$uid = (int)$this->getUid(); 
		if($this->isPost() && isset($_POST['EsEnterpriseForm']))
		{	
			$form->attributes = $_POST['EsEnterpriseForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('EsEnterprise');/* @var $model EsEnterprise */
				if ($model->signEnterprise( $_POST['EsEnterpriseForm'], $uid))
				{
					$this->redirect($this->createFrontUrl('home/logout'),array('s'=>'enterprise'));
				}else{
					$formError['enterprise'][-1] = '注册时写入数据失败!';
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$companyNumber = array_values(GlobalStatus::getStatusColumn(60 , 'user_title'));
		$companyNumber = CMap::mergeArray(array(''=>'请选择') , array_combine($companyNumber , $companyNumber));
		
		$companyType = array_values(GlobalStatus::getStatusColumn(61 , 'user_title'));
		$companyType = CMap::mergeArray(array(''=>'请选择') , array_combine($companyType , $companyType));
		$this->render('enterprise' , array(
			'form'			=> $form,
			'companyNumber'	=> $companyNumber,
			'companyType'	=> $companyType,
			'formError'		=> $formError,
		));
	}
}