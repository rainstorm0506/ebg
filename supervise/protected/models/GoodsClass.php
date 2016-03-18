<?php
/**
 * 商品分类
 * 
 * @author 涂先锋
 */
class GoodsClass extends SModels
{
	public function exchangeRank(array $up , array $down)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('goods_class' , array('rank'=>$down['rank']) , "id={$up['id']}");
			$this->update('goods_class' , array('rank'=>$up['rank']) , "id={$down['id']}");
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	public function getClassInfo($classId)
	{
		return $this->queryRow("SELECT * FROM goods_class WHERE id={$classId}");
	}
	
	public function getClassPrice(array $class)
	{
		if (empty($class))
			return array();
		
		$oneId = $twoId = $threeId = 0;
		switch ($class['tier'])
		{
			case 3 : $oneId = $class['root_id']; $twoId = $class['parent_id']; $threeId = $class['id']; break;
			case 2 : $oneId = $class['root_id']; $twoId = $class['id']; $threeId = 0; break;
			case 1 : $oneId = $class['id']; $twoId = 0; $threeId = 0; break;
		}
		
		return $this->queryAll("
			SELECT price_start,price_end
			FROM goods_price_section
			WHERE class_one_id={$oneId} AND class_two_id={$twoId} AND class_three_id={$threeId}
		");
	}
	
	
	/**
	 * 添加部门
	 * @param	array	$post	post
	 */
	public function create(array $post)
	{
		
		$ary = array(
			'root_id'	=> (int)$post['one_id'],
			'parent_id'	=> (int)$post['two_id'],
			'title'		=> $post['title'],
			'is_show'	=> (int)$post['is_show'],
			'time'		=> time()
		);
		
		if ($ary['root_id']>0 && $ary['parent_id']>0 && $ary['root_id'] != $ary['parent_id'])
		{
			$ary['tier'] = 3;
		}elseif ($ary['root_id']>0){
			$ary['tier'] = 2;
			$ary['parent_id'] = $ary['root_id'];
		}else{
			$ary['tier'] = 1;
			$ary['parent_id'] = $ary['root_id'] = 0;
		}
			
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('goods_class' , $ary);
			$cid = $this->getInsertId();
			
			//设置排序
			$rank = 0;
			switch ($ary['tier'])
			{
				case 1: $rank = $cid * 1000000; break;
				case 2: $rank = $ary['root_id'] * 1000000 + $cid * 10000; break;
				case 3: $rank = $ary['root_id'] * 1000000 + $ary['parent_id'] * 10000 + $cid * 100; break;
			}
			$this->update('goods_class', array('rank' => $rank) , "id={$cid}");
			
			if (!empty($post['price']['s']))
			{
				$oneId = $twoId = $threeId = 0;
				switch ($ary['tier'])
				{
					case 3: $oneId = $ary['root_id']; $twoId = $ary['parent_id']; $threeId = $cid; break;
					case 2: $oneId = $ary['root_id']; $twoId = $cid; $threeId = 0; break;
					case 1: $oneId = $cid; $twoId = 0; $threeId = 0; break;
				}
				foreach ($post['price']['s'] as $k => $v)
				{
					$this->insert('goods_price_section' , array(
						'class_one_id'		=> $oneId,
						'class_two_id'		=> $twoId,
						'class_three_id'	=> $threeId,
						'price_start'		=> (int)$v,
						'price_end'			=> empty($post['price']['e'][$k]) ? ((int)$v+1) : (int)$post['price']['e'][$k]
					));
				}
			}
			
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
		
		return $cid;
	}
	
	public function modify(array $post , array $class , $id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('goods_class' , array('title' => $post['title'] , 'is_show' => (int)$post['is_show']) , "id={$id}");
			
			if (!empty($post['price']['s']))
			{
				$oneId = $twoId = $threeId = 0;
				switch ($class['tier'])
				{
					case 3: $oneId = $class['root_id']; $twoId = $class['parent_id']; $threeId = $class['id']; break;
					case 2: $oneId = $class['root_id']; $twoId = $class['id']; $threeId = 0; break;
					case 1: $oneId = $class['id']; $twoId = 0; $threeId = 0; break;
				}
				$this->delete('goods_price_section' , "class_one_id={$oneId} AND class_two_id={$twoId} AND class_three_id={$threeId}");
				
				foreach ($post['price']['s'] as $k => $v)
				{
					$this->insert('goods_price_section' , array(
						'class_one_id'		=> $oneId,
						'class_two_id'		=> $twoId,
						'class_three_id'	=> $threeId,
						'price_start'		=> (int)$v,
						'price_end'			=> empty($post['price']['e'][$k]) ? ((int)$v+1) : (int)$post['price']['e'][$k]
					));
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
	 * 检查 分类名称 是否重名
	 * @param		string		$title		分类名称
	 * @param		int			$id			ID
	 * @param		int			$oneId		第一层分类ID
	 * @param		int			$twoId		第二层分类ID
	 * @return		boolean
	 */
	public function checkTitle($title , $id , $oneId , $twoId)
	{
		if (!$title) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		//三级
		if ($oneId > 0 && $twoId > 0 && $oneId != $twoId)
		{
			$SQL .= " AND root_id={$oneId} AND parent_id={$twoId} AND tier=3";
		}elseif ($oneId > 0){
			//二级
			$SQL .= " AND root_id={$oneId} AND parent_id={$oneId} AND tier=2";
		}else{
			//一级
			$SQL .= " AND root_id=0 AND parent_id=0 AND tier=1";
		}
		return (boolean)$this->queryRow("SELECT id FROM goods_class WHERE `title`={$this->quoteValue($title)} {$SQL}");
	}
	
	/**
	 * 删除分类
	 * @param		int		$id			分类ID
	 * @param		int		$tier		分类层级
	 */
	public function deletes($id , $tier)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('goods_class' , "id={$id} OR root_id={$id} OR parent_id={$id}");
			switch ($tier)
			{
				case 1:
					$this->update('goods',array('class_one_id'=>0,'class_two_id'=>0,'class_three_id'=>0),"class_one_id={$id}");
				break;
						
				case 2:
					$this->update('goods',array('class_two_id'=>0,'class_three_id'=>0),"class_two_id={$id}");
				break;
						
				case 3:
					$this->update('goods',array('class_three_id'=>0),"class_three_id={$id}");;
				break;
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
}
