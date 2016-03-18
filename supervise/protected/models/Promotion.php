<?php

/**
 * 广告管理模型类
 * @auther jeson.Q 
 * 
 *@table adver
 */
class Promotion extends SModels
{
	/**
	 * 编辑 广告信息
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
			$select_type 			= isset($post['select_type']) ? $post['select_type'] : 0;
			$field['class_one_id'] 	= $select_type ? $post['class_one_id']:0;
			$field['code_key'] 		= $select_type ? $post['code_key_one'] : $post['code_key_two'];
			$field['title'] 		= trim($post['title']);
			$field['link'] 			= trim($post['link']);
			$field['image_url'] 	= isset($post['image_url']) ? $this->getPhotos(trim($post['image_url']) , 'promotion' , $field['code_key']) : '';
			$field['is_show'] 		= (int)$post['is_show'];
			$field['addtime'] 		= time();
			
			// 判断是 修改数据 还是 添加数据
			if ($id)
			{
				$flag = $this->update('adver' , $field , "id={$id}");
			}else
			{
				$flag = $this->insert('adver' , $field);
			}
			return $flag;
		}else
		{
			return false;
		}
	}
	
	/**
	 * 获得 单个广告的信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($id)
	{
		$id = (int)$id;
		if ($id)
		{
			return $this->queryRow("SELECT a.*,at.width,at.height FROM adver a LEFT JOIN adver_type at ON a.code_key = at.code_key WHERE a.id={$id}");
		}else{
			return array();
		}
	}
	
	/**
	 * 查询 广告列表
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
		$params = $promotionInfo = array();
		$where = '';
		
		// 判断是否关键字收索
		$where .= $keyword ? " WHERE a.id = " .(int)$keyword ." OR a.title like '%" .$keyword ."%' OR at.code_key = '{$keyword}'" : '';
		// 组装sql 语句
		$sql = "SELECT a.*,at.name FROM adver a LEFT JOIN adver_type at ON a.code_key = at.code_key {$where} limit {$offset},{$limit}";
		$promotionInfo = $this->queryAll($sql);
		
		return $promotionInfo;
	}
	
	/**
	 *
	 * 统计广告总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($keyword = '')
	{
		$where = $sql = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and (a.id = " .(int)$keyword ." OR a.title like '%" .$keyword ."%' OR at.code_key = '{$keyword}') " : '';
		$sql = "SELECT count(*) FROM adver a LEFT JOIN adver_type at ON a.code_key = at.code_key WHERE 1=1 {$where} ";
		
		return (int)$this->queryScalar($sql);
	}
	
	
	/**
	 * 查询 广告列表
	 *
	 * @param string $keyword
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getClassName($keyword = '' , $offset = 0 , $limit = 20)
	{
		$allData = $typeDataArr = $goodsClassInfo = $classList = array();
		$sql = '';
		// 查询 商品分类-列表
		$sql = "SELECT id, title FROM goods_class WHERE tier = 1 ";
		$classList = $this->queryAll($sql);
		foreach ($classList as $key => $val)
		{
			$goodsClassInfo[$val['id']] = $val['title'];
		}
		//查询 广告位置-列表
		$typeInfo = ClassLoad::Only('PromotionType')->getList();
		foreach($typeInfo as $key => $val){
			if($val['type'] == 1){
				$typeDataArr['classOne'][$val['code_key']] = $val['name'];
			}else{
				$typeDataArr['otherOne'][$val['code_key']] = $val['name'];
			}
		}
		$allData['goodsClassInfo'] 	= $goodsClassInfo;
		$allData['typeDataArr'] = $typeDataArr;
		
		return $allData;
	}
	
	/**
	 * 删除广告
	 *
	 * @param int $id
	 */
	public function clear($id)
	{
		if ($id)
		{
			return $this->delete('adver' , 'id=' .$id);
			GlobalAdver::flush();
		}else
		{
			return false;
		}
	}
	/**
	 * 检查广告名称 是否重名
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
		return (boolean)$this->queryRow("SELECT id FROM adver WHERE `{$tag}` = {$this->quoteValue($name)} {$SQL}");
	}
}
