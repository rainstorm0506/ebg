<?php

/**
 * User: 谭甜
 * Date: 2016/1/19
 * Time: 10:22
 */
class PointsGoods extends SModels
{
	//列表统计
	public function getLIstCount($search)
	{
		$where = empty($search['keyword']) ? '' : "WHERE title LIKE '{$search['keyword']}' OR goods_num LIKE '{$search['keyword']}'";
		return (int)$this->queryScalar("SELECT COUNT(*) FROM points_goods {$where}");
	}

	//列表
	public function getList($search, $offset, $rows, $total, array $schema = array())
	{
		$where = empty($search['keyword']) ? '' : "WHERE g.title LIKE '{$search['keyword']}' OR g.goods_num LIKE '{$search['keyword']}'";
		return $this->queryAll("SELECT g.*,seo.seo_title FROM points_goods AS g
			LEFT JOIN seo ON seo.code='{$search['SEOCODE']}' AND seo.id=g.id
			{$where} ORDER BY id DESC LIMIT {$offset},{$rows}");
	}

	/**
	 *检查商品货号
	 */
	public function checkGoodsNum($goods_num)
	{
		return $goods_num && (boolean)$this->queryRow("SELECT id FROM points_goods WHERE `goods_num`={$this->quoteValue($goods_num)}");
	}

	//商品名称验证
	public function checkTitle($title, $id)
	{
		if (!$title)
		{
			return false;
		}

		$SQL = $id > 0 ? " AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM points_goods WHERE title={$this->quoteValue($title)} {$SQL}");
	}

	/**
	 * 获得默认的产品编号
	 */
	public function getDefaultNum()
	{
#-------------------------此数组请勿改动-----------------------------------------------
		$range = array(
			0 => 2,
			1 => 4,
			2 => 6,
			3 => 1,
			4 => 5,
			5 => 9,
			6 => 7,
			7 => 3,
			8 => 0,
			9 => 8
		);

		$code = 'CP' . mt_rand(0, 9);
		foreach (str_split(time()) as $k => $v)
			$code .= (($k && $k % 4 == 0) ? mt_rand(0, 9) : '') . $range[$v];
		return $code;
	}

	//添加
	public function create(array $post)
	{
		$classOneID = (int)$post['class_one_id'];
		$classTwoID = (int)$post['class_two_id'];
		$classThreeID = (int)$post['class_three_id'];
		$brandID = (int)$post['brand_id'];

		$goodsNum = trim($post['goods_num']);
		$goodsNum = $goodsNum ? $goodsNum : $this->getDefaultNum();
		$time = time();

		$attrs = array(
			'attrs' => !empty($post['attrs']) && is_array($post['attrs']) ? $post['attrs'] : array(),
			'attrVal' => !empty($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array(),
		);
		$goods = array(
			'title' => trim($post['title']),
			'points' => (int)$post['points'],
			'person' => isset($post['person']) ? (int)$post['person'] : 0,
			'merchant' => isset($post['merchant']) ? (int)$post['merchant'] : 0,
			'company' => isset($post['company']) ? (int)$post['company'] : 0,
			'goods_num' => $goodsNum,
			'brand_id' => $brandID,
			'class_one_id' => $classOneID,
			'class_two_id' => $classTwoID,
			'class_three_id' => $classThreeID,
			'shelf_id' => $post['shelf_id'],
			'remark' => $post['remark'],
			'content' => trim($post['content']),
			'args' => json_encode(!empty($post['args']) && is_array($post['args']) ? $post['args'] : array()),
			'attrs' => json_encode($attrs),
			'create_time' => $time,
			'shelf_time' => $post['shelf_id'] == 1 ? $time : 0,
			'cover' => $this->getPhotos($post['cover'], 'points', 0)
		);

		if (empty($post['attrVal']))
		{
			$goods['weight'] = isset($post['weight']) ? (double)$post['weight'] : 0;
			if ((int)$post['attrInStock'] === 1)
			{
				$goods['stock'] = -999;
			}
			else
			{
				$goods['stock'] = isset($post['stock']) ? (int)$post['stock'] : 0;
			}
		}
		else
		{
			$str = (int)'';
			if (isset($post['attrVal']['stock']))
			{
				foreach ($post['attrVal']['stock'] as $v1)
				{
					if (is_array($v1))
					{
						foreach ($v1 as $v2)
						{
							if (is_array($v2))
							{
								foreach ($v2 as $v)
								{
									$str = (int)$str + (int)$v;
								}
							}
							else
							{
								$str = (int)$str + (int)$v2;
							}
						}
					}
					else
					{
						$str = (int)$str + (int)$v1;
					}
				}
			}
			$wx = (int)'';
			if (isset($post['attrVal']['inStock']))
			{
				foreach ($post['attrVal']['inStock'] as $v1)
				{
					if (is_array($v1))
					{
						foreach ($v1 as $v2)
						{
							if (is_array($v2))
							{
								foreach ($v2 as $v)
								{
									$wx = (int)$wx + (int)$v;
								}
							}
							else
							{
								$wx = (int)$wx + (int)$v2;
							}
						}
					}
					else
					{
						$wx = (int)$wx + (int)$v1;
					}
				}
			}
			$goods['stock'] = $wx == 0 ? $str : -999;
		}
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//商品
			$this->insert('points_goods', $goods);
			$gid = $this->getInsertId();

			//商品图片组
			foreach ($post['img'] as $k => $v)
			{
				if (!empty($v))
				{
					{
						$arr1 = array(
							'src' => $this->getPhotos($v, 'points', 0),
							'rank' => $k,
							'goods_id' => $gid
						);
						$this->insert('points_goods_photo', $arr1);
					}
				}

			}
			//商品属性
			$goodsAttrs = $this->_set_attrs($gid, $classOneID, $classTwoID, $classThreeID, (isset($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array()));
			foreach ($goodsAttrs as $v)
			{
				$this->insert('points_goods_attrs', $v);
			}

			$transaction->commit();
			return true;
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			return false;
		}
	}

	//设定属性
	private function _set_attrs($gid, $classOneID, $classTwoID, $classThreeID, array $attrVal)
	{
		if (!$gid)
		{
			return array();
		}

		$this->delete('points_goods_attrs', "goods_id={$gid}");

		$model = ClassLoad::Only('GoodsAttrs');
		/* @var $model GoodsAttrs */
		if (!$classAttrs = $model->getAttrsKeyValue($classOneID, $classTwoID, $classThreeID))
		{
			return array();
		}

		$inStock = !empty($attrVal['inStock']) && is_array($attrVal['inStock']) ? $attrVal['inStock'] : array();
		$stock = !empty($attrVal['stock']) && is_array($attrVal['stock']) ? $attrVal['stock'] : array();
		$weight = !empty($attrVal['weight']) && is_array($attrVal['weight']) ? $attrVal['weight'] : array();

		$attrs = array();
		foreach ($weight as $ak => $av)
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
							$_s = ($_is > 0 || $_is === -999) ? -999 : (empty($stock[$ak][$bk][$ck]) ? 0 : (int)$stock[$ak][$bk][$ck]);
							$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];
							$v2 = empty($classAttrs[$bk]) ? '' : $classAttrs[$bk];
							$v3 = empty($classAttrs[$ck]) ? '' : $classAttrs[$ck];

							if (!$_p || !$_s || !$_w || !$v1 || !$v2 || !$v3)
							{
								continue;
							}

							$attrs[] = array(
								'key_code' => md5($gid . $ak . $bk . $ck),
								'goods_id' => $gid,
								'attrs_1_unite_code' => $ak,
								'attrs_2_unite_code' => $bk,
								'attrs_3_unite_code' => $ck,
								'attrs_1_value' => $v1,
								'attrs_2_value' => $v2,
								'attrs_3_value' => $v3,
								'stock' => $_s,
								'weight' => $_w,
							);
						}
					}
					else
					{
						//二层结构
						$_is = empty($inStock[$ak][$bk]) ? 0 : (int)$inStock[$ak][$bk];
						$_p = (double)$bv;
						$_w = empty($weight[$ak][$bk]) ? 0 : $weight[$ak][$bk];
						$_s = ($_is > 0 || $_is === -999) ? -999 : (empty($stock[$ak][$bk]) ? 0 : (int)$stock[$ak][$bk]);
						$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];
						$v2 = empty($classAttrs[$bk]) ? '' : $classAttrs[$bk];

						if (!$_p || !$_s || !$_w || !$v1 || !$v2)
						{
							continue;
						}

						$attrs[] = array(
							'key_code' => md5($gid . $ak . $bk),
							'goods_id' => $gid,
							'attrs_1_unite_code' => $ak,
							'attrs_2_unite_code' => $bk,
							'attrs_3_unite_code' => '',
							'attrs_1_value' => $v1,
							'attrs_2_value' => $v2,
							'attrs_3_value' => '',
							'stock' => $_s,
							'weight' => $_w,
						);
					}
				}
			}
			else
			{
				//一层结构
				$_is = empty($inStock[$ak]) ? 0 : (int)$inStock[$ak];
				$_p = (double)$av;
				$_w = empty($weight[$ak]) ? 0 : $weight[$ak];
				$_s = ($_is > 0 || $_is === -999) ? -999 : (empty($stock[$ak]) ? 0 : (int)$stock[$ak]);
				$v1 = empty($classAttrs[$ak]) ? '' : $classAttrs[$ak];

				if (!$_p || !$_s || !$_w || !$v1)
				{
					continue;
				}

				$attrs[] = array(
					'key_code' => md5($gid . $ak),
					'goods_id' => $gid,
					'attrs_1_unite_code' => $ak,
					'attrs_2_unite_code' => '',
					'attrs_3_unite_code' => '',
					'attrs_1_value' => $v1,
					'attrs_2_value' => '',
					'attrs_3_value' => '',
					'stock' => $_s,
					'weight' => $_w,
				);
			}
		}
		return $attrs;
	}

	//删除
	public function clear($id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			/**
			 *删除商品
			 */
			$this->delete('points_goods', 'id=' . $id);
			/**
			 *删除商品图片
			 */
			$this->delete('points_goods_photo', 'goods_id=' . $id);
			/**
			 *删除商品属性
			 */
			$this->delete('points_goods_attrs', 'goods_id=' . $id);
			$transaction->commit();
			return true;
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			return false;
		}
	}

	//详情
	public function intro($id)
	{
		if ($tmp = $this->queryRow("SELECT * FROM points_goods WHERE id={$id}"))
		{
			$tmp['args'] = $this->jsonDnCode($tmp['args']);
			$tmp['attrs'] = $this->jsonDnCode($tmp['attrs']);
			$tmp['img'] = $this->queryColumn("SELECT `src` FROM points_goods_photo WHERE goods_id={$id}");
		}
		return $tmp;
	}

	//编辑
	public function Modify(array $post)
	{
		$classOneID = (int)$post['class_one_id'];
		$classTwoID = (int)$post['class_two_id'];
		$classThreeID = (int)$post['class_three_id'];
		$brandID = (int)$post['brand_id'];
		$id = (int)$post['id'];
		$time = time();
		$attrs = array(
			'attrs' => !empty($post['attrs']) && is_array($post['attrs']) ? $post['attrs'] : array(),
			'attrVal' => !empty($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array(),
		);
		$goods = array(
			'title' => trim($post['title']),
			'points' => (int)$post['points'],
			'person' => isset($post['person']) ? (int)$post['person'] : 0,
			'merchant' => isset($post['merchant']) ? (int)$post['merchant'] : 0,
			'company' => isset($post['company']) ? (int)$post['company'] : 0,
			'brand_id' => $brandID,
			'class_one_id' => $classOneID,
			'class_two_id' => $classTwoID,
			'class_three_id' => $classThreeID,
			'shelf_id' => $post['shelf_id'],
			'remark' => $post['remark'],
			'content' => trim($post['content']),
			'args' => json_encode(!empty($post['args']) && is_array($post['args']) ? $post['args'] : array()),
			'attrs' => json_encode($attrs),
			'cover' => $this->getPhotos($post['cover'], 'points', 0),
		);
		if ($post['shelf_id'] == 1)
		{
			$goods['shelf_time'] = $time;
		}
		if (empty($post['attrVal']))
		{
			$goods['weight'] = isset($post['weight']) ? (double)$post['weight'] : 0;
			if ((int)$post['attrInStock'] === 1)
			{
				$goods['stock'] = -999;
			}
			else
			{
				$goods['stock'] = isset($post['stock']) ? (int)$post['stock'] : 0;
			}
		}
		else
		{
			$str = (int)'';
			foreach ($post['attrVal']['stock'] as $v1)
			{
				if (is_array($v1))
				{
					foreach ($v1 as $v2)
					{
						if (is_array($v2))
						{
							foreach ($v2 as $v)
							{
								$str = (int)$str + (int)$v;
							}
						}
						else
						{
							$str = (int)$str + (int)$v2;
						}
					}
				}
				else
				{
					$str = (int)$str + (int)$v1;
				}
			}
			$wx = (int)'';
			foreach ($post['attrVal']['inStock'] as $v1)
			{
				if (is_array($v1))
				{
					foreach ($v1 as $v2)
					{
						if (is_array($v2))
						{
							foreach ($v2 as $v)
							{
								$wx = (int)$wx + (int)$v;
							}
						}
						else
						{
							$wx = (int)$wx + (int)$v2;
						}
					}
				}
				else
				{
					$wx = (int)$wx + (int)$v1;
				}
			}
			$goods['stock'] = $wx == 0 ? $str : -999;
		}
		$this->update('points_goods', $goods, 'id=' . $id);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('points_goods_photo', 'goods_id=' . $id);
			//商品图片组
			foreach ($post['img'] as $k => $v)
			{
				if (!empty($v))
				{
					{
						$arr1 = array(
							'src' => $this->getPhotos($v, 'points', 0),
							'rank' => $k,
							'goods_id' => $id
						);
						$this->insert('points_goods_photo', $arr1);
					}
				}
			}
			//商品属性
			$goodsAttrs = $this->_set_attrs($id, $classOneID, $classTwoID, $classThreeID, (isset($post['attrVal']) && is_array($post['attrVal']) ? $post['attrVal'] : array()));
			foreach ($goodsAttrs as $v)
			{
				$this->insert('points_goods_attrs', $v);
			}

			$transaction->commit();
			return true;
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			return false;
		}
	}

	//获取商品属性值
	public function getGoodsAttr($id)
	{
		return $this->queryAll("SELECT * FROM points_goods_attrs WHERE goods_id={$id}");
	}

	//上下架
	public function handleShelf($shelf, $id)
	{
		if ($shelf == 1101)
		{
			$arr = array(
				'shelf_id' => 1102
			);
			$data = array(
				'shelf' => 1102
			);
		}
		if ($shelf == 1102)
		{
			$arr = array(
				'shelf_id' => 1101,
				'shelf_time' => time()
			);
			$data = array(
				'shelf' => 1101
			);
		}
		if ($this->update('points_goods', $arr, 'id=' . $id))
		{
			return $data;
		}

		return array();
	}
}