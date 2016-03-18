<?php
class DispatchingController extends WebController
{
	public function actionIndex()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('dispatching' , 0));
		
		$this->layout = 'simplest';
		
		$this->render('index');
	}
}