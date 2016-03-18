<?php
class SupplyForm extends ApiForm
{
	public $apt , $id , $cid , $type , $brand , $title , $price , $page , $order , $keyword , $goods_id , $need_goods_id , $mer_id;
	public $brand_id , $num , $describes , $color_id , $size_id , $class_id , $content , $pic , $rapaport , $sid;
	public $pic_group = array();
	public $main_pic;
	public $collect = array();
	public function rules()
	{
		return array(
			array('apt,id',' required'),
			array('apt' , 'numerical', 'integerOnly'=>true, 'min'=>1),
			array('apt','checkLogin'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'apt' => '时间',
		);
	}
	
	public function checkLogin()
	{
		if(!$this->getUid())
		{
			$this->addError('apt', '请先登录账号！');
		}
	}
	
	public function validateClassAttrs()
	{
		$rx = true;
		
		if ($this->cid <= 0)
		{
			$rx = false;
			$this->addError('cid' , '分类ID不存在');
		}
		
		if ($this->apt <= 0)
		{
			$rx = false;
			$this->addError('apt' , 'APP抛数据的时间错误');
		}
		
		return $rx;
	}
	
	public function validateBrand()
	{
		$err = true;
		if($this->apt <=0)
		{
			$err = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		return $err;
	}
	
	public function validatePub()
	{
		$pub = true;
		if($this->apt <= 0)
		{
			$pub = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if($this->class_id <=0){
			$pub = false;
			$this->addError('class_id', '分类ID不存在');
		}
		if($this->type <= 0){
			$pub = false;
			$this->addError('type', '类型错误!');
		}
		if(empty($this->title))
		{
			$pub=false;
			$this->addError('title', '商品名称错误');
		}
		
		if($this->price <=0){
			$pub=false;
			$this->addError('price', '商品价格错误');
		}
		if($this->brand_id <=0){
			$pub=false;
			$this->addError('brand_id', '品牌ID错误');
		}
		if($this->num <=0){
			$pub=false;
			$this->addError('num', '商品数量错误');
		}
		return $pub;
	} 
	
	//搜索列表
	public function validateSearch()
	{
		$search = true;
		if($this->apt <= 0)
		{
			$search=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if($this->title == '')
		{
			$search=false;
			$this->addError('title', '请输入商品或商家!');
		}
		
		if($this->page <= 0)
		{
			$search=false;
			$this->addError('page', '页码错误');
		}
		return $search;
	}
		
	public function validatorDemandShow()
	{
		
		$show = true;
		if($this->apt<=0){
			$show=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if($this->id<=0){
			$show=false;
			$this->addError('id', '商品ID错误');
		}
		
		if($this->page<=0){
			$show=false;
			$this->addError('page', '页码错误');
		}
	

		return $show;
	}
	
	public function validatorDemand()
	{
		$offer = true;
		if($this->apt<=0)
		{
			$offer=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if(empty($this->rapaport)){
			$offer=false;
			$this->addError('rapaport', '报价不能为空,请重试');
		}
		if($this->id<=0){
			$offer=false;
			$this->addError('id', '需求商品ID错误');
		} 
		
		if($this->mer_id<=0)
		{
			$offer=false;
			$this->addError('id', '需求商家ID');
		}
		return $offer;
	}
	
	
	public function validateInfo()
	{
		$info=true;
		if($this->apt<=0){
			$info=false;
			$this->addError('apt','APP抛数据的时间错误');
		}
		return $info;
	}
	
	//比价验证
	public function validateCompare()
	{
		$compare=true;
		if($this->apt<=0)
		{
			$compare=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if($this->id<=0)
		{
			$compare=false;
			$this->addError('id', 'ID错误');
		}
		
		if($this->page<=0)
		{
			$compare=false;
			$this->addError('page', '页码错误');
		}
		
		return $compare;
	}
	
	//供应数据删除或编辑
	public function validatorData()
	{
	
		$show = true;
		if($this->apt<=0){
			$show=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if($this->id<=0){
			$show=false;
			$this->addError('id', '商品ID错误');
		}
		
		return $show;
	}
	//关注验证
	public function validatorFocus()
	{
	
		$show = true;
		if($this->apt<=0){
			$show=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
	
		if($this->cid<=0){
			$show=false;
			$this->addError('id', '商品ID错误');
		}
		
		if($this->type<=0)
		{
			$show=false;
			$this->addError('type', '类型错误');
		}
	
		return $show;
	}
	//个人订阅
	public function validatorRss()
	{
	
		$rss = true;
		if($this->apt<=0){
			$rss=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if($this->page<=0){
			$rss=false;
			$this->addError('page', '页码错误');
		}
		return $rss;
	}
	//添加关注
	public function validatorInsert()
	{
	
		$insert = true;
		if($this->apt<=0){
			$insert=false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if(empty($this->collect)){
			$insert=false;
			$this->addError('collect', '数组不能为空');
		}
		return $insert;
	}

}