<?php
/**
 * 物流快递运费 控制器
 * @author simon
 */
class ExpressController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('e.l');
		$keyword = (string)$this->getQuery('keyword');
		
		$model = ClassLoad::Only('Express');/* @var $model Express */
		$this->render('list' , array(
			'keyword'	=> $keyword,
			'list'		=> $model->getExpressList($keyword),
		));
	}

	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('e.c');
	
		$form = ClassLoad::Only('ExpressForm');/* @var $form ExpressForm */
		$this->exitAjaxPost($form);
	
		$model = ClassLoad::Only('Express');/* @var $model Express */
		if(isset($_POST['ExpressForm']))
		{
			$form->attributes = $_POST['ExpressForm'];
			if($form->validate())
			{
				$model->create($_POST['ExpressForm']);
				$this->redirect(array('express/list'));
			}
		}
	
		$this->render('append' , array(
			'form' => $form
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('e.m');
		
		$form = ClassLoad::Only('ExpressForm');/* @var $form ExpressForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Express');/* @var $model Express */
		if (!$info = $model->getExpressInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['ExpressForm']) ? $_POST['ExpressForm'] : array();
		if(isset($_POST['ExpressForm']) && $form->validate())
		{
			$model->modify($_POST['ExpressForm'] , $id);
			$this->redirect(array('express/list'));
		}
		
		$this->render('append' , array(
			'form' => $form,
			'info' => $info
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('e.d');
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('Express');/* @var $model Express */
		$model->deletes($id);
		$this->redirect(array('express/list'));
	}
	
	//运费列表
	public function actionFreightList()
	{
		$this->checkUserPurview('e.f.l');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Express');/* @var $model Express */
		if (!$info = $model->getExpressInfo($id))
			$this->error('你查看的公司不存在!');
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getFreightCount($id));
		$page->pageSize = 50;
		$this->render('freightList' , array(
			'express'	=> $info,
			'page'		=> $page,
			'list'		=> $model->getFreightList($id , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}
	
	//运费设置
	public function actionFreightSetting()
	{
		$this->checkUserPurview('e.f.s');
		
		$form = ClassLoad::Only('ExpressFreightForm');/* @var $form ExpressFreightForm */
		$this->exitAjaxPost($form);
		
		if (!$expId = (int)$this->getQuery('id'))
			$this->error('错误的物流公司ID');
		
		//运费ID
		$freId = (int)$this->getQuery('fid');
		
		$model = ClassLoad::Only('Express');/* @var $model Express */
		if (!$info = $model->getExpressInfo($expId))
			$this->error('你查看的公司不存在!');
		
		$freight = array();
		if ($freId && !($freight = $model->getFreightInfo($freId , $expId)))
			$this->error('你查询的运费数据不存在!');
		
		$form->attributes = isset($_POST['ExpressFreightForm']) ? $_POST['ExpressFreightForm'] : array();
		if(isset($_POST['ExpressFreightForm']) && $form->validate())
		{
			$model->freightSetting($_POST['ExpressFreightForm'] , $freId , $expId);
			$this->redirect(array('express/freightList' , 'id'=>$expId));
		}
		
		$this->render('freightSetting' , array(
			'express'	=> $info,
			'freight'	=> $freight,
			'form'		=> $form,
		));
	}
	
	//删除运费
	public function actionFreightDelete()
	{
		$this->checkUserPurview('e.f.d');
		
		if (!$expId = (int)$this->getQuery('id'))
			$this->error('错误的物流公司ID');
		
		if (!$freId = (int)$this->getQuery('fid'))
			$this->error('错误的运费ID');
		
		$model = ClassLoad::Only('Express');/* @var $model Express */
		$model->freightDelete($expId , $freId);
		$this->redirect(array('express/freightList' , 'id'=>$expId));
	}
}