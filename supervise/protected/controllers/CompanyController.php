<?php
/**
 * 企业会员控制器
 *
 * @author 夏勇高
 */
class CompanyController extends SController
{
	const PAGESIZE = 10;
	/**
	 * 获得地区数据
	 *
	 * @param		GET		int		dictOneId		第一层ID
	 * @param		GET		int		dictTwoId		第二层ID
	 * @param		GET		int		dictThreeId		第三层ID
	 */
	public function actionDictChild()
	{
		$oneId = (int)$this->getQuery('dictOne');
		$twoId = (int)$this->getQuery('dictTwo');
		$threeId = (int)$this->getQuery('dictThree');
		
		$this->jsonOutput(0 , GlobalDict::getUnidList($oneId , $twoId , $threeId));
	}
	
	public function actionVerify()
	{
		$this->checkUserPurview("c.m");
		$cid = $this->getQuery("id");
		$uModel = ClassLoad::Only("User");
		$cModel = ClassLoad::Only("Company");
		
		if(isset($_POST['VerifyForm']))
		{
			$state = $_POST['VerifyForm']['state'];
			$state == 'Y' ? $state = 610 : $state = 614;
			$remark = $_POST['VerifyForm']['remark'];
			
			$uModel->reviewUser($cid , $state , $remark);
			$this->redirect(array (
				'company/list' 
			));
		}
		
		$this->render("verify" , array (
			"info" => $uModel->getInfo($cid), 
			"com" => $cModel->getInfo($cid) 
		));
	}
	
	public function actionView()
	{
		$this->checkUserPurview("c.l");
		$uid = $this->getQuery("id");
		$uModel = ClassLoad::Only("User");
		$cModel = ClassLoad::Only("Company");
		$Recommform = ClassLoad::Only('RecommForm');
		$type = $this->getQuery("type");
		$condition = unserialize($this->getQuery("condition"));
		$this->render("view" , array (
			"info" => $uModel->getInfo($uid), 
			"com" => $cModel->getInfo($uid), 
			"Recommform" =>$Recommform,
			"follows" => $uModel->getFollows($uid), 
			"comments" => $uModel->getComments($uid), 
			"histories" => $uModel->getHistories($uid), 
			"orders" => $uModel->getOrders($uid), 
			"growLogs" => $uModel->getGrowLogs($uid), 
			"purchases" => $cModel->getPurchases($uid), 
			"addresses" => $uModel->getUserAddresses($uid),
			"type" =>$type,
			"condition" => $condition,
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview("c.c");
		
		$form = ClassLoad::Only('CompanyForm'); /* @var $form CompanyForm */
		$form->user_type = 2;
		$this->exitAjaxPost($form);
		
		if(isset($_POST['CompanyForm']))
		{
			$form->attributes = $_POST['CompanyForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('Company'); /* @var $model Company */
				$model->create($_POST['CompanyForm']);
				GlobalUser::flush();
				$this->redirect(array (
					'company/list' 
				));
			}
		}
		
		$companyNumber = array_values(GlobalStatus::getStatusColumn(60 , 'user_title'));
		$companyNumber = CMap::mergeArray(array(''=>'请选择') , array_combine($companyNumber , $companyNumber));
		
		$companyType = array_values(GlobalStatus::getStatusColumn(61 , 'user_title'));
		$companyType = CMap::mergeArray(array('' => '- 请选择 -' ) , array_combine($companyType , $companyType));
		
		$this->render('append' , array (
			'form'			=> $form, 
			'companyType'	=> $companyType,
			'companyNumber'	=> $companyNumber,
			'action'		=> 'create',
		));
	}
	
	//列表
	public function actionList()
	{
		$this->checkUserPurview('c.l');
		
		$keyword = (string)$this->getQuery('keyword');
		
		$model = ClassLoad::Only('Company'); /* @var $model Company */
		$page = ClassLoad::Only('CPagination'); /* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->countCompany($keyword));
		$page->pageSize = 20;
		
		$this->render('list' , array (
			'page' => $page, 
			'list' => $model->getCompanyList($keyword , $page->getOffset() , $page->getLimit() , $page->getItemCount()) 
		));
	}
	
	//修改
	public function actionModify()
	{
		$this->checkUserPurview('c.m');
		
		$form = ClassLoad::Only('CompanyForm'); /* @var $form CompanyForm */
		$form->user_type = 2;
		$this->exitAjaxPost($form);
		
		if(!$id = (int)$this->getQuery('id' , 0))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Company'); /* @var $model Company */
		$uModel = ClassLoad::Only('User'); /* @var $model User */
		
		$form->attributes = isset($_POST['CompanyForm']) ? $_POST['CompanyForm'] : array ();
		if(isset($_POST['CompanyForm']) && $form->validate())
		{
			$model->modify($_POST['CompanyForm'] , $id);
			GlobalUser::flush();
			$this->redirect('company/list');
		}
		
		$companyNumber = array_values(GlobalStatus::getStatusColumn(60 , 'user_title'));
		$companyNumber = CMap::mergeArray(array(''=>'请选择') , array_combine($companyNumber , $companyNumber));
		
		$companyType = array_values(GlobalStatus::getStatusColumn(61 , 'user_title'));
		$companyType = CMap::mergeArray(array('' => '- 请选择 -') , array_combine($companyType , $companyType));
		
		$this->render('append' , array (
			'form'			=> $form,
			'info'			=> $model->getInfo($id),
			'user'			=> $uModel->getInfo($id),
			'companyType'	=> $companyType,
			'companyNumber'	=> $companyNumber,
			'action'		=> 'modify',
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview("c.d");
		
		if(!$id = (int)Yii::app()->getRequest()->getQuery('id' , 0))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Company'); /* @var $model Company */
		$model->clear($id);
		GlobalUser::flush();
		
		$this->redirect(array (
			'company/list' 
		));
	}
	
	/**
	 * 根据 关键词 搜索 企业
	 *
	 * @author 涂先锋
	 */
	public function actionSearchKeyword()
	{
		if(!$keyword = (string)$this->getQuery('keyword'))
			$this->jsonOutput(1 , '请输入搜索关键词');
		
		$model = ClassLoad::Only('Company'); /* @var $model Company */
		$this->jsonOutput(0 , $model->searchKeyword($keyword , 11));
	}
	
	public function actionRecommPage()
	{
		$this->checkUserPurview("u.l");
		$uModel = ClassLoad::Only("User");
		$pageNow = Yii::app()->request->getPost('pageNum',0);
		$uid = Yii::app()->request->getPost('id');
		$data = urldecode(Yii::app()->request->getPost('data'));
		//$data = urldecode('start_time=2016-03-01+00%3A00%3A00&end_time=2016-03-11+00%3A00%3A00&RecommForm%5Bkey%5D=phone&keyword=152');
		$condition = array();
		if(!empty($data)){
			$array = explode('&',$data);
			foreach ($array as $val){
				$brray = explode('=', $val);
				if(!empty($brray[1])){
					if($brray[0] == 'RecommForm[key]'){
						$crr[0] = $brray[1];
					}else if ($brray[0] == 'keyword'){
						$crr[1] = $brray[1];
					}else if($brray[0] == 'RecommForm[status]'){
						$crr[2] = $brray[1];
					}else{
						$condition[$brray[0]] = $brray[1];
					}
				}
			}
			if(!empty($crr[0]) && !empty($crr[1])){
				$condition[$crr[0]] = $crr[1];
			}
			if(!empty($crr[2])){
				$condition['status'] = $crr[2];
			}
		}
		if(!empty($condition['start_time']) && !empty($condition['end_time'])){
			if(strtotime($condition['start_time']) > strtotime($condition['start_time'])){
				$message = array('code'=>'error','message'=>'开始时间必须大于结束时间');
			}
		}
		if(!empty($condition['start_time'])){
			if(strtotime($condition['start_time']) > time()){
				$message = array('code'=>'error','message'=>'开始时间不能大于现在时间');
			}
		}
		if(!empty($condition['end_time'])){
			if(strtotime($condition['end_time']) > time()){
				$message = array('code'=>'error','message'=>'结束时间不能大于现在时间');
			}
		}
			
		if(!empty($message) && count($message) > 0){
			echo json_encode($message);die;
		}
		$pageSize = self::PAGESIZE;
		$offset = $pageNow > 1 ? ($pageNow - 1) * $pageSize : 0;
		$count = $uModel->getOrderRecCount($uid,$condition);
		$totalPage = ceil($count/self::PAGESIZE);
		$recommended = $uModel->getOrderRecommended($uid,$condition,$offset,$pageSize);
		foreach ($recommended as $key=>$val){
			$recommended[$key]['reg_time'] = date('Y-m-d h:i:s',$val['create_time']);
		}
		$array = array('total'=>$count,'page'=>$pageNow,'totalPage'=>$totalPage,'list'=>$recommended);
		echo json_encode($array);
	}
	
	/**
	 * 2016-3-10
	 * yp  推荐奖金的列表页    16:20
	 */
	public function actionRecommListPage()
	{
		$this->checkUserPurview("u.l");
		$uModel = ClassLoad::Only("User");
		$pageNow = Yii::app()->request->getPost('pageNum',0);
		$uid = Yii::app()->request->getPost('id');
		$data = urldecode(Yii::app()->request->getPost('data'));
		//$data = urldecode('start_time=2016-03-01+00%3A00%3A00&end_time=2016-03-11+00%3A00%3A00&RecommForm%5Bkey%5D=phone&keyword=152');
		$condition = array();
		if(!empty($data)){
			$array = explode('&',$data);
		foreach ($array as $val){
				$brray = explode('=', $val);
				if(!empty($brray[1])){
					if($brray[0] == 'RecommForm[key]'){
						$crr[0] = $brray[1];
					}else if ($brray[0] == 'keyword'){
						$crr[1] = $brray[1];
					}else{
						$condition[$brray[0]] = $brray[1];
					}					
				}
			}
			if(!empty($crr[0]) && !empty($crr[1])){
				$condition[$crr[0]] = $crr[1];
			}
		}
		if(!empty($condition['start_time']) && !empty($condition['end_time'])){
			if(strtotime($condition['start_time']) > strtotime($condition['start_time'])){
				$message = array('code'=>'error','message'=>'开始时间必须大于结束时间');
			}
		}
		if(!empty($condition['start_time'])){
			if(strtotime($condition['start_time']) > time()){
				$message = array('code'=>'error','message'=>'开始时间不能大于现在时间');
			}
		}
		if(!empty($condition['end_time'])){
			if(strtotime($condition['end_time']) > time()){
				$message = array('code'=>'error','message'=>'结束时间不能大于现在时间');
			}
		}
			
		if(!empty($message) && count($message) > 0){
			echo json_encode($message);die;
		}
		$pageSize = self::PAGESIZE;
		$offset = $pageNow > 0 ? ($pageNow - 1) * $pageSize : 0;
		$count = $uModel->getRecCount($uid,$condition);
		$totalPage = ceil($count/self::PAGESIZE);
		$recommended = $uModel->getRecommendedList($uid,$condition,$offset,$pageSize);
	
		foreach ($recommended as $key=>$val){
			$recommended[$key]['reg_time'] = date('Y-m-d h:i:s',$val['reg_time']);
			$recommended[$key]['exp'] = GlobalUser::getUserLayerName($val['exp'] , 1);
		}
		$array = array('total'=>$count,'page'=>$pageNow,'totalPage'=>$totalPage,'list'=>$recommended);
		echo json_encode($array);
	}
	
	/**
	 * 2016-3-10
	 *
	 * yp  个人推荐的详情页
	 *
	 */
	
	public function  actionDetail(){
		$this->checkUserPurview("u.l");
		$uModel = ClassLoad::Only("User");
		$cModel = ClassLoad::Only("Company");
		$pageNow = Yii::app()->request->getPost('pageNum',0);
		$uid = Yii::app()->request->getQuery('re_uid');
		$id = Yii::app()->request->getQuery('id');
		$Recommform = ClassLoad::Only('RecommForm');
		$data = urldecode(Yii::app()->request->getQuery('data'));
		$page = urldecode(Yii::app()->request->getQuery('page'));
		$start_time = Yii::app()->request->getQuery('start');
		$end_time = Yii::app()->request->getQuery('end');
		$key = Yii::app()->request->getQuery('key');
		$keyword = Yii::app()->request->getQuery('keyword');
		$condition = array();
		if(!empty($data)){
			$array = explode('&',$data);
			foreach ($array as $val){
				$brray = explode('=', $val);
				if(!empty($brray[1])){
					if($brray[0] == 'RecommForm[key]'){
						$crr[0] = $brray[1];
					}else if ($brray[0] == 'keyword'){
						$crr[1] = $brray[1];
					}else{
						$condition[$brray[0]] = $brray[1];
					}
						
				}
			}
			if(!empty($crr[0])){
				$condition[$crr[0]] = $crr[1];
			}
		}
		
		if(!empty($page)){
			$condition['page'] = $page;
		}
		if(!empty($start_time)){
			$condition['start_time'] = $start_time;
		}
		if(!empty($end_time)){
			$condition['end_time'] = $end_time;
		}
		if(!empty($key)){
			$condition['key'] = $key;
		}
		if(!empty($keyword)){
			$condition['keyword'] = $keyword;
		}
		$condition = serialize($condition);
		$this->render('details',
				array(
					'Recommform'=>$Recommform,
					'id'=>$uid,
					"info" => $uModel->getInfo($uid),
					"com" => $cModel->getInfo($uid),
					'condition'=>$condition,
				)
		);
	}
}
?>