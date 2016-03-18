<?php
/**
 * 经营范围Model类
 */
class Scope extends SModels {
	
	/**
	 * 获取商家经营范围信息
	 * @param $mid	int	商家ID
	 */
	public function getMerchantScope($mid)
	{
		$SQL="SELECT sb.* FROM scope_business sb 
			INNER JOIN user_mer_scope_business ub ON sb.id = ub.sb_id 
			WHERE ub.mer_uid={$mid}";
		$rows= $this->queryAll($SQL);
		$scopes = array();
		foreach ($rows as $key => $v)
		{
			if ((int)$v['id'])
				$scopes[] = (int)$v['id'];
		}
		return $scopes;
	}
	
	/**
	 * 查询静音个范围信息列表
	 * @param	$keyword	string		搜索关键字
	 * @param	$offset		int			
	 * @param	$rows		int
	 * @param	$total		int
	 * @return
	 */
	public function getList($keyword, $offset, $rows, $total)
	{
		if (!$total || $offset>=$total)
			return array();
		
		$SQL = '';
		if ($keyword)
		{
			$keyword = $this->quoteLikeValue($keyword);
			$SQL = " WHERE title LIKE {$keyword}";
		}
		return $this->queryAll("SELECT * FROM scope_business {$SQL} ORDER BY rank ASC,time DESC LIMIT {$offset},{$rows}");
	}
	
	/**
	 * 统计记录数
	 */
	public function getCount($keyword)
	{
		$SQL = '';
		if ($keyword)
		{
			$keyword = $this->quoteLikeValue($keyword);
			$SQL = " WHERE title LIKE {$keyword}";
		}
		return (int)$this->queryScalar("SELECT COUNT(*) FROM scope_business {$SQL}");
	
	}
	
	/**
	 * 获得某个经营范围信息
	 * @param		$id		int		范围ID
	 * @return 		array
	 */
	public function getInfo($id)
	{
		if ($id) {
			return $this -> queryRow("SELECT * FROM scope_business WHERE `id`={$this->quoteValue($id)}");
		} else {
			return array();
		}
	}
	
	/**
	 * 添加营业范围信息
	 * @param		$post	array		经营范围
	 * @return		添加的ID
	 */
	public function create(array $post)
	{
		$data = array(
			'title'		=>	$post['title'], 
			'describe'	=>	$post['describe'], 
			'rank'		=>	$post['rank'],
			'time'		=>	time());
		$this->insert("scope_business", $data);
		return $this->getInsertId();
	}
	
	/**
	 * 删除某个经营范围信息
	 * @param	$id		int		经营范围ID
	 * @return	成功true,反之false
	 */
	public function clear($id)
	{
		return $id ? (boolean)$this->delete("scope_business", 'id='.$id) : false;
	}
	
	/**
	 * 修改某个经营范围信息
	 * @param	$post	array	经营范围信息
	 * @param	$id		int		经营范围信息ID
	 */
	public function modify(array $post, $id)
	{
		$data = array();
		if($id){
			$data['title']		=	$post['title'];
			$data['describe']	=	$post['describe'];
			$data['rank']		=	$post['rank'];
			return $this->update("scope_business", $data, "id=".$id);
		}else{
			return false;
		}
	}
	
	/**
	 * 获取全部列表（不带分页）
	 */
	function getAll()
	{
		return $this->queryAll("SELECT * FROM scope_business ORDER BY rank ASC,time DESC");
	}
	
	/**
	 * 检测营业范围名是否存在
	 * @param		$title		string		营业范围名
	 * @param		$id			int			营业范围ID
	 * @return
	 */
	function checkName($title, $id) {
		if ($title) {
			$SQL = $id ? "AND id!={$id}" : "";
			return (boolean)$this->queryRow("SELECT id FROM scope_business WHERE `title`={$this->quoteValue($title)} {$SQL}");
		} else {
			return false;
		}
	}
	
}

?>