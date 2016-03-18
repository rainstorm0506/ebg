<?php
/**
 * 通讯录控制器
 */
class ContactsBookController extends ApiController
{
	public function actionCount(){
		if(!$apt=(int)$this->getPost('apt'))
			$this->jsonOutput(2,'错误的参数');
		
		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录！' );
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		if($row = $model->getAttentionCount($id)) {
			$this->jsonOutput ( 0, $row);
		} else{
			$this->jsonOutput ( 2, '亲，你还没有关注，快去结交新朋友吧!');
		}
	}
	/**
	 * 通讯录列表
	 * @param $id 商家id
	 * @param $type 1=我关注的商家,2=关注我的商家
	 */
	public function actionList(){
		if(!$type=(int)$this->getPost('type'))
			$this->jsonOutput(2,'错误的参数');

		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录！' );

		if(!$apt=(int)$this->getPost('apt'))
			$this->jsonOutput(2,'错误的参数');
		
		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		//获取关注列表
		if($list = $model->getAttentionList($id ,$type , empty($pageNow) ? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize)) {
			$this->jsonOutput ( 0, $list );
		} else{
			$this->jsonOutput ( 2, '亲，你还没有关注，快去结交新朋友吧!');
		}
	}
	/**
	 * 发送关注邀请
	 * @param $id 商家id
	 * @param $invitation 邀请对象id
	 */
	public function actionSendInvitation () {
		if(!$id=(int)$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录!' );

		if(!$invitation=array_unique(array_filter((array)$this->getPost('invitation'))))
			$this->jsonOutput(2,'请选择正确的邀请对象!');

		$model=ClassLoad::Only('Remind');/* @var $model Remind */
		if($row = $model->addRemind($id,$invitation)) {
			if(isset($row['id'])){
				if($name=GlobalMerchant::getStoreName($row['id'])){
					$this->jsonOutput(2 ,array('info'=>'你今天已对'.$name.'发出邀请，请明天再来'));
				} else{
					$this->jsonOutput(2, '邀请发送失败,请重试!' );
				}
			}else{
				$this->jsonOutput(0 ,array('info'=>'已成功发送邀请，坐等对方关注'));
			}
		} else{
			$this->jsonOutput ( 2, '邀请发送失败,请重试!' );
		}
	}
	/**
	 * 关注商家
	 * @param $attention 被关注的商家id
	 */
	public function actionAttention() {
		if(!$id=$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录!' );
		
		if(!$attention=$this->getPost('merchant_id'))
			$this->jsonOutput(2,'错误的商家id');

		if(!$row=GlobalUser::CheckUser($attention))
			$this->jsonOutput(2,'错误的商家id');

		if(!$time=$this->getPost('apt'))
			$this->jsonOutput(2,'错误的操作');
		
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		if($row=$model->getAttention($id,$attention))
			$this->jsonOutput(2,'已关注该商家');
		
		if($row=$model->attention($id,$attention)){
			$this->jsonOutput ( 0, '已成功关注！');
		}else{
			$this->jsonOutput ( 2,'关注失败，请重试!');
		}
	}
	/**
	 * 获取所有的经营范围
	 */
	public function actionScopeBusiness() {
		if(!$time=$this->getPost('apt'))
			$this->jsonOutput(2,'错误的操作');

		if($row=GlobalMerchant::getScopeBusiness()){
			$data=array();
			foreach($row as $k=>$v){
				$arr=array(
					'id'	=>	$k,
					'title'	=>	$v
				);
				$data[]=$arr;
			}
			$this->jsonOutput ( 0, $data);
		}else{
			$this->jsonOutput(2,'获取失败，请重试');
		}
	}
	/**
	 * 更多商家列表
	 */
	public function actionMoreMerchant() {
		if(!$id=$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录!' );
		
		if(!$time=$this->getPost('apt'))
			$this->jsonOutput(2,'错误的操作');
		
		$keyword=$this->getPost('keyword');
		$scope=$this->getPost('scope');
		$brand=$this->getPost('brand');
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		$search=array(
			'keyword'	=>	$keyword,
			'scope'		=>	$scope,
			'brand'		=>	$brand
		);

		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		if($list=$model->getMerchantList($search , empty($pageNow) ? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize))
		{
			$data=array();
			foreach($list as $v){
				if($v['uid']){
					$data[]=$v;
				}
			}
			$this->jsonOutput(0,$data);
		}else{
			$this->jsonOutput(2,'没有符合条件的商家');
		}
	}
	/**
	 * 店铺详情
	 */
	public function actionMerchantInfo(){
		if(!$time=$this->getPost('apt'))
			$this->jsonOutput(2,'错误的操作');

		if(!$id=$this->getPost('id'))
			$this->jsonOutput(2,'错误的操作');

		if(!$row=GlobalUser::CheckUser($id))
			$this->jsonOutput(2,'错误的商家id');

		if(!$me=$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录!' );

		$Personal=ClassLoad::Only('Personal');/* @var $Personal Personal */
		$info = $Personal->getShop($id);
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		if($info = $Personal->getShop($id))
		{
			$info['attention_side']=$model->getAttention($me,$id);
			$this->jsonOutput(0,$info);
		}else{
			$this->jsonOutput(2,'未找到该信息!');
		}
	}
	/**
	 * 取消关注商家
	 */
	public function actionUnfollow(){
		if(!$id=$this->getPost('id'))
			$this->jsonOutput(2,'错误的操作');

		if(!$row=GlobalUser::CheckUser($id))
			$this->jsonOutput(2,'错误的商家id');

		if(!$me=$this->getMerchantID())
			$this->jsonOutput ( 2, '请登录!' );

		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		if($row=$model->unfollow($id , $me)){
			$this->jsonOutput(0,$row);
		}else{
			$this->jsonOutput(2,'操作失败，请重试');
		}
	}
}