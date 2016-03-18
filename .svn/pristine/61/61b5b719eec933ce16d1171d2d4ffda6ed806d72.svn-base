<?php

class MemberModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'member.components.*',
			'member.form.*',
			'member.models.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		return (boolean)parent::beforeControllerAction($controller, $action);
	}
}
