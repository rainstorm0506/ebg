<?php
/**
 * 全新商品  - 控制器
 */
class StoreController extends WebApiController
{
	/**
	 * 店铺基本信息
	 */
	public function actionStore()
	{
		if(!$id=$this->getPost('id') )
			$this->jsonOutput(2 , '参数有误');

		if(!$row=GlobalMerchant::CheckUser($id))
			$this->jsonOutput(2 , '错误的商家id');

		if($data=GlobalMerchant::getMerchantInfo($id))
		{
			$data=array(
				'uid'				=>$data['uid'],
				'store_name'		=>$data['store_name'],
				'mer_name'			=>$data['mer_name'],
				'bus_start_year'	=>$data['bus_start_year'],
				'mer_ensure_money'	=>$data['mer_ensure_money'],
				'store_avatar'		=>$data['store_avatar'],
				'store_grade'		=>$data['store_grade']
			);
			$this->jsonOutput(0 , $data);
		}
		else
		{
			$this->jsonOutput(2,'没有该商家信息');
		}
	}
	/**
	 * 店铺详情
	 */
	public function actionStoreInfo()
	{
		if(!$id=$this->getPost('id') )
			$this->jsonOutput(2 , '参数有误');

		if(!$row=GlobalMerchant::CheckUser($id))
			$this->jsonOutput(2 , '错误的商家id');

		if($data=GlobalMerchant::getMerchantInfo($id))
		{
			$this->jsonOutput(0 , $data);
		}
		else
		{
			$this->jsonOutput(2 , '没有该商家信息');
		}
	}
	/**
	 * 商家商品
	 */
	public function actionStoreGoods()
	{
		if(!$id=$this->getPost('id') )
			$this->jsonOutput(2 , '参数有误');

		if(!$row=GlobalMerchant::CheckUser($id))
			$this->jsonOutput(2 , '错误的商家id');

		$search=array(
			'order'			=> (string)$this->getPost('o'),
			'by'			=> (string)$this->getPost('by')
		);
		$pageNow=$this->getPost('pageNow');
		$pageSize=$this->getPost('pageSize');
		$model=ClassLoad::Only('store'); /* @var $model store */
		if($row=$model->goodsList($search , $id , empty($pageNow)?1: $pageNow , empty($pageSize)?6:$pageSize))
		{
			$this->jsonOutput(0 , $row);
		}
		else
		{
			$this->jsonOutput(2 , '没有符合条件商品');
		}
	}
}