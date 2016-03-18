<?php
Yii::import('system.extensions.splitword.SplitWord');
class GoodsApi extends ApiModels
{
	#商品主图片键名
	const GOODSMAINPICKEY = 'main';
	
	public function goodsAudit($gid , $action)
	{
		if ($action == 1)
		{
			return $this->update('goods' , array('shelf_id'=>410 , 'shelf_time'=>time()) , "id={$gid} AND merchant_id={$this->getMerchantID()}");
		}else{
			return $this->update('goods' , array('shelf_id'=>411 , 'shelf_time'=>0) , "id={$gid} AND merchant_id={$this->getMerchantID()}");
		}
	}
	
	/**
	 * 商品总数
	 * 
	 * @param		CFormModel		$form		from
	 */
	public function getGoodsCount(CFormModel $form)
	{
		$SQL = '';
		switch ($form->status)
		{
			case 1 : $SQL .= " AND g.status_id=400";break;
			case 2 : $SQL .= " AND g.status_id=401";break;
			case 3 : $SQL .= " AND g.status_id=402";break;
			case 4 : $SQL .= " AND g.shelf_id=410";break;
			case 5 : $SQL .= " AND g.shelf_id=411";break;
		}
		$SQL .= $form->type==1 ? " AND g.merchant_id={$this->getMerchantID()}" : '';
		$SQL .= $form->type==3&&$form->mid>0 ? " AND g.merchant_id={$form->mid}" : '';
		
		if (trim($form->keyword))
		{
			$spWord = ClassLoad::Only('SplitWord');/* @var $spWord SplitWord */
			$spWord->SetSource($form->keyword);
			$spWord->SetResultType(2);
			$spWord->StartAnalysis(true);
			
			$word = array();
			foreach ($spWord->GetFinallyResultArray(false) as $w => $num)
				$word[] = sprintf('%u' , crc32($w));
			
			if (!$word)
				return array();
			
			return $this->queryScalar("
				SELECT COUNT(*)
				FROM goods AS g
				INNER JOIN search_tag AS st ON g.id=st.gid AND st.type=2 AND st.word_crc32 IN (".join(',' , $word).")
				WHERE 1 {$SQL} GROUP BY g.id");
		}else{
			return $this->queryScalar("SELECT COUNT(*) FROM goods AS g WHERE 1 {$SQL}");
		}
	}
	
	/**
	 * 商品列表
	 * @param		CFormModel		$form		from
	 * @param		CPagination		$page		pege
	 * @param		int				$_p			页码
	 */
	public function getGoodsList(CFormModel $form , CPagination $page , $_p)
	{
		if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
			return array();
	
		$SQL = '';
		switch ($form->status)
		{
			case 1 : $SQL .= " AND g.status_id=400";break;
			case 2 : $SQL .= " AND g.status_id=401";break;
			case 3 : $SQL .= " AND g.status_id=402";break;
			case 4 : $SQL .= " AND g.shelf_id=410";break;
			case 5 : $SQL .= " AND g.shelf_id=411";break;
		}
		$SQL .= $form->type==1 ? " AND g.merchant_id={$this->getMerchantID()}" : '';
		$SQL .= $form->type==3&&$form->mid>0 ? " AND g.merchant_id={$form->mid}" : '';
		
		$SQL .= " AND g.delete_id=0  GROUP BY g.id ORDER BY g.last_time DESC";
		
		$findStr = 'g.id,g.is_self,g.title,g.vice_title,g.cover,g.retail_price,g.base_price,g.min_price,g.max_price,g.shelf_id,g.status_id,g.goods_num,g.sales,g.stock';
		$querys = '';
		if (trim($form->keyword))
		{
			$spWord = ClassLoad::Only('SplitWord');/* @var $spWord SplitWord */
			$spWord->SetSource($form->keyword);
			$spWord->SetResultType(2);
			$spWord->StartAnalysis(true);
			
			$word = array();
			foreach ($spWord->GetFinallyResultArray(false) as $w => $num)
				$word[] = sprintf('%u' , crc32($w));
			
			if (!$word)
				return array();
			
			$querys = "
				SELECT {$findStr}
				FROM goods AS g
				INNER JOIN search_tag AS st ON g.id=st.gid AND st.type=2 AND st.word_crc32 IN (".join(',' , $word).")
				WHERE 1 {$SQL} LIMIT {$page->getOffset()},{$page->getLimit()}";
		}else{
			$querys = "SELECT {$findStr} FROM goods AS g WHERE 1 {$SQL} LIMIT {$page->getOffset()},{$page->getLimit()}";
		}
		
		//计算真实的库存
		$datas = $gids = array();
		foreach ($this->queryAll($querys) as $val)
		{
			if ($val['min_price']>0 && $val['max_price']>0)
				$gids[$val['id']] = $val['id'];
			
			$datas[$val['id']] = $val;
		}
		
		//如果存在属性 , 计算属性库存的总数
		if ($gids)
		{
			foreach ($this->queryAll('SELECT goods_id,(SUM(stock)/COUNT(*))=-999 AS infinite,SUM(IF(stock!=-999,stock,0)) AS stock FROM `goods_join_attrs` WHERE goods_id IN ('.join(',' , $gids).') GROUP BY goods_id') as $gas)
			{
				if (isset($datas[$gas['goods_id']]))
					$datas[$gas['goods_id']]['stock'] = $gas['infinite']==1 ? -999 : $gas['stock'];
			}
		}
		return array_values($datas);
	}
	
	public function goodsUpdate(CFormModel $form)
	{
		$classOneID		= (int)$form->class_one_id;
		$classTwoID		= (int)$form->class_two_id;
		$classThreeID	= (int)$form->class_three_id;
		$merchantID		= $this->getMerchantID();
		$brandID		= (int)$form->brand_id;
		$gid			= (int)$form->gid;
		$time			= time();
		$goodsDatas		= $form->type == 2 && $gid ? $this->getGoodsInfo($gid , false) : array();
		
		$attrs = array(
			'attrs'		=> $form->attrs,
			'attrVal'	=> $form->attrVal,
			'imgsSet'	=> '',
		);
		$goods = array(
			'title'				=> trim($form->title),
			'vice_title'		=> trim($form->vice_title),
			'brand_id'			=> $brandID,
			'class_one_id'		=> $classOneID,
			'class_two_id'		=> $classTwoID,
			'class_three_id'	=> $classThreeID,
			'shelf_id'			=> 411,
			'status_id'			=> 400,
			'delete_id'			=> 0,
			'retail_price'		=> (double)$form->retail_price,
			'base_price'		=> 0,
			'weight'			=> 0,
			'stock'				=> 0,
			'min_price'			=> 0,
			'max_price'			=> 0,
			'last_time'			=> $time,
			'amount_ratio'		=> json_encode($form->amount),
			'user_layer_ratio'	=> json_encode($form->userLayer),
			'attrs'				=> json_encode($attrs),
			'cover'				=> $this->getPhotos($form->cover , 'goods' , $merchantID)
		);
		
		//添加 or 复制
		if ($form->type != 2)
		{
			$goodsNum				= trim($form->goods_num);
			$goods['create_time']	= $time;
			$goods['goods_num']		= $goodsNum ? $goodsNum : GlobalGoods::getDefaultNum();
			$goods['merchant_id']	= $merchantID;
			$goods['is_self']		= (int)GlobalMerchant::isSelfMerchant($merchantID);
		}
		
		if (empty($form->attrVal))
		{
			$goods['base_price']	= (double)$form->base_price;
			$goods['weight']		= (double)$form->weight;
			$goods['stock']			= $form->stock > 0 ? $form->stock : ($form->stock==-999?-999:0);
		}
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//商品编辑
			if ($form->type == 2)
			{
				$this->update('goods' , $goods , "id={$gid}");
				
				//品牌商品+1
				if ($brandID != $goodsDatas['brand_id'])
				{
					$this->execute("UPDATE goods_brand SET goods_num=goods_num+1 WHERE id={$goodsDatas['brand_id']}");
					$this->execute("UPDATE goods_brand SET goods_num=goods_num-1 WHERE id={$brandID}");
				}
			}else{
			//添加 or 复制
				$this->insert('goods' , $goods);
				$gid = $this->getInsertId();
				
				//品牌商品+1
				$this->execute("UPDATE goods_brand SET goods_num=goods_num+1 WHERE id={$brandID}");
			}
			
			GlobalSplitWord::setWord(2 , $gid , array($goods['title'] , $goods['vice_title']) , true);
			
			//主图片组
			$picMain = $this->_set_main_photo($gid , $merchantID , $form->img);
			foreach ($picMain as $v)
				$this->insert('goods_photo' , $v);
			
			//商品属性
			$minPrice = $maxPrice = 0;
			$goodsAttrs = $this->_set_attrs($gid , $classOneID , $classTwoID , $classThreeID , $form->attrVal);
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
					'picGroup'		=> array(),
					'goodsAttrs'	=> $goodsAttrs
				))
			));
			
			$transaction->commit();
			return $gid;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	/**
	 * 获得商品信息
	 *
	 * @param		int		$gid		商品ID
	 * @param		bool	$attrVal	是否更新 attrs.attrVal中的数据
	 */
	public function getGoodsInfo($gid , $attrVal = false)
	{
		if ($gid < 1)
			return array();
		
		if ($tmp = $this->queryRow("SELECT * FROM goods WHERE id={$gid}"))
		{
			$tmp['amount_ratio']		= $this->jsonDnCode($tmp['amount_ratio']);
			$tmp['user_layer_ratio']	= $this->jsonDnCode($tmp['user_layer_ratio']);
			$tmp['join_goods']			= $this->jsonDnCode($tmp['join_goods']);
			$tmp['attrs']				= $this->jsonDnCode($tmp['attrs']);
			unset($tmp['args']);
			
			$ats = array();
			if (!empty($tmp['attrs']['attrs']))
			{
				foreach ($tmp['attrs']['attrs'] as $k => $v)
				{
					if (empty($v))
						continue;
					
					$child = array();
					foreach ($v as $kb => $vb)
						$child[] = array('id'=>$kb , 'value'=>$vb);
					
					$ats['attrs'][] = array('id'=>$k , 'child'=>$child);
				}
			}
			
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
							$tmp['attrs']['attrVal']['stock'][$code1][$code2][$code3] = -999;
						}else{
							$tmp['attrs']['attrVal']['stock'][$code1][$code2][$code3] = $asv['stock'];
						}
					}elseif ($code1 && $code2){
						if ($asv['stock'] == -999)
						{
							$tmp['attrs']['attrVal']['stock'][$code1][$code2] = -999;
						}else{
							$tmp['attrs']['attrVal']['stock'][$code1][$code2] = $asv['stock'];
						}
					}elseif ($code1){
						if ($asv['stock'] == -999)
						{
							$tmp['attrs']['attrVal']['stock'][$code1] = -999;
						}else{
							$tmp['attrs']['attrVal']['stock'][$code1] = $asv['stock'];
						}
					}
				}
				unset($tmp['attrs']['attrVal']['inStock']);
				
				if (!empty($tmp['attrs']['attrVal']['price']) && !empty($tmp['attrs']['attrVal']['stock']) && !empty($tmp['attrs']['attrVal']['weight']))
				{
					$ai = $bi = $ci = -1;
					foreach ($tmp['attrs']['attrVal']['price'] as $ak => $av)
					{
						$ai++;
						if (is_array($av))
						{
							$bi = -1;
							foreach ($av as $bk => $bv)
							{
								$bi++;
								if (is_array($bv))
								{
									$ci = -1;
									foreach ($bv as $ck => $cv)
									{
										$ci++;
										$ats['attrVal']['price'][$ai]['id'] = $ak;
										$ats['attrVal']['price'][$ai]['child'][$bi]['id'] = $bk;
										$ats['attrVal']['price'][$ai]['child'][$bi]['child'][$ci] = array('id'=>$ck , 'value'=>$cv);
										
										$ats['attrVal']['stock'][$ai]['id'] = $ak;
										$ats['attrVal']['stock'][$ai]['child'][$bi]['id'] = $bk;
										$ats['attrVal']['stock'][$ai]['child'][$bi]['child'][$ci] = array('id'=>$ck , 'value'=>$tmp['attrs']['attrVal']['stock'][$ak][$bk][$ck]);
										
										$ats['attrVal']['weight'][$ai]['id'] = $ak;
										$ats['attrVal']['weight'][$ai]['child'][$bi]['id'] = $bk;
										$ats['attrVal']['weight'][$ai]['child'][$bi]['child'][$ci] = array('id'=>$ck , 'value'=>$tmp['attrs']['attrVal']['weight'][$ak][$bk][$ck]);
									}
								}else{
									$ats['attrVal']['price'][$ai]['id'] = $ak;
									$ats['attrVal']['price'][$ai]['child'][$bi] = array('id'=>$bk , 'value'=>$bv);
									
									$ats['attrVal']['stock'][$ai]['id'] = $ak;
									$ats['attrVal']['stock'][$ai]['child'][$bi] = array('id'=>$bk , 'value'=>$tmp['attrs']['attrVal']['stock'][$ak][$bk]);
									
									$ats['attrVal']['weight'][$ai]['id'] = $ak;
									$ats['attrVal']['weight'][$ai]['child'][$bi] = array('id'=>$bk , 'value'=>$tmp['attrs']['attrVal']['weight'][$ak][$bk]);
								}
							}
						}else{
							$ats['attrVal']['price'][$ai] = array('id'=>$ak , 'value'=>$av);
							$ats['attrVal']['stock'][$ai] = array('id'=>$ak , 'value'=>$tmp['attrs']['attrVal']['stock'][$ak]);
							$ats['attrVal']['weight'][$ai] = array('id'=>$ak , 'value'=>$tmp['attrs']['attrVal']['weight'][$ak]);
						}
					}
				}
			}
			
			$tmp['attrs'] = $ats;
		}
		return $tmp;
	}
	
	public function getGoodsPic($gid)
	{
		$key = self::GOODSMAINPICKEY;
		return $this->queryColumn("SELECT src FROM goods_photo WHERE goods_id={$gid} AND attrs_unite_code='{$key}' ORDER BY rank ASC");
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
							);
						}
					}else{
						//二层结构
						$_is = empty($inStock[$ak][$bk]) ? 0 : (int)$inStock[$ak][$bk];
						$_p = (double)$bv;
						$_w = empty($weight[$ak][$bk]) ? 0 : $weight[$ak][$bk];
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
						);
					}
				}
			}else{
				//一层结构
				$_is = empty($inStock[$ak]) ? 0 : (int)$inStock[$ak];
				$_p = (double)$av;
				$_w = empty($weight[$ak]) ? 0 : $weight[$ak];
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
				);
			}
		}
		return $attrs;
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
	 * @param		int			$gid		商品ID
	 */
	public function checkGoodsNum($num , $gid = 0)
	{
		if (!$num)
			return false;
		
		$gid = $gid > 0 ? "AND id!={$gid}" : '';
		return $num && (boolean)$this->queryRow("SELECT id FROM goods WHERE `goods_num`={$this->quoteValue($num)} {$gid}");
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
	
	public function getClassAttrs($oneID , $twoID , $threeID)
	{
		$temp = array();
		$k = -1;
		foreach (GlobalGoodsAttrs::getClassAttrs($oneID , $twoID , $threeID) as $v)
		{
			if (empty($v['child']))
				continue;
			
			$k++;
			$temp[$k] = array('unite_code'=>$v['unite_code'] , 'title'=>$v['title']);
			foreach ($v['child'] as $cv)
				$temp[$k]['child'][] = array('unite_code'=>$cv['unite_code'] , 'title'=>$cv['title']);
		}
		return $temp;
	}
}