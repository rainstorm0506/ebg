<?php
class Goods extends WebModels
{
	public function getCommentCount($gid)
	{
		return $this->queryScalar("SELECT COUNT(*) FROM order_comment WHERE goods_id={$gid}");
	}
	
	public function getCommentList($gid , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
		
		return $this->queryAll("SELECT * FROM order_comment WHERE goods_id={$gid} AND is_show=1 ORDER BY id DESC LIMIT {$offset},{$rows}");
	}
	
	public function getUserGroup(array $_uid , $privacy = false)
	{
		return GlobalUser::getUserGroup($_uid , $privacy);
	}
	
	/**
	 * 点赞
	 * 
	 * @param		int		$gid		商品ID
	 * @param		int		$type		类别 , 1=商品 , 2=二手 , 3=积分 , 4=店铺
	 * @param		int		$uid		用户ID
	 */
	public function praise($gid , $type , $uid)
	{
		if ($gid<1 || $type<1 || $uid<1)
			return 0;
		
		if ((bool)$this->queryScalar("SELECT uid FROM user_praise WHERE uid={$uid} AND praise_type={$type} AND praise_id={$gid}"))
		{
			return -1;
		}else{
			$this->insert('user_praise' , array('uid'=>$uid,'praise_type'=>$type,'praise_id'=>$gid,'praise_time'=>time()));
			return (int)$this->execute("UPDATE goods SET praise=praise+1 WHERE id={$gid}");
		}
	}
	
	public function getGoodsAttrs(array $args , array $goodsAttrs , $one , $two , $three)
	{
		if (!$goodsAttrs)
			return array();
		
		if (empty($args['attrs']))
			return array();
		
		if (!$classAttrs = GlobalGoodsAttrs::getClassAttrs($one , $two , $three))
			return array();
		
		$temp = array();
		foreach ($args['attrs'] as $ak => $av)
		{
			if (empty($classAttrs[$ak]))
				continue;
			$temp[$ak]['title'] = $classAttrs[$ak]['title'];
			foreach ($av as $bk => $bv)
			{
				if (empty($classAttrs[$ak]['child'][$bk]))
					continue;
				$temp[$ak]['child'][$bk] = $classAttrs[$ak]['child'][$bk]['title'];
			}
			
			if (empty($temp[$ak]['child']))
				unset($temp[$ak]);
		}
		#print_r($goodsAttrs);exit;
		return $temp;
	}
	
	/**
	 * 获得商品的用户等级折扣比率
	 * @param		array		$userLayerRatio		商品的用户等级设定
	 * @return		number
	 */
	public function getUserRatio(array $userLayerRatio)
	{
		if ($this->getUid() && !empty($userLayerRatio))
		{
			$user = $this->getUser();
			return GlobalGoods::getUserRatio($user['exp'] , $user['user_type'] , $userLayerRatio);
		}else{
			return 100;
		}
	}
	
	public function getJoinGoods(array $gidGroup)
	{
		if (empty($gidGroup))
			return array();
		
		$returned = $this->queryAll("
			SELECT id,is_self,title,cover,praise,base_price,min_price,max_price,user_layer_ratio
			FROM `goods` WHERE id IN (".join(',', $gidGroup).")");
		return (count($returned) == 4) ? $returned : array();
	}
	
	/**
	 * 获得商品的数据
	 * 
	 * @param		int		$gid		商品ID
	 */
	public function getGoodsInfo($gid)
	{
		return $this->queryRow("SELECT * FROM goods WHERE id={$gid} AND shelf_id=410 AND status_id=401 AND delete_id=0");
	}
	
	public function getSnapshoots(array $vers)
	{
		if ($shoots = $this->queryRow("SELECT goods_type,goods_id,unit_price,goods_attrs,goods_cover,goods_title FROM order_goods WHERE id={$vers[3]}"))
		{
			$shoots['goods_attrs']	= $this->jsonDnCode($shoots['goods_attrs']);
			$shoots['content']		= '';
			$shoots['args']			= array();
			$shoots['merchant_id']	= 0;
			if ($vers[1] == 1)
			{
				if ($obj = $this->queryScalar("SELECT vers_text FROM goods_versions WHERE goods_id={$vers[2]} AND vers_num={$vers[4]}"))
				{
					$obj					= @unserialize($obj);
					$shoots['content']		= isset($obj['goods']['content']) ? $obj['goods']['content'] : '';
					$shoots['args']			= isset($obj['goods']['args']) ? $this->jsonDnCode($obj['goods']['args']) : array();
					$shoots['merchant_id']	= isset($obj['goods']['merchant_id']) ? (int)$obj['goods']['merchant_id'] : 0;
				}
			}else{
				if ($obj = $this->queryScalar("SELECT vers_text FROM used_goods_versions WHERE goods_id={$vers[2]} AND vers_num={$vers[4]}"))
				{
					$obj					= @unserialize($obj);
					$shoots['content']		= isset($obj['goods']['content']) ? $obj['goods']['content'] : '';
					$shoots['merchant_id']	= isset($obj['goods']['merchant_id']) ? (int)$obj['goods']['merchant_id'] : 0;
				}
			}
		}
		return $shoots;
	}
	
	public function getGoodsPic($gid)
	{
		$returned = array();
		foreach ($this->queryAll("SELECT * FROM `goods_photo` WHERE goods_id={$gid} order BY attrs_unite_code DESC,rank ASC") as $val)
			$returned[$val['attrs_unite_code']][$val['rank']] = $val['src'];
		
		return $returned;
	}
	//获取某商品的所有属性情况下的京东价格
	public function getJdprice($id)
	{
		$row=$this->queryAll("SELECT jd_id,jd_price FROM goods_join_attrs WHERE goods_id=$id");
		$data=array();
		if($row)
		{
			foreach($row as $v)
			{
				$data[$v['jd_id']]=$v['jd_price'];
			}
		}
		return $data;
	}
}