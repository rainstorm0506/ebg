<?php
/**
 * 个人 / 企业模块
 * 
 * @author simon
 */
class UserController extends WebApiController
{
	/**
	 * 个人 / 企业模块 - 找回密码
	 * 
	 * @param		bigint		phone			手机号码
	 * @param		int			vxCode			图像验证码
	 * @param		int			smsCode			短信验证码
	 * @param		string		password_1		密码1，以字母开头，长度在6~18之间，只能包含字符、数字和下划线		(/^[a-zA-Z]\w{5,17}$/)
	 * @param		string		password_2		密码2
	 */
	public function actionFind()
	{
		$form				= ClassLoad::Only('UserForm');/* @var $form UserForm */
		$form->phone		= trim((string)$this->getPost('phone'));
		$form->vxCode		= (int)$this->getPost('vxCode');
		$form->smsCode		= (int)$this->getPost('smsCode');
		$form->password_1	= (string)$this->getPost('password_1');
		$form->password_2	= (string)$this->getPost('password_2');
		
		if ($form->verifyFind() === true)
		{
			$model = ClassLoad::Only('User');/* @var $model User */
			if ($model->find($form))
				$this->jsonOutput(0);
			
			$this->jsonOutput(66 , '未知错误!');
		}
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 个人 / 企业模块 - 登录
	 * 
	 * @param		bigint		phone		手机号码
	 * @param		string		password	密码，以字母开头，长度在6~18之间，只能包含字符、数字和下划线		(/^[a-zA-Z]\w{5,17}$/)
	 */
	public function actionLogin()
	{
		$form				= ClassLoad::Only('UserForm');/* @var $form UserForm */
		$form->phone		= trim((string)$this->getPost('phone'));
		$form->password		= (string)$this->getPost('password');
		
		if ($form->verifyLogin())
		{
			//已登录的用户直接从session中返回
			if ($user = $this->getUser())
				$this->jsonOutput(0 , $user);
			
			if ($form->login())
				$this->jsonOutput(0 , $this->getUser());
		}
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 个人 / 企业模块 - 注册
	 * 
	 * @param		int			type					1=个人注册 , 2=企业注册
	 * @param		bigint		phone					手机号码
	 * @param		int			vxCode					图像验证码
	 * @param		int			smsCode					短信验证码
	 * @param		string		password_1				密码1，以字母开头，长度在6~18之间，只能包含字符、数字和下划线		(/^[a-zA-Z]\w{5,17}$/)
	 * @param		string		password_2				密码2
	 * @param		int			reCode					推荐码
	 * @param		int			agree					是否同意注册协议 , 1=同意
	 *
	 * ************************ 以下是企业注册 , 还需要传递的参数 *************************************************************************
	 *
	 * @param		string		com_name				公司名称
	 * @param		int			one_id					省 ID
	 * @param		int			two_id					市 ID
	 * @param		int			three_id				区/县 ID
	 * @param		string		com_address				公司地址
	 * @param		string		com_num					公司人数
	 * @param		string		com_property			公司类型
	 * @param		string		com_license				营业执照
	 * @param		string		com_license_timeout		营业执照到期时间
	 * @param		string		com_tax					税务登记
	 * @param		string		com_org					组织机构代码
	 */
	public function actionSign()
	{
		$form				= ClassLoad::Only('UserForm');/* @var $form UserForm */
		$form->type			= (int)$this->getPost('type');
		$form->attributes	= empty($_POST) ? array() : $_POST;
		
		if ($form->validate())
		{
			$model = ClassLoad::Only('User');/* @var $model User */
			if ($model->sign($form))
			{
				# 如果是个人 , 自动登录
				if ($form->type == 1)
					GlobalUser::setReflushUser(array('phone'=> $form->phone , 'user_type'=>$form->type) , 4);
				
				$this->jsonOutput(0);
			}else{
				$this->jsonOutput(55 , '数据写入失败!');
			}
		}
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
}