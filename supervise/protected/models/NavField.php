<?php
/**
 * 后台导航
 *
 * @author 涂先锋
 */
class NavField extends SModels
{
	//获得导航的第一级数据
	public function getRootField()
	{
		$res = array();
		foreach ($this->queryAll("SELECT id,title FROM back_role_fields WHERE parent_id=0 ORDER BY rank DESC,id ASC") as $vs)
			$res[$vs['id']] = $vs['title'];
		return $res;
	}

	/**
	 * 获得导航栏的权限
	 * @param		int		$id		栏位ID
	 */
	public function getFieldPurview($id)
	{
		$id = (int)$id;
		if (!$id) return array();

		return $this->queryAll("SELECT operation_key AS `key` , operation_title AS title
				FROM back_role_purviews
				WHERE field_id={$id} ORDER BY rank ASC");
	}

	/**
	 * 设置栏位对应的权限
	 * @param		array		$post		post
	 * @param		int			$id			栏位ID
	 */
	public function privilege(array $post , $id)
	{
		$id = (int)$id;
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('back_role_purviews' , "field_id={$id}");
			$i = 0;
			foreach ($post as $vs)
			{
				if ($vs['key'] && $vs['title'])
					$this->insert('back_role_purviews' , array(
						'field_id'			=> $id ,
						'operation_key'		=> $vs['key'] ,
						'operation_title'	=> $vs['title'] ,
						'rank'				=> ++$i
					));
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}

	/**
	 * 获得栏位的信息
	 * @param		int			$id			栏位ID
	 */
	public function getFieldInfo($id)
	{
		$id = (int)$id;
		if (!$id) return array();

		return $this->queryRow("SELECT * FROM back_role_fields WHERE id={$id}");
	}

	/**
	 * 列表
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getList($offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();

		return $this->queryAll("SELECT * FROM back_role_fields ORDER BY rank ASC LIMIT {$offset},{$rows}");
	}

	/**
	 * 得到列表的总数
	 */
	public function getListCount()
	{
		return (int)$this->queryScalar("SELECT COUNT(*) FROM back_role_fields");
	}

	/**
	 * 添加
	 * @param		array		$post		post
	 */
	public function create(array $post)
	{
		$field					= array();
		$field['parent_id']		= intval($post['parent_id']);
		$field['title']			= trim($post['title']);
		$field['route']			= trim($post['route']);
		$field['rank']			= (int)$post['rank'];

		return $this->insert('back_role_fields' , $field);
	}

	/**
	 * 编辑信息
	 * @param	array	$post		post
	 * @param	int		$id			ID
	 */
	public function modify(array $post , $id)
	{
		$id = (int)$id;

		$field					= array();
		$field['parent_id']		= intval($post['parent_id']);
		$field['title']			= trim($post['title']);
		$field['route']			= trim($post['route']);
		$field['rank']			= (int)$post['rank'];

		return $this->update('back_role_fields' , $field , "id={$id}");
	}

	/**
	 * 删除信息
	 * @param	int		$id		ID
	 */
	public function clear($id)
	{
		$this->delete('back_role_fields' , "id={$id} OR parent_id={$id}");
	}
}
