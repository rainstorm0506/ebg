<?php

/**
 * 商品分类参数 控制器
 *
 * @author 涂先锋
 */
class GoodsArgsController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('args.l');
		
		$model = ClassLoad::Only('GoodsArgs');/* @var $model GoodsArgs */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getArgsCount());
		$page->pageSize = 20;
		$this->render('list' , array(
			'page' => $page,
			'list' => $model->getArgsList($page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}

	//添加 & 编辑
	public function actionSetting()
	{
		$this->checkUserPurview('args.set');
		
		$form = ClassLoad::Only('GoodsArgsForm');/* @var $form GoodsArgsForm */
		$this->exitAjaxPost($form);
		
		$defVal = '默认值可以不用填写';
		$info = array();
		$classID = array(0 , 0 , 0);
		$model = ClassLoad::Only('GoodsArgs');/* @var $model GoodsArgs */
		if ($id = (string)$this->getQuery('cid'))
		{
			$classID = explode('.', $id);
			$classID[0] = empty($classID[0]) ? 0 :(int)$classID[0];
			$classID[1] = empty($classID[1]) ? 0 :(int)$classID[1];
			$classID[2] = empty($classID[2]) ? 0 :(int)$classID[2];
			
			if (!GlobalGoodsClass::verifyClassChain($classID[0] , $classID[1] , $classID[2]))
				$this->error('分类异常!');
			
			$info = array_values($model->getArgsInfo($classID[0] , $classID[1] , $classID[2]));
		}
		
		if(isset($_POST['GoodsArgsForm']))
		{
			$form->attributes = $_POST['GoodsArgsForm'];
			if($form->validate())
			{
				$model->setting($_POST['GoodsArgsForm'] , $classID[0] , $classID[1] , $classID[2] , $defVal);
				GlobalGoodsClass::flush();
				$this->redirect(array('list'));
			}
		}
		
		$this->render('setting' , array(
			'form'		=> $form,
			'oneID'		=> $classID[0],
			'twoID'		=> $classID[1],
			'threeID'	=> $classID[2],
			'tree'		=> GlobalGoodsClass::getTree(),
			'info'		=> $info,
			'defVal'	=> $defVal,
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('args.d');
		
		if (!$id = (string)$this->getQuery('cid'))
			$this->error('错误的分类ID');
		
		$classID = explode('.', $id);
		$classID[0] = empty($classID[0]) ? 0 :(int)$classID[0];
		$classID[1] = empty($classID[1]) ? 0 :(int)$classID[1];
		$classID[2] = empty($classID[2]) ? 0 :(int)$classID[2];
			
		if (!GlobalGoodsClass::verifyClassChain($classID[0] , $classID[1] , $classID[2]))
			$this->error('分类异常!');
		
		$model = ClassLoad::Only('GoodsArgs');/* @var $model GoodsArgs */
		$model->deletes($classID[0] , $classID[1] , $classID[2]);
		GlobalGoodsClass::flush();
		$this->redirect(array('list'));
	}
	
	public function actionGetClassArgs()
	{
		$oneId = (int)$this->getQuery('one_id');
		$twoId = (int)$this->getQuery('two_id');
		$threeId = (int)$this->getQuery('three_id');
	
		if (!GlobalGoodsClass::verifyClassChain($oneId , $twoId , $threeId))
			$this->jsonOutput(1 , '分类异常!');
	
		$model = ClassLoad::Only('GoodsArgs');/* @var $model GoodsArgs */
		$this->jsonOutput(0 , array_values($model->getArgsInfo($oneId , $twoId , $threeId)));
	}
}
