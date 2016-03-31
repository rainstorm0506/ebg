<?php
/**
 * 活动管理 - 控制器
 * @author GL
 */
class ActiveController extends SController
{
	
	public function actionIndex()
	{
		$this->checkUserPurview('act.in');
		$app = Yii::app()->request;

		$form  = $app->getParam('ActiveForm');
		$start = $app->getParam('start');
		$end   = $app->getParam('end');
		$start = !empty($start)?strtotime($start):'';
		$end   = !empty($end)?strtotime($end):'';

		$actmodel   =  ClassLoad::Only("Active");
		$activeform = ClassLoad::Only('ActiveForm');

		$pages = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$pages->pageVar = 'p';
		$pages->setItemCount($actmodel->getCountItem($form,$start,$end));
		$pages->pageSize = 20;

		$list	    = $actmodel->getActiveList($form,$start,$end,$pages->getOffset() , $pages->getLimit() , $pages->getItemCount());
		$data = array(
			'activeform'=>$activeform,
			'page'		=>$pages,
			'list'		=>$list,
			'form'		=>$form,
			'start'		=>$start,
			'end'		=>$end
		);
		$this->render('list',$data);
	}
	/**
	 * 创建活动
	 * */
	public function actionAddAct()
	{
		$this->checkUserPurview('act.add');
		$app   = Yii::app()->request;
		//$type  = $this->getQuery('type');
		$activeform = ClassLoad::Only('ActiveForm');
		$activemodel = ClassLoad::Only('Active');
		if($this->isPost())
		{
			$ActiveForm  =  $app->getParam('ActiveForm');
			$userlimitce = $app->getParam('userlimitce');
			$userdaylimit = $app->getParam('userdaylimit');
			//$buynummin = $app->getParam('buynummin');
			//$buynummax = $app->getParam('buynummax');
			//$promotiontype = $app->getParam('promotiontype');
			$remark		= $app->getParam('remark');
			$isxg		= $app->getParam('isxg');

			if(empty($ActiveForm['title']))
			{
				$this->error('名称不能为空');
			}
			if(empty($ActiveForm['type']))
			{
				$this->error('类型不能为空');
			}
			if(empty($userlimitce))
			{
				$this->error('总限购次数');
			}
			if(empty($userdaylimit))
			{
				$this->error('每天限购次数');
			}
			//商品信息
			$gid		= $app->getParam('gids');
			$price		= $app->getParam('price');
			$nums		= $app->getParam('nums');
			$onece		= $app->getParam('onece');
			//时间信息
			$start		= $app->getParam('start');
			$end		= $app->getParam('end');
			//分值
			$title		= $ActiveForm['title'];
			$type 		= $ActiveForm['type'];
			$userexp 	=  $ActiveForm['userexp'];
			$companyexp =  $ActiveForm['companyexp'];
			$mgh = $zkl = '';
			if($type == 1)
			{
				$mgh = $app->getParam('mgh');
			}
			if($type == 2)
			{
				$zkl = $app->getParam('zkl');
			}
			$activedata = array(
				'title'=>$title,
				'type'=>$type,
				'userexp'=>$userexp,
				'companyexp'=>$companyexp,
				'userlimitce'=> $userlimitce,
				'userdaylimit'=> $userdaylimit,
				'promotion'=>!empty($zkl)&&isset($zkl)?$zkl:'',
				'isxg'=>!empty($isxg)?$isxg:1,
				'remark'=>$remark,
			);
			$aid = $activemodel->add($activedata);
			if($aid)
			{
				//处理商品
				if(!empty($gid))
				{
					$activemodel->addactgoods($aid,$gid,$price,$nums,$onece);
				}
				//处理属性
				if(!empty($start) && !empty($end))
				{
					$attr = array();
					if($mgh)
					{
						$attr['name'] = 'timepoint';
						$attr['val']  = $mgh;
					}
					$activemodel->addactattr($aid,$start,$end,$attr);
				}
				$this->redirect(array('index'));
			}
		}
		else
		{
			$userlayer 		= ClassLoad::Only("UserLayerSet");
			$usertplist 	= $userlayer->getList(1);
			$companytplist  = $userlayer->getList(2);
			$classone		= $activemodel->getGoodsClass(0);
				$usertp = $companytp = array();
				foreach($usertplist as $key=>$val)
				{
					$usertp[$val['id']] = $val['name'];
				}
				foreach($companytplist as $key=>$val)
				{
					$companytp[$val['id']] = $val['name'];
				}
				$data = array(
						'activeform'=>$activeform,
						'usertype' => $usertp,
						'companytype' => $companytp,
						'classone'	=>$classone,
				);
				$this->render('mgadd',$data);
		}

	}
	/**
	 * 修改活动信息
	 * */
	public function actionEditAct()
	{
		$app   			= Yii::app()->request;
		$aid			= $this->getQuery('id');
		$activeform 	= ClassLoad::Only('ActiveForm');
		$activemodel 	= ClassLoad::Only('Active');
		$userlayer 		= ClassLoad::Only("UserLayerSet");
		if($this->isPost())
		{
			$ActiveForm  =  $app->getParam('ActiveForm');
			$userlimitce = $app->getParam('userlimitce');
			$userdaylimit = $app->getParam('userdaylimit');
			$remark		= $app->getParam('remark');
			$isxg		= $app->getParam('isxg');

			if(empty($ActiveForm['title']))
			{
				$this->error('名称不能为空');
			}
			if(empty($ActiveForm['type']))
			{
				$this->error('类型不能为空');
			}
			if(empty($userlimitce))
			{
				$this->error('总限购次数');
			}
			if(empty($userdaylimit))
			{
				$this->error('每天限购次数');
			}
			//商品信息
			$gid		= $app->getParam('gids');
			$price		= $app->getParam('price');
			$nums		= $app->getParam('nums');
			$onece		= $app->getParam('onece');
			//时间信息
			$start		= $app->getParam('start');
			$end		= $app->getParam('end');
			//分值
			$title		= $ActiveForm['title'];
			$type 		= $ActiveForm['type'];
			$userexp 	=  $ActiveForm['userexp'];
			$companyexp =  $ActiveForm['companyexp'];
			$mgh = $zkl = '';
			if($type == 1)
			{
				$mgh = $app->getParam('mgh');
			}
			if($type == 2)
			{
				$zkl = $app->getParam('zkl');
			}
			$activedata = array(
					'title'=>$title,
					'type'=>$type,
					'userexp'=>$userexp,
					'companyexp'=>$companyexp,
					'userlimitce'=> $userlimitce,
					'userdaylimit'=> $userdaylimit,
					'promotion'=>!empty($zkl)&&isset($zkl)?$zkl:'',
					'isxg'=>!empty($isxg)?$isxg:1,
					'remark'=>$remark,
			);
			$activemodel->updateact($activedata,$aid);
			if($aid)
			{
				//处理商品
				if(!empty($gid))
				{
					$activemodel->updateactgoods($aid,$gid,$price,$nums,$onece);
				}
				//处理属性
				if(!empty($start) && !empty($end))
				{
					$attr = array();
					if($mgh)
					{
						$attr['name'] = 'timepoint';
						$attr['val']  = $mgh;
					}
					$activemodel->updateactattr($aid,$start,$end,$attr);
				}
			}
			$this->redirect(array('index'));
		}

		$usertplist 	= $userlayer->getList(1);
		$companytplist  = $userlayer->getList(2);
		$classone		= $activemodel->getGoodsClass(0);
		$usertp = $companytp = array();
		foreach($usertplist as $key=>$val)
		{
			$usertp[$val['id']] = $val['name'];
		}
		foreach($companytplist as $key=>$val)
		{
			$companytp[$val['id']] = $val['name'];
		}
		$info = $activemodel->getActiveInfo($aid);
		$data = array(
				'activeform'=>$activeform,
				'usertype' => $usertp,
				'companytype' => $companytp,
				'classone'	=>$classone,
				'info'		=> $info,
		);

		$this->render('edit',$data);
	}
	/**
	 * 能参与活动商品的列表
	 * */
	public function actionJoinGoods()
	{
		$data  = urldecode($this->getParam('data'));
		$dataarray = array();
		if(!empty($data))
		{
			$one = explode('&',$data);
			foreach($one as $key=>$val)
			{
				$two = explode('=',$val);
				$dataarray[$two[0]] = $two[1];
			}
		}
		$page = $this->getParam('pageNum');
		$page = !empty($page)?$page:1;
		$pagesize = !empty($pagesize)?$pagesize:10;
		$actgoods = ClassLoad::Only('ActGoods');/* @var $actgoods ActGoods */
		$res 	=  $actgoods->getActGoodsList($dataarray,$page,$pagesize);
		echo json_encode($res);
	}
	/**
	 * 选中的商品信息
	 * */
	public function actionCheckedGoods()
	{
		$actgoods = ClassLoad::Only('ActGoods');/* @var $actgoods ActGoods */
		$ids = $this->getParam('ids');
		$list = $actgoods->getCheckedGoods($ids);
		echo json_encode($list);
	}
	/**
	 * 商品分类联动菜单
	 * */
	public function actionGetClassSelect()
	{
		$classid 	 = $this->getParam('classid');
		$activemodel = ClassLoad::Only('Active');

		if($classid)
		{
			$list		 = $activemodel->getGoodsClass($classid);
			echo CHtml::tag('option', array('value'=>''),CHtml::encode('-----'),true);
			foreach($list as $value=>$name)
			{
				echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
			}
		}
		else
		{
			echo CHtml::tag('option', array('value'=>''),CHtml::encode('-----'),true);;
		}

	}
	/**
	 * 删除活动
	 * */
	function actionDeleteAct()
	{
		$id = $this->getQuery('id');
		$activemodel = ClassLoad::Only('Active');
		$activemodel->delAct($id);

		$this->redirect(array('index'));
	}
}

