<?php

/**
 * 促销活动-满减类模型
 *
 * @author jeson.Q
 */
class Reduction extends SModels {
	
	/**
	 * 编辑 满减活动信息
	 * 
	 * @param array $post
	 * @param int $id
	 */
	public function modify(array $post, $id) {
		$id = ( int ) $id;
		$datastr = $flag = '';
		$expire = $minus = $attain_money = array ();
		if ($post) {
			// 组装字段数据
			$field = array ();
			$field ['title']				= isset ( $post ['title'] ) ? trim ( $post ['title'] ) : '';
			$field ['description']			= isset ( $post ['description'] ) ? trim ( $post ['description'] ) : '';
			$field ['active_starttime'] 	= isset ( $post ['active_starttime'] ) ?  strtotime($post ['active_starttime']) : '';
			$field ['active_endtime'] 		= isset ( $post ['active_endtime'] ) ? strtotime($post ['active_endtime']) : '';
			$field ['is_use'] 				= isset ( $post ['is_use'] ) ? ( int ) $post ['is_use'] : '';
			
			// 判断是 修改数据 还是 添加数据
			if ($id) {
				$flag = $this->update ( 'activities_reduction', $field, "aid={$id}" );
			} else {
				$flag = $this->insert ( 'activities_reduction', $field );
				$id=$this->getInsertId();
			}
			
			if($id)
			{
				$this->delete('activities_reduction_relevance' , "aid={$id}");
			}
			
			// 组装多个 满——减——金额数据
			if (isset ( $post ['attain_money'] ) && is_array ( $post ['attain_money'] )) {
				foreach ( $post ['attain_money'] as $keys => $vals ) {
					if ($vals && ! in_array ( $vals, $attain_money )) {
						$attain_money[]=$vals;
						$relevance['expire'] = $vals;
						$relevance['aid'] = isset($id) ? $id : 0;
						$relevance['minus'] = ! empty ( $post ['privilege_money'] [$keys] ) ? $post ['privilege_money'] [$keys] : 0;
						$this->insert ( 'activities_reduction_relevance', $relevance );
					}
				}
			}
			return $flag;
		}
		return false;
	}
	
	/**
	 * 获得 单个满减活动的信息
	 * 
	 * @param int $id
	 */
	public function getActiveInfo($id) {
		$id = ( int ) $id;
		$info = array ();
		if ($id) {
			$sql = "SELECT * FROM activities_reduction WHERE aid={$id}";
			$info = $this->queryRow ($sql);
			$info['actList'] = $this->getRelevanceInfo($id);
			return $info;
		} else {
			return array ();
		}
	}
	
	/**
	 * 获得 详细满减活动列表
	 *
	 * @param int $id
	 */
	public function getRelevanceInfo($id) {
		$id = ( int ) $id;
		$activeList = array ();
		if ($id) {
			$sql = "SELECT * FROM activities_reduction_relevance WHERE aid = {$id}";
			$activeList = $this->queryAll ($sql);
			return $activeList;
		} else {
			return array ();
		}
	}
	
	/**
	 * 查询满减活动 列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @param int $typeId
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($keyword = '', $offset, $limit = 20) {
		$where = $sql = $limits = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and (aid = '{$keyword}' OR title like '%".$keyword."%') " : '';
		$limits = " {$offset},{$limit} ";
		$sql = "SELECT * FROM activities_reduction WHERE 1=1 {$where} order by aid desc limit {$limits}";
		return $this->queryAll ( $sql );
	}
	
	
	/**
	 *
	 * 统计满减活动 总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($keyword = '') {
		$where = $sql = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and (aid = '{$keyword}' OR title like '%".$keyword."%') " : '';
		$sql = "SELECT count(*) FROM activities_reduction WHERE 1=1 {$where}";
		return (int)$this->queryScalar( $sql );
	}
	
	/**
	 * 删除 满减活动
	 * 
	 * @param int $id
	 */
	public function clear($id) {
		if ($id = (int) $id) {
			return $this->delete ( 'activities_reduction', 'aid=' . $id );
		}
		return false;
	}
	
	/**
	 * 获得商品类型列表
	 * 
	 * @param int $parent_id
	 */
	public function getGoodsType($parent_id = 0, $tier = 3) {
		$parent_id = trim ( $parent_id );
		$sql = "SELECT * FROM goods_class where tier=$tier";
		return $row = $this->queryAll ($sql);
	}
	
	/**
	 * 获得商品列表 通过品牌id 分类id
	 * 
	 * @param int $parent_id
	 */
	public function getGoodList($parent_id = 0, $type = 0) {
		$parent_id = trim ( $parent_id );
		if ($type == 1) {
			$sql = "SELECT * FROM goods where category_id = {$parent_id} order by id desc limit 0,50";
		} elseif ($type == 2) {
			$sql = "SELECT * FROM goods where brand_id = {$parent_id} order by id desc limit 0,50";
		} else {
		}
		return $row = $this->queryAll ($sql);
	}
	
	/**
	 * 获得商品列表 搜索
	 * 
	 * @param
	 * search
	 */
	public function getGoodByKeywords($keywords) {
		$sql = "SELECT * FROM goods where concat(title,id,sub_title) like '%{$keywords}%'";
		return $row = $this->queryAll ($sql);
	}
	/**
	 * 获得品牌列表
	 * 
	 * @param
	 * search
	 */
	public function getGoodsBrand($parent_id = 0) {
		$parent_id = trim ( $parent_id );
		$sql = "SELECT * FROM goods_brand";
		return $row = $this->queryAll ($sql);
	}
}
