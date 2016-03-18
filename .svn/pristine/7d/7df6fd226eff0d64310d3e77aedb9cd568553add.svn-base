<?php
/**
 * 订单管理 - 控制器
 * @author jeson.Q
 */
class OrderController extends SController
{
	
	// 订单列表
	public function actionList()
	{
		// 设置访问权限
		$this->checkUserPurview('order:l');
		$keyword = $this->getParam('keyword');
		
		$searchPost = isset($_POST['OrderForm']) ? $_POST['OrderForm'] : (isset($_GET['status']) ? array('status'=>$_GET['status']) : '');
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost);
		$page = new CPagination();
		$page->pageSize = 20;
		$page->pageVar = 'pagenum';
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		// 查询列表并 渲染试图
		$order = $model->getList($searchPost , $offset , $page->pageSize);
		$this->render('order_list' , array(
			'order' => $order ,
			'page' => $page ,
			'button_search' => isset($_GET['status']) ? $_GET['status'] : '',
			'is_search' => $searchPost && !isset($_GET['status']) ? ($searchPost['is_self'] || $searchPost['starttime'] || $searchPost['endtime'] || ($searchPost['keyword'] && $searchPost['keyword'] != '支持搜索订单号、商家名称、收货人手机号码') ? 1 : 0) : 0,
			'searchPost' => $searchPost && !isset($_GET['status']) ? $searchPost : array('is_self'=>'','status'=>'','starttime'=>'','endtime'=>'','keyword'=>'')
		));
	}
	
	// 订单列表
	public function actionChildOrderList()
	{
		// 设置访问权限
		$this->checkUserPurview('order:l');
		$order = $orderInfo = array();
		$order_sn = $this->getParam('order_sn');
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		if($order_sn){
			// 查询列表并 渲染试图
			$order = $model->getChildrenList($order_sn);
		}
		$orderInfo = $model->getActiveInfo($order_sn);
		$this->render('order_split' , array(
			'order' => $order,
			'order_sn' => $order_sn,
			'orderInfo' => $orderInfo,
			'searchPost' => array('is_self'=>'','status'=>'','starttime'=>'','endtime'=>'','keyword'=>'')
		));
	}
	
	// 编辑 订单
	public function actionEdit()
	{
		// 设置访问权限
		//$this->checkUserPurview('order:e');
		// 加载类 、检查数据
		$form = ClassLoad::Only('OrderForm'); /* @var $form OrderForm */
		$this->exitAjaxPost($form);
		
		if (!$order_sn = (string)$this->getQuery('order_sn'))
			$this->error('错误的分类ID');
		
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		if (!$info = $model->getActiveInfo($order_sn))
			$this->error('栏位不存在!');

		// 查询列表并 渲染试图
		$this->render('detail' , array(
			'form' => $form ,
			'info' => $info,
			'searchPost' => array('is_self'=>'','status'=>'','starttime'=>'','endtime'=>'','keyword'=>'')
		));
	}
	
	//跳转到 相关操作订单页面
	public function actionShowOption()
	{
		$logData = $orderData = $local = $commentList = array(); $linkpage = $order_sn = $express_name = '';
		if($_GET['type'])
		{
			switch ($_GET['type'])
			{
				case 'system_memo':			$linkpage = 'system_memo';		break;
				case 'prepare_goods':		$linkpage = 'prepare_goods';	break;
				case 'finish_prepare':		$linkpage = 'finish_prepare';	break;
				case 'send':				$linkpage = 'edit_send';		break;
				case 'pay':					$linkpage = 'edit_pay';			break;
				case 'edit_money':			$linkpage = 'edit_money';		break;
				case 'abolish':				$linkpage = 'edit_abolish';		break;
				case 'option_abolish':		$linkpage = 'option_abolish';	break;
				case 'show_abolish':		$linkpage = 'show_abolish';		break;
				case 'user_receive_goods':	$linkpage = 'user_receive_goods';		break;
				case 'user_goods_comment':	$linkpage = 'user_goods_comment';		break;
				case 'back_goods':			$linkpage = 'back_goods';		break;
				case 'back_money':			$linkpage = 'back_money';		break;
				default:	break;
			}
		}
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		$goods_id = isset($_GET['goods_id']) ? $_GET['goods_id'] : '';
		//查询当前配送方式
		$order = ClassLoad::Only('Order');/* @var $order Order */
		$express_name = $order->getExpressName($order_sn);
		$local = array(
			'express' => $order->getExpressList(),
			'order_sn'=> $order_sn,
			'express_name'=> $express_name['express_name'],
			'cancel_title' => $express_name['user_title'],
			'order_money' => $express_name['order_money'],
			'pay_type' => $express_name['pay_type'],
			'system_remark' => $express_name['system_remark'],
			'goods_id' => $goods_id
		);
		if($linkpage == 'user_goods_comment'){
			//查询订单商品评论列表
			$commentInfo = $order->getOrderComment($goods_id,$order_sn);
			$local['commentInfo'] = $commentInfo;
		}
		// 查询列表并 渲染试图
		$this->render($linkpage, $local);
	}
	
	//操作订单 ---发货 
	public function actionOptionOrder()
	{
		$info = '';
		if($_POST)
		{
			$order = ClassLoad::Only('Order');/* @var $order Order */
			$info = $order->setOrderInfo($_POST);
			echo $info;
		}else{
			echo 0;
		}
	}
	
	//验证密码
	public function actionCheckPassword()
	{
		$currentUser = array(); $password = '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		if($password){
			//获取当前登录用户信息
			$currentUser = $this->getUser();
			$user = ClassLoad::Only('Governor');/* @var $user Governor */
			$info = $user->getUserInfo($currentUser['account']);
			//判断是否是正确的密码
			if(!GlobalUser::validatePassword($password , $info['password']))
				echo 0;
			else
				echo 1;
		}else 
			echo 0;
	}
	
}
