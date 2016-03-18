<?php
class ServiceController extends WebController
{
	public function actionIndex()
	{
		$content = array();
		if (!($sid = (int)$this->getQuery('id')))
		{
			if (($list = GlobalContent::getContentData()) && ($list = current($list)))
			{
				$list = current($list);
				$sid = empty($list['id']) ? 0 : (int)$list['id'];
			}
		}
		
		$model = ClassLoad::Only('Service');/* @var $model Service */
		if (!$content = $model->getContents($sid))
			$this->error('没有数据!');
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('sve' , $sid));
		
		$this->render('index' , array(
			'sid'		=> $sid ,
			'classID'	=> (int)$content['type'],
			'content'	=> $content,
		));
	}
}