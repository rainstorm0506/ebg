<?php

/**
 * 二手商品 控制器
 * @author 谭甜
 */
Yii::import('system.extensions.editor.UEditor');
class UsedGoodsController extends MerchantController
{
    #SEO 前缀
    const SEOCODE = 'ug';
    //列表
    public function actionList()
    {
        $this->showLeftNav='usedGoods';
        $this->leftNavType='usedGoods.list';
        $model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
        $shelf = (int)$this->getQuery('shelf');
        $search = array(
            'class_one_id'      => (int)$this->getQuery('class_one'),
            'class_two_id'      => (int)$this->getQuery('class_two'),
            'class_three_id'    => (int)$this->getQuery('class_three'),
            'shelf_id'          => $shelf === 419 ? 0 : $shelf,
            'status_id'         => (int)$this->getQuery('verify'),
            'keyword'           => trim((string)$this->getQuery('keyword')),
	        'SEOCODE'			=> self::SEOCODE,
        );
        $page = ClassLoad::Only('CPagination');/* @var $page CPagination */
        $page->setItemCount($model->getListCount($search));
        $page->pageSize = 20;
        $arr=GlobalUsedClass::getAllList();

        $this->render('list' , array(
            'class'         => GlobalUsedClass::getTree(),
            'shelfStatus'   => $model->getShelfStatus(),
            'verifyStatus'  => $model->getVerifyStatus(),
            'page' => $page,
            'list' => $model->getList($search,$page->getOffset() , $page->getLimit() , $page->getItemCount()),
        ));
    }
	/**
	 * 关键字设置
	 */
	public function actionSeo()
	{
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);

		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');

		$model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		if (!$info = $model->intro($id))
			$this->error('你编辑的商品信息不存在!');

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
			'tree'		=> GlobalUsedClass::getTree(),
		));
	}
    //添加
    public function actionCreate()
    {
        $this->showLeftNav='usedGoods';
        $this->leftNavType='usedGoods.create';
        $form = ClassLoad::Only('UsedGoodsForm');/* @var $form UsedGoodsForm */
        $post = isset($_POST['UsedGoodsForm']) ? $_POST['UsedGoodsForm'] : array();

        $form->img			= isset($post['img']) ? $post['img'] : array();
        $this->exitAjaxPost($form , 'formWrap');

        $formError = array();
        if($this->isPost() && $post)
        {
            $form->attributes = $post;
            if($form->validate())
            {
                $model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
                $model->create($post);
                $this->redirect(array('list'));
            }else{
                $formError = $form->getErrors();
                foreach ($formError as &$val)
                    $val = array_unique($val);
                $form->clearErrors();
            }
        }
        $arr=GlobalUsedClass::getAllList();
        $class=array();
        foreach($arr as $v){
            $class[$v['id']]=$v['title'];
        }
        $brand=array();
        foreach(GlobalBrand::getAllList() as $v){
            $brand[$v['id']]=$v['zh_name'].'/'.$v['en_name'];
        }
        $this->render('create' , array(
            'brand'         => $brand,
            'class'			=> GlobalUsedClass::getTree(),
            'form'			=> $form,
            'formError'		=> $formError,
			'tag'			=> GlobalGoodsTag::getTagKV(1),
        ));
    }
    //删除
    public function actionDelete(){
        if(!$id=(int)$this->getQuery('id'))
            $this->error('错误的商品id');

        $model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
        $model->clear($id);
        $this->redirect(array('list'));
    }

    //详情
    public function actionIntro(){
        if(!$id=(int)$this->getQuery('id'))
            $this->error('错误的商品id');

        $model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
        $this->render('intro' , array(
            'intro'=>$model->intro($id)
        ));
    }

    //编辑
    public function actionModify()
    {
        if(!$id=(int)$this->getQuery('id'))
            $this->error('错误的商品id');

        $model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
        if (!$intro = $model->intro($id))
            $this->error('找不到商品数据');

        $form=ClassLoad::Only('UsedGoodsForm');/* @var $form UsedGoodsForm */
        $post = isset($_POST['UsedGoodsForm']) ? $_POST['UsedGoodsForm'] : array();

        $form->img      = isset($post['img']) ? $post['img'] : $intro['img'];
        $this->exitAjaxPost($form , 'formWrap');

        $formError = array();
        if($this->isPost() && $post)
        {
            $form->attributes = $post;
            if($form->validate())
            {
                $model->modify($post);
                $this->redirect(array('list'));
            }else{
                $formError = $form->getErrors();
                foreach ($formError as &$val)
                {
                	$val = array_unique($val);
                }
                    
                $form->clearErrors();
            }
        }

        $class=array();
        foreach(        $arr=GlobalUsedClass::getAllList() as $v){
            $class[$v['id']]=$v['title'];
        }
        $brand=array();
        foreach(GlobalBrand::getAllList() as $v){
            $brand[$v['id']]=$v['zh_name'].'/'.$v['en_name'];
        }
        $this->render('modify',array(
            'used'          => $intro,
            'brand'         => $brand,
            'class'         => GlobalUsedClass::getTree(),
            'form'          => $form,
            'formError'     => $formError,
        	'tag'			=> GlobalGoodsTag::getTagKV(1),
        ));
    }

    //复制
    public function actionCopy($id){
        if(!$id=(int)$this->getQuery('id'))
            $this->error('错误的商品id');

        $model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
        if (!$intro = $model->intro($id))
            $this->error('找不到商品数据');

        $form=ClassLoad::Only('UsedGoodsForm');/* @var $form UsedGoodsForm */
        $post = isset($_POST['UsedGoodsForm']) ? $_POST['UsedGoodsForm'] : array();

        $form->img      = isset($post['img']) ? $post['img'] : $intro['img'];
        $this->exitAjaxPost($form , 'formWrap');

        $formError = array();
        if($this->isPost() && $post)
        {
            $form->attributes = $post;
            if($form->validate())
            {
                $model->create($post);
                $this->redirect(array('list'));
            }else{
                $formError = $form->getErrors();
                foreach ($formError as &$val)
                    $val = array_unique($val);
                $form->clearErrors();
            }
        }

        $class=array();
        foreach(        $arr=GlobalUsedClass::getAllList() as $v){
            $class[$v['id']]=$v['title'];
        }
        $brand=array();
        foreach(GlobalBrand::getAllList() as $v){
            $brand[$v['id']]=$v['zh_name'].'/'.$v['en_name'];
        }
        $this->render('copy',array(
            'used'          => $intro,
            'brand'         => $brand,
            'class'         => GlobalUsedClass::getTree(),
            'form'          => $form,
            'formError'     => $formError,
			'tag'			=> GlobalGoodsTag::getTagKV(1),
        ));

    }
    //批量操作
    public function actionBatch(){
        if($_POST){
            if(empty($_POST['id']))
                $this->error('错误的操作方式！');
            
            $model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
            $model->batch($_POST);
            $this->redirect(array('list'));
        }
    }
	/**
	 * 根据分类获取品牌
	 */
	public function actionGetBrand()
	{
		$one_id=$this->getQuery('one_id');
		$two_id=$this->getQuery('two_id');
		$three_id=$this->getQuery('three_id');

		$data=GlobalBrand::getBrand(2 , $one_id , $two_id , $three_id);
		$this->jsonOutput(0,$data);
	}
}