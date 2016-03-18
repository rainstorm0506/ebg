<?php
/**
 * 二手控制器
 */
class UsedController extends ApiController
{
	/**
	 * 获取全部二手分类
	 */
	public function actionClass(){
		$ct = (int)$this->getPost('ct');
		$this->jsonOutput(0 , array(
			'category'		=> $ct ==2 ? GlobalUsedClass::getTree() : GlobalUsedClass::getApiTree()
		));
	}
	/**
	 * 成色区间范围
	 */
	public function actionUseTime(){
		$apt = (int)$this->getPost('apt');
		$used_time=GlobalUsedGoods::$UseTime;
		$data=array();
		foreach($used_time as $k=>$v){
			$arr=array(
				'id'=>$k,
				'title'=>$v
			);
			$data[]=$arr;
		}
		$this->jsonOutput(0,$data);
	}
	/**
	 * 发布二手商品
	 */
	public function actionCreate() {
		$form = ClassLoad::Only('UsedGoodsForm'); /* @var $form UsedGoodsForm */
		$form->title			=	trim($this->getPost('title'));
		$form->brand_id			=	(int)$this->getPost('brand_id');
		$form->goods_num		=	trim($this->getPost('goods_num'));
		$form->lightspot		=	trim($this->getPost('lightspot'));
		$form->dict_one_id		=	(int)$this->getPost('dict_one_id');
		$form->dict_two_id		=	(int)$this->getPost('dict_two_id');
		$form->dict_three_id	=	(int)$this->getPost('dict_three_id');
		$form->buy_price		=	$this->getPost('buy_price');
		$form->sale_price		=	$this->getPost('sale_price');
		$form->stock			=	$this->getPost('stock');
		$form->weight			=	$this->getPost('weight');
//		$form->content			=	$this->getPost('content');
		$form->cover			=	$this->getPost('cover');
		$form->use_time			=	(int)$this->getPost('use_time');
		$form->class_one_id		=	(int)$this->getPost('class_one_id');
		$form->class_two_id		=	(int)$this->getPost('class_two_id');
		$form->class_three_id	=	(int)$this->getPost('class_three_id');
		$form->img				=	(array)$this->getPost('img');
		if($form->validate())
		{
			$model = ClassLoad::Only('Used');/* @var $model Used */
			if($row = $model->create($form))
			{
				$this->jsonOutput(0,'');
			}else{
				$this->jsonOutput(2,'添加失败');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	/**
	 * 二手商品上下架
	 */
	public function actionShelf() {
		if(!$id = $this->getPost('id'))
			$this->jsonOutput(2,'请选择正确的商品id');
		
		if(!$apt = $this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');
		
		if(!$shelf = $this->getPost('shelf'))
			$this->jsonOutput(2,'参数有误');
		
		$model = ClassLoad::Only('Used');/* @var $model Used */
		if($row=$model->shelf($id,$shelf)){
			$this->jsonOutput(0,'');
		}else{
			$this->jsonOutput(2,'操作失败');
		}
	}
	/**
	 * 二手商品详情
	 */
	public function actionInfo() {
		if(!$id = $this->getPost('id'))
			$this->jsonOutput(2,'请选择正确的商品id');
		
		if(!$apt = $this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');
		
		$model = ClassLoad::Only('Used');/* @var $model Used */
		$ss=Yii::app()->params['imgDomain'];
		if($row=$model->getInfo($id)){
			$merchant=GlobalMerchant::getMerchantInfo($row['merchant_id']);
			$info=array(
				'id'				=>	$row['id'],
				'title'				=>	$row['title'],
				'lightspot'			=>	$row['lightspot'],
				'merchant_id'		=>	$row['merchant_id'],
				'mer_name'			=>	GlobalMerchant::getStoreName($row['merchant_id']),
				'is_self'			=>	$row['is_self'],
				'class_one_id'		=>	$row['class_one_id'],
				'class_one_name'	=>	GlobalUsedClass::getClassName($row['class_one_id']),
				'class_two_id'		=>	$row['class_two_id'],
				'class_two_name'	=>	GlobalUsedClass::getClassName($row['class_two_id']),
				'class_three_id'	=>	$row['class_three_id'],
				'class_three_name'	=>	GlobalUsedClass::getClassName($row['class_three_id']),
				'goods_num'			=>	$row['goods_num'],
				'brand_id'			=>	$row['brand_id'],
				'brand_name'		=>	GlobalBrand::getBrandName($row['brand_id']),
				'shelf_id'			=>	$row['shelf_id'],
				'status_id'			=>	$row['status_id'],
				'use_time'			=>	$row['use_time'],
				'use_time_name'		=>	GlobalUsedGoods::getUseTime($row['use_time']),
				'buy_price'			=>	$row['buy_price'],
				'sale_price'		=>	$row['sale_price'],
				'detail'			=>	$row['detail'],
				'stock'				=>	$row['stock']==-999?-1:$row['stock'],
				'weight'			=>	$row['weight'],
				'dict_one_id'		=>	$row['dict_one_id'],
				'dict_one_name'		=>	GlobalDict::getAreaName($row['dict_one_id']),
				'dict_two_id'		=>	$row['dict_two_id'],
				'dict_two_name'		=>	GlobalDict::getAreaName($row['dict_two_id'],$row['dict_one_id']),
				'dict_three_id'		=>	$row['dict_three_id'],
				'dict_three_name'	=>	GlobalDict::getAreaName($row['dict_three_id'],$row['dict_one_id'],$row['dict_two_id']),
				'cover'				=>	$row['cover'],
				'content'			=>	$row['content'],
				'img'				=>	$row['img'],
				'store_grade'		=>	$merchant['store_grade'],
				'store_tel'			=>	$merchant['store_tel']
			);
			$this->jsonOutput(0,$info);
		}else{
			$this->jsonOutput(2,'没有该商品信息！');
		}
	}
	/**
	 * 编辑商品
	 */
	public function actionModify(){
		$form = ClassLoad::Only('UsedGoodsForm'); /* @var $form UsedGoodsForm */
		$form->id				=	$this->getPost('id');
		$form->title			=	$this->getPost('title');
		$form->brand_id			=	$this->getPost('brand_id');
		$form->lightspot		=	$this->getPost('lightspot');
		$form->dict_one_id		=	$this->getPost('dict_one_id');
		$form->dict_two_id		=	$this->getPost('dict_two_id');
		$form->dict_three_id	=	$this->getPost('dict_three_id');
		$form->buy_price		=	$this->getPost('buy_price');
		$form->sale_price		=	$this->getPost('sale_price');
		$form->stock			=	$this->getPost('stock');
		$form->weight			=	$this->getPost('weight');
//		$form->content			=	$this->getPost('content');
		$form->cover			=	$this->getPost('cover');
		$form->use_time			=	$this->getPost('use_time');
		$form->class_one_id		=	$this->getPost('class_one_id');
		$form->class_two_id		=	$this->getPost('class_two_id');
		$form->class_three_id	=	$this->getPost('class_three_id');
		$form->img				=	(array)$this->getPost('img');
		if($form->validate())
		{
			$model = ClassLoad::Only('Used');/* @var $model Used */
			if($row = $model->modify($form))
			{
				$this->jsonOutput(0,array());
			}else{
				$this->jsonOutput(2,'修改失败，请重试!');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	/**
	 * 商家删除商品
	 */
	public function actionClear() {
		if(!$id=$this->getPost('id'))
			$this->jsonOutput(2,'参数有误');
		
		if(!$apt = $this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');
		
		$model = ClassLoad::Only('Used');/* @var $model Used */
		if($model->clear($id)){
			$this->jsonOutput(0,'操作成功');
		}else{
			$this->jsonOutput(2,'操作失败，请重试!');
		}
	}
	/**
	 * 二手商品列表
	 */
	public function actionList(){
		if(!$apt = $this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');

		if(!$id=$this->getMerchantID())
			$this->jsonOutput(2,'请登录');

		$merchant=$this->getPost('id');
		$id=empty($merchant)?$id:$merchant;
		$status=$this->getPost('status');
		$keyword=$this->getPost('keyword');
		$search=array(
			'keyword'	=>	$keyword,
			'status'	=>	$status
		);
		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		$model = ClassLoad::Only('Used');/* @var $model Used */
		if($row=$model->getList($search ,$id , empty($pageNow)? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize)){
			$this->jsonOutput(0,$row);
		}else{
			$this->jsonOutput(2,'没有符合条件的二手商品');
		}
	}
	/**
	 * 根据分类获取品牌列表
	 */
	public function actionBrandList(){
		if(!$cid = (int)$this->getPost('cid'))
			$this->jsonOutput(2,'参数有误');

		if(!$apt = $this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');

		if($row=GlobalBrand::getBrand(2 , 0 , 0 , $cid)){
			$data=array();
			foreach($row as $k=>$v)
			{
				$data[]=array('id'=>$k,'title'=>$v);
			}
			$this->jsonOutput(0,$data);
		}else{
			$this->jsonOutput(2,'该分类下没有二手商品');
		}
	}
}