<?php
/**
 * description： 用户信息Form
 * @author 夏勇高
 */
class ActiveForm extends SForm
{
	// $user_layer,
	public $userexp , $companyexp , $gtype , $type , $name,$title,$id,$status ;
	
	// 基本验证
	public function rules()
	{
		if(Yii::app()->controller->action->id == 'addact')
		{
			return array(
				array('userexp , companyexp , gtype ,'),
				array('name,type','checkNull'),
				array('password' , 'checkPassword'),
			);
		}else{
			return array(
				array('title , id' , 'checkNull'),
			);
		}
	}

}
