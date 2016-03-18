<?php
/**
 * 商品分类 控制器
 * @author simon
 */
class GoodsClassController extends SController
{
	#SEO 前缀
	const SEOCODE = 'gc';
	
	//列表
	public function actionList()
	{
		$this->checkUserPurview('gc.l');
		
		$this->render('list' , array(
			'tree'		=> $this->getListJson(),
			'attrs'		=> ClassLoad::Only('GoodsAttrs')->getAttrsExist(),
			'args'		=> ClassLoad::Only('GoodsArgs')->getArgsExist(),
			'seos'		=> GlobalSEO::getSeoState(self::SEOCODE)
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('gc.c');
		
		$form = ClassLoad::Only('GoodsClassForm');/* @var $form GoodsClassForm */
		$form->price = isset($form->price) ? $form->price : array();
		$this->exitAjaxPost($form);
		
		if(isset($_POST['GoodsClassForm']))
		{
			$form->attributes = $_POST['GoodsClassForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('GoodsClass');/* @var $model GoodsClass */
				$model->create($_POST['GoodsClassForm']);
				GlobalGoodsClass::flush();
				$this->redirect(array('list'));
			}
		}
		
		$this->render('create' , array(
			'form'		=> $form,
			'tree'		=> GlobalGoodsClass::getTree(),
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('gc.m');
		
		$form = ClassLoad::Only('GoodsClassForm');/* @var $form GoodsClassForm */
		$form->price = isset($form->price) ? $form->price : array();
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的分类ID');
		
		$model = ClassLoad::Only('GoodsClass');/* @var $model GoodsClass */
		if (!$info = $model->getClassInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['GoodsClassForm']) ? $_POST['GoodsClassForm'] : array();
		if(isset($_POST['GoodsClassForm']) && $form->validate())
		{
			if ($model->modify($_POST['GoodsClassForm'] , $info , $id))
				GlobalGoodsClass::flush();
			
			$this->redirect(array('list'));
		}
		
		$this->render('modify' , array(
			'form'	=> $form,
			'info'	=> $info,
			'price'	=> $model->getClassPrice($info)
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('gc.d');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的分类ID');
		
		$model = ClassLoad::Only('GoodsClass');/* @var $model GoodsClass */
		if (!$info = $model->getClassInfo($id))
			$this->error('你删除的分类不存在!');
		
		if ($model->deletes($id , (int)$info['tier']))
			GlobalGoodsClass::flush();
		
		$this->redirect(array('list'));
	}
	
	//交换排序
	public function actionExchangeRank()
	{
		$this->checkUserPurview('gc.er');
		
		$up		= (int)$this->getQuery('up');
		$down	= (int)$this->getQuery('down');
		
		if (!$up || !$down)
			$this->error('错误的分类ID');
		
		$model	= ClassLoad::Only('GoodsClass');/* @var $model GoodsClass */
		$up		= $model->getClassInfo($up);
		$down	= $model->getClassInfo($down);
		
		if (!$up || !$down)
			$this->error('分类数据不存在!');
		
		if ($model->exchangeRank($up , $down))
			GlobalGoodsClass::flush();
		
		$this->jsonOutput(0);
	}
	
	//设定SEO关键词
	public function actionSeo()
	{
		$this->checkUserPurview('gc.seo');
		
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的分类ID');
		
		$model = ClassLoad::Only('GoodsClass');/* @var $model GoodsClass */
		if (!$info = $model->getClassInfo($id))
			$this->error('你编辑的信息不存在!');
		
		if(isset($_POST['SeoForm']))
		{
			$form->attributes = $_POST['SeoForm'];
			if($form->validate())
			{
				GlobalSEO::setting($_POST['SeoForm'] , self::SEOCODE , $id);
				$this->redirect(array('list'));
			}
		}
		
		$this->render('seo' , array(
			'form'		=> $form,
			'info'		=> $info,
			'seo'		=> GlobalSEO::getSeoInfo(self::SEOCODE , $id),
			'tree'		=> GlobalGoodsClass::getTree(),
		));
	}
	
	private function getListJson()
	{
		$cache = array();
		foreach (GlobalGoodsClass::getTree() as $ak => $av)
		{
			if (!empty($av['child']))
			{
				foreach ($av['child'] as $bk => $bv)
				{
					if (!empty($bv['child']))
					{
						foreach ($bv['child'] as $ck => $cv)
							$cache['_'.$ak]['child']['_'.$bk]['child']['_'.$ck] = $cv;
					}
					$cache['_'.$ak]['child']['_'.$bk] = array(
						$bv[0],
						$bv[1],
						$bv[2],
						'child' =>isset($cache['_'.$ak]['child']['_'.$bk]['child'])?$cache['_'.$ak]['child']['_'.$bk]['child']:array()
					);
				}
			}
			$cache['_'.$ak] = array($av[0],$av[1],$av[2],'child'=>isset($cache['_'.$ak]['child'])?$cache['_'.$ak]['child']:array());
		}
		return $cache;
	}
}