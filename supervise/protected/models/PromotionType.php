<?php

/**
 * 广告类型管理模型类
 * @auther jeson.Q 
 * 
 *@table adver
 */
class PromotionType extends SModels
{
	/**
	 *
	 * @return string 设置表名
	 */
	public function tableName()
	{
		return 'adver_type';
	}
	
	/**
	 * 编辑 广告信息
	 *
	 * @param array $post
	 * @param int $id
	 */
	public function modifys(array $post , $id)
	{
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
			$field['describe'] = trim($post['describe']);
			$field['is_show'] = (int)$post['is_show'];
			
			// 判断是 修改数据 还是 添加数据
			if ($id)
			{
				$flag = $this->update('adver_type' , $field , "code_key = '{$id}'");
			}else
			{
				$flag = $this->insert('adver_type' , $field);
			}
			return $flag;
		}else
		{
			return false;
		}
	}
	
	/**
	 * 获得 单个广告类型的信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($id)
	{
		$info = array();
		if ($id)
		{
			$sql = "SELECT * FROM adver_type WHERE code_key='{$id}'";
			$info = $this->queryRow($sql);
			return $info;
		}else
		{
			return array();
		}
	}
	
	/**
	 * 查询 广告类型 列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($keyword = '' , $offset = 0 , $limit = 20)
	{
		$where = $sql = $limits = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and ( name like '%" .$keyword ."%' OR code_key like '%" .$keyword ."%' ) " : '';
		$limits = " {$offset},{$limit} ";
		$sql = "SELECT * FROM adver_type WHERE 1=1 {$where} limit {$limit}";

		return $this->queryAll($sql);
	}
	
	/**
	 *
	 * 统计广告类型 总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($keyword = '')
	{
		$where = $sql = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and ( name like '%" .$keyword ."%' OR code_key like '%" .$keyword ."%' ) " : '';
		$sql = "SELECT count(*) FROM adver_type WHERE 1=1 {$where}";
		
		return (int)$this->queryScalar($sql);
	}
	
	/**
	 * 删除广告
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
			$this->update('adver' , $field , "type={$id}");
			return $this->delete('adver_type' , 'id=' .$id);
		}else
		{
			return false;
		}
	}
	/**
	 * 检查广告类型名称 是否重名
	 *
	 * @param string $name
	 * @param int $id
	 * @return boolean
	 */
	public function checkName($tag , $name , $id)
	{
		if (!$name)
			return false;
		$SQL = $id ? "AND code_key != '{$id}'" : '';
		return (boolean)$this->queryRow("SELECT code_key FROM adver_type WHERE `{$tag}` = {$this->quoteValue($name)} {$SQL}");
	}
}
