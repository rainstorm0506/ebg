<?php
class YellowPage extends ApiModels{
	/**
	 * 列表
	 */
	public function getList($search , $pageNow , $pageSize) {
		$pageNow=($pageNow-1)*$pageSize;
		$total=$this->getListCount($search);
		if (!$total || $pageNow>=$total)
			return array();
		
		if ($SQL = $this->_getListSQL($search , 'list'))
			return $this->queryAll($SQL . " ORDER BY y.time DESC LIMIT {$pageNow},{$pageSize}");
		else
			return array();
	}
	/**
	 * 搜索满足条件的商家总数
	 * @param 搜索条件 $search
	 */
	public function getListCount($search)
	{
		$SQL = $this->_getListSQL($search , 'count');
		$row=$this->queryAll($SQL);
		return $SQL ? (int)count($row) : 0;
	}
	/**
	 * 构建SQL语句
	 */
	public function _getListSQL($search,$type){
		static $returned = array();
		if (isset($returned[$type]))
			return $returned[$type];
		
		$SQL='';
		$table='';
		$keyword	=	$search['keyword'];
		$scope		=	$search['scope'];
		$gather		=	$search['gather'];
		unset($search['keyword'],$search['scope'],$search['brand'],$search['gather']);
		if ($keyword)
		{
			$table	.="LEFT JOIN supply_goods AS g ON g.merchant_id=y.mer_id";
			if($keyword && !is_numeric($keyword)){
				$keyword = $this->quoteLikeValue($keyword);
				$SQL .= " g.title LIKE {$keyword} OR y.title LIKE {$keyword}";
			}
		}
		if ($scope && is_numeric($scope)){
			if(!empty($SQL))
				$SQL .=" AND ";
				
			$SQL 	.= " s.id={$scope}";
		}
		if($gather && is_numeric($scope)){
			if(!empty($SQL))
				$SQL .=" AND ";

			$SQL 	.= " y.gather={$gather}";
		}
		if(!empty($SQL))
			$SQL=' WHERE'.$SQL;
			
		$returned['count']  = "SELECT COUNT(*)
			FROM yellow_page AS y
			LEFT JOIN yellow_page_scope_business AS b ON b.mer_id=y.mer_id
			LEFT JOIN scope_business AS s ON s.id=b.sb_id
			{$table} {$SQL} GROUP BY y.mer_id";
		$returned['list']   = "SELECT y.title,y.mer_id,s.title AS scope
			FROM yellow_page AS y
			LEFT JOIN yellow_page_scope_business AS b ON b.mer_id=y.mer_id
			LEFT JOIN scope_business AS s ON s.id=b.sb_id
			{$table} {$SQL} GROUP BY y.mer_id";

		return isset($returned[$type]) ? $returned[$type] : '';
	}
	/**
	 * 检查公司名称
	 */
	public function checkTitle($title,$id) {
		$SQL = $id>0 ? " AND mer_id!={$id}" : '';
		return $title && (boolean)$this->queryRow("SELECT id FROM yellow_page WHERE `title`={$this->quoteValue($title)} {$SQL}");
	}
	/**
	 * 添加黄页
	 */
	public function create($form){
		$merchant_id=$this->getMerchantID();
		$scope=$form->scope;
		$arr=array(
			'title'			=>	$form->title,
			'gather'		=>	$form->gather,
			'mer_id'		=>	$merchant_id,
			'address'		=>	$form->address,
			'content'		=>	$form->content,
			'phone'			=>	$form->phone,
			'is_phone'		=>	$form->is_phone,
			'landline'		=>	$form->landline,
			'is_landline'	=>	$form->is_landline,
			'qq'			=>	$form->qq,
			'is_qq'			=>	$form->is_qq,
			'weixin'		=>	$form->weixin,
			'is_weixin'		=>	$form->is_weixin,
			'time'			=>	time(),
			'scope'			=>	json_encode($scope)
		);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try {
			$this->insert('yellow_page', $arr);
			//主营范围
			foreach($scope as $v){
				if(!empty($v)){
					$arr1=array(
						'mer_id'	=>	$merchant_id,
						'sb_id'		=>	$v
					);
					$this->insert('yellow_page_scope_business',$arr1);
				}
			}
			$transaction->commit();
			return true;
		} catch (Exception $e) {
			$transaction->rollBack();
			return false;
		}
	}
}