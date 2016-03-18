<?php
/**
 * 二手商品  - 控制器
 */
class UsedController extends WebController
{
	// 二手首页
	public function actionIndex()
	{
		$model = ClassLoad::Only('Used'); /* @var $model Used */
		$used = array ('is_self' => 1);
		$class_id = (int)$this->getQuery('id');
		$chain = array ();
		if($class_id && !($chain = GlobalUsedClass::getClassChainById($class_id)))
			$this->error('分类异常!');

		$brand_id = (int)$this->getQuery('b');
		$search = array (
			'keyword'		=> trim((string)$this->getQuery('w')), 
			'order'			=> (string)$this->getQuery('o'), 
			'by'			=> (string)$this->getQuery('by'), 
			'brand_id'		=> $brand_id, 
			'priceStart'	=> (int)$this->getQuery('ps'), 
			'priceEnd'		=> (int)$this->getQuery('pe'), 
			'tier'			=> empty($chain[0]) ? 0 : (int)$chain[0], 
			'self'			=> (int)$this->getQuery('self'),
			'class_id'		=> $class_id,
			'classOne'		=> 0,
			'classTwo'		=> 0,
			'classThree'	=> 0
		);
		unset($class_id);
		if($search['order'] && !in_array($search['order'] , array ('price','sales','putaway')))
			$this->error('请选择正确的排序!');
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('used' , 0));
		
		if($search['tier'] > 0)
		{
			switch($search['tier'])
			{
				case 1 :
					$search['classOne'] = (int)$search['class_id'];
				break;
				case 2 :
					$search['classOne'] = (int)$chain[1];
					$search['classTwo'] = (int)$search['class_id'];
				break;
				case 3 :
					$search['classOne'] = (int)$chain[1];
					$search['classTwo'] = (int)$chain[2];
					$search['classThree'] = (int)$search['class_id'];
				break;
			}
		}
		if($search['priceStart'] > $search['priceEnd'])
		{
			$search['priceStart'] = ($search['priceStart'] + $search['priceEnd']);
			$search['priceEnd'] = $search['priceStart'] - $search['priceEnd'];
			$search['priceStart'] = $search['priceStart'] - $search['priceEnd'];
		}
		$brandList=array();
		if(!empty($search['classOne']) || !empty($search['classTwo']) || !empty($search['classThree']))
		{
			$brandList=GlobalBrand::getBrandList(2,$search['classOne'],$search['classTwo'],$search['classThree']);
		}
		$page = ClassLoad::Only('CPagination'); /* @var $page CPagination */
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;
		$this->render('index' , array_merge(array (
			'page' 			=> $page,
			'id' 			=> $search['class_id'],
			'used' 			=> $used,
			'self' 			=> $search['self'],
			'chain' 		=> $chain,
			'brand' 		=> $brandList,
			'classall' 		=> $search['class_id'] == 0 ? $model->getAllclass() : '',
			'priceGroup' 	=> GlobalUsedClass::getClassPriceGroup($search['class_id'] , true),
			'classList' 	=> ($search['tier'] < 3) ? GlobalUsedClass::getUnidList($search['classOne'] , ($search['tier'] == 1 ? $search['classOne'] : $search['classTwo'])) : array (),
			'list' 			=> $model->getList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount())
		) , $search));
	}
	// 二手详情页
	public function actionIntro()
	{
		if(!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$model = ClassLoad::Only('Used'); /* @var $model Used */
		if(!$intro = $model->intro($id))
			$this->error('查询不到商品信息');
		
		if(!$intro['merchant'] = $model->getMerchant($intro['merchant_id']))
			$this->error('商家信息有误！');
		
		if (!$seo = GlobalSEO::getSeoInfo('ug', $id))
			$seo = array('seo_title' => "二手{$intro['title']}报价，价格，参数-e办公商城");
		$this->setPageSeo($seo);

		$this->render('intro' , array (
			'classall'	=> $model->getAllclass(),
			'intro'		=> $intro
		));
	}
	// 收藏商品
	public function collectGoods()
	{
		$id = (int)$this->getQuery('id');
	}
}