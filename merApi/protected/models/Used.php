<?php
class Used extends ApiModels
{
	/**
	 * 添加二手商品
	 * @param 商品信息 $form
	 */
	public function create($form)
	{
		$merchant_id=$this->getMerchantID();
		$goodsNum = trim($form->goods_num);
		$goodsNum = $goodsNum ? $goodsNum : $this->getDefaultNum();
		$arr=array(
			'title'			=>	$form->title,
			'brand_id'		=>	$form->brand_id,
			'goods_num'		=>	$goodsNum,
			'merchant_id'	=>	$merchant_id,
			'lightspot'		=>	$form->lightspot,
			'dict_one_id'	=>	$form->dict_one_id,
			'dict_two_id'	=>	$form->dict_two_id,
			'dict_three_id'	=>	$form->dict_three_id,
			'buy_price'		=>	$form->buy_price,
			'sale_price'	=>	$form->sale_price,
			'status_id'     =>	1011,
			'shelf_id'		=>	1002,
			'stock'			=>	$form->stock==-1?-999:$form->stock,
			'weight'		=>	$form->weight,
//			'content'		=>	$form->content,
			'cover'			=>	$this->getPhotos($form->cover , 'used_goods' , $merchant_id),
			'use_time'		=>	$form->use_time,
			'class_one_id'	=>	$form->class_one_id,
			'class_two_id'	=>	$form->class_two_id,
			'class_three_id'=>	$form->class_three_id,
			'create_time'   =>	time(),
			'last_time'     =>	time()
		);
		$img=$form->img;
		$transaction = Yii::app()->getDb()->beginTransaction();
		try {
			$this->insert('used_goods', $arr);
			$used_id = $this->getInsertId();
			$id=(int)$this->getMerchantID();
			//品牌+1
			$this->execute("UPDATE goods_brand SET goods_num=goods_num+1 WHERE id={$arr['brand_id']}");
			//商品图片
			foreach($img as $k=>$v)
			{
				if(!empty($v)){
					$arr1=array(
							'src'       =>$this->getPhotos($v , 'used_goods' , $merchant_id),
							'rank'      =>$k,
							'used_id'   =>$used_id
					);
					$this->insert('used_goods_photo' , $arr1);
				}
			}
			//添加分词
			GlobalSplitWord::setWord(3 , $used_id , array($arr['title']) , true);
			//商品版本
			$this->insert('used_goods_versions' , array(
					'goods_id'		=> $used_id,
					'vers_num'		=> time(),
					'vers_text'		=> serialize(array(
							'goods'			=> $arr,
							'picture'		=> $img
					))
			));

			$transaction->commit();
				return true;
		} catch (Exception $e) {
			$transaction->rollBack();
			return false;
		}
	}
	/**
	 * 商品上下架
	 */
	public function shelf($id,$shelf)
	{
		return $this->update('used_goods',$arr=array('shelf_id'=>$shelf),"id={$id}");
	}
	/**
	 * 二手商品详情
	 */
	public function getInfo($id)
	{
		$row=$this->queryRow("SELECT * FROM used_goods WHERE id={$id}");
		$row['img'] = $this->getImg($id);
		return $row;
	}
	/**
	 * 获取二手商品图片
	 * @param 二手商品id $id
	 */
	private function getImg($id)
	{
		return $this->queryAll("SELECT src FROM used_goods_photo WHERE used_id={$id}");
	}
	/**
	 * 修改
	 */
	public function modify($form)
	{
		$used_id=$form->id;
		$merchant_id=$this->getMerchantID();
		if(strpos($form->cover,'used_goods') === false){     //使用绝对等于
			$src=$this->getPhotos($form->cover , 'used_goods' , $merchant_id);
		}else{
			$src=$form->cover;
		}
		$arr=array(
			'title'			=>	$form->title,
			'brand_id'		=>	$form->brand_id,
			'merchant_id'	=>	$merchant_id,
			'lightspot'		=>	$form->lightspot,
			'dict_one_id'	=>	$form->dict_one_id,
			'dict_two_id'	=>	$form->dict_two_id,
			'dict_three_id'	=>	$form->dict_three_id,
			'buy_price'		=>	$form->buy_price,
			'sale_price'	=>	$form->sale_price,
			'status_id'     =>	1011,
			'stock'			=>	$form->stock==-1?-999:$form->stock,
			'weight'		=>	$form->weight,
//			'content'		=>	$form->content,
			'cover'			=>	$src,
			'use_time'		=>	$form->use_time,
			'class_one_id'	=>	$form->class_one_id,
			'class_two_id'	=>	$form->class_two_id,
			'class_three_id'=>	$form->class_three_id,
			'last_time'     =>	time()
		);
		$img=$form->img;
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//商品
			$this->update('used_goods' , $arr,'id='.$used_id);

			//商品图片组
			$this->delete('used_goods_photo','used_id='.$used_id);
			foreach($img as $k=>$v)
			{
				if(!empty($v)){
					if(strpos($v,'used_goods') === false){     //使用绝对等于
						$src=$this->getPhotos($v , 'used_goods' , $merchant_id);
					}else{
						$src=$v;
					}
					$arr1=array(
							'src'       =>$src,
							'rank'      =>$k,
							'used_id'   =>$used_id
					);
					$this->insert('used_goods_photo' , $arr1);
				}
			}
			//编辑分词
			GlobalSplitWord::delWord(3 , $used_id);
			GlobalSplitWord::setWord(3 , $used_id , array($arr['title']) , true);
			//商品版本
			$this->insert('used_goods_versions' , array(
					'goods_id'		=> $used_id,
					'vers_num'		=> time(),
					'vers_text'		=> serialize(array(
							'goods'			=> $arr,
							'picture'		=> $img
					))
			));

			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	/**
	 * 检查标题
	 */
	public function checkTitle($title , $id)
	{
		$SQL = $id>0 ? " AND id!={$id}" : '';
		return $title && (boolean)$this->queryRow("SELECT id FROM used_goods WHERE `title`={$this->quoteValue($title)} {$SQL}");
	}
	/**
	 *检查商品货号
	 */
	public function checkGoodsNum($goods_num)
	{
		return $goods_num && (boolean)$this->queryRow("SELECT id FROM used_goods WHERE `goods_num`={$this->quoteValue($goods_num)}");
	}
	/**
	 *商家删除商品
	 */
	public function clear($id){
		return $this->update('used_goods',array('delete_id'=>1019),'id='.$id);
	}
	/**
	 * 获得默认的商品货号
	 */
	public function getDefaultNum()
	{
		#-------------------------此数组请勿改动-----------------------------------------------
		$range = array(0=>2 , 1=>4 , 2=>6 , 3=>1 , 4=>5 , 5=>9 , 6=>7 , 7=>3 , 8=>0 , 9=>8);

		$code = 'CP'.mt_rand(0,9);
		foreach (str_split(time()) as $k => $v)
			$code .= (($k && $k % 4 == 0) ? mt_rand(0, 9) : '') . $range[$v];
		return $code;
	}
	/**
	 * 我的二手商品列表
	 */
	public function getList($search , $id , $pageNow , $pageSize)
	{
		$pageNow=($pageNow-1)*$pageSize;
		$keyword=$search['keyword'];
		$status=$search['status'];
		unset($search['keyword'] , $search['status']);
		$where='';
		if(!empty($keyword))
		{
			$keyword = $this->quoteLikeValue($keyword);
			$where .= " AND title LIKE {$keyword}";
		}
		$where .= $status==1?" AND status_id=1011 ":($status==2?" AND shelf_id=1001 ":($status==3?" AND shelf_id=1002 ":""));
		return $this->queryAll("SELECT id,title,cover,stock,detail,shelf_id,status_id,sale_price FROM used_goods WHERE merchant_id={$id} AND delete_id=0 {$where} LIMIT {$pageNow},{$pageSize}");
	}
}