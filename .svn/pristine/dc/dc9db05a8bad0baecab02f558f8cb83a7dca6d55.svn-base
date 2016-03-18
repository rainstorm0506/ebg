<?php
class SetUserController extends ScheController
{
	public function actionIndex()
	{
		//$this->setScriptTimeout(0);
		//$model = ClassLoad::Only('SetUser'); /* @var $model SetUser */
		//$model->setActiveInfo();
		exit('ok');
	}
	
	/**
	 * http://58bangong.com/schedule/setUser.changeUserCode
	 * http://loc.ebg.com/schedule/setUser.changeUserCode
	 */
	public function actionChangeUserCode()
	{
		$this->setScriptTimeout(0);
		$model = ClassLoad::Only('SetUser'); /* @var $model SetUser */
		$model->changeUserCode();
		CacheBase::flush();
		exit('ok');
	}
}