<?php
Yii::import('system.extensions.splitword.SplitWord');
class Supply extends ApiModels
{
	
	//供应模块 - 我的发布列表
	public function getIssue(CFormModel $form , CPagination $page , $_p)
	{
            if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                    return array(); 
            
            $temp = $this->queryAll("SELECT * FROM supply_goods where merchant_id={$this->getMerchantID()} ORDER BY time DESC LIMIT {$page->getOffset()},{$page->getLimit()}");
            if(!empty($temp)){
                foreach ($temp as &$row) {
                    $row['pic_group'] = json_decode($row['pic_group']);
                }
                return $temp;
            }else{
                return false;
            }
	}
        //获取 我的 发布列表的总的 条数
	public function getPublishCount()
        {
            $arr = $this->queryRow("SELECT count(id) as all_num FROM supply_goods WHERE merchant_id={$this->getMerchantID()}");
            return !empty($arr) ? $arr['all_num'] : 0;
        }
        //商品供应列表
	public function getSuppyCount(CFormModel $form)
	{
		$SQL = '';
		$SQL .= $form->brandID > 0 ? " AND brand_id={$form->brandID}" : '';
		$SQL .= $form->classID > 0 ? " AND class_id={$form->classID}" : '';
		return $this->queryScalar("SELECT COUNT(*) FROM supply_goods WHERE 1 {$SQL}");
	}
	
	public function getSupplyList(CFormModel $form , CPagination $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$SQL = '';
		$SQL .= $form->brandID > 0 ? " AND sg.brand_id={$form->brandID}" : '';
		$SQL .= $form->classID > 0 ? " AND sg.class_id={$form->classID}" : '';
		$by = 'ORDER BY sg.id DESC';
		switch ($form->order)
		{
			case 1 : $by = 'ORDER BY sg.id DESC'; break;
			case 2 : $by = 'ORDER BY sg.`price` ASC'; break;
			case 3 : $by = 'ORDER BY sg.`price` DESC'; break;
		}
		
		$temp  = $this->queryAll("SELECT sg.*,um.store_name FROM supply_goods as sg LEFT JOIN user_merchant as um ON sg.merchant_id=um.uid WHERE sg.type=1 {$SQL} {$by} LIMIT {$page->getOffset()},{$page->getLimit()}");

                foreach ($temp as &$row) {
                    $row['pic_group'] = json_decode($row['pic_group']);
                }              
		return $temp;
	}
	

	//品牌列表信息
	public function getbrandList()
	{	
		return array_values(GlobalBrand::getAllList(0));
	
	}
	
	public function getClassAttrs($cid)
	{
		$temp = array();
		foreach ($this->queryAll("SELECT id,`type`,`value` FROM supply_class_attrs WHERE class_id={$cid} ORDER BY rank ASC") as $val)
			$temp[$val['type']][] = array('id'=>$val['id'] , 'value'=>$val['value']);
		
		return array(
			'color'	=> empty($temp[1]) ? array() : $temp[1],
			'size'	=> empty($temp[2]) ? array() : $temp[2],
		);
	}
	
	// 供需求分类列表(无分页)
	public function getClassList()
	{
		return $this->queryAll("select * from supply_class ORDER BY rank ASC");
	}
	
	//发布供需求
	public function getPublish($form)
	{
		$tmp = array(
			'brand_id'	=> $form->brand_id,
			'class_id'	=> $form->class_id,
			'title'		=> $form->title,
			'price'		=> $form->price,
			'num'		=> $form->num,
			'type'		=> $form->type,
			'color_id'	=> $form->color_id,
			'size_id'	=> $form->size_id,
			'describes'	=> $form->describes,
			'pic_group'	=> json_encode($form->pic_group),
                        'main_pic'      => $form->main_pic,
			'time'		=> time(),
			'merchant_id'=> $this->getMerchantID(),
		);
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('supply_goods', $tmp);
			$gid = $this->getInsertId();
			
			$spWord = ClassLoad::Only('SplitWord');/* @var $spWord SplitWord */
			$spWord->SetSource($form->title);
			$spWord->SetResultType(2);
			$spWord->StartAnalysis(true);
			foreach ($spWord->GetFinallyResultArray(false) as $word => $num)
			{
				$this->insert('search_tag' , array(
					'type'			=>1,
					'gid'			=> $gid,
					'word_crc32'	=> sprintf('%u' , crc32($word))
				));
			}
			
			$transaction->commit();
			return $gid;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	// 供应详情
	public function getSupplyShow($id,$type)
	{
		if ($data = $this->getInfo($id,$type))
		{
			$data['brand']	= $this->getBrand($id);
		}
		return $data;
	}
	
	private function getBrand($id)
	{
		$tmp = $this->queryRow("select * from supply_goods where id = {$id}");
		return GlobalBrand::getBrandName($tmp['brand_id']);
	}
	
        //需求列表

	public function  getDemandList( $form , CPagination $page , $_p)
	{
		if($tmp = $this->getList($form , $page , $_p))
		{
			$data=array();
			foreach($tmp as $val){
				$arr1=$val;
				$arr1['rapaport'] = $this->getRapaport($val['id']);
				if(	$arr1['rapaport']>0)
				{
					$arr1['rapaport']=1;
				}
				$data[]=$arr1;
				unset($arr1);
			}
		return $data;
		}
		
		return $tmp;
	}
	
	private function getList($form , $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$SQL = '';
		$SQL .= $form->brandID > 0 ? " AND sg.brand_id={$form->brandID}" : '';
		$SQL .= $form->classID > 0 ? " AND sg.class_id={$form->classID}" : '';
		
		$by = 'ORDER BY sg.id DESC';
		return $this->queryAll("SELECT sg.id,sg.title,sg.num,sg.describes,sg.time,sg.price,um.store_name FROM supply_goods as sg LEFT JOIN user_merchant as um ON sg.merchant_id=um.uid WHERE sg.type=2 {$SQL} {$by} LIMIT {$page->getOffset()},{$page->getLimit()}");
	}
	
 	public function getRapaport($id)
	{
		return $this->queryScalar("select count(id) from supply_rapaport where need_goods_id={$id}");
	} 
	//搜索列表
	
	public function getSearchCount(CFormModel $form)
	{
		$title = !empty($form->title) ? " AND title LIKE '%{$form->title}%' or store_name LIKE '%{$form->title}%'" : '';
		$type = !empty($form->type) ? " s.type={$form->type}" : "s.type = 1";
		$brand = $form->brand_id > 0 ? " AND s.brand_id={$form->brand_id}" : '';
		$cid = $form->cid > 0 ? " AND class_id={$form->cid}" : '';
		$size = $form->size_id > 0 ? " AND size_id={$form->size_id}" : '';
		$color= $form->color_id > 0 ? " OR color_id={$form->color_id}" : '';
		return $this->queryScalar("SELECT COUNT(s.id)  FROM supply_goods AS s left JOIN user_merchant AS m ON s.merchant_id= m.uid where {$type} {$title}{$brand}{$cid}{$size}{$color}");
	}
	
	public function getSearch(CFormModel $form , CPagination $page , $_p)
	{
	
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$tmp		=!empty($form->title)? " AND s.title LIKE '%{$form->title}%' or m.store_name LIKE '%{$form->title}%'" : '';
		$type		=!empty($form->type)?" s.type={$form->type}" :"  s.type=1";
		$brand		=$form->brand_id>0?"AND s.brand_id={$form->brand_id}":'';
		$classid	=$form->cid>0?"AND s.class_id={$form->cid}":'';
		$size		=$form->size_id>0?"AND s.size_id={$form->size_id}":'';
		$color		=$form->color_id>0?"OR s.color_id={$form->color_id}":'';
	
		$by = 'ORDER BY time DESC';
		switch ($form->order)
		{
			case 1 : $by = 'ORDER BY time DESC'; break;
			case 2 : $by = 'ORDER BY `price` ASC'; break;
			case 3 : $by = 'ORDER BY `price` DESC'; break;
		}
		
		return $this->queryAll("SELECT s.title,s.num,s.time,s.id,s.main_pic,s.describes,s.price FROM supply_goods AS s left JOIN user_merchant AS m ON s.merchant_id= m.uid where {$type} {$tmp} {$brand} {$classid} {$size} {$color}{$by} LIMIT {$page->getOffset()},{$page->getLimit()}");
	
	}
	//需求详情
	public function getDemandShow($id, $page, $_p,$type)
	{
		if ($tmp = $this->getInfo($id,$type))
		{
			$tmp['total']			= $this->getTotal($id);
			$tmp['is_self_send']	= (int)($this->getMerchantID() == (int)$tmp['merchant_id']);
			$tmp['quote']			= $this->getQuote($id , $tmp['merchant_id'] , $tmp['is_self_send'], $page, $_p);
			$tmp['xqbrand']			= $this->getxqBrand($id);
		}
		
		return $tmp;
	}    
	
	private function getInfo($gid,$type)
	{
                if ($rows = $this->queryRow("SELECT s.*,m.*,u.phone FROM supply_goods AS s left JOIN user_merchant AS m ON s.merchant_id= m.uid LEFT JOIN user as u ON m.uid=u.id where s.id={$gid} AND type={$type}")){
                        //分类 名
                        $arr_class = $this->queryRow ("SELECT title FROM supply_class WHERE id={$rows['class_id']}");
                        if(!empty($arr_class)){
                            $rows['class_name'] = $arr_class['title'];
                        }else{
                            $rows['class_name'] = "";
                        }
                        //根据 颜色ID 得到颜色的value
                        $arr_color = $this->queryRow ("SELECT value FROM supply_class_attrs WHERE class_id={$rows['class_id']} AND type={$type} AND id={$rows['color_id']}");
                        if(!empty($arr_color)){
                            $rows['color_name'] = $arr_color['value'];
                        }else{
                            $rows['color_name'] = "";
                        } 
                        //根据 尺寸ID 得到尺寸的value
                        $arr_size = $this->queryRow ("SELECT value FROM supply_class_attrs WHERE class_id={$rows['class_id']} AND type={$type} AND id={$rows['size_id']}");
                        if(!empty($arr_size)){
                            $rows['size_name'] = $arr_size['value'];
                        }else{
                            $rows['size_name'] = "";
                        }                         
			$rows['pic_group'] = $this->jsonDnCode($rows['pic_group']);
			return $rows;                    
                }else{
                    return false;
                }
	}
	
	private function getQuote($gid , $mid , $self, $page, $_p)
	{
            
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$SQL = $self ? "" : "AND sr.supply_mer_id={$this->getMerchantID()}";
		$res = $this->queryAll("SELECT sr.*,um.store_name,sg.title FROM supply_rapaport as sr LEFT JOIN user_merchant as um ON sr.supply_mer_id=um.uid LEFT JOIN supply_goods AS sg on sg.id=sr.supply_mer_goods_id  WHERE sr.need_goods_id={$gid} AND sr.need_mer_id={$mid} {$SQL}");
                if(!empty($res)){
                    $temp['data'] = array();
                    $times = array();
                    foreach ($res as $row) {
                        $times[] = $row['supply_mer_id'];
                    }
                    //循环 外层(列出 几个商家)
                    foreach (array_unique($times) as $i=>$v) {
                        $store_name = "";
                        foreach ($res as $row) {
                            if($v==$row['supply_mer_id'])
                                $temp['data'][$i]['lists'][] = $row;
                                $store_name = $row['store_name'];
                        }
                        $temp['data'][$i]['store_name'] = $store_name;
                        $temp['data'][$i]['store_phone'] = $this->getPhoneByMid($v);
                    }
                    $final = array();
                    foreach ($temp['data'] as $row) {
                        $final[] = $row;
                    }
                    return $final;
                }else{
                    return array();
                }
	}
        /*
         * 根据 merchant_id得到 商家的号码
         */
        private function getPhoneByMid($mid)
        {
            $res = $this->queryRow("SELECT phone FROM user WHERE id={$mid}");
            return !empty($res) ? $res['phone'] : "";
        }


        private function getTotal($gid)
	{
		return (int)$this->queryScalar("select COUNT(need_goods_id) FROM supply_rapaport WHERE need_goods_id={$gid}");
	}
	
	private function getxqBrand($id)
	{
		$tmp = $this->queryRow("select * from supply_goods where id = {$id} and type =2");
		return GlobalBrand::getBrandName($tmp['brand_id']);
	}
	//提交报价
	
	public function setOffer($form)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$uid = $this->getMerchantID();
			$time = time();
                        if(!empty($form->sid)){     //当前商家 选择了 自己店铺的商品作为供应商品
                            foreach ($form->sid as $sid)
                            {
                                $this->insert('supply_rapaport' , array(
                                        'need_mer_id'			=> $form->mer_id,   //发布需求的商家ID
                                        'need_goods_id'			=> $form->id,       //需求的产品ID
                                        'supply_mer_id'			=> $uid,            //当前登录商家进行报价
                                        'supply_mer_goods_id'	=> $sid,                    //商家 店铺里的 推荐商品的ID
                                        'supply_rapaport'		=> $form->rapaport,
                                        'supply_content'		=> $form->content,
                                        'supply_time'			=> $time,
                                ));
                            }                            
                        }else{
                                $this->insert('supply_rapaport' , array(
                                        'need_mer_id'			=> $form->mer_id,   //发布需求的商家ID
                                        'need_goods_id'			=> $form->id,       //需求的产品ID
                                        'supply_mer_id'			=> $uid,            //当前登录商家进行报价
                                        'supply_mer_goods_id'	=> "",                    //商家 店铺里的 推荐商品的ID
                                        'supply_rapaport'		=> $form->rapaport,
                                        'supply_content'		=> $form->content,
                                        'supply_time'			=> $time,
                                ));                            
                        }
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	
	public function getTitleInfo($title, CPagination $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		$keyword=!empty($title) ? " where title LIKE '%{$title}%'" : '';
                $temp = $this->queryAll("select * from supply_goods {$keyword} LIMIT {$page->getOffset()},{$page->getLimit()}");

                if(!empty($temp)){
                    foreach ($temp as &$row) {
                        $row['pic_group'] = json_decode($row['pic_group']);
                    }
                    return $temp;
                }else{
                    return array();
                }                
	}
        //获取 关键字 商品的 总条数
        public function getNumKeyword($title)
        {
		$keyword=!empty($title) ? " where title LIKE '%{$title}%'" : '';
                $temp = $this->queryAll("select count(id) as all_num from supply_goods {$keyword}"); 
                if(!empty($temp)) return $temp[0]['all_num'];
                else{
                    return 0;
                }
        }


        //比价
	public function getCompare(array $serch , CPagination $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$spWord = ClassLoad::Only('SplitWord');/* @var $spWord SplitWord */
		$spWord->SetSource($serch['title']);
		$spWord->SetResultType(2);
		$spWord->StartAnalysis(true);
		
		$hash = array();
		foreach ($spWord->GetFinallyResultArray(false) as $word => $num)
			$hash[] = sprintf('%u' , crc32($word));
		
		if (!$hash)
			return array();
		$temp = $this->queryAll("
				SELECT * FROM supply_goods AS sg
				INNER JOIN search_tag AS w ON sg.id=w.gid
				WHERE sg.type=1 AND sg.id!={$serch['id']} AND w.word_crc32 IN (".join(',' , $hash).")
				GROUP BY sg.id
				ORDER BY sg.id ASC
				LIMIT {$page->getOffset()},{$page->getLimit()}");
                if(!empty($temp)){
                    foreach ($temp as &$row) {
                        $row['pic_group'] = json_decode($row['pic_group']);
                        //根据merchant_id得到 店铺名称
                        $arr = $this->queryRow("SELECT store_name FROM user_merchant WHERE uid={$row['merchant_id']}");
                        $row['store_name'] = !empty($arr) ? $arr['store_name'] : "";
                    }
                    return $temp;
                }else{
                    return false;
                }
	}
	
	public function getCompareCount(array $serch)
	{
		return $this->queryScalar("SELECT COUNT(id) FROM supply_goods where type=1 ");	
		
	}
	
	public function compre($gid)
	{
		if ($rows = $this->queryRow("SELECT * FROM supply_goods where id={$gid}"))
			$rows['pic_group'] = $this->jsonDnCode($rows['pic_group']);
	
		return $rows;
	}
	
	//删除供应数据
	public function getDelete($id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$tmp = $this->queryAll("select merchant_id from supply_goods where id={$id}");
			$need = $this->queryScalar("select id from supply_rapaport where need_goods_id={$id}");
                        $need = $need===false ? 0 : $need;
                        
			$data=array();
			foreach($tmp as $val){
				$arr1=$val;
				if($arr1['merchant_id']==$this->getMerchantID() && !empty($tmp))
				{
					$this->delete("supply_goods",'id='.$id);
					$this->delete("search_tag",'gid='.$id);
					$this->delete("supply_rapaport",'id='.$need);
					$transaction->commit();
					return true;
				}
			}
			return $data;
			
		
		}catch(Exception $e){
			$transaction->rollBack();
		}
	}
	
	//编辑供应数据
	public function getUpData($form)
	{
		$data = $this->queryAll("SELECT * FROM supply_goods WHERE id={$form->id}");
		$word = $this->queryAll("SELECT * FROM search_tag WHERE gid={$form->id}");
		$tmp1=array();
		foreach($data as $val){
			$arr1=$val;
			if(empty($data) || $arr1['merchant_id']!=$this->getMerchantID())
			return false;
			$tmp = array(
					'brand_id'	=> $form->brand_id,
					'class_id'	=> $form->class_id,
					'title'		=> $form->title,
					'price'		=> $form->price,
					'num'		=> $form->num,
					'type'		=> $form->type,
					'color_id'	=> $form->color_id,
					'size_id'	=> $form->size_id,
					'describes'	=> $form->describes,
					'pic_group'	=> json_encode($form->pic_group),
			);
			
			$word = array(
					'word_crc32'		=> $form->title,
			);
			
			$transaction = Yii::app()->getDb()->beginTransaction();
			
			try
			{
				$this->update('supply_goods',$tmp,'id='.$form->id);
				$this->delete('search_tag','gid='.$form->id);
				$spWord = ClassLoad::Only('SplitWord');/* @var $spWord SplitWord */
				$spWord->SetSource($form->title);
				$spWord->SetResultType(2);
				$spWord->StartAnalysis(true);
				foreach ($spWord->GetFinallyResultArray(false) as $word => $num)
				{
					$this->insert('search_tag', array(
						'type'			=>1,
						'gid'			=> $form->id,
						'word_crc32'	=> sprintf('%u' , crc32($word))
					));
				}
				
				$transaction->commit();
				return true;
				
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		 return $tmp1;
	}
	
	//个人订阅
	public function getRss(CFormModel $form , CPagination $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
		
		$SQL = '';
		$SQL .= $form->brand_id > 0 ? " AND brand_id={$form->brand_id}" : '';
		$SQL .= $form->class_id > 0 ? " AND class_id={$form->class_id}" : '';
		$mer= $this->getMerchantID();
		$info= $this->queryAll("select id,title,num,main_pic,describes from supply_goods where merchant_id={$mer}{$SQL} LIMIT {$page->getOffset()},{$page->getLimit()}");
		if(empty($info))
			return array(); 
		return $info;
			
	}
	public function getRssCount(CFormModel $form)
	{
		$SQL = '';
		$SQL .= $form->brand_id > 0 ? " AND brand_id={$form->brand_id}" : '';
		$SQL .= $form->class_id > 0 ? " AND class_id={$form->class_id}" : '';
		$mer=$this->getMerchantID();
		
		return $this->queryScalar("select COUNT(*) from supply_goods where merchant_id={$mer}{$SQL}");
	}
}