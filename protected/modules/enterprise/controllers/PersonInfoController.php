<?php
/**
 * 企业中心--密码及基本信息管理 - 控制器
 * 
 * @author simon
 */
class PersonInfoController extends EnterpriseController
{
	//个人中心首页
	public function actionIndex()
	{
		$this->leftNavType = 'personInfo';
		$form = ClassLoad::Only('PersonForm'); /* @var $form PersonForm */
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form->personType = 'index';
		$id = $this->getUid();
		$personData = $model->getPersonInfo($id);
		//获取所有市级城市数据
		$cityData = $model->getCityList(0,0);

		// 查询列表并 渲染试图
		$this->render('index' , array(
			'form'=>$form,
			'personData'=>$personData,
			'cityData'=>$cityData
		) );
	}

	/**
	 * 提交个人  信息
	 **/
	public function actionEdit()
	{
		$model = ClassLoad::Only('User');/* @var $model User */
		$form = ClassLoad::Only('PersonForm'); /* @var $form PersonForm */
		$form->personType = 'index';
		$id = $this->getUid();
		if(!$info=$model->getPersonInfo($id))
			$this->error('无该用户信息');
		$this->exitAjaxPost($_POST);
		if (isset ( $_POST )) {
			$model->modify ( $_POST, $id);
			GlobalUser::setReflushUser($this->getUser() , 1);
			$this->redirect ( array (
				'personInfo/index'
			) );
		}
	}

	//修改密码页操作
	public function actionShowVerity()
	{
	
		$this->leftNavType = 'personInfo';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('PersonForm'); /* @var $form PersonForm */
		$form->personType = 'modVerify';
		$id = $this->getUid();
		
		$personData = $model->getPersonInfo($id);
		if ($this->isPost() && isset ( $_POST['PersonForm'] )){
			$form->attributes = $_POST['PersonForm'];
			if ($form->validate()) {
				$this->redirect(array('personInfo/editPassword'));
			}
		}
		// 查询列表并 渲染试图
		$this->render('modVerify' , array(
			'form'			=>	$form,
			'personData'	=>	$personData
		));
	}

	//修改密码页操作
	public function actionEditPassword()
	{

		$this->leftNavType = 'personInfo';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('PersonForm'); /* @var $model PersonForm */
		$form->personType = 'editPassword';
		$id = $this->getUid();

		if ($this->isPost() && isset ( $_POST['PersonForm'])){
			$form->attributes = $_POST['PersonForm'];
			if ($form->validate()) {
				$model->setUserPassword ( $_POST['PersonForm'], $id);
				$this->redirect(array('personInfo/modComplete' , 's'=>'member'));
			}
		}
		// 查询列表并 渲染试图
		$this->render('modLoginPassword' , array(
			'form'=>$form
		));
	}

	//修改密码完成页操作
	public function actionModComplete()
	{
	
		$this->leftNavType = 'personInfo';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('PersonForm'); /* @var $model PersonForm */
		if (!$id = (int)Yii::app()->getUser()->getId())
			$this->error('无权操作该数据');
		// 查询列表并 渲染试图
		$this->render('modComplete' , array(
			'form'=>$form
		));
	}
	//修改电话号码数据操作
	public function actionEditPhone()
	{
		$this->leftNavType = 'personInfo';
		// 加载类 、检查数据
		if (!$id = (int)Yii::app()->getUser()->getId())
			$this->error('无权操作该数据');
		$form = ClassLoad::Only('PersonForm'); /* @var $model PersonForm */
		$model = ClassLoad::Only('User'); /* @var $model User */
		$personData = $model->getPersonInfo($id);

		//获取所有市级城市数据
		$cityData = $model->getCityList(0,0);
		if ($this->isPost() && isset ( $_POST['PersonForm'] )){
			//if ($form->validate()) {
				$model->setUserPhone ( $_POST['PersonForm'], $id);
				$userInfo = $this->getUser();
				$userInfo['phone'] = $_POST['PersonForm']['phone'];
				GlobalUser::setReflushUser($userInfo , 1);
				$this->redirect(array('personInfo/index'));
			//}
		}
		// 查询列表并 渲染试图
		$this->render('/personInfo/index' , array(
			'personData' => $personData,
			'cityData'=> $cityData,
			'form' => $form
		));
	}

	//查询地区信息 ajax 操作
	public function actionGetDictList()
	{
		// 加载类 、检查数据
		$dictDataList = array();$selectOption = '';
		$model = ClassLoad::Only('User'); /* @var $model User */

		if(isset($_POST) && $_POST['dictid']){
			$dictDataList = $model->getDictLists($_POST['dictid'], $_POST['type']);
			if($dictDataList){
				foreach ($dictDataList as $val){
					$selectOption .= "<option value=".$val['id'].">".$val['name']."</option>";
				}
			}
			echo $selectOption;
		}else{
			echo 0;
		}	
	}

	//短信验证手机号 ajax 操作
 	public function actionCheckPhone()
	{
		// 加载类 、检查数据
		$phone = $codeNum = null;
		$form = ClassLoad::Only('PersonForm'); /* @var $model PersonForm */
		$model = ClassLoad::Only('User'); /* @var $model User */
		$phone = (string)$this->getPost('tel');
		$codeNum = (int)$this->getPost('codeNum');
		if(isset($_POST) && $phone && $codeNum){
			$flag = $form->checkPhone($phone, $codeNum);
			if($flag == 1){
				if($model->getUserPhone($phone))
					echo 1;
				else
					echo " * 输入的电话号码已存在，请重新输入！";
			}else{
				echo $flag;
			}
		}else{
			echo ' * 手机号和验证码不能为空！';
		}
	}
}