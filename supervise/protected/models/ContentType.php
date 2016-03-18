<?php

/**
 * 文章类型-模型
 * @author jeson.Q
 * 
 *@table content_type
 */
class ContentType extends SModels
{
	/**
	 * 编辑 文章类型 信息
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
			foreach ($post as $key => $val)
			{
				$datastr = $this->safe_replace($val);
				$post[$key] = $this->remove_xss($datastr);
			}
			// 组装数据
			$field = array();
			$field['name'] = trim($post['name']);
			$field['foot_show'] = (int)$post['foot_show'];
			$field['orderby'] = (int)trim($post['orderby']);
			$field['addtime'] = time();
			
			// 判断是 修改数据 还是 添加数据
			if ($id)
			{
				$flag = $this->update('content_type' , $field , "id={$id}");
			}else
			{
				$flag = $this->insert('content_type' , $field);
			}
			return $flag;
		}
		return false;
	}
	
	/**
	 * 获得 文章类型 的信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($id)
	{
		$id = (int)$id;
		$info = array();
		if ($id)
		{
			$sql = "SELECT * FROM content_type WHERE id={$id}";
			$info = $this->queryRow($sql);
			
			return $info;
		}else
		{
			return array();
		}
	}
	
	/**
	 * 查询文章类型列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($keyword = '' , $offset = 0 , $limit = 20)
	{
		$params = $contentTypeInfo = array();
		$where = '';
		
		// 判断是否关键字收索
		$where = $keyword ? " WHERE id = " .(int)$keyword ." OR name like '%" .$keyword ."%' " : '';
		// 组装sql语句
		$sql = "SELECT * FROM content_type {$where} ORDER BY `orderby` ASC limit {$offset},{$limit}";
		$contentTypeInfo = $this->queryAll($sql);
		
		return $contentTypeInfo;
	}
	
	/**
	 *
	 * 统计 文章类型 总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($keyword = '')
	{
		$where = $sql = '';
		// 判断是否关键字收索
		$where = $keyword ? " WHERE id = " .(int)$keyword ." OR name like '%" .$keyword ."%' " : '';
		// 组装sql语句
		$sql = "SELECT count(*) FROM content_type {$where}";
		
		return (int)$this->queryScalar($sql);
	}
	
	/**
	 * 删除 文章类型
	 *
	 * @param int $id        	
	 */
	public function clear($id)
	{
		$field = array();
		if ($id)
		{
			$field['type'] = 0;
			$field['is_show'] = 0;
			$this->update('content' , $field , "type={$id}");
			return $this->delete('content_type' , 'id=' .$id);
		}else
		{
			return false;
		}
	}
	/**
	 * 检查 文章类型 是否重名
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
		return (boolean)$this->queryRow("SELECT id FROM content_type WHERE `{$tag}` = {$this->quoteValue($name)} {$SQL}");
	}
}
