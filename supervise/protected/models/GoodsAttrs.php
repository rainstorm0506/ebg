<?php
/**
 * 商品分类属性
 *
 * @author 涂先锋
 */
class GoodsAttrs extends SModels
{
	/**
	 * 列表
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getAttrsList($offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
		
		if ($list = $this->queryAll("SELECT * FROM goods_attrs WHERE parent_unite_code='' GROUP BY class_one_id,class_two_id,class_three_id ASC ORDER BY rank ASC LIMIT {$offset},{$rows}") )
		{
			$group = $tmp = array();
			foreach ($list as $val)
			{
				$group['one'][$val['class_one_id']] = $val['class_one_id'];
				$group['two'][$val['class_two_id']] = $val['class_two_id'];
				$group['three'][$val['class_three_id']] = $val['class_three_id'];
					
				$key = $val['class_one_id'].'.'.$val['class_two_id'].'.'.$val['class_three_id'];
				$val['title'] = array();
				$tmp[$key] = $val;
			}
			
			foreach ($this->queryAll("
					SELECT * FROM goods_attrs
					WHERE parent_unite_code=''
						AND class_one_id IN (".join(',', $group['one']).")
						AND class_two_id IN (".join(',', $group['two']).")
						AND class_three_id IN (".join(',', $group['three']).")
					ORDER BY rank ASC LIMIT {$offset},{$rows}") as $val)
			{
				$key = $val['class_one_id'].'.'.$val['class_two_id'].'.'.$val['class_three_id'];
				$tmp[$key]['title'][] = $val['title'];
			}
			return $tmp;
		}else{
			return array();
		}
	}

	/**
	 * 得到列表的总数
	 */
	public function getAttrsCount()
	{
		return (int)$this->queryScalar("SELECT COUNT(*) FROM goods_attrs WHERE parent_unite_code='' GROUP BY class_one_id,class_two_id,class_three_id ASC");
	}
	
	/**
	 * 添加 & 编辑
	 * @param		array		$post		post
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 */
	public function setting(array $post , $one = 0 , $two = 0 , $three = 0)
	{
		if (!($one>0 && $two>0 && $three>0))
		{
			$one = (int)$post['class_one_id'];
			$two = (int)$post['class_two_id'];
			$three = (int)$post['class_three_id'];
		}
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('goods_attrs' , "class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three}");
			
			$rk = 0;
			foreach ($post['title'] as $k => $val)
			{
				if (!trim($val) || (!$attrs = isset($post['val'][$k]) ? array_filter($post['val'][$k]) : array()))
					continue;
				
				$uniteCode = md5(serialize(array($one , $two , $three , '0' , ($rk++))));
				$data = array(
					'unite_code'			=> $uniteCode,
					'class_one_id'			=> $one,
					'class_two_id'			=> $two,
					'class_three_id'		=> $three,
					'parent_unite_code'		=> '',
					'title'					=> trim($val),
					'rank'					=> $rk
				);
				$this->insert('goods_attrs' , $data);
				
				$atsk = 0;
				foreach ($attrs as $atk => $atx)
				{
					$data['parent_unite_code']	= $uniteCode;
					$data['unite_code']			= md5(serialize(array($one , $two , $three , $uniteCode , ($atsk++))));
					$data['title']				= trim($atx);
					$data['rank']				= empty($post['rank'][$k][$atk]) ? 0 : (int)$post['rank'][$k][$atk];
					$this->insert('goods_attrs' , $data);
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	/**
	 * 得到 商品分类属性组
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 */
	public function getAttrsInfo($one = 0 , $two = 0 , $three = 0)
	{
		return GlobalGoodsAttrs::getClassAttrs($one , $two , $three);
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
	
	public function getAttrsExist()
	{
		$tmp = array();
		foreach ($this->query("
				SELECT class_one_id,class_two_id,class_three_id
				FROM goods_attrs
				WHERE parent_unite_code=''
				GROUP BY class_one_id,class_two_id,class_three_id") as $val)
			$tmp[$val['class_one_id'].'.'.$val['class_two_id'].'.'.$val['class_three_id']] = 1;
		
		return $tmp;
	}
	
	/**
	 * 删除 商品分类属性组
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 */
	public function deletes($one = 0 , $two = 0 , $three = 0)
	{
		return $this->delete('goods_attrs' , "class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three}");
	}
}
