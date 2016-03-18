<?php
class BackLogController extends SController
{
	/**
	 * 列表
	 */
	public function actionList()
	{
		$this->checkUserPurview('bl.lt');
		$model=ClassLoad::Only('BackLog');/* @var $model BackLog */
		$keyword = trim((string)$this->getQuery('keyword'));

		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount($keyword));
		$page->pageSize = 20;
		$this->render('list' , array(
			'page'	=> $page,
			'list'	=> $model->getList($keyword , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}
}