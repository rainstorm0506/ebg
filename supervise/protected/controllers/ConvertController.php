<?php

/**
 * 兑换 控制器
 * @author 谭甜
 */
class ConvertController extends SController
{
	public function actionList(){
		$this->checkUserPurview('cn.lt');
		$form = ClassLoad::Only('ConvertForm');/* @var $form ConvertForm */
		$post = isset($_POST['ConvertForm']) ? $_POST['ConvertForm'] : array();
		$this->exitAjaxPost($form);

		$search=array();
		if($post){
			$search = array(
				'time'              => $post['convert_time'],
				'accept_time'       => $post['accept_time'],
				'user'              => $post['user'],
				'keyword'           => $post['keyword'],
				'status'            => $post['status'],
				'delivery'          => $post['delivery'],
			);
		}
		$model=ClassLoad::Only('Convert');/* @var $model Convert */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;
		$this->render('list' , array(
			'form'          =>$form,
			'page'			=> $page,
			'list'			=> $model->getList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount())
		));
	}
	//操作状态
	public function actionHandle(){
		$this->checkUserPurview('cn.ha');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		if(!$status=(int)$this->getQuery('status'))
			$this->error('错误的操作方式');

		$model=ClassLoad::Only('Convert');/* @var $model Convert */
		$model->handle($id,$status);
		$this->redirect(array('list'));
	}
}