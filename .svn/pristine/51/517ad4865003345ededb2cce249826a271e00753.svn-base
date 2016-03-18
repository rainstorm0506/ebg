<?php
/**
 * SEO 设置
 */
class SeoController extends SController
{
	public function actionList()
	{
		$this->checkUserPurview('seo.l');
		
		$this->render('list' , array(
			'list' => GlobalSEO::getGlobalSet(),
		));
	}
	
	public function actionSet() 
	{
		$this->checkUserPurview('seo.s');
		
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);
		
		if (!$code = (string)$this->getQuery('code'))
			$this->error('错误的商品key');
		
		if(isset($_POST['SeoForm']))
		{
			$form->attributes = $_POST['SeoForm'];
			if($form->validate())
			{
				GlobalSEO::setting($_POST['SeoForm'] , $code , 0);
				$this->redirect(array('list'));
			}
		}
		$list = GlobalSEO::getGlobalSet();
		$title = isset($list[$code]) ? $list[$code] : '';
		
		$this->render('seo' , array(
			'form'		=> $form,
			'title'		=> $title,
			'seo'		=> GlobalSEO::getSeoInfo($code , 0),
		));
	}
}