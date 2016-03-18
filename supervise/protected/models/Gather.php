<?php

/**
 * 二手商品
 *
 * @author 谭甜
 */
class Gather extends SModels
{
	//得到店铺数量
	public function getListCount($search)
	{
		$where = empty($search['keyword']) ? '' : "WHERE title LIKE '{$search['keyword']}'";
		return $this->queryScalar("SELECT COUNT(*) FROM gather {$where}");
	}

	//得到列表
	public function getList($search, $offset, $rows, $total, array $schema = array())
	{
		if (!$total || $offset >= $total)
		{
			return array();
		}

		$where = empty($search['keyword']) ? '' : "WHERE g.title LIKE '{$search['keyword']}'";
		return $this->queryAll("SELECT g.*,d.name AS one,i.name AS two,t.name AS three,h.title AS parent FROM gather AS g
			LEFT JOIN dict AS d ON d.id=g.dict_one_id
			LEFT JOIN dict AS i ON i.id=g.dict_two_id
			LEFT JOIN dict AS t ON t.id=g.dict_three_id
			LEFT JOIN gather AS h ON h.id=g.parent_id
			{$where} ORDER BY rank ASC LIMIT {$offset},{$rows}");
	}

	//获取所有的电脑城
	public function getComputer()
	{
		$row = $this->queryAll("SELECT id,title FROM gather WHERE parent_id=0 ORDER BY rank ASC");
		$data = array();
		foreach ($row as $v)
		{
			$data[$v['id']] = $v['title'];
		}
		return $data;
	}

	//获取电脑城
	private function computer($id)
	{
		return $this->queryRow("SELECT * FROM gather WHERE id={$id}");
	}

	//添加电脑城
	public function create(array $post)
	{
		$arr = array(
			'dict_one_id' => $post['dict_one_id'],
			'dict_two_id' => $post['dict_two_id'],
			'dict_three_id' => $post['dict_three_id'],
			'title' => $post['title'],
			'time' => time()
		);
		return $this->insert('gather', $arr);
	}

	//添加楼层
	public function storey(array $post)
	{
		$data = $this->computer($post['gather']);
		$arr = array(
			'dict_one_id' => $data['dict_one_id'],
			'dict_two_id' => $data['dict_two_id'],
			'dict_three_id' => $data['dict_three_id'],
			'parent_id' => $post['gather'],
			'storey' => $post['title'],
			'time' => time()
		);
		return $this->insert('gather', $arr);
	}

	//添加店铺
	public function store(array $post)
	{

		$data = $this->computer($post['gather']);
		$arr = array(
			'dict_one_id' => $data['dict_one_id'],
			'dict_two_id' => $data['dict_two_id'],
			'dict_three_id' => $data['dict_three_id'],
			'parent_id' => $post['gather'],
			'storey' => $post['storey'],
			'title' => $post['title'],
			'rank' => $post['rank'],
			'time' => time()
		);
		return $this->insert('gather', $arr);
	}

	//获取楼层名字
	private function getFloor($id)
	{
		return $this->queryColumn("SELECT storey FROM gather WHERE id={$id}");
	}

	//删除
	public function clear($id)
	{
		return $this->delete('gather', 'id=' . $id);
	}

	//详情
	public function intro($id)
	{
		return $this->queryRow("SELECT * FROM gather WHERE id={$id}");
	}

	//检查店铺重复
	public function checkTitle($title, $id)
	{
		if (!$title)
		{
			return false;
		}

		$SQL = $id > 0 ? " AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM gather WHERE title={$this->quoteValue($title)} {$SQL}");
	}

	//获取电脑城下面的店铺
	public function getstorelist($computer)
	{
		$row = $this->queryAll("SELECT id,title FROM gather WHERE parent_id={$computer} ORDER BY rank ASC");
		$data = array();
		foreach ($row as $v)
		{
			$data[$v['id']] = $v['title'];
		}
		return $data;
	}

	//获取电脑城对应楼层
	public function getstorey($id)
	{
		$row = $this->queryAll("SELECT id,storey FROM gather WHERE parent_id=$id ORDER BY rank ASC");
		$data = array();
		foreach ($row as $v)
		{
			$data[$v['storey']] = $v['storey'];
		}
		return $data;
	}

	//检查楼层重复
	public function checkStorey($storey, $gather, $id)
	{
		if (!$storey)
		{
			return false;
		}

		$SQL = $id > 0 ? " AND id!={$id}" : '';
		return $this->queryRow("SELECT id FROM gather WHERE storey={$this->quoteValue($storey)} AND parent_id={$gather} AND title='' {$SQL}");
	}

	//检查店铺重复
	public function checkStore($store, $gather, $storey, $id)
	{
		if (!$store)
		{
			return false;
		}

		$SQL = $id > 0 ? " AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM gather WHERE storey={$storey} AND parent_id={$gather} AND title='{$store}' {$SQL}");
	}

	//编辑电脑城
	public function mygather(array $post)
	{
		$arr = array(
			'dict_one_id' => $post['dict_one_id'],
			'dict_two_id' => $post['dict_two_id'],
			'dict_three_id' => $post['dict_three_id'],
			'title' => $post['title']
		);
		return $this->update('gather', $arr, 'id=' . $post['id']);
	}

	//编辑楼层
	public function mystorey(array $post)
	{
		$data = $this->computer($post['gather']);
		$arr = array(
			'dict_one_id' => $data['dict_one_id'],
			'dict_two_id' => $data['dict_two_id'],
			'dict_three_id' => $data['dict_three_id'],
			'parent_id' => $post['gather'],
			'storey' => $post['title'],
		);
		return $this->update('gather', $arr, 'id=' . $post['id']);
	}

	//编辑店铺编号
	public function mystore(array $post)
	{
		$data = $this->computer($post['gather']);
		$arr = array(
			'dict_one_id' => $data['dict_one_id'],
			'dict_two_id' => $data['dict_two_id'],
			'dict_three_id' => $data['dict_three_id'],
			'parent_id' => $post['gather'],
			'storey' => $post['storey'],
			'title' => $post['title'],
			'rank' => $post['rank'],
		);
		#self::flush();
		return $this->update('gather', $arr, 'id=' . $post['id']);
	}

	//获取都成对应店铺编号
	public function getstore($gather, $storey)
	{
		$row = $this->queryAll("SELECT id,title FROM gather WHERE parent_id={$gather} AND storey={$storey} AND title!='' ORDER BY rank ASC");
		$data = array();
		foreach ($row as $v)
		{
			$data[$v['id']] = $v['title'];
		}
		return $data;
	}
}