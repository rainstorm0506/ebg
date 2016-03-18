<?php

/**
 * 文章管理模型类
 * @author jeson.Q 
 * 
 * @table content
 */
class Content extends SModels
{
	/**
	 * 编辑文章信息
	 *
	 * @param array $post
	 * @param int $id
	 */
	public function modifys(array $post , $id)
	{
		$id = (int)$id;
		$datastr = '';
		if ($post)
		{
			// 批量 安全过滤特殊字符和 脚步xss
			//foreach ($post as $key => $val)
			//{
			// 	$datastr = $this->safe_replace($val);
			// 	$post[$key] = $this->remove_xss($datastr);
			//}
			// 组装数据
			$field = array();
			$field['type'] = trim($post['type']);
			$field['title'] = trim($post['title']);
			$field['content'] = trim($post['content']);
			$field['orderby'] = (int)$post['orderby'];
			$field['is_show'] = (int)$post['is_show'];
			$field['foot_show'] = (int)$post['foot_show'];
			$field['addtime'] = time();
			
			// 判断是 修改数据 还是 添加数据
			if ($id)
			{
				$flag = $this->update('content' , $field , "id={$id}");
			}else
			{
				$flag = $this->insert('content' , $field);
			}
			return $flag;
		}
		return false;
	}
	
	/**
	 * 获得文章 单个信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($id)
	{
		$id = (int)$id;
		$info = array();
		if ($id)
		{
			$sql = "SELECT * FROM content WHERE id={$id}";
			$info = $this->queryRow($sql);
			return $info;
		}else
		{
			return array();
		}
	}
	
	/**
	 * 查询文章列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList(array $search, $offset = 0 , $limit = 20, $typeId = null)
	{
		$params = $contentInfo = array();
		$where = '';
		$SEOCODE = $search['SEOCODE'] ? $search['SEOCODE'] : '';
		// 判断是否关键字收索
		$where .= $search['keyword'] ? " and (c.id = " .(int)$search['keyword'] ." OR c.title like '%" .$search['keyword'] ."%' OR ct.name = '{$search['keyword']}')" : '';
		// 判断是否来至单个栏目
		$where .= $typeId ? " and type = {$typeId} " : '';
		// 组装sql 语句
		$sql = "SELECT c.*,ct.name,s.seo_title FROM content c 
				LEFT JOIN content_type ct ON c.type = ct.id 
				LEFT JOIN seo s ON s.code='{$SEOCODE}' AND s.id=c.id
				WHERE 1=1 {$where} ORDER BY c.orderby ASC limit {$offset},{$limit}";
		$contentInfo = $this->queryAll($sql);
		
		return $contentInfo;
	}
	
	/**
	 *
	 * 统计 文章 总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber(array $search, $typeId = null)
	{
		$where = $sql = '';
		// 判断是否关键字收索
		$SEOCODE = $search['SEOCODE'] ? $search['SEOCODE'] : '';
		$where = $search['keyword'] ? " and c.id = " .(int)$search['keyword'] ." OR c.title like '%" .$search['keyword'] ."%' OR ct.name = '{$search['keyword']}'" : '';
		// 判断是否来至单个栏目
		$where .= $typeId ? " and type = {$typeId} " : '';
		// 组装sql 语句
		$sql = "SELECT count(*) FROM content c LEFT JOIN content_type ct ON c.type = ct.id WHERE 1=1 {$where}";
		
		return (int)$this->queryScalar($sql);
	}
	
	/**
	 * 删除文章
	 *
	 * @param int $id        	
	 */
	public function clear($id)
	{
		if ($id)
		{
			return $this->delete('content' , 'id=' .$id);
		}else
		{
			return false;
		}
	}
	/**
	 * 检查 文章标题 是否重名
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function checkName($tag , $name , $id)
	{
		if (!$name)
			return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM content WHERE `{$tag}` = {$this->quoteValue($name)} {$SQL}");
	}
}
