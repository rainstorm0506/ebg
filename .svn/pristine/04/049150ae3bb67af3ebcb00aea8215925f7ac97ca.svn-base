<?php
class PraiseWidget extends CWidget
{
	# 商品ID
	public $gid = 0;
	#赞
	public $praise = 0;
	#文字
	public $text = '赞';
	# 类别 , 1=商品 , 2=二手 , 3=积分 , 4=店铺
	public $type = 0;
	
	public $class = 'zan-wrap';
	
	public $url = '';
	
	public function init()
	{
		parent::init();
		
		static $init = false;
		if ($init === false)
		{
			if ((int)Yii::app()->getUser()->getId())
			{
				$path = Yii::app()->getClientScript()->getCoreScriptUrl().'/layer/';
				Yii::app()->getClientScript()->registerScript('PraiseWidget_praise' , "jQuery(document).ready(function(){jQuery('a.{$this->class}').click(function(){var e=this,_v=parseInt(jQuery(e).text()||0,10);jQuery.ajax({'url':jQuery(e).attr('href'),'dataType':'json','success':function(json){var _xxs='';if(json.code==0){if(json.data.join){jQuery(e).text((_v+1)+' {$this->text}')}else{_xxs='你点过赞了!'}}else{_xxs=json.message}if(window.layer){layer.msg(_xxs)}else{jQuery.getScript('{$path}layer.js',function(){layer.config({path:'{$path}'});layer.msg(_xxs)})}},'error':function(){if(window.layer){layer.msg('请求失败!')}else{jQuery.getScript('{$path}layer.js',function(){layer.config({path:'{$path}'});layer.msg('请求失败!')})}}});return false})});");
			}else{
				Yii::app()->getClientScript()->registerScript('PraiseWidget_login' , "jQuery(document).ready(function(){jQuery('a.{$this->class}').click(function(){window.top.userLoginPop&&window.top.userLoginPop.remove();window.top.userLoginPop=jQuery('<iframe class=\"pop-iframe\" src=\"".Yii::app()->createUrl('asyn/login')."\"></iframe>');jQuery('body').append(window.top.userLoginPop);return false})});");
			}
			$init = true;
		}
	}
	
	public function run()
	{
		$url = $this->url ? $this->url : $this->getController()->createUrl('asyn/userPraise' , array('gid'=>$this->gid , 'type'=>$this->type));
		echo CHtml::link($this->praise.' '.$this->text , $url , array('class'=>$this->class));
	}
}