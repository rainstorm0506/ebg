<?php
/**
 * 管理员部门
 * 
 * @author 涂先锋
 */
class Branch extends SModels
{
	//获得管理员的部门列表(二维数组)
	public function getList()
	{
		return $this->queryAll('SELECT * FROM back_governor_branch ORDER BY rank,id ASC');
	}
	
	//获得管理员部门 的键值对(一维数组)
	public function getListPair()
	{
		$tmp = array();
		foreach ($this->queryAll('SELECT id,name FROM back_governor_branch ORDER BY rank,id ASC') as $val)
			$tmp[$val['id']] = $val['name'];
		return $tmp;
	}
	
	/**
	 * 添加部门
	 * @param	array	$post	post
	 */
	public function create(array $post)
	{
		return $this->insert('back_governor_branch' , array(
			'name'	=> $post['name'],
			'rank'	=> (int)$post['rank'],
			'time'	=> time()
		));
	}
	
	/**
	 * 获得某一个部门的信息
	 * @param	int		$id		ID		部门ID
	 */
	public function getInfo($id = 0)
	{
		return $this->queryRow("SELECT * FROM back_governor_branch WHERE id=" .(int)$id);
	}
	
	/**
	 * 编辑部门
	 * @param		array		$post		post
	 * @param		int			$id			部门ID
	 */
	public function modify(array $post , $id = 0)
	{
		return $this->update('back_governor_branch' , array(
			'name'	=> $post['name'],
			'rank'	=> (int)$post['rank'],
		) , 'id='.(int)$id);
	}
	
	/**
	 * 删除部门
	 * @param		int			$id		部门ID
	 */
	public function clear($id)
	{
		return $this->delete('back_governor_branch' , 'id='.$id);
	}
	
	/**
	 * 检查部门名称 是否重名
	 * @param		string		$name		部门名称
	 * @param		int			$id			ID
	 * @return		boolean
	 */
	public function checkName($name , $id)
	{
		if (!$name) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM back_governor_branch WHERE `name`={$this->quoteValue($name)} {$SQL}");
	}
}
