<?php
class Cart extends WebApiModels
{
	/**
	 * 得到购物车中商品的列表
	 * @param		array		$cartData		商品 id
	 * @param		double		$totals			商品总价
	 */
	public function getCartList(array &$cartData , &$totals = 0)
	{
		$carts = empty($cartData['goods']) ? array() : $cartData['goods'];		# 购物车主数据
                $select = !empty($cartData['select']) ? $cartData['select'] : "";
		$ginfos = $uginfos = $redx = array();
		foreach ($carts as $mid => $merVal)
		{
			foreach ($merVal as $k => $vs)
			{
				if ($vs['type'] == 1)
					$ginfos[$vs['gid']] = $vs['gid'];
				else
					$uginfos[$vs['gid']] = $vs['gid'];
			}
		}
		if (empty($ginfos) && empty($uginfos))
			return array();

		$ginfos			= $this->getGoodsList($ginfos);
		$uginfos		= $this->getUsedGoodsList($uginfos);
		$userLayerID	= $this->getUserLayerID();
		foreach ($carts as $mid => $merVal)
		{
			foreach ($merVal as $k => $vs)
			{
				$finalTotal = 0;
				if ($vs['type'] == 1)
				{
					if (!empty($ginfos[$vs['gid']]))
					{
						$vm				= $ginfos[$vs['gid']];
						$vm['amount']	= $vs['amount'];
						$vm['type']		= 1;//商品
						$vm['attrs']	= GlobalGoods::getAttrsRows($vs['gid'] , $vs['attrs_1_unite_code'], $vs['attrs_2_unite_code'], $vs['attrs_3_unite_code'] , true);

						$core = $this->getGoodsCore($vm , $userLayerID);
						//商品没有库存,跳过
						if ($core['stock'] <=0 && $core['stock'] != -999)
						{
							unset($cartData['goods'][$mid][$k]);
							if (empty($cartData['goods'][$mid]))
								unset($cartData['goods'][$mid]);

							$cartData['cartNum'] -= 1;
							continue;
						}

						$vm['final_price']		= $core['price'];
						$vm['final_stock']		= $core['stock'];
						$vm['final_weight']		= $core['weight'];
						$vm['final_total']		= $finalTotal = $core['total'];

						$redx[$mid]['goods'][$k] = $vm;
					}
				}else{
					if (empty($uginfos[$vs['gid']]))
						continue;

					$vm				= $uginfos[$vs['gid']];
					$vm['amount']	= $vs['amount'];
					$vm['type']		= 2;//商品

					//商品没有库存,跳过
					if ($vm['stock'] <=0 && $vm['stock'] != -999)
					{
						unset($cartData['goods'][$mid][$k]);
						if (empty($cartData['goods'][$mid]))
							unset($cartData['goods'][$mid]);

						$cartData['cartNum'] -= 1;
						continue;
					}

					$vm['final_price']		= $vm['sale_price'];
					$vm['final_stock']		= $vm['stock'];
					$vm['final_weight']		= $vm['weight'];
					$vm['final_total']		= $finalTotal = ($vm['sale_price'] * $vs['amount']);
					$redx[$mid]['goods'][$k] = $vm;
				}

				$totals += $finalTotal;
			}
                        if(!empty($redx[$mid]['goods']))
                            $redx[$mid]['store_name'] = GlobalMerchant::getStoreName($mid);
		}
                $redx['select'] = $select;
		return $redx;
	}

	public function getGoodsList(array $goods)
	{
		if (!$goods)
			return array();

		$temp = array();
		foreach ($this->queryAll("
			SELECT
				id,class_one_id,class_two_id,class_three_id,merchant_id,title,cover,base_price,stock,
				weight,min_price,max_price,user_layer_ratio,amount_ratio,last_time
			FROM `goods` WHERE id IN (".join(',', $goods).") AND shelf_id=410 AND status_id=401 AND delete_id=0 ORDER BY merchant_id ASC") as $val)
		{
			$val['user_layer_ratio'] = $this->jsonDnCode($val['user_layer_ratio']);
			$val['amount_ratio'] = $this->jsonDnCode($val['amount_ratio']);
			$temp[$val['id']] = $val;
		}

		return $temp;
	}
	
	public function getUsedGoodsList(array $ugs)
	{
		if (!$ugs)
			return array();

		$temp = array();
		foreach ($this->queryAll("SELECT * FROM used_goods WHERE id IN (".join(',',$ugs).") AND shelf_id=1001 AND status_id=1013 AND delete_id=0") as $vs)
			$temp[$vs['id']] = $vs;
		return $temp;
	}
	/**
	 * 获得用户的等级ID
	 */
	private function getUserLayerID()
	{
		if ($user = $this->getUser())
			return GlobalUser::getUserLayerID($user['exp'] , $user['user_type']);

		return 0;
	}
	private function getGoodsCore(array $goods , $userLayerID)
	{
		$basePrice	= floatval(empty($goods['attrs']['base_price']) ? $goods['base_price'] : $goods['attrs']['base_price']);
		$stock		= intval(empty($goods['attrs']['stock']) ? $goods['stock'] : $goods['attrs']['stock']);
		$weight		= (empty($goods['attrs']['weight']) ? $goods['weight'] : $goods['attrs']['weight']);
		$amount		= intval($goods['amount']);
		$uRatio		= $this->getUserRatio($userLayerID , $goods['user_layer_ratio']);
		$aRatio		= $this->getAmountRatio($amount , $goods['amount_ratio']);

		$basePrice = $basePrice * min($uRatio , $aRatio);
		$basePrice = $basePrice ? $basePrice / 100 : 0;

		return array(
			'price'		=> $basePrice,
			'total'		=> $basePrice * $amount,
			'stock'		=> $stock,
			'weight'	=> $weight,
		);
	}
	private function getUserRatio($userLayerID , array $userRatio)
	{
		if ($userLayerID && isset($userRatio[$userLayerID]))
		{
			if (is_numeric($userRatio[$userLayerID]) && $userRatio[$userLayerID]<=100 && $userRatio[$userLayerID] >0)
				return $userRatio[$userLayerID];
		}
		return 100;
	}
	private function getAmountRatio($amount , array $amountRatio)
	{
		if (empty($amountRatio['s']) || empty($amountRatio['e']) || empty($amountRatio['p']))
			return 100;

		$lastRatio = 100;
		foreach ($amountRatio['s'] as $k => $v)
		{
			$lastRatio = min($lastRatio , (empty($amountRatio['p'][$k]) ? 100 : $amountRatio['p'][$k]));
			if ($amount>= $v && !empty($amountRatio['e'][$k]) && $amount < $amountRatio['e'][$k] && isset($amountRatio['p'][$k]))
				return floatval($amountRatio['p'][$k]);
		}
		return $lastRatio;
	}
}