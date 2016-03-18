<?php
/**
 * 店铺设置控制器 - 控制器
 * 
 * @author Jeson.Q
 */
class MyStoreController extends MerchantController
{
	//店铺设置首页
	public function actionIndex()
	{
		$this->leftNavType = 'store';
		$form = ClassLoad::Only('MyStoreForm'); /* @var $form MyStoreForm */
		$model = ClassLoad::Only('MyStore'); /* @var $model MyStore */
		$id= (int)$this->getMerchantID();
		$this->exitAjaxPost($form , 'formBox');
		
		if(isset($_POST['MyStoreForm']))
		{
			$form->attributes = $_POST['MyStoreForm'];
			if($form->validate())
			{
				$model->setStoreInfo($_POST['MyStoreForm'], $id);
				GlobalUser::setReflushUser($this->getUser() , 1);
				GlobalMerchant::flush();
				$this->redirect(array('home/index'));
			}
		}
		//获取商家店铺数据
		$myStoreData = $model->getStoreInfo($id);

		// 查询列表并 渲染试图
		$this->render('index' , array(
			'info'=>$myStoreData,
			'users'=>$this->getUser(),
			'form'=>$form
		));
	}

	//短信验证手机号 ajax 操作
	public function actionCheckPhone()
	{
		// 加载类 、检查数据
		$phone = $codeNum = null;
		$form = ClassLoad::Only('MyStoreForm'); /* @var $model PersonForm */
		$model = ClassLoad::Only('MyStore'); /* @var $model User */
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

	//修改电话号码数据操作
	public function actionEditPhone()
	{
		// 加载类 、检查数据
		if (!$id = (int)Yii::app()->getUser()->getId())
			$this->error('无权操作该数据');

		$model = ClassLoad::Only('User'); /* @var $model User */
		if ($this->isPost() && isset ( $_POST['MyStoreForm'] )){
			$model->setUserPhone ( $_POST['MyStoreForm'], $id);
			$userInfo = $this->getUser();
			$userInfo['phone'] = $_POST['MyStoreForm']['phone'];
			GlobalUser::setReflushUser($userInfo , 1);
		}
		$this->redirect(array('myStore/index'));
	}
}