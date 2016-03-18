<?php
class StoreController extends WebController
{
	public function actionIndex()
	{
		if (!($mid = (int)$this->getQuery('mid')) || $mid < 1)
			$this->error('店铺ID错误!');
		
		$type = (string)$this->getQuery('t');
		$type = $type=='used' ? 'used' : 'new';
		
		$search = array(
			'keyword'	=> trim((string)$this->getQuery('w')),
			'order'		=> (string)$this->getQuery('o'),
			'by'		=> (string)$this->getQuery('by'),
			'type'		=> $type,
			'mid'		=> $mid,
		);

		$model = ClassLoad::Only('Store');/* @var $model Store */
		if (!$store = $model->getMerchantInfo($mid))
			$this->error('查询不到店铺信息');

		# 商家店铺SEO , 先预定在这里
		//$this->setPageSeo(GlobalSEO::getSeoInfo('store', $mid));
		$this->setPageSeo(array('seo_title' => "e办公商城-{$store['store_name']}"));

		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getGoodsCount($search));
		$page->pageSize = 20;

		$this->render('index' , array_merge($search , array(
			'store'		=> $store,
			'page'		=> $page,
			'list'		=> $model->getGoodsList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
		)));
	}
}