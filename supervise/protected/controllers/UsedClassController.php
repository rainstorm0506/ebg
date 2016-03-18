<?php

/**
 * 二手商品分类控制器
 * @author 谭甜
 */
class UsedClassController extends SController
{
	#SEO 前缀
	const SEOCODE = 'uc';
	//分类列表
	public function actionList()
	{
		$this->checkUserPurview('uc.lt');

		$this->render('list' , array(
			'tree'		=> $this->getListJson(),
			'seos'		=> GlobalSEO::getSeoState(self::SEOCODE)
		));
	}

	//添加分类
	public function actionCreate(){
		$this->checkUserPurview('uc.ce');

		$form = ClassLoad::Only('UsedClassForm');/* @var $model UsedClassForm */
		$this->exitAjaxPost($form);

		if(isset($_POST['UsedClassForm']))
		{
			$form->attributes = $_POST['UsedClassForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('UsedClass');/* @var $model UsedClass */
				$model->create($_POST['UsedClassForm']);
				GlobalUsedClass::flush();
				$this->redirect(array('list'));
			}
		}
		$this->render('create' , array(
			'form'      => $form,
			'tree'		=> GlobalUsedClass::getTree(),
		));
	}
	/**
	 * 删除
	 */
	public function actionDelete(){
		$this->checkUserPurview('uc.cl');
		if (!$id = (string)$this->getQuery('id'))
			$this->error('错误的分类ID');

		$model = ClassLoad::Only('UsedClass');/* @var $model UsedClass */
		if (!$info = $model->getClassInfo($id))
			$this->error('你删除的分类不存在!');

		if ($model->deletes($id , (int)$info['tier']))
			GlobalUsedClass::flush();

		$this->redirect(array('list'));
	}
	/**
	 * 编辑
	 */
	public function actionModify(){
		$this->checkUserPurview('uc.my');
		if (!$id = (string)$this->getQuery('id'))
			$this->error('错误的分类ID');

		$model=ClassLoad::Only('UsedClass');/* @var $model UsedClass */
		if (!$info = $model->getClassInfo($id))
			$this->error('你编辑的信息不存在!');

		$form=ClassLoad::Only('UsedClassForm');/* @var $model UsedClassForm */
		$this->exitAjaxPost($form);

		if(isset($_POST['UsedClassForm']))
		{
			$form->attributes = $_POST['UsedClassForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('UsedClass');/* @var $model UsedClass */
				$model->modify($_POST['UsedClassForm'], $info , $id);
				GlobalUsedClass::flush();
				$this->redirect(array('list'));
			}
		}
		$this->render('modify' , array(
			'form'=>$form,
			'info' => $info,
			'price'=>$model->getClassPrice($info)
		));
	}
	/**
	 * 交换排序
	 */
	public function actionExchangeRank()
	{
		$this->checkUserPurview('uc.my');

		$up		= (int)$this->getQuery('up');
		$down	= (int)$this->getQuery('down');

		if (!$up || !$down)
			$this->error('错误的分类ID');

		$model	= ClassLoad::Only('UsedClass');/* @var $model UsedClass */
		$up		= $model->getClassInfo($up);
		$down	= $model->getClassInfo($down);

		if (!$up || !$down)
			$this->error('分类数据不存在!');

		if ($model->exchangeRank($up , $down))
			GlobalUsedClass::flush();

		$this->jsonOutput(0);
	}
	/**
	 * 设定SEO关键词
	 */
	public function actionSeo()
	{
		$this->checkUserPurview('uc.seo');

		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);

		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的分类ID');

		$model = ClassLoad::Only('UsedClass');/* @var $model UsedClass */
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
		foreach (GlobalUsedClass::getTree() as $ak => $av)
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