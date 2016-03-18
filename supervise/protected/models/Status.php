<?php

/**
 * 全局状态管理管理模型类
 * @auther jeson.Q 
 * 
 *@table adver
 */
class Status extends SModels
{
    /**
     * @return string 设置表名
     */
    public function tableName()
    {
        return 'status';
    }

    /**
     *
     * @return Promotion模型类
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	/**
	 * 编辑 全局状态管理信息
	 * @param	array	$post		post
	 * @param	int		$id			ID
	 */
	public function modifys(array $post , $id)
	{
		$id = (int)$id;$datastr = '';
		if($post){
			//批量 安全过滤特殊字符和 脚步xss
			//组装数据
			$field				= 	array();
			$field['user_title']		= 	trim($post['user_title']);
			$field['merchant_title']		= 	trim($post['merchant_title']);
			$field['back_title']		= 	trim($post['back_title']);
			$field['user_describe']	= 	trim($post['user_describe']);
			$field['merchant_describe']	= 	trim($post['merchant_describe']);
			$field['back_describe']	= 	trim($post['back_describe']);
			
			//判断是 修改数据 还是 添加数据
			if($id){			
				$flag = $this->update($this->tableName() , $field , "id={$id}");
				//刷新状态
				/* CacheBase::setCache('file');
				CacheBase::clear('GlobalStatus');
				
				CacheBase::setCache('memCache');
				CacheBase::clear('GlobalStatus'); */
			}else{
				$flag = $this->insert($this->tableName() , $field );
			}
			return $flag;
		}else{
			return false;
		}	
	}
	
	/**
	 * 获得 单个全局状态管理的信息
	 * @param		int			$id			栏位ID
	 */
	public function getActiveInfo($id)
	{
		$id = (int)$id;
		if (!$id) return array();
		$info = array();
		$sql  = "SELECT * FROM ".$this->tableName()." WHERE id={$id}";
		$info = $this->queryRow($sql);
		return $info;
	}

	/**
	 * 查询 全局状态管理列表
	 * @param string $type
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($type = '',$offset=0,$limit = 20)
	{		
		$params = $commentInfo = array();$where = '';

		//判断是否关键字收索
		if ($type)
		{
			$where = " WHERE  type = {$type}";
		}
		
		//组装sql 语句
		return $this->queryAll("SELECT * from {$this->tableName()} {$where} limit {$offset},{$limit}");
	}

	/**
	 * 
	 * 统计全局状态管理总数
	 * @param string $type
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($type = '')
	{
		$where='';
		if ($type) {
		   $where = "  where type = {$type}";
		}
		$sql = "select count(*) as sum from  ".$this->tableName().$where; 
		$result = $this->queryAll($sql);
		return $result[0]['sum'];		
	}
}
