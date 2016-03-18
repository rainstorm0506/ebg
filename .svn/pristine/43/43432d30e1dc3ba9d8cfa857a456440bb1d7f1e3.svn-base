<?php
/**
 * 会员行为日志 控制器
 * @author simon
 */
class UserActLogController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('ual.l');
		$keyword = (string)$this->getQuery('keyword');
		
		$model = ClassLoad::Only('UserActLog');/* @var $model UserActLog */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getLogCount($keyword));
		$page->pageSize = 50;
		
		$ULSet = ClassLoad::Only('UserLayerSet');/* @var $ULSet UserLayerSet */
		$this->render('list' , array(
			'keyword'	=> $keyword,
			'page'		=> $page,
			'list'		=> $model->getLogList($keyword , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
			'action'	=> $ULSet->getUserAction()
		));
	}
}