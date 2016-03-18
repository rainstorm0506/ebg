<?php
class CollectWidget extends CWidget
{
	#ID
	public $id = 0;
	# 收藏类别（1商品，2店铺 , 3=二手商品）
	public $type = 2;
	#文字
	public $text = '收藏店铺';

	public $class = 'btn-6';

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
				Yii::app()->getClientScript()->registerScript('CollectWidget_click' , "jQuery(document).ready(function(){jQuery('a.collects').click(function(){var e=this;jQuery.ajax({'url':jQuery(e).attr('href'),'dataType':'json','success':function(json){var _tx='';if(json.code==0){if(json.data.join){var _q=jQuery(e).nextAll('q:eq(0)');_q.html(parseInt(_q.html(),10)+1)}switch(json.data.type){case 1:_tx='您已成功收藏此商品!';break;case 2:_tx='您已成功收藏此店铺!';break;case 3:_tx='您已成功收藏此商品!';break}}else{_tx=json.message}if(window.layer){layer.msg(_tx)}else{jQuery.getScript('{$path}layer.js',function(){layer.config({path:'{$path}'});layer.msg(_tx)})}},'error':function(){if(window.layer){layer.msg('请求失败!')}else{jQuery.getScript('{$path}layer.js',function(){layer.config({path:'{$path}'});layer.msg('请求失败!')})}}});return false})});");
			}else{
				Yii::app()->getClientScript()->registerScript('CollectWidget_login' , "jQuery(document).ready(function(){jQuery('a.collects').click(function(){window.top.userLoginPop&&window.top.userLoginPop.remove();window.top.userLoginPop=jQuery('<iframe class=\"pop-iframe\" src=\"".Yii::app()->createUrl('asyn/login')."\"></iframe>');jQuery('body').append(window.top.userLoginPop);return false})});");
			}
			$init = true;
		}
	}

	public function run()
	{
		$url = $this->url ? $this->url : $this->getController()->createUrl('asyn/collects' , array('id'=>$this->id , 'type'=>$this->type));
		echo CHtml::link($this->text , $url , array('class'=>$this->class.' collects'));
	}
}