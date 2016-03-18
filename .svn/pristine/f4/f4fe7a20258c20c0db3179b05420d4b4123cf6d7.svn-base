<?php

class EnterpriseModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'enterprise.components.*',
			'enterprise.form.*',
			'enterprise.models.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		return (boolean)parent::beforeControllerAction($controller, $action);
	}
}
