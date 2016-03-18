<?php
class StrollController extends WebController
{
	public function init()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('stroll' , 0));
	}
	
	public function actionIndex()
	{
		if (!$gatherID = (int)$this->getQuery('g'))
		{
			foreach (GlobalGather::getGatherFirst() as $k => $v)
			{
				$gatherID = $k;
				break;
			}
		}
		
		if ($gatherID <= 0 || !($gatherTree = GlobalGather::getGatherTree(false , $gatherID)))
			$this->error('没有数据!');
		
		$gatherName = $gatherTree['title'];
		$gatherTree = empty($gatherTree['child']) ? array() : $gatherTree['child'];
		$this->render('index' , array(
			'business'		=> GlobalGather::getScopeBusiness(),
			'gatherID'		=> $gatherID,
			'gatherTree'	=> $gatherTree,
			'gatherName'	=> $gatherName,
		));
	}
	
	public function actionStore()
	{
		if (!($gid = (int)$this->getQuery('gid')) || $gid<=0)
			$this->jsonOutput(1 , '参数错误');
		
		$model = ClassLoad::Only('Store');/* @var $model Store */
		if ($store = $model->getStoreByGid($gid))
		{
			$this->jsonOutput(0 , array('html' => $this->renderPartial('store' , array(
				'store' => $store,
			) , true)));
		}
		
		$this->jsonOutput(2 , '查询不到店铺信息!');
	}
}