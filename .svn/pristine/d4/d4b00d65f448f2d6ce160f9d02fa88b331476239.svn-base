<?php
class MaintainController extends WebController
{
	public function actionIndex()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('maintain' , 0));
		
		$this->layout = 'simplest';
		
		$this->render('index');
	}
}