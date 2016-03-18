<?php

/**
 * 促销活动-折扣类模型
 *
 * @author jeson.Q
 */
class Discount extends SModels {
	
	/**
	 * 编辑 折扣活动信息
	 * 
	 * @param array $post
	 * @param int $id
	 */
	public function modify(array $post, $id) {
		$id = ( int ) $id;
		$datastr = $flag = '';
		$attain_money = $privilege_money = array ();
		if ($post) {
			// 批量 安全过滤特殊字符和 脚步xss
			foreach ( $post as $key => $val ) {
				$datastr = $this->safe_replace ( $val );
				$post [$key] = $this->remove_xss ( $datastr );
			}
			// 组装字段数据
			$field = array ();
			$field ['discount']				= isset ( $post ['discount'] ) ? trim ( $post ['discount'] ) : '';
			$field ['title']				= isset ( $post ['title'] ) ? trim ( $post ['title'] ) : '';
			$field ['type']					= isset ( $post ['type'] ) ? ( int ) trim ( $post ['type'] ) : 0;
			$field ['description']			= isset ( $post ['description'] ) ? trim ( $post ['description'] ) : '';
			$field ['active_starttime'] 	= isset ( $post ['active_starttime'] ) ? trim ( $post ['active_starttime'] )." {$post['hour1']}:{$post['min1']}:{$post['sec1']}" : '';
			$field ['active_endtime'] 		= isset ( $post ['active_endtime'] ) ? trim ( $post ['active_endtime'] )." {$post['hour2']}:{$post['min2']}:{$post['sec2']}" : '';
			$field ['good_count'] 			= isset ( $post ['good_count'] ) ? ( int ) $post ['good_count'] : '';
			$field ['is_use'] 				= isset ( $post ['is_use'] ) ? ( int ) $post ['is_use'] : '';
			$field ['is_exemption_postage'] = isset ( $post ['is_exemption_postage'] ) ? ( int ) $post ['is_exemption_postage'] : '';
			$field ['privilege_cash']		= isset ( $post ['privilege_cash'] ) ? ( int ) $post ['privilege_cash'] : '';
			// 组装多个 满——减——金额数据			
			if (isset ( $post ['attain_money'] ) && is_array ( $post ['attain_money'] )) {
				foreach ( $post ['attain_money'] as $keys => $vals ) {
					if ($vals && ! in_array ( $vals, $attain_money )) {
						$attain_money [$keys] = $vals;
						$privilege_money [$keys] = ! empty ( $post ['privilege_money'] [$keys] ) ? $post ['privilege_money'] [$keys] : 0;
					}
				}
				$field ['attain_money'] = serialize ( $attain_money );
				$field ['privilege_money'] = serialize ( $privilege_money );
			}
			// 判断是 修改数据 还是 添加数据
			if ($id) {
				$flag = $this->update ( 'activities_reduction', $field, "id={$id}" );
			} else {
				$flag = $this->insert ( 'activities_reduction', $field );
			}
			
			return $flag;
		}
		return false;
	}
	
	/**
	 * 获得 单个折扣活动的信息
	 * 
	 * @param int $id
	 */
	public function getActiveInfo($id) {
		$id = ( int ) $id;
		$info = array ();
		if ($id) {
			$sql = "SELECT * FROM activities_reduction WHERE id={$id}";
			$info = $this->queryRow ($sql);
			return $info;
		} else {
			return array ();
		}
	}
	
	/**
	 * 查询折扣活动 列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @param int $typeId
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($keyword = '', $offset, $limit = 20, $typeId = null) {
		$where = $sql = $limits = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and (id = {$keyword} OR title like '%".$keyword."%') " : '';
		// 判断是否来至单个栏目
		$where .= $typeId ? " and type = {$typeId} " : '';
		$limits = " {$offset},{$limit} ";
		$sql = "SELECT * FROM activities_reduction WHERE 1=1 {$where} limit {$limits}";
		return $this->queryAll ( $sql );
	}
	
	
	/**
	 *
	 * 统计折扣活动 总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($keyword = '', $typeId = null) {
		$where = $sql = '';
		// 判断是否关键字收索
		$where .= $keyword ? " and (id = {$keyword} OR title like '%".$keyword."%') " : '';
		// 判断是否来至单个栏目
		$where .= $typeId ? " and type = {$typeId} " : '';
		$sql = "SELECT count(*) FROM activities_reduction WHERE 1=1 {$where}";
		return (int)$this->queryScalar( $sql );
	}
	
	/**
	 * 折扣活动 折扣活动
	 * 
	 * @param int $id
	 */
	public function clear($id) {
		if ($id = ( int ) $id) {
			return $this->delete ( 'activities_reduction', 'id=' . $id );
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
			$sql = "SELECT * FROM goods where class_three_id = {$parent_id} order by id desc limit 0,50";
		} else{
			$sql = "SELECT * FROM goods where brand_id = {$parent_id} order by id desc limit 0,50";
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
