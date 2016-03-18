<?php
/**
 * 推荐奖金
 * yp   2016-3-14
 */

class CommissionController extends EnterpriseController{
	const PAGESIZE = 10;
	public $showLeftNav = true;
	public $leftNavType = '';
	//列表展示页
	
	public function actionIndex(){
		$this->leftNavType = 'commission';
		$commentList = array();
		$model = ClassLoad::Only('Commission'); /* @var $model Comment */
		$form = ClassLoad::Only('RecommForm');
		$uid = $this->getUid();
		$formError = array();
		$array = array();
		if(isset($_GET['RecommForm']) && !empty($_GET['RecommForm'])){
			$form->attributes = isset($_GET['RecommForm']) ? $_GET['RecommForm'] : array ();
			if($form->validate()){
				foreach ($_GET['RecommForm'] as $key => $val){
					if(!empty($val)){
						$array[$key] = $val;
					}
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		$pageNow = $this->getParam('pagenum',0);
		$count = $model->getRecCount($uid,$array);
		$page = new CPagination();
		$page->pageSize = self::PAGESIZE;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询未评论商品列表并 渲染试图
		$list['list']  = $model->getRecommendedList($uid,$array,$offset , $page->pageSize);
		$list['page'] = $page;
		$this->render('index',array('list'=>$list,'count'=>$count,'form'=>$form,'formError'=>$formError,'array'=>$array));
	}
	
	//推荐人的详细页面
	
	public function actionDetail(){
		$this->leftNavType = 'commission';
		$commentList = array();
		$model = ClassLoad::Only('Commission'); /* @var $model Comment */
		$form = ClassLoad::Only('RecommForm');
		$uid = $this->getUid();//推荐用户的id
		$id = $this->getQuery('id');
		$this->exitAjaxPost($form);
		$formError = array();
		$array = array();
		if(isset($_GET['RecommForm']) && !empty($_GET['RecommForm'])){
			$form->attributes = isset($_GET['RecommForm']) ? $_GET['RecommForm'] : array ();
			if($form->validate()){
				foreach ($_GET['RecommForm'] as $key => $val){
					if(!empty($val)){
						$array[$key] = $val;
					}
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		$pageNow = $this->getParam('pagenum',0);
		$count = $model->getOrderRecCount($id,$array);
		$page = new CPagination();
		$page->pageSize = self::PAGESIZE;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询未评论商品列表并 渲染试图
		$list['list']  = $model->getOrderRecommended($id,$array,$offset,$page->pageSize);
		$list['page'] = $page;
		$this->render('detail',array('id'=>$id,'list'=>$list,'count'=>$count,'form'=>$form,'formError'=>$formError,'array'=>$array));
	}
}