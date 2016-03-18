<?php
/**
 * 提醒控制器
 */
class RemindController extends ApiController
{
	/**
	 * 设置是否接收别人的邀请
	 */
	public function actionSetAccept(){
		if(!$apt=(int)$this->getPost('apt'))
			$this->jsonOutput(2,'错误的参数');

		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录！' );

		$is_accept=(int)$this->getPost('is_accept');
		$model=ClassLoad::Only('Remind');/* @var $model Remind */
		if($row=$model->setAccept($id,$is_accept)){
			$this->jsonOutput(0,$row);
		}else{
			$this->jsonOutput(2,'设置失败，请重试');
		}
	}
	/**
	 * 获取提醒消息商家列表
	 */
	public function actionOneList(){
		if(!$apt=(int)$this->getPost('apt'))
			$this->jsonOutput(2,'错误的参数');
		
		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录！' );
		
		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		$model=ClassLoad::Only('Remind');/* @var $model Remind */
		if($list=$model->getOneList($id ,empty($pageNow) ? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize)){
			foreach($list as $k => $v){
				$list[$k]['status']=$model->getUnread($id,$v['send_id']);
			}
			$this->jsonOutput ( 0, $list );
		}else{
			$this->jsonOutput ( 2, '没有消息' );
		}
	}
	/**
	 * 获取提醒消息列表
	 */
	public function actionTwoList(){
		if(!$apt=(int)$this->getPost('apt'))
			$this->jsonOutput(2,'错误的参数');
		
		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录！' );
		
		if(!$send_id=(int)$this->getPost('id'))
			$this->jsonOutput(2,'错误的参数');
		
		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		$model=ClassLoad::Only('Remind');/* @var $model Remind */
		if($list=$model->getTwoList($id,$send_id , empty($pageNow) ? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize)){
			$row=$model->markRead($send_id);
			$this->jsonOutput ( 0, $list );
		}else{
			$this->jsonOutput ( 2, '没有提醒消息' );
		}
	}
}