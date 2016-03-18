<?php
class LoginWidget extends CWidget
{
	#文字
	public $text = '登录';

	public $class = '';

	public function init()
	{
		parent::init();

		static $init = false;
		if ($init === false)
		{
			if ((int)Yii::app()->getUser()->getId())
			{
				Yii::app()->getClientScript()->registerScript('global_login_x' , "jQuery(document).ready(function(){window.history.back()});");
			}else{
				Yii::app()->getClientScript()->registerScript('global_login' , "jQuery(document).ready(function(){jQuery('a.global_login').click(function(){window.top.userLoginPop&&window.top.userLoginPop.remove();window.top.userLoginPop=jQuery('<iframe class=\"pop-iframe\" src=\"".Yii::app()->createUrl('asyn/login')."\"></iframe>');jQuery('body').append(window.top.userLoginPop);return false})});");
			}
			$init = true;
		}
	}

	public function run()
	{
		echo CHtml::link($this->text , '' , array('class'=>$this->class.' global_login' , 'rel'=>'nofollow'));
	}
}