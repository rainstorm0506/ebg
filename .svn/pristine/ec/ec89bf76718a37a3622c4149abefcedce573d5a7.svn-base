<?php
class UEditor extends CInputWidget
{
	private		$baseUrl			= '';
	private		$jsFiles			= array('/ueditor.config.js' , '/ueditor.all.min.js');

	public		$id					= '';
	public		$name				= '';
	public		$form				= null;
	public		$value				= '';
	public		$options			= array();

	/**
	 * Initialize the widget
	 */
	public function init()
	{
		if ($this->form)
		{
			$value = empty($this->form->{$this->name}) ? $this->value : $this->form->{$this->name};
			$this->id = $this->id ? $this->id : CHtml::activeId($this->form , $this->name);
			echo CHtml::activeTextArea($this->form , $this->name , array('id'=>$this->id , 'value'=>$value));
		}else{
			$this->id = $this->id ? $this->id : str_replace(array('[' , ']') , '_' , $this->name);
			echo CHtml::textArea($this->name , $this->value , array('id'=>$this->id));
		}
		$this->publishAssets();
		$this->registerClientScripts();
		parent::init();
	}

	/**
	 * Publishes the assets
	 */
	public function publishAssets()
	{
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ueditor';
		
		$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
	}

	/**
	 * Registers the external javascript files
	 */
	public function registerClientScripts()
	{

		if ($this->baseUrl === '')
			throw new CException(Yii::t('Ueditor', 'baseUrl must be set. This is done automatically by calling publishAssets()'));

		//Register the main script files
		$cs = Yii::app()->getClientScript();
		foreach ($this->jsFiles as $jsFile)
		{
			$ueditorJsFile = $this->baseUrl . $jsFile;
			$cs->registerScriptFile($ueditorJsFile, CClientScript::POS_HEAD);
		}

		//Register the widget-specific script on ready
		$js = $this->generateOnloadJavascript();
		$cs->registerScript('ueditor' . $this->id , $js, CClientScript::POS_END);
	}

	protected function generateOnloadJavascript()
	{
		$object = array();
		$object['initialFrameWidth']	= empty($this->options['width']) ? '100%' : $this->options['width'];
		$object['initialFrameHeight']	= empty($this->options['height']) ? 0 : (int)$this->options['height'];
		$object['initialFrameHeight']	= $object['initialFrameHeight'] ? $object['initialFrameHeight'] : 400;
		$object['autoHeightEnabled']	= false;
		$object['elementPathEnabled']	= false;
		$object['wordCount']			= false;
		$object['serverUrl']			= Yii::app()->params['imgDomain'] . 'DUpload/controller.php';
		
		$object = array_merge($object , $this->options);
		return 'UE.getEditor("'.$this->id.'" , '.json_encode($object).');';
	}

	/**
	 * Run the widget
	 */
	public function run()
	{
		parent::run();
	}
}