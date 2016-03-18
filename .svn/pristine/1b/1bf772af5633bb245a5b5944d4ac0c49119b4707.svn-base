<?php
/**
 * 默认控制器 - 控制器
 * 
 * @author simon
 */
class MyAddressController extends EnterpriseController
{
	//企业用户收货地址首页
	public function actionIndex()
	{
		$this->leftNavType = 'myAddress';
		$keyword = (string)$this->getQuery('keyword');
		$form = ClassLoad::Only('MyAddressForm'); /* @var $form MyAddressForm */
		$model = ClassLoad::Only('MyAddress'); /* @var $model MyAddress */
		$user = ClassLoad::Only('User'); /* @var $user User */
		$id = $this->getUid();

		//获取所有市级城市数据
		$allCityData = $user->getDictList(0,0);
		//获取四川省城市数据
		$scsCityData = $user->getDictList(29967,1);
		//获取当前用户收货地址数据
		$addressData = $model->getList($id);

		// 查询列表并 渲染试图
		$this->render('index' , array(
			'addressData'=>$addressData,
			'scsCityData'=>$scsCityData,
			'allCityData'=>$allCityData,
			'form'=>$form
		));
	}

	/**
	 * 提交企业  信息
	 **/
	public function actionSubmitAddress()
	{
		$this->leftNavType = 'myAddress';
		
		$model = ClassLoad::Only('MyAddress');/* @var $model MyAddress */
		$form = ClassLoad::Only('MyAddressForm'); /* @var $model MyAddressForm */
		$id = $this->getUid();
		$this->exitAjaxPost($form , 'addressForm');
		//判断是否存在提交数据
		if(isset($_POST['MyAddressForm']))
		{
			$form->attributes = $_POST['MyAddressForm'];
			if($form->validate())
			{
				$model->addAddress($_POST['MyAddressForm'], $id);
				$this->redirect(array('index'));
			}
		}
	}
	
	/**
	 * 设置选中地址为默认地址 信息
	 **/
	public function actionSetAddressDefault()
	{
		$this->leftNavType = 'myAddress';		
		$model = ClassLoad::Only('MyAddress');/* @var $model MyAddress */
		$id = $this->getUid();
		//判断是否存在提交数据
		if(isset($_POST))
		{
			$flag = $model->setAddressDef($_POST, $id);
			echo $flag;
		}else
			echo 0; 
	}
	
	/**
	 * 设置选中地址的详细 信息
	 **/
	public function actiongetAddressInfo()
	{
		$this->leftNavType = 'myAddress';		
		$model = ClassLoad::Only('MyAddress');/* @var $model MyAddress */
		$id = $this->getUid();
		//判断是否存在提交数据
		if(isset($_POST))
		{
			$addressInfo = $model->getAddressData($_POST, $id);
			echo $addressInfo ? json_encode($addressInfo) : 0;
		}else
			echo 0; 
	}
	
}