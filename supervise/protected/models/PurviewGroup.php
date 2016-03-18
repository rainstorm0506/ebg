<?php
/**
 * 角色
 *
 * @author 涂先锋
 */
class PurviewGroup extends SModels
{
	/**
	 * 检查角色名称
	 * @param		string		$title		角色名称
	 * @param		int			$id			角色ID
	 * 
	 * @return		boolean
	 */
	public function checkGroupTitle($title , $id)
	{
		if (!$title) return false;
		
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryScalar("
				SELECT id FROM back_role
				WHERE title={$this->quoteValue($title)} {$SQL} LIMIT 0,1"
		);
	}
	
	/**
	 * 得到角色的信息
	 * @param		int		$id		角色ID
	 */
	public function getPurviewGroup($id)
	{
		if ($row = $this->queryRow("SELECT * FROM back_role WHERE id={$id}"))
		{
			$row['fields'] = json_decode($row['fields'] , true);
			$row['fields'] = implode(',' , array_keys($row['fields']));
			$row['purviews'] = json_decode($row['purviews'] , true);
		}
		return $row;
	}
	
	//获得角色列表
	public function getList()
	{
		return $this->queryAll("SELECT id,title,`explain` FROM back_role ORDER BY id DESC");
	}
	
	//获得全部的权限
	public function getAllPurview()
	{
		$res = array();
		foreach ($this->queryAll("SELECT * FROM back_role_purviews ORDER BY rank ASC") as $vs)
			$res[$vs['field_id']][$vs['operation_key']] = $vs['operation_title'];
		return $res;
	}
	
	/**
	 * 创建一个角色
	 * @param		array		$post		post
	 * 
	 * @return		int
	 */
	public function create(array $post)
	{
		$fields = json_encode($post['fields'] ? array_fill_keys(explode(',' , $post['fields']), 1) : array());
		
		$ary = array(
			'title'		=> $post['title'],
			'explain'	=> $post['explain'],
			'fields'	=> $fields,
			'purviews'	=> json_encode(empty($post['purviews'])?array():$post['purviews']),
		);
		$this->insert('back_role', $ary);
		return $this->getInsertId();
	}
	
	/**
	 * 编辑一个角色
	 * @param		array		$post		post
	 * @param		int		$id				角色ID
	 */
	public function modify(array $post , $id)
	{
		$fields = json_encode($post['fields'] ? array_fill_keys(explode(',' , $post['fields']), 1) : array());
		
		$ary = array(
			'title'		=> $post['title'],
			'explain'	=> $post['explain'],
			'fields'	=> $fields,
			'purviews'	=> json_encode(empty($post['purviews'])?array():$post['purviews']),
		);
		
		return $this->update('back_role', $ary , "id={$id}");
	}
	
	/**
	 * 删除一个角色
	 * @param		int		$id				角色ID
	 */
	public function clear($id)
	{
		return $this->delete('back_role' , 'id='.$id);
	}
}