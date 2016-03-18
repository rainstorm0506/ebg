<?php
/**
 * 会员表 控制器
 * @author simon
 */
class UserController extends SController
{
	const PAGESIZE = 10;
	public function actionOn()
	{
		$this->checkUserPurview("u.m");
		$uModel = ClassLoad::Only("User");
		$uModel->setOn($this->getQuery("id"));
		$this->redirect("user/list");
	}
	
	public function actionOff()
	{
		$this->checkUserPurview("u.m");
		$uModel = ClassLoad::Only("User");
		$uModel->setOff($this->getQuery("id"));
		$this->redirect("user/list");
	}
	
	public function actionView()
	{
		$this->checkUserPurview("u.l");
		
		$uid = $this->getQuery("id");
		$uModel = ClassLoad::Only("User");
		$Recommform = ClassLoad::Only('RecommForm');
		$type = $this->getQuery("type");
		$condition = unserialize($this->getQuery("condition"));
		$this->render("view" , array (
			'id'=>$uid,
			"info" => $uModel->getInfo($uid), 
			"moneyLogs" => $uModel->getMoneyLogs($uid), 
			"follows" => $uModel->getFollows($uid), 
			"comments" => $uModel->getComments($uid), 
			"histories" => $uModel->getHistories($uid), 
			"orders" =>$uModel->getOrders($uid),
			"Recommform"=>$Recommform,
			"growLogs" => $uModel->getGrowLogs($uid), 
			"addresses" => $uModel->getUserAddresses($uid),
			"type" =>$type,
			"condition" => $condition,
		));
	}
	
	//列表
	public function actionList()
	{
		$this->checkUserPurview('u.l');
		$keyword = (string)$this->getQuery('keyword');
		
		$model = ClassLoad::Only('User'); /* @var $model User */
		
		// 设置分页
		$pageNow = Yii::app()->request->getParam('pagenum' , 0);
		$count = $model->getCount(1 , $keyword);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount($count);
		$offset = $pageNow > 1 ? ($pageNow - 1) * $page->pageSize : 0;
		
		$this->render('list' , array (
			'keyword' => $keyword, 
			'page' => $page, 
			'list' => $model->getList(1 , $keyword , $offset , $page->pageSize) 
		));
	}
	
	// 添加
	public function actionCreate()
	{
		$this->checkUserPurview('u.c');
		
		$form = ClassLoad::Only('UserForm'); /* @var $form UserForm */
		$form->user_type = 1;
		$this->exitAjaxPost($form);
		
		// $type=1;
		if($this->isPost() && !empty($_POST['UserForm']))
		{
			// $type=$_POST['UserForm']['user_type'];
			$form->attributes = $_POST['UserForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('User'); /* @var $model User */
				$model->create($_POST['UserForm']);
				GlobalUser::flush();
				$this->redirect(array (
					'user/list' 
				));
			}
		}
		
		// $form->user_type=$type;
		$this->render('append' , array (
			'form'		=> $form,
			'action'	=> 'create',
		));
	}
	
	// 编辑
	public function actionModify()
	{
		$this->checkUserPurview('u.m');
		
		$form = ClassLoad::Only('UserForm');/* @var $form UserForm */
		$form->user_type = 1;
		$this->exitAjaxPost($form);
		
		if(!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('User'); /* @var $model User */
		if(!$info = $model->getInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['UserForm']) ? $_POST['UserForm'] : array ();
		if(!empty($_POST['UserForm']) && $form->validate())
		{
			$model->modify($_POST['UserForm'] , $id);
			GlobalUser::flush();
			$this->redirect(array('user/list'));
		}
		
		$this->render('append' , array(
			'form'		=> $form,
			'info'		=> $info,
			'action'	=> 'modify',
		));
	}
	
	// 删除
	public function actionDelete()
	{
		$this->checkUserPurview('u.d');
		
		if(!$id = (int)$this->getQuery('id' , 0))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('User'); /* @var $model User */
		$model->deletes($id);
		GlobalUser::flush();
		$this->redirect(array (
			'user/list' 
		));
	}
	/**
	 * 推荐用户提成统计数据导出excl格式文件
	 * @author gl
	 * */
	public function actionUserReExcel()
	{
		$this->checkUserPurview('g.l');

		$app 	= Yii::app()->request;
		$uid 	= $app->getParam('id');
		$start	= $app->getParam('start');
		$end	= $app->getParam('end');
		$type	= $app->getParam('type');
		$keys	= $app->getParam('keys');
		$start  = !empty($start) ? strtotime($start) :'';
		$end 	= !empty($end) ? strtotime($end) :'';
		$keys 	= !empty($keys) ? $keys :'';

		if(empty($uid))
		{
			$this->error('ID不能为空');
		}
		if($uid <=0)
		{
			$this->error('错误的ID');
		}

		$uModel = ClassLoad::Only("User");

		$uinfo  = $uModel->getInfo($uid);
		$list	= $uModel->getOutUserOrderList($uid,$start,$end,$type,$keys);
		$data['user']['name']	= !empty($uinfo['nickname'])?$uinfo['nickname'] : $uinfo['realname'];
		$data['user']['name']	= !empty($data['user']['name'])?$data['user']['name']:$uinfo['phone'];
		$data['user']['nums']	= count($list);
		$data['list']			= $list;

		if(empty($list))
		{
			echo '<script>alert("数据为空");history.go(-1);</script>';
			exit;
		}
		//导出excel表
		//Yii::app()->PHPexcelout->OutDzExcel($data,$start,$end);
		PHPexcelout::OutDzExcel($data,$start,$end);
	}
	/**
	 * 被推荐用户提成详细列表
	 * @author gl
	 * */
	public function actionReUserOrder()
	{
		$this->checkUserPurview('g.l');

		$app 	= Yii::app()->request;
		$uid 	= $app->getParam('id');
		$status	= $app->getParam('status');
		$start	= $app->getParam('start');
		$end	= $app->getParam('end');
		$type	= $app->getParam('type');
		$keys	= $app->getParam('keys');
		$start  = !empty($start) ? strtotime($start) :'';
		$end 	= !empty($end) ? strtotime($end) :'';
		$keys 	= !empty($keys) ? $keys :'';

		if(empty($uid))
		{
			$this->error('ID不能为空');
		}
		if($uid <=0)
		{
			$this->error('错误的ID');
		}
		//实例化数据模型
		$uModel = ClassLoad::Only("User");
		$uinfo  = $uModel->getInfo($uid);
		$list	= $uModel->getReUserOrderList($uid,$status,$start,$end,$type,$keys);

		$data['username']	= !empty($uinfo['nickname'])?$uinfo['nickname'] : $uinfo['realname'];
		$data['username']   = !empty($data['username'])?$data['username']:$uinfo['phone'];
		$data['list']		= $list;

		if(empty($list))
		{
			echo '<script>alert("数据为空");history.go(-1);</script>';
			exit;
		}
		//导出excel表
		PHPexcelout::OutReInfoExcel($data,$start,$end);
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
			$recommended[$key]['create_time'] = date('Y-m-d h:i:s',$val['create_time']);	
		}
		$array = array('total'=>$count,'page'=>$pageNow,'totalPage'=>$totalPage,'list'=>$recommended);
		echo json_encode($array);
	}
	
	/**
	 * 2016-3-10
	 * yp  推荐奖金的列表页
	 */
	public function actionRecommListPage()
	{
		$this->checkUserPurview("u.l");
		$uModel = ClassLoad::Only("User");
		$pageNow = Yii::app()->request->getPost('pageNum',0);
		$uid = Yii::app()->request->getPost('id');
		$data = urldecode(Yii::app()->request->getPost('data'));
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
					'id'=>$id,
					"info" => $uModel->getInfo($uid),
					'condition'=>$condition,
				)
		);
	}
}
?>