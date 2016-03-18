<?php
/**
 * 文章列表管理 控制器
 * @author jeson.Q
 */
class ContentController extends SController
{
	#SEO 前缀
	const SEOCODE = 'sve';
	
	public $typeId = null;
	
	// 广告列表
	public function actionList()
	{
		// 设置访问权限
		$this->checkUserPurview('content:l');
		$this->typeId = $typeId = isset($_GET['id']) ? intval($_GET['id']) : null;
		$keyword = $this->getParam('keyword');
		$search = array(
			'keyword'		=> $this->getParam('keyword'),
			'SEOCODE'		=> self::SEOCODE,
		);
		
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$model = ClassLoad::Only('Content'); /* @var $model Content */
		$count = $model->getTotalNumber($search , $typeId);
		$page = new CPagination();
		$page->pageSize = 20;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$content = $model->getList($search , $offset , $page->pageSize , $typeId);
		$this->render('list' , array(
			'content' => $content ,
			'page' => $page ,
			'keyword' => $keyword 
		));
	}

	// 编辑广告
	public function actionEdit()
	{
		// 设置访问权限
		$this->checkUserPurview('content:e');
		// 加载类 、检查数据
		$form = ClassLoad::Only('ContentForm'); /* @var $form ContentForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Content'); /* @var $model Content */
		if (!$info = $model->getActiveInfo($id))
			$this->error('栏位不存在!');
			//echo "<pre>";var_dump($_POST['ContentForm']);exit;
			// 是否提交 编辑数据
		if (isset($_POST['ContentForm']))
		{
			$form->attributes = $_POST['ContentForm'];
			if ($form->validate())
			{
				$model->modifys($_POST['ContentForm'] , $id);
				GlobalContent::flush();
				$this->redirect(array(
					'content/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form ,
			'info' => $info 
		));
	}
	
	// 添加 文章
	public function actionCreate()
	{
		// 设置访问权限
		$this->checkUserPurview('content:c');
		$form = ClassLoad::Only('ContentForm'); /* @var $model ContentForm */
		$this->exitAjaxPost($form);
		// 判断是否点击分类栏目进入
		$form->type = isset($_GET['id']) ? $_GET['id'] : '';
		
		// 是否提交 创建数据
		if (isset($_POST['ContentForm']))
		{
			$form->attributes = $_POST['ContentForm'];
			if ($form->validate())
			{
				$model = ClassLoad::Only('Content'); /* @var $model Content */
				$model->modifys($_POST['ContentForm'] , null);
				GlobalContent::flush();
				$this->redirect(array(
					'content/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form 
		));
	}

	public function actionSeo()
	{
		$this->checkUserPurview('content:seo');
	
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的文章ID');
	
		$model = ClassLoad::Only('Content');/* @var $model Content */
		if (!$info = $model->getActiveInfo($id))
			$this->error('你编辑的文章信息不存在!');
	
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

	// 删除 文章
	public function actionClear()
	{
		// 设置访问权限
		$this->checkUserPurview('content:d');
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Content'); /* @var $model Content */
		$model->clear($id);
		GlobalContent::flush();
		$this->redirect(array(
			'content/list' 
		));
	}
}