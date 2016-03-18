<?php

class MerchantModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'merchant.components.*',
			'merchant.form.*',
			'merchant.models.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		return (boolean)parent::beforeControllerAction($controller, $action);
	}
}
