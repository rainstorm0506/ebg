<?php
/**
 * 分类列表 - 控制器
 * 
 * @author simon
 */
class ClassController extends WebController
{
	//商品首页
	public function actionIndex()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('home', 0));
		
		$model = ClassLoad::Only('WebClass');/* @var $model WebClass */
		$this->render('index' , array(
			'banner'		=> GlobalAdver::getAdverByCode('home_banner'),
			//'new'			=> $model->getNewGoods(6),
			'explosion' 	=> $model->getExplosionGoods(6),
			'class'			=> $model->getClassGoods(5),
				
		));
	}
	
	//新品爆款
	public function actionExplosion()
	{
		$search = array(
				'order'			=> (string)$this->getQuery('o'),
				'by'			=> (string)$this->getQuery('by'),
				'self'			=> (int)$this->getQuery('s'),
		);
		if ($search['order'] && !in_array($search['order'] , array('price' , 'sales' , 'putaway')))
			$this->error('请选择正确的排序!');
		$model = ClassLoad::Only('WebClass');/* @var $model WebClass */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getExplosionGoodsCount($search));
		$page->pageSize = 20;
		$list = $model->getExplosionGoodsList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount());
		$this->render('explosion' , array_merge(array(
				'page'			=> $page,
				'list'			=> $list,
		), $search));
	}
	
	//自营
	public function actionSelf()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('self', 0));
		
		$id = (int)$this->getQuery('id');
		$chain = array();
		if ($id && !($chain = GlobalGoodsClass::getClassChainById($id)))
			$this->error('分类异常!');

		$search = array(
			'keyword'		=> trim((string)$this->getQuery('w')),
			'order'			=> (string)$this->getQuery('o'),
			'by'			=> (string)$this->getQuery('by'),
			'brandID'		=> (int)$this->getQuery('b'),
			'priceStart'	=> (int)$this->getQuery('ps'),
			'priceEnd'		=> (int)$this->getQuery('pe'),
			'self'			=> 1,
			'tier'			=> empty($chain[0]) ? 0 : (int)$chain[0],
			'id'			=> $id,
			'classOne'		=> 0,
			'classTwo'		=> 0,
			'classThree'	=> 0,
			'attrs_val'		=> array(),
		);
		
		$this->_list_base($chain , $search , GlobalAdver::getAdverByCode('self_banner') , 'self');
	}

	//搜索
	public function actionSearch()
	{
		if (!$keyword = trim((string)$this->getQuery('keyword')))
			$this->error('错误的搜索关键字!');
		
		$id = (int)$this->getQuery('id');
		$chain = array();
		if ($id && !($chain = GlobalGoodsClass::getClassChainById($id)))
			$this->error('分类异常!');
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('search', 0));
		
		$this->viewsKeyword = $keyword;
		$search = array(
			'keyword'		=> $keyword,
			'order'			=> (string)$this->getQuery('o'),
			'by'			=> (string)$this->getQuery('by'),
			'brandID'		=> (int)$this->getQuery('b'),
			'priceStart'	=> (int)$this->getQuery('ps'),
			'priceEnd'		=> (int)$this->getQuery('pe'),
			'self'			=> (int)$this->getQuery('s'),
			'tier'			=> empty($chain[0]) ? 0 : (int)$chain[0],
			'id'			=> $id,
			'classOne'		=> 0,
			'classTwo'		=> 0,
			'classThree'	=> 0,
			'attrs_val'		=> array(),
		);
		
		$this->_list_base($chain , $search , array() , 'search');
	}
	
	//分类
	public function actionList()
	{
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的分类ID');
		
		if (!$chain = GlobalGoodsClass::getClassChainById($id))
			$this->error('分类异常!');
		$this->setPageSeo(GlobalSEO::getSeoInfo('gc', $id));
		
		$search = array(
			'keyword'		=> trim((string)$this->getQuery('w')),
			'order'			=> (string)$this->getQuery('o'),
			'by'			=> (string)$this->getQuery('by'),
			'brandID'		=> (int)$this->getQuery('b'),
			'priceStart'	=> (int)$this->getQuery('ps'),
			'priceEnd'		=> (int)$this->getQuery('pe'),
			'self'			=> (int)$this->getQuery('s'),
			'tier'			=> (int)$chain[0],
			'id'			=> $id,
			'classOne'		=> 0,
			'classTwo'		=> 0,
			'classThree'	=> 0,
		);
		
		$this->_list_base($chain , $search , ($search['tier']==1 ? GlobalAdver::getAdverByCode('class_banner' , $search['classOne']) : array()) , 'list');
	}
	
	private function _list_base(array $chain , array $search , array $banner , $viewName , $pageSize = 20)
	{
		if ($search['order'] && !in_array($search['order'] , array('price' , 'sales' , 'putaway')))
			$this->error('请选择正确的排序!');
		
		if ($search['priceStart'] > $search['priceEnd'])
		{
			$search['priceStart']	= ($search['priceStart'] + $search['priceEnd']);
			$search['priceEnd']		= $search['priceStart'] - $search['priceEnd'];
			$search['priceStart']	= $search['priceStart'] - $search['priceEnd'];
		}
		
		$id = empty($search['id']) ? 0 : (int)$search['id'];
		$attrs = array();
		if ($search['tier'] > 0)
		{
			switch ($search['tier'])
			{
				case 1 : $search['classOne'] = (int)$id; break;
				case 2 : $search['classOne'] = (int)$chain[1]; $search['classTwo'] = (int)$id; break;
				case 3 : $search['classOne'] = (int)$chain[1]; $search['classTwo'] = (int)$chain[2]; $search['classThree'] = (int)$id; break;
			}
			$attrs = $this->_html_attrs($search['classOne'] , $search['classTwo'] , $search['classThree']);
			$search['attrs_val'] = $attrs['attrs_val'];
		}
		
		$model = ClassLoad::Only('WebClass');/* @var $model WebClass */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getClassCount($search));
		$page->pageSize = $pageSize;
		$list = $model->getClassList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount());

		unset($chain[0]);
		if ($viewName == 'search')
			unset($search['keyword']);

		$brandList=array();
		if(!empty($search['classOne']) || !empty($search['classTwo']) || !empty($search['classThree']))
		{
			$brandList=GlobalBrand::getBrandList(1,$search['classOne'],$search['classTwo'],$search['classThree']);
		}
		$this->render($viewName , array_merge(array(
			'banner'		=> $banner,
			'page'			=> $page,
			'list'			=> $list,
			'chain'			=> $chain,
			'brand'			=> $brandList,
			'priceGroup'	=> GlobalGoodsClass::getClassPriceGroup($id),
			'classList'		=> $search['tier']<3 ? GlobalGoodsClass::getUnidList($search['classOne'] , ($search['tier']==1?$search['classOne']:$search['classTwo'])) : array(),
			'html_nav'		=> empty($attrs['html_nav']) ? '' : $attrs['html_nav'],
			'html_attrs'	=> empty($attrs['html_attrs']) ? '' : $attrs['html_attrs'],
		), $search));
	}
	
	private function _html_attrs($classOne , $classTwo , $classThree)
	{
		$attrs_html = $_html_nav = '';
		$_attrsVal = array();
		foreach (GlobalGoodsAttrs::getClassAttrs($classOne , $classTwo , $classThree) as $one)
		{
			if (empty($one['child']))
				continue;
			
			if (!empty($_GET['as'.$one['rank']]))
			{
				$_key = 'as'.$one['rank'];
				$as = $_GET[$_key];
				$_name = '';
				foreach ($one['child'] as $two)
				{
					if ($as == $two['rank'])
					{
						$_name = $two['title'];
						$_attrsVal[$one['rank']] = $two['unite_code'];
						break;
					}
				}
				$_html_nav .= '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array($_key)).'"><i>'.$one['title'].'：</i><em>'.$_name.'</em><b></b></a>';
			}else{
				$attrs_html .= "<li><h6>{$one['title']}：</h6><aside><dl>";
				foreach ($one['child'] as $two)
					$attrs_html .= '<dd>'.CHtml::link($two['title'] , $this->createAppendUrl($this , array('as'.$one['rank']=>$two['rank']))).'</dd>';
				$attrs_html .= '</dl></aside></li>';
			}
		}
		
		return array(
			'html_attrs'	=> $attrs_html,
			'html_nav'		=> $_html_nav,
			'attrs_val'		=> $_attrsVal,
		);
	}
	
}