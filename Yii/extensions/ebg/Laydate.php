<?php
class Laydate extends CInputWidget
{
	private		$baseUrl		= '';
	private		$jsFiles		= array('/laydate.js');

	public		$id				= '';		# 表单ID
	public		$name			= '';		# 表单名称
	public		$form			= null;		# yii form
	public		$value			= '';		# 默认值
	public		$class			= '';		# css名称
	public		$style			= '';		# css样式
	public		$placeholder	= '';		# 提示 html5新特性
	public		$skin			= '';		# 皮肤
	public		$isTime			= true;		# 显示 时分秒
	public		$dateFormat		= '';		# 日期格式
	public		$options		= array();	# 更多Laydate参数
	public		$choose			= null;		# 回调函数

	/**
	 * Initialize the widget
	 */
	public function init()
	{
		$text					= $this->options;
		$text['placeholder']	= $this->placeholder ? $this->placeholder : '请输入日期';
		$this->style && $text['style'] = $this->style;
		$this->class && $text['class'] = $this->class;
		
		$laydate = '';
		if ($this->isTime)
		{
			$laydate = 'true';
			$this->dateFormat = $this->dateFormat ? $this->dateFormat : 'YYYY-MM-DD hh:mm:ss';
		}else{
			$laydate = 'false';
			$this->dateFormat = $this->dateFormat ? $this->dateFormat : 'YYYY-MM-DD';
		}
		$choose = is_string($this->choose) && $this->choose ? (",choose:{$this->choose}") : '';
		$text['onclick'] = "laydate({istime:{$laydate},format:\"{$this->dateFormat}\"{$choose}})";
		
		if ($this->form)
		{
			$value = empty($this->form->{$this->name}) ? $this->value : $this->form->{$this->name};
			$this->id = $this->id ? $this->id : CHtml::activeId($this->form , $this->name);

			$text['id'] = $this->id;
			$text['value'] = $value;
			echo CHtml::activeTextField($this->form , $this->name , $text);
		}else{
			$this->id = $this->id ? $this->id : str_replace(array('[' , ']') , '_' , $this->name);

			$text['id'] = $this->id;
			echo CHtml::textField($this->name , $this->value , $text);
		}

		$this->baseUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'laydate');

		$this->registerClientScripts();
		parent::init();
	}

	/**
	 * Registers the external javascript files
	 */
	public function registerClientScripts()
	{
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');

		foreach ($this->jsFiles as $jsFile)
		{
			$ueditorJsFile = $this->baseUrl . $jsFile;
			$cs->registerScriptFile($ueditorJsFile, CClientScript::POS_HEAD);
		}

		//Register the widget-specific script on ready
		$cs->registerScript('Laydate' . $this->id , $this->generateOnloadJavascript() , CClientScript::POS_END);
	}

	protected function generateOnloadJavascript()
	{
		static $init = 1;
		$js = '';
		if ($init === 1)
		{
			$js = '!function(){laydate.skin("molv");}();'.chr(10);
			$init = 0;
		}
		return $js;
	}

	/**
	 * Run the widget
	 */
	public function run()
	{
		parent::run();
	}
}