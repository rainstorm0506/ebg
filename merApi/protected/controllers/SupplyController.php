<?php
class SupplyController extends ApiController
{
	/**
	 * 供应列表
	 * 
	 * @param		int		brandID		品牌ID
	 * @param		int		classID		分类ID
	 * @param		int		page		页码
	 * @param		int		order		排序
	 */
	public function actionGetSupply()
	{
		$form = ClassLoad::Only('SupplyGetSupplyForm'); /* @var $form SupplyGetSupplyForm */
		$form->attributes = $_POST;
		
		if ($form->validate())
		{
			$_p = (int)$this->getPost ('page');
			$model = ClassLoad::Only ( 'Supply' ); /* @var $model Supply */
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getSuppyCount($form));
		
			if ($list = $model->getSupplyList($form, $page, $_p))
			{
				$this->jsonOutput ( 0, $list );
			} else
			{
				$this->jsonOutput ( 2, '没有数据！' );
			}
		}
		$this->jsonOutput ( 1, $this->getFormError ( $form ) );
	}
	
	public function actionGetSup()
	{
		$form = ClassLoad::Only ( 'SupplyGetSupplyForm' ); /* @var $form SupplyGetSupplyForm */
		$form->attributes = empty ( $_POST ) ? array () : $_POST;
	}
	
	/**
	 * 供应模块 - 我的发布列表
	 *
	 * @param	int		apt			APP抛数据的时间
	 * @param	int		page		页码
	 */
	public function actionGetIssue()
	{	
		$form = ClassLoad::Only ('SupplyForm'); /* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		$_p = $form->page = (int)$this->getPost('page');
		
		if($form->validateBrand())
		{
			$model = ClassLoad::Only('Supply'); /* @var $model Supply */
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getPublishCount());  
                        
			if ($info = $model->getIssue($form,$page,$_p))
			{
				$this->jsonOutput ( 0, $info );
			} else
			{
				$this->jsonOutput ( 2, '未找到供需信息！' );
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 品牌列表(无分页)
	 *
	 * @param	int		apt		APP抛数据的时间
	 */
	public function actionBrandList()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		
		if($form->validateBrand())
		{
			$model = ClassLoad::Only('Supply'); /* @var $model Supply */
			if ($info = $model->getbrandList($form->apt))
			{
				$this->jsonOutput ( 0, $info );
			}else{
				$this->jsonOutput ( 2, '未找到信息!' );
			}
		}
		
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 供需求分类列表
	 * 
	 * @param		int		apt			APP抛数据的时间
	 */
	public function actionClassList()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		
		if($form->validateBrand())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			if ($info = $model->getClassList())
			{
				$this->jsonOutput ( 0, $info );
			}else{
				$this->jsonOutput ( 2, '未找到信息!' );
			}
		}
		$this->jsonOutput(1,$this->jsonOutput($this->getFormError($form)));
	}
	
	/**
	 * 颜色和尺寸列表(无分页)
	 * 
	 * @param		int			cid 	分类ID
	 * @param		int			apt 	APP抛数据的时间
	 */
	public function actionClassAttrs()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->cid = (int)$this->getPost('cid');
		$form->apt = (int)$this->getPost('apt');
		
		if($form->validateClassAttrs())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			if ($info = $model->getClassAttrs($form->cid))
			{
				$this->jsonOutput ( 0, $info );
			}else{
				$this->jsonOutput ( 2, '未找到信息' );
			}
		}
		
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 发布供需求
	 *  
	 * @param	int			type		供需类型(供应=1;需求=2)
	 * @param	string		title		商品名称
	 * @param	decimal		price		商品价格
	 * @param	int			brand_id	品牌ID
	 * @param	int			num			商品数量
	 * @param	text		describes	商品描述
	 * @param	array		pic_group	图片组
	 * @param	int			class_id	分类ID
	 * @param	int			color_id	颜色ID
	 * @param	int			size_id		尺寸ID
	 * @param	int			apt			APP抛数据的时间
	 */
	public function actionPublish()
	{
		$form				= ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->type			= (int)$this->getPost('type');
		$form->apt			= (int)$this->getPost('apt');
		$form->class_id		= (int)$this->getPost('class_id');
		$form->brand_id		= (int)$this->getPost('brand_id');
		$form->num			= (int)$this->getPost('num');
		$form->title		= trim((string)$this->getPost('title'));
		$form->price		= (int)$this->getPost('price');
		$form->size_id		= (int)$this->getPost('size_id');
		$form->color_id		= (int)$this->getPost('color_id');
		$form->describes	= trim((string)$this->getPost('describes'));
		$form->pic_group = $img	= (array)$this->getPost('pic_group');
		$form->main_pic	= !empty($img) ? (string)$img[0] : "";

		if($form->validatePub())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			
			if($gid = $model->getPublish($form))
			{
				$this->jsonOutput(0,array('gid' => $gid));
			}else{
				$this->jsonOutput(2,'数据添加失败,请重试');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 供应详情
	 * 
	 * @param	int		id		供应商品ID
	 * @param	int		apt 	APP抛数据的时间
	 */
	public function actionSupplyShow()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		$form->id = (int)$this->getPost('id');
		$type = (int)$this->getPost('type');  //1：供应;2：需求
                
		if($form->validatorData())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			if($info = $model->getSupplyShow($form->id,$type))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到数据');
			}
		}
		
		$this->jsonOutput(1,$this->getFormError($form));	
	}
	
	/**
	 * 需求列表
	 * 
	 * @param	int		brand_id		供应品牌ID
	 * @param	int		class_id		供应商品分类ID
	 * @param	int		page			页码
	 * @param	int		apt				APP抛数据的时间
	 */
	public function actionDemandList()
	{
		$form = ClassLoad::Only('DemandListForm'); /* @var $form DemandListForm */
		$form->attributes = empty ( $_POST ) ? array () : $_POST;
		
		if ($form->validate ())
		{
			$_p = ( int ) $this->getPost ( 'page' );
			$model = ClassLoad::Only ( 'Supply' ); /* @var $model Supply */
			
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount ( $model->getSuppyCount ( $form ) );
			
			if ($list = $model->getDemandList ( $form, $page, $_p ))
			{
				$this->jsonOutput ( 0, $list );
			} else
			{
				$this->jsonOutput ( 2, '没有数据！' );
			}
		}
		$this->jsonOutput ( 1, $this->getFormError ( $form ) );
	}
	
	/**
	 * 搜索列表
	 * @param	int			apt				APP抛数据的时间
	 * @param	int			type			需求类型
	 * @param	String		title			商品或商家
	 * @param	int			brand_id		品牌ID
	 * @param	int			cid				分类ID
	 * @param	int			size_id			尺寸ID
	 * @param	int			color_id		颜色ID
	 * @param	int			order			排序
	 * 
	 */
	
	public function actionSearch()
	{
		
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		$form->title = (string)$this->getPost('title');
		$form->type = (int)$this->getPost('type');
		$form->brand_id = (int)$this->getPost('brand_id');
		$form->cid = (int)$this->getPost('cid');
		$form->size_id = (int)$this->getPost('size_id');
		$form->color_id = (int)$this->getPost('color_id');
		$form->order = (int)$this->getPost('order'); 
		$form->page = (int)$this->getPost('page');
		
		if($form->validateSearch())
		{
			$_p = ( int ) $this->getPost ('page');
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getSearchCount($form));
			if ($info = $model->getSearch($form, $page, $_p))
			{
				$this->jsonOutput ( 0, $info );
			}else{
				$this->jsonOutput ( 2, '未找到信息' );
			}
		}	
			$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 需求详情
	 * @param int		id			商品ID
	 * @param int		page		页码
	 * @param int		apt			APP抛数据的时间
	 */
	public function actionDemandShow()
	{
		$form=ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->id = (int)$this->getPost('id');
		$form->apt = (int)$this->getPost('apt');
		$form->page = (int)$this->getPost('page');
                $type = (int)$this->getPost('type');  //1：供应;2：需求

		if($form->validatorDemandShow())
		{
			$_p = (int)$this->getPost ('page');
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getRapaport($form->id));
			
			if($info=$model->getDemandShow($form->id, $page, $_p,$type))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到信息');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	/**
	 * 需求报价
	 * @param		int 		supply_rapaport		所报价格
	 * @param		text 		supply_content		备注
	 */
	
	public function actionDemandOffer()
	{
		$form				= ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->rapaport		= (double)$this->getPost('rapaport');
		$form->content		= trim((string)$this->getPost('content'));
		$form->apt			= (int)$this->getPost('apt');
		$form->id			= (int)$this->getPost('id');
		$form->sid			= array_unique(array_filter((array)$this->getPost('sid'))); //当前 商家需求报价的 所有产品ID编号
		$form->mer_id		= (int)$this->getPost('mer_id');
		if($form->validatorDemand())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			if($info=$model->setOffer($form))
			{
				$this->jsonOutput(0);
			}else{
				$this->jsonOutput(2,'数据处理异常!');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 通过关键词获取商品
	 * @param	string		title		商品名称
	 */
	
	public function actionInfo()
	{
		$form=ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt=$this->getPost('apt');
		$form->title=$this->getPost('title');
		$form->page = $_p = (int)$this->getPost('page');
                
		if($form->validateInfo()){
			$model=ClassLoad::Only('Supply');/* @var $model Supply */
                        
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getNumKeyword($form->title));  
                        
			if($info=$model->getTitleInfo($form->title,$page, $_p))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到数据!');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 商品比价
	 * @param	int			id			商品ID
	 * @param	int			page		页码
	 * @param	int			apt			APP抛数据的时间
	 */
	public function actionCompare()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt			=(int)$this->getPost('apt');
		$form->id			=(int)$this->getPost('id');
		$form->page			=(int)$this->getPost('page');

		if($form->validateCompare())
		{
			$model = ClassLoad::Only('Supply');/* @var $model Supply */
			if (!$goods = $model->compre($form->id))
				$this->jsonOutput(1 , '商品ID错误');
			$serch = array(
				'id'	=> $form->id,
				'page'	=> $form->page,
				'title'	=> $goods['title'],
				'bid'	=> $goods['brand_id'],
				'cid'	=> $goods['class_id'],
			);
			$_p = (int)$this->getPost ('page');
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$page->setItemCount($model->getCompareCount($serch));
			
			if($info = $model->getCompare($serch, $page, $_p))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到比较数据');
			}
		}
		
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 删除供应数据
	 * @param		int			apt			APP抛数据的时间
	 * @param		int			id			商品ID
	 */
	public function actionDelete()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->id = (int)$this->getPost('id');
		$form->apt = (int)$this->getPost('apt');
		if($form->validatorData())
		{
			$model=ClassLoad::Only('Supply');/* @var $model Supply */
			if($info=$model->getDelete($form->id))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'不能删除数据或该数据不存在');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 编辑供应数据
	 * @param		int		id			商品ID
	 * @param		int		apt			APP抛数据的时间
	 * @param		int		brand_id	品牌ID
	 * @param		int		class_id	分类ID
	 * @param		int		color_id	颜色ID
	 * @param		int		size_id		尺寸ID
	 * @param		int		num			供应数量
	 * @param		String	title		商品名称
	 * @param		int		price		商品价格
	 * @param		array	pic_group	图片组
	 * @param		string	describes	商品描述
	 */
	public function actionUpdata()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->id = (int)$this->getPost('id');
		$form->apt = (int)$this->getPost('apt');
		$form->brand_id = (int)$this->getPost('brand_id');
		$form->class_id = (int)$this->getPost('class_id');
		$form->color_id = (int)$this->getPost('color_id');
		$form->size_id = (int)$this->getPost('size_id');
		$form->num = (int)$this->getPost('num');
		$form->type = (int)$this->getPost('type');
		$form->title = trim((string)$this->getPost('title'));
		$form->price = (int)$this->getPost('price');
		$form->pic_group =(array)$this->getPost('pic_group');
		$form->describes = trim((string)$this->getPost('describes'));
		
		if($form->validatorData())
		{
			$model=ClassLoad::Only('Supply');/* @var $model Supply */
			if($info=$model->getUpData($form))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'未找到数据或不能修改!');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 个人订阅
	 * @param	int		apt			APP抛数据的时间
	 * @param	int		class_id	供应分类ID
	 * @param	int		brand_id	品牌ID
	 */
	
	public function actionRss()
	{
		$form = ClassLoad::Only('SupplyForm');/* @var $form SupplyForm */
		$form->apt = (int)$this->getPost('apt');
		$form->brand_id = (int)$this->getPost('brand_id');
		$form->class_id = (int)$this->getPost('class_id');
		$form->page = (int)$this->getPost('page');
		if($form->validatorRss())
		{
			$_p = (int)$this->getPost ('page');
			$model=ClassLoad::Only('Supply');/* @var $model Supply */
			$page = ClassLoad::Only ( 'CPagination' ); /* @var $page CPagination */
			$page->pageSize = 6;
			$page->setCurrentPage ( $_p > 0 ? ($_p - 1) : 0 );
			$in= $page->setItemCount($model->getRssCount($form));
			if($info = $model->getRss($form, $page, $_p))
			{
				$this->jsonOutput(0,$info);
			}else{
				$this->jsonOutput(2,'数据错误');
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	//测试
 	public function actionTest()
	{
		Yii::import('system.extensions.splitword.SplitWord');
	
		$string = '清华电脑';
		echo 'original : '.$string . chr(10);
		$sp = ClassLoad::Only('SplitWord');/* @var $sp SplitWord */
		$sp->SetSource($string);
		$sp->SetResultType(2);
		$sp->StartAnalysis(true);
		$titleindexs = $sp->GetFinallyResultArray(false);
		/* print_r($titleindexs);exit;  */
		 foreach ($titleindexs as $word => $num)
			echo sprintf('%u' , crc32($word)) . chr(10); 	
	}

}