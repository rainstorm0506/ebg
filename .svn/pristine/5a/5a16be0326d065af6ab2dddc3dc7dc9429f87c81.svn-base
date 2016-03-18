<?php
/**
 * 提醒控制器
 */
class YellowPageController extends ApiController
{
	/**
	 * 列表
	 */
	public function actionList() {
		if(!$apt=$this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');
		
		$keyword=$this->getPost('keyword');
		$scope=(int)$this->getPost('scope');
		$gather=(int)$this->getPost('gather');
		$model=ClassLoad::Only('ContactsBook');/* @var $model ContactsBook */
		$search=array(
				'keyword'	=>	$keyword,
				'scope'		=>	$scope,
				'gather'	=>	$gather
		);
		$pageNow = (int)$this->getPost('pageNow');
		$pageSize = (int)$this->getPost('pageSize');
		$model=ClassLoad::Only('YellowPage');/* @var $model YellowPage */
		if($row=$model->getList($search , empty($pageNow) ? 1 : $pageNow , empty($pageSize) ? 6 : $pageSize)){
			$this->jsonOutput(0,$row);
		}else {
			$this->jsonOutput(2,'没有你想要的结果!');
		}
	}
	/**
	 * 所有电脑城
	 */
	public function actionGather() {
		if(!$apt=$this->getPost('apt'))
			$this->jsonOutput(2,'参数有误');

		if(!$type=$this->getPost('type'))
			$this->jsonOutput(2,'参数有误');

		$data=array();
		if($type==1){
			$row=GlobalGather::getGatherFirst();
			foreach($row as $k=>$v){
				$dict=GlobalGather::getGatherArea($k);
				$arr=array(
					'id'	=>	$k,
					'title'	=>	$v,
					'dict'	=>	$dict
				);
				$data[]=$arr;
			}
		}
		if($type==2){
			$row=GlobalGather::getGatherTree();
			foreach($row as $k => $v){
				$dict=GlobalGather::getGatherArea($k);
				$arr=array(
					'id'	=>	$k,
					'title'	=>	$v['title'],
					'dict'	=>	$dict
				);
				if(!empty($v['child'])){
					foreach($v['child'] as $k1 => $v1){
						foreach($v1 as $k2 => $v2){
							if(empty($v2)){
								$id=$k2;
							}else{
								$a2=array(
									'id'	=>	$k2,
									'title'	=>$v2
								);
								$arr2[]=$a2;
							}
						}
						$a1=array(
							'id'	=>	$id,
							'title'	=>	$k1,
							'child'	=>	$arr2
						);
						$arr1[]=$a1;
					}
					$arr['child']= $arr1;
				}
				$data[]=$arr;
			}
		}
		if($data){
			$this->jsonOutput(0,$data);
		}else{
			$this->jsonOutput(2,'获取失败');
		}
	}
	/**
	 * 添加黄页
	 */
	public function actionCreate(){
		$form = ClassLoad::Only('YellowForm'); /* @var $form YellowForm */
		$form->title		=	trim($this->getPost('title'));
		$form->gather		=	(int)$this->getPost('gather');
		$form->address		=	trim($this->getPost('address'));
		$form->phone		=	trim($this->getPost('phone'));
		$form->is_phone		=	(int)$this->getPost('is_phone');
		$form->content		=	(int)$this->getPost('content');
		$form->landline		=	$this->getPost('landline');
		$form->is_landline	=	(int)$this->getPost('is_landline');
		$form->qq			=	$this->getPost('qq');
		$form->is_qq		=	(int)$this->getPost('is_qq');
		$form->weixin		=	$this->getPost('weixin');
		$form->is_weixin	=	$this->getPost('is_weixin');
		$form->scope		=	$this->getPost('scope');
		if($form->validate())
		{
			$model = ClassLoad::Only('YellowPage');/* @var $model YellowPage */
			if($row = $model->create($form))
			{
				$this->jsonOutput(0,'添加成功');
			}else{
				$this->jsonOutput(2,'添加失败');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
}