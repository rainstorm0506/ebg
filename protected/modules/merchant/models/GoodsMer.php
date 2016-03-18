<?php
class GoodsMer extends SModels
{
	#商品主图片键名
	const GOODSMAINPICKEY = 'main';
	
	public function setStatus($gid , $newStatus)
	{
		$mid = $this->getMerchantID();
		if ($newStatus == 1)
			$this->update('goods' , array('shelf_id'=>410,'shelf_time'=>time()) , "id={$gid} AND merchant_id={$mid}");
		else
			$this->update('goods' , array('shelf_id'=>411,'shelf_time'=>0) , "id={$gid} AND merchant_id={$mid}");
		return true;
	}
	
	public function setGoodsData(array $post , array $goods , $gid , $copy = false)
	{
		$classOneID		= (int)$post['class_one_id'];
		$classTwoID		= (int)$post['class_two_id'];
		$classThreeID	= (int)$post['class_three_id'];
		$brandID		= (int)$post['brand_id'];
		$goodsNum		= $goods['goods_num'];
		$merchantID		= $this->getMerchantID();
		
		//复制
		if ($copy)
		{
			$goodsNum = trim($post['goods_num']);
			$goodsNum = $goodsNum ? $goodsNum : GlobalGoods::getDefaultNum();
		}
		
		$time = time();
		$attrs = array(
			'attrs'		=> !empty($post['attrs']) && is_array($post['attrs']) ? $post['attrs'] : array(),
			'attrVal'	=> !empty($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array(),
			'imgsSet'	=> !empty($post['imgsSet']) ? $post['imgsSet'] : '',
		);
		$goods = array(
			'title'				=> trim($post['title']),
			'vice_title'		=> trim($post['vice_title']),
			'goods_num'			=> $goodsNum,
			'brand_id'			=> $brandID,
			'class_one_id'		=> $classOneID,
			'class_two_id'		=> $classTwoID,
			'class_three_id'	=> $classThreeID,
			'merchant_id'		=> $merchantID,
			'is_self'			=> (int)GlobalMerchant::isSelfMerchant($merchantID),
			'shelf_id'			=> 411,
			'status_id'			=> 400,
			'delete_id'			=> 0,
			'tag_id'			=> (int)$post['tag_id'],
			'retail_price'		=> (double)$post['retail_price'],
			'base_price'		=> 0,
			'weight'			=> 0,
			'stock'				=> 0,
			'min_price'			=> 0,
			'max_price'			=> 0,
			'content'			=> trim($post['content']),
			'create_time'		=> $time,
			'last_time'			=> $time,
			'amount_ratio'		=> json_encode(!empty($post['amount'])&&is_array($post['amount'])?$post['amount']:array()),
			'user_layer_ratio'	=> json_encode(!empty($post['userLayer'])&&is_array($post['userLayer'])?$post['userLayer']:array()),
			'args'				=> json_encode(!empty($post['args'])&&is_array($post['args'])?$post['args']:array()),
			'attrs'				=> json_encode($attrs),
			'cover'				=> $this->getPhotos($post['cover'] , 'goods' , $merchantID),
			'rank'				=> $post['rank']
		);
	
		if (empty($post['attrVal']))
		{
			$goods['base_price']	= (double)$post['base_price'];
			$goods['weight']		= (double)$post['weight'];
			$goods['jd_id']			= $post['jd_id'];
			if ((int)$post['attrInStock'] === 1)
			{
				$goods['stock']		= -999;
			}else{
				$goods['stock']		= (int)$post['stock'];
			}
		}
	
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//复制
			if ($copy)
			{
				$this->insert('goods' , $goods);
				$gid = $this->getInsertId();
			}else{
				$this->update('goods' , $goods , "id={$gid}");
			}

			GlobalSplitWord::setWord(2 , $gid , array($goods['title'] , $goods['vice_title']) , true);
				
			//主图片组
			$picMain = $this->_set_main_photo($gid , $merchantID , (isset($post['img'])&&is_array($post['img']) ? $post['img'] : array()));
			foreach ($picMain as $v)
				$this->insert('goods_photo' , $v);
				
			//属性图片组
			$picGroup = $this->_set_group_photo($gid , $merchantID , (isset($post['imgGroup'])&&is_array($post['imgGroup']) ? $post['imgGroup'] : array()));
			foreach ($picGroup as $pgv)
			{
				foreach ($pgv as $v)
					$this->insert('goods_photo' , $v);
			}
	
			//商品属性
			$minPrice = $maxPrice = 0;
			$goodsAttrs = $this->_set_attrs($gid , $classOneID , $classTwoID , $classThreeID , (isset($post['attrVal'])&&is_array($post['attrVal']) ? $post['attrVal'] : array()));
			foreach ($goodsAttrs as $v)
			{
				$minPrice = min(($minPrice == 0?$v['base_price']:$minPrice) , $v['base_price']);
				$maxPrice = max($maxPrice , $v['base_price']);
				$this->insert('goods_join_attrs' , $v);
			}
	
			//更新商品表中的最大 , 最小价格
			$this->update('goods' , array('min_price'=>$minPrice , 'max_price'=>$maxPrice) , 'id='.$gid);
				
			//商品版本
			$this->insert('goods_versions' , array(
				'goods_id'		=> $gid,
				'vers_num'		=> $time,
				'vers_text'		=> serialize(array(
					'goods'			=> $goods,
					'picMain'		=> $picMain,
					'picGroup'		=> $picGroup,
					'goodsAttrs'	=> $goodsAttrs
				))
			));
				
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	public function getGoodsPic($gid)
	{
		$tmp = array();
		foreach ($this->queryAll("SELECT attrs_unite_code,src FROM goods_photo WHERE goods_id={$gid} ORDER BY rank ASC") as $val)
			$tmp[$val['attrs_unite_code']][] = $val['src'];
		return $tmp;
	}
	
	public function deletes($gid , array $goods)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$brandID = empty($goods['brand_id']) ? 0 : (int)$goods['brand_id'];
			
			$this->update('goods' , array('delete_id'=>419) , "id={$gid} AND merchant_id={$this->getMerchantID()}");
			
			// $this->execute("UPDATE goods_brand_join_class SET goods_num=goods_num-1 WHERE brand_id={$brandID} AND class_three_id={$goods['class_three_id']} AND type=1");
			
			GlobalSplitWord::delWord(2 , $gid);

			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	public function getGoodsInfo($gid , $copy = false , $attrVal = false)
	{
		if ($tmp = $this->queryRow("SELECT * FROM goods WHERE id={$gid}" . ($copy ? '' : " AND merchant_id={$this->getMerchantID()}")))
		{
			$tmp['amount_ratio']		= $this->jsonDnCode($tmp['amount_ratio']);
			$tmp['user_layer_ratio']	= $this->jsonDnCode($tmp['user_layer_ratio']);
			$tmp['args']				= $this->jsonDnCode($tmp['args']);
			$tmp['attrs']				= $this->jsonDnCode($tmp['attrs']);
			$tmp['join_goods']			= $this->jsonDnCode($tmp['join_goods']);
			
			# 更新 attrs.attrVal中的数据
			if (!empty($tmp['attrs']['attrVal']) && $attrVal && ($attrs = GlobalGoods::getAttrs($gid , true)))
			{
				foreach ($attrs as $asv)
				{
					$code1 = $asv['attrs_1_unite_code'];
					$code2 = $asv['attrs_2_unite_code'];
					$code3 = $asv['attrs_3_unite_code'];
						
					if ($code1 && $code2 && $code3)
					{
						if ($asv['stock'] == -999)
						{
							$tmp['attrs']['attrVal']['inStock'][$code1][$code2][$code3] = -999;
						}else{
							$tmp['attrs']['attrVal']['inStock'][$code1][$code2][$code3] = 0;
							$tmp['attrs']['attrVal']['stock'][$code1][$code2][$code3] = $asv['stock'];
						}
					}elseif ($code1 && $code2){
						if ($asv['stock'] == -999)
						{
							$tmp['attrs']['attrVal']['inStock'][$code1][$code2] = -999;
						}else{
							$tmp['attrs']['attrVal']['inStock'][$code1][$code2] = 0;
							$tmp['attrs']['attrVal']['stock'][$code1][$code2] = $asv['stock'];
						}
					}elseif ($code1){
						if ($asv['stock'] == -999)
						{
							$tmp['attrs']['attrVal']['inStock'][$code1] = -999;
						}else{
							$tmp['attrs']['attrVal']['inStock'][$code1] = 0;
							$tmp['attrs']['attrVal']['stock'][$code1] = $asv['stock'];
						}
					}
				}
			}
		}
		return $tmp;
	}
	
	public function create(array $post)
	{
		$classOneID		= (int)$post['class_one_id'];
		$classTwoID		= (int)$post['class_two_id'];
		$classThreeID	= (int)$post['class_three_id'];
		$brandID		= (int)$post['brand_id'];
		$merchantID		= $this->getMerchantID();
		
		$goodsNum = trim($post['goods_num']);
		$goodsNum = $goodsNum ? $goodsNum : GlobalGoods::getDefaultNum();
		$time = time();
		
		$attrs = array(
			'attrs'		=> !empty($post['attrs']) && is_array($post['attrs']) ? $post['attrs'] : array(),
			'attrVal'	=> !empty($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array(),
			'imgsSet'	=> !empty($post['imgsSet']) ? $post['imgsSet'] : '',
		);
		$goods = array(
			'title'				=> trim($post['title']),
			'vice_title'		=> trim($post['vice_title']),
			'goods_num'			=> $goodsNum,
			'merchant_id'		=> $merchantID,
			'is_self'			=> (int)GlobalMerchant::isSelfMerchant($merchantID),
			'brand_id'			=> $brandID,
			'class_one_id'		=> $classOneID,
			'class_two_id'		=> $classTwoID,
			'class_three_id'	=> $classThreeID,
			'shelf_id'			=> 411,
			'status_id'			=> 400,
			'delete_id'			=> 0,
			'tag_id'			=> (int)$post['tag_id'],
			'retail_price'		=> (double)$post['retail_price'],
			'base_price'		=> 0,
			'weight'			=> 0,
			'stock'				=> 0,
			'min_price'			=> 0,
			'max_price'			=> 0,
			'content'			=> trim($post['content']),
			'create_time'		=> $time,
			'last_time'			=> $time,
			'amount_ratio'		=> json_encode(!empty($post['amount'])&&is_array($post['amount'])?$post['amount']:array()),
			'user_layer_ratio'	=> json_encode(!empty($post['userLayer'])&&is_array($post['userLayer'])?$post['userLayer']:array()),
			'args'				=> json_encode(!empty($post['args'])&&is_array($post['args'])?$post['args']:array()),
			'attrs'				=> json_encode($attrs),
			'cover'				=> $this->getPhotos($post['cover'] , 'goods' , $merchantID),
			'rank'				=> $post['rank']
		);
		
		if (empty($post['attrVal']))
		{
			$goods['base_price']	= (double)$post['base_price'];
			$goods['weight']		= (double)$post['weight'];
			$goods['jd_id']			= $post['jd_id'];
			if ((int)$post['attrInStock'] === 1)
			{
				$goods['stock']		= -999;
			}else{
				$goods['stock']		= (int)$post['stock'];
			}
		}
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//商品
			$this->insert('goods' , $goods);
			$gid = $this->getInsertId();
			GlobalSplitWord::setWord(2 , $gid , array($goods['title'] , $goods['vice_title']) , true);
			
			//品牌商品+1
			// $this->execute("UPDATE goods_brand_join_class SET goods_num=goods_num+1 WHERE brand_id={$brandID} AND class_three_id={$classThreeID} AND type=1");
			
			//主图片组
			$picMain = $this->_set_main_photo($gid , $merchantID , (isset($post['img'])&&is_array($post['img']) ? $post['img'] : array()));
			foreach ($picMain as $v)
				$this->insert('goods_photo' , $v);
			
			//属性图片组
			$picGroup = $this->_set_group_photo($gid , $merchantID , (isset($post['imgGroup'])&&is_array($post['imgGroup']) ? $post['imgGroup'] : array()));
			foreach ($picGroup as $pgv)
			{
				foreach ($pgv as $v)
					$this->insert('goods_photo' , $v);
			}
			
			//商品属性
			$minPrice = $maxPrice = 0;
			$goodsAttrs = $this->_set_attrs($gid , $classOneID , $classTwoID , $classThreeID , (isset($post['attrVal'])&&is_array($post['attrVal']) ? $post['attrVal'] : array()));
			foreach ($goodsAttrs as $v)
			{
				$minPrice = min(($minPrice == 0?$v['base_price']:$minPrice) , $v['base_price']);
				$maxPrice = max($maxPrice , $v['base_price']);
				$this->insert('goods_join_attrs' , $v);
			}
			
			//更新商品表中的最大 , 最小价格
			if ($minPrice || $maxPrice)
				$this->update('goods' , array('min_price'=>$minPrice , 'max_price'=>$maxPrice) , 'id='.$gid);
			
			//商品版本
			$this->insert('goods_versions' , array(
				'goods_id'		=> $gid,
				'vers_num'		=> $time,
				'vers_text'		=> serialize(array(
					'goods'			=> $goods,
					'picMain'		=> $picMain,
					'picGroup'		=> $picGroup,
					'goodsAttrs'	=> $goodsAttrs
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
	 * 得到 分类的属性组 键值对
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 */
	public function getAttrsKeyValue($one = 0 , $two = 0 , $three = 0)
	{
		$tmp = array();
		foreach ($this->queryAll("SELECT unite_code,title FROM goods_attrs WHERE class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three}") as $val)
			$tmp[$val['unite_code']] = $val['title'];
		return $tmp;
	}
	
	/**
	 * 设定 商品属性
	 * @param		int			$gid				商品ID
	 * @param		int			$classOneID			第一层分类ID
	 * @param		int			$classTwoID			第二层分类ID
	 * @param		int			$classThreeID		第三层分类ID
	 * @param		array		$attrVal			商品属性
	 */
	private function _set_attrs($gid , $classOneID , $classTwoID , $classThreeID , array $attrVal)
	{
		if (!$gid)
			return array();
	
		$this->delete('goods_join_attrs' , "goods_id={$gid}");
	
		if (!$classAttrs = $this->getAttrsKeyValue($classOneID , $classTwoID , $classThreeID))
			return array();
	
		$price		= !empty($attrVal['price']) && is_array($attrVal['price']) ? $attrVal['price'] : array();
		$inStock	= !empty($attrVal['inStock']) && is_array($attrVal['inStock']) ? $attrVal['inStock'] : array();
		$stock		= !empty($attrVal['stock']) && is_array($attrVal['stock']) ? $attrVal['stock'] : array();
		$weight		= !empty($attrVal['weight']) && is_array($attrVal['weight']) ? $attrVal['weight'] : array();
		$jd_id		= !empty($attrVal['jd_id']) && is_array($attrVal['jd_id']) ? $attrVal['jd_id'] : array();
	
		$attrs = array();
		foreach ($price as $ak => $av)
		{
			if (!empty($av) && is_array($av))
			{
				foreach ($av as $bk => $bv)
				{
					if (!empty($bv) && is_array($bv))
					{
						foreach ($bv as $ck => $cv)
						{
							//三层结构
							$_is = empty($inStock[$ak][$bk][$ck]) ? 0 : (int)$inStock[$ak][$bk][$ck];
							$_p = (double)$cv;
							$_w = empty($weight[$ak][$bk][$ck]) ? 0 : $weight[$ak][$bk][$ck];
							$_j = empty($jd_id[$ak][$bk][$ck]) ? 0 : $jd_id[$ak][$bk][$ck];
							$_s = ($_is>0 || $_is === -999) ? -999 : (empty($stock[$ak][$bk][$ck]) ? 0 : (int)$stock[$ak][$bk][$ck]);
							$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];
							$v2 = empty($classAttrs[$bk]) ? '' : $classAttrs[$bk];
							$v3 = empty($classAttrs[$ck]) ? '' : $classAttrs[$ck];
								
							if (!$_p || !$_s || !$_w || !$v1 || !$v2 || !$v3)
								continue;
								
							$attrs[] = array(
								'key_code'				=> md5($gid.$ak.$bk.$ck),
								'goods_id'				=> $gid,
								'attrs_1_unite_code'	=> $ak,
								'attrs_2_unite_code'	=> $bk,
								'attrs_3_unite_code'	=> $ck,
								'attrs_1_value'			=> $v1,
								'attrs_2_value'			=> $v2,
								'attrs_3_value'			=> $v3,
								'base_price'			=> $_p,
								'stock'					=> $_s,
								'weight'				=> $_w,
								'jd_id'					=> $_j
							);
						}
					}else{
						//二层结构
						$_is = empty($inStock[$ak][$bk]) ? 0 : (int)$inStock[$ak][$bk];
						$_p = (double)$bv;
						$_w = empty($weight[$ak][$bk]) ? 0 : $weight[$ak][$bk];
						$_j = empty($jd_id[$ak][$bk][0]) ? 0 : $jd_id[$ak][$bk];
						$_s = ($_is>0 || $_is === -999) ? -999 : (empty($stock[$ak][$bk]) ? 0 : (int)$stock[$ak][$bk]);
						$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];
						$v2 = empty($classAttrs[$bk]) ? '' : $classAttrs[$bk];
	
						if (!$_p || !$_s || !$_w || !$v1 || !$v2)
							continue;
	
						$attrs[] = array(
							'key_code'				=> md5($gid.$ak.$bk),
							'goods_id'				=> $gid,
							'attrs_1_unite_code'	=> $ak,
							'attrs_2_unite_code'	=> $bk,
							'attrs_3_unite_code'	=> '',
							'attrs_1_value'			=> $v1,
							'attrs_2_value'			=> $v2,
							'attrs_3_value'			=> '',
							'base_price'			=> $_p,
							'stock'					=> $_s,
							'weight'				=> $_w,
							'jd_id'					=> $_j
						);
					}
				}
			}else{
				//一层结构
				$_is = empty($inStock[$ak]) ? 0 : (int)$inStock[$ak];
				$_p = (double)$av;
				$_w = empty($weight[$ak]) ? 0 : $weight[$ak];
				$_j = empty($jd_id[$ak]) ? 0 : $jd_id[$ak];
				$_s = ($_is>0 || $_is === -999) ? -999 : (empty($stock[$ak]) ? 0 : (int)$stock[$ak]);
				$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];
				if (!$_p || !$_s || !$_w || !$v1)
					continue;
	
				$attrs[] = array(
					'key_code'				=> md5($gid.$ak),
					'goods_id'				=> $gid,
					'attrs_1_unite_code'	=> $ak,
					'attrs_2_unite_code'	=> '',
					'attrs_3_unite_code'	=> '',
					'attrs_1_value'			=> $v1,
					'attrs_2_value'			=> '',
					'attrs_3_value'			=> '',
					'base_price'			=> $_p,
					'stock'					=> $_s,
					'weight'				=> $_w,
					'jd_id'					=> $_j
				);
			}
		}
		return $attrs;
	}
	
	/**
	 * 设定商品的属性图片组
	 * @param		int			$gid			商品ID
	 * @param		int			$merchantID		商家ID
	 * @param		array		$mainImg		原始的图片组
	 */
	private function _set_group_photo($gid , $merchantID , array $oldImg)
	{
		#print_r($oldImg);exit;
		if (!$gid)
			return array();
	
			$key = self::GOODSMAINPICKEY;
			$this->delete('goods_photo' , "goods_id={$gid} AND attrs_unite_code!='{$key}'");
	
			$pic = array();
			foreach ($oldImg as $k => $v)
			{
				if (empty($v) || !is_array($v))
					continue;
					
				$x = 0;
				foreach ($v as $ik => $iv)
				{
					$pic[$k][$x] = array(
						'goods_id'			=> $gid,
						'attrs_unite_code'	=> $k,
						'rank'				=> $x,
						'src'				=> $this->getPhotos($iv , 'goods' , $merchantID)
					);
					$x++;
				}
			}
			return $pic;
	}
	
	/**
	 * 设定商品的主图片组
	 * @param		int			$gid			商品ID
	 * @param		int			$merchantID		商家ID
	 * @param		array		$mainImg		原始的图片组
	 */
	private function _set_main_photo($gid , $merchantID , array $oldImg)
	{
		if (!$gid)
			return array();
	
		$key = self::GOODSMAINPICKEY;
		$this->delete('goods_photo' , "goods_id={$gid} AND attrs_unite_code='{$key}'");
	
		$x = 0;
		$pic = array();
		foreach ($oldImg as $v)
		{
			$pic[$x] = array(
				'goods_id'			=> $gid,
				'attrs_unite_code'	=> $key,
				'rank'				=> $x,
				'src'				=> $this->getPhotos($v , 'goods' , $merchantID)
			);
			$x++;
		}
		return $pic;
	}
	
	/**
	 * 检查商品编号
	 * @param		string		$num		商品编号
	 */
	public function checkGoodsNum($num)
	{
		return $num && (boolean)$this->queryRow("SELECT id FROM goods WHERE `goods_num`={$this->quoteValue($num)}");
	}
	
	/**
	 * 检查商品名称
	 * @param		string		$title		商品名称
	 * @param		int			$id			商品ID
	 * @param		bool		$copy		复制
	 */
	public function checkTitle($title , $id , $copy = false)
	{
		$SQL = $id>0 ? ($copy ? " AND id={$id}" : " AND id!={$id}") : '';
		return $title && (boolean)$this->queryRow("SELECT id FROM goods WHERE merchant_id={$this->getMerchantID()} AND delete_id=0 AND `title`={$this->quoteValue($title)} {$SQL}");
	}
	
	/**
	 * 得到 分类的属性组
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 */
	public function getArgsInfo($one = 0 , $two = 0 , $three = 0)
	{
		return GlobalGoodsClass::getArgsInfo($one , $two , $three);
	}
	
	/**
	 * 获得用户的等级设定列表
	 * @param	int		$userType		用户类型 , 1=个人 , 2=企业 , 3=商家 , 0=全部
	 */
	public function getUserLayer($userType)
	{
		return GlobalUser::getLayerList($userType);
		#$SQL = $userType>0 ? "WHERE user_type={$userType}" : '';
		#return $this->queryAll("SELECT * FROM user_layer_setting {$SQL} ORDER BY user_type,start_exp ASC");
	}
	
	public function getBrandList()
	{
		$list = array();
		foreach (GlobalBrand::getAllList() as $id => $val)
			$list[$id] = join(' / ' , array_filter(array($val['en_name'] , $val['zh_name']))) . (empty($val['is_using'])?' (不可用)':'');
		return $list;
	}
	
	/**
	 * 获得商品上下架状态
	 */
	public function getShelfStatus()
	{
		return $this->_getStatusBase(410 , 419);
	}
	
	/**
	 * 获得商品审核状态
	 */
	public function getVerifyStatus()
	{
		return $this->_getStatusBase(400 , 410);
	}
	
	private function _getStatusBase($x , $y)
	{
		$status = GlobalStatus::getStatusMainList(4);
		ksort($status);
	
		$tmp = array();
		foreach ($status as $k => $v)
		{
			if ($k >= $x && $k < $y)
				$tmp[$k] = $v['back_title'];
		}
		return $tmp;
	}
	
	/**
	 * 列表
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getGoodsList(array $search , $copy , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
	
		if ($SQL = $this->_getListSQL($search , 'list' , $copy))
			return $this->queryAll($SQL . " ORDER BY last_time DESC LIMIT {$offset},{$rows}");
		else
			return array();
	}
	
	/**
	 * 得到列表的总数
	 */
	public function getGoodsCount(array $search , $copy = false)
	{
		$SQL = $this->_getListSQL($search , 'count' , $copy);
		return $SQL ? (int)$this->queryScalar($SQL) : 0;
	}
	
	private function _getListSQL(array $search , $type , $copy)
	{
		static $returned = array();
		if (isset($returned[$type]))
			return $returned[$type];
	
		$keyword	= trim($search['keyword']);
		$timeStart	= empty($search['timeStart']) ? '' : trim($search['timeStart']);
		$timeEnd	= empty($search['timeEnd']) ? '' : trim($search['timeEnd']);
		unset($search['keyword'] , $search['timeStart'] , $search['timeEnd']);
	
		$field = array('id' , 'brand_id' , 'title' , 'merchant_id' , 'shelf_id' , 'status_id', 'delete_id' , 'goods_num');
		$field = join(',', $field);
	
		$SQL = $copy===true ? 'WHERE 1' : 'WHERE delete_id=0 AND merchant_id='.$this->getMerchantID();
		foreach (array_filter($search) as $k => $v)
			$SQL .= " AND `{$k}`={$v}";
		
		if ($timeStart && $timeEnd)
		{
			$timeStart = strtotime($timeStart);
			$timeEnd = strtotime($timeEnd);
			$SQL .= " AND create_time>={$timeStart} AND create_time<{$timeEnd}";
		}
		
		if ($keyword)
		{
			$keyword = $this->quoteLikeValue($keyword);
			$SQL .= " AND (title LIKE {$keyword} OR goods_num LIKE {$keyword})";
		}
		
		$returned['count']	= "SELECT COUNT(*) FROM goods {$SQL}";
		$returned['list']	= "SELECT {$field} FROM goods {$SQL}";
		return isset($returned[$type]) ? $returned[$type] : '';
	}
}