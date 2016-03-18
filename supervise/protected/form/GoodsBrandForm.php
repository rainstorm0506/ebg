<?php
class GoodsBrandForm extends SForm
{
	public $zh_name , $en_name , $is_using , $rank , $logo;
	public $goods_class = array() , $used_class = array();
	//基本验证
	public function rules()
	{
		return array
		(
			array('is_using , rank', 'required'),
			array('zh_name , en_name', 'length', 'min'=>1, 'max'=>50),
			array('zh_name , en_name', 'checkName'),
			array('goods_class , used_class', 'checkClass'),
			array('logo , goods_class , used_class' , 'checkNull'),
		);
	}
	
	//检查 部门名称 是否重名
	public function checkName($tag)
	{
		if ($this->zh_name == '' && $this->en_name == '')
			$this->addError('en_name' , '品牌的中文名、英文名至少选题一个。');
		
		if ($tag == 'zh_name' && $this->zh_name && preg_match('/\w+/' , $this->zh_name))
			$this->addError($tag , '只能输入中文。');
		
		if ($tag == 'en_name' && $this->en_name && !preg_match('/\w+/' , $this->en_name))
			$this->addError($tag , '只能输入英文。');
		
		$model = ClassLoad::Only('GoodsBrand');/* @var $model GoodsBrand */
		if ($model->checkName($this->{$tag} , $tag , (int)$this->getQuery('id')))
			$this->addError($tag , '你填写的 [品牌名称] 已存在.');
	}
	//检查是否选择分类
	public function checkClass(){
		if ($this->goods_class == array() && $this->used_class == array())
			$this->addError('used_class' , '全新分类或二手分类请至少选择一个。');
	}

}