<?php

/**
 * 商品分类属性 控制器
 *
 * @author 涂先锋
 */
class GoodsAttrsController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('ga.l');
		
		$model = ClassLoad::Only('GoodsAttrs');/* @var $model GoodsAttrs */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getAttrsCount());
		$page->pageSize = 20;
		$this->render('list' , array(
			'page' => $page,
			'list' => $model->getAttrsList($page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}

	//添加 & 编辑
	public function actionSetting()
	{
		$this->checkUserPurview('ga.set');
		
		$form = ClassLoad::Only('GoodsAttrsForm');/* @var $form GoodsAttrsForm */
		$this->exitAjaxPost($form);
		
		$info = array();
		$classID = array(0 , 0 , 0);
		$model = ClassLoad::Only('GoodsAttrs');/* @var $model GoodsAttrs */
		if ($id = (string)$this->getQuery('cid'))
		{
			$classID = explode('.', $id);
			$classID[0] = empty($classID[0]) ? 0 :(int)$classID[0];
			$classID[1] = empty($classID[1]) ? 0 :(int)$classID[1];
			$classID[2] = empty($classID[2]) ? 0 :(int)$classID[2];
			
			if (!GlobalGoodsClass::verifyClassChain($classID[0] , $classID[1] , $classID[2]))
				$this->error('分类异常!');
			
			$info = array_values($model->getAttrsInfo($classID[0] , $classID[1] , $classID[2]));
		}
		
		if(isset($_POST['GoodsAttrsForm']))
		{
			$form->attributes = $_POST['GoodsAttrsForm'];
			if($form->validate())
			{
				if ($model->setting($_POST['GoodsAttrsForm'] , $classID[0] , $classID[1] , $classID[2]))
					GlobalGoodsAttrs::flush();
				$this->redirect(array('list'));
			}
		}
		
		$this->render('setting' , array(
			'form'		=> $form,
			'oneID'		=> $classID[0],
			'twoID'		=> $classID[1],
			'threeID'	=> $classID[2],
			'tree'		=> GlobalGoodsClass::getTree(),
			'info'		=> $info
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('ga.d');
		
		if (!$id = (string)$this->getQuery('cid'))
			$this->error('错误的分类ID');
		
		$classID = explode('.', $id);
		$classID[0] = empty($classID[0]) ? 0 :(int)$classID[0];
		$classID[1] = empty($classID[1]) ? 0 :(int)$classID[1];
		$classID[2] = empty($classID[2]) ? 0 :(int)$classID[2];
			
		if (!GlobalGoodsClass::verifyClassChain($classID[0] , $classID[1] , $classID[2]))
			$this->error('分类异常!');
		
		$model = ClassLoad::Only('GoodsAttrs');/* @var $model GoodsAttrs */
		$model->deletes($classID[0] , $classID[1] , $classID[2]);
		GlobalGoodsAttrs::flush();
		$this->redirect(array('list'));
	}
	
	public function actionGetClassAttrs()
	{
		$oneId = (int)$this->getQuery('one_id');
		$twoId = (int)$this->getQuery('two_id');
		$threeId = (int)$this->getQuery('three_id');
		
		if (!GlobalGoodsClass::verifyClassChain($oneId , $twoId , $threeId))
			$this->jsonOutput(1 , '分类异常!');
		
		$data = array();
		$model = ClassLoad::Only('GoodsAttrs');/* @var $model GoodsAttrs */
		foreach ($model->getAttrsInfo($oneId , $twoId , $threeId) as $key => $val)
		{
			unset($val['parent_unite_code'] , $val['unite_code'] , $val['class_one_id'] , $val['class_two_id'] , $val['class_three_id'] , $val['rank']);
			
			$ct = array();
			if (!empty($val['child']))
			{
				foreach ($val['child'] as $k => $v)
					$ct[$k] = $v['title'];
			}
			
			$val['child'] = $ct;
			$data[$key] = $val;
		}

		$this->jsonOutput(0 , $data);
	}
}
