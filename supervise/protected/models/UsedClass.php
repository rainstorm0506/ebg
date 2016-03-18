<?php
/**
 * 二手商品分类
 *
 * @author 谭甜
 */
class UsedClass extends SModels
{
    /**
     * 所有分类
     */
    public function getSelectList()
    {
        $list = array();
        foreach (GlobalUsedClass::getAllList() as $id => $val)
            $list[$id] = join(' / ' , array_filter(array($val['title'] )));

        return $list;
    }
    /**
     * 得到列表的总数
     */
    public function getListCount()
    {
        return (int)$this->queryScalar("SELECT COUNT(*) FROM used_class ORDER BY id DESC");
    }
    /**
     * 分类列表
     */
    public function getList($offset , $rows , $total , array $schema = array()){
        if (!$total || $offset>=$total)
            return array();

        return $this->queryAll("SELECT * FROM used_class ORDER BY rank ASC LIMIT {$offset},{$rows}");
    }
    /**
     * 检查分类重名
     */
    public function checkTitle($title , $id){
        if (!$title) return false;

        $SQL = $id>0 ? " AND id!={$id}" : '';
        return (boolean)$this->queryRow("SELECT id FROM used_class WHERE title={$this->quoteValue($title)} {$SQL}");
    }
    /**
     * 获取分类id
     */
    private function getclassid($data){
        return $this->queryRow("SELECT id FROM used_class WHERE title='".$data['title']."' AND rank={$data['rank']}");
    }
    /**
     * 添加分类
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
			$this->insert('used_class' , $ary);
			$cid = $this->getInsertId();

			//设置排序
			$rank = 0;
			switch ($ary['tier'])
			{
				case 1: $rank = $cid * 1000000; break;
				case 2: $rank = $ary['root_id'] * 1000000 + $cid * 10000; break;
				case 3: $rank = $ary['root_id'] * 1000000 + $ary['parent_id'] * 10000 + $cid * 100; break;
			}
			$this->update('used_class', array('rank' => $rank) , "id={$cid}");

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
					$this->insert('used_price_section' , array(
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
    /**
     * 获得分类价格区间
     */
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
			FROM used_price_section
			WHERE class_one_id={$oneId} AND class_two_id={$twoId} AND class_three_id={$threeId}
		");
	}
	/**
	 * 编辑分类
	 */
	public function modify(array $post , array $class , $id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('used_class' , array('title' => $post['title'] , 'is_show' => (int)$post['is_show']) , "id={$id}");

			if (!empty($post['price']['s']))
			{
				$oneId = $twoId = $threeId = 0;
				switch ($class['tier'])
				{
					case 3: $oneId = $class['root_id']; $twoId = $class['parent_id']; $threeId = $class['id']; break;
					case 2: $oneId = $class['root_id']; $twoId = $class['id']; $threeId = 0; break;
					case 1: $oneId = $class['id']; $twoId = 0; $threeId = 0; break;
				}
				$this->delete('used_price_section' , "class_one_id={$oneId} AND class_two_id={$twoId} AND class_three_id={$threeId}");

				foreach ($post['price']['s'] as $k => $v)
				{
					$this->insert('used_price_section' , array(
						'class_one_id'  => $oneId,
						'class_two_id'	=> $twoId,
						'class_three_id'=> $threeId,
						'price_start'	=> (int)$v,
						'price_end'   	=> empty($post['price']['e'][$k]) ? ((int)$v+1) : (int)$post['price']['e'][$k]
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
	 * 获取分类信息
	 */
	public function getClassInfo($classId)
	{
		return $this->queryRow("SELECT * FROM used_class WHERE id={$classId}");
	}
	public function exchangeRank(array $up , array $down)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->update('used_class' , array('rank'=>$down['rank']) , "id={$up['id']}");
			$this->update('used_class' , array('rank'=>$up['rank']) , "id={$down['id']}");
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
    /**
     * 删除分类
     */
	public function deletes($id , $tier)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('used_class' , "id={$id} OR root_id={$id} OR parent_id={$id}");
			switch ($tier)
			{
				case 1:
					$this->update('used_goods',array('class_one_id'=>0,'class_two_id'=>0,'class_three_id'=>0),"class_one_id={$id}");
					break;

				case 2:
					$this->update('used_goods',array('class_two_id'=>0,'class_three_id'=>0),"class_two_id={$id}");
					break;

				case 3:
					$this->update('used_goods',array('class_three_id'=>0),"class_three_id={$id}");;
					break;
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
    /**
     * 验证分类id是否正确
     */
    public function exist($id){
        return $this->queryRow("SELECT * FROM used_class WHERE id={$id}");
    }
}