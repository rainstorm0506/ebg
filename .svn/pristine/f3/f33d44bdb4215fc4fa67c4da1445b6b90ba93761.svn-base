<?php
/**
 * 产品品牌
 * 
 * @author 涂先锋
 *
 */
class GoodsBrand extends SModels
{
	public function getSelectList()
	{
		$list = array();
		foreach (GlobalBrand::getAllList() as $id => $val)
			$list[$id] = join(' / ' , array_filter(array($val['en_name'] , $val['zh_name']))) . (empty($val['is_using'])?' (不可用)':'');
		
		return $list;
	}
	/**
	 * 列表筛选条件
	 */
	public function getWhere($search){
		$SQL='';
		$keyword = $search['keyword'];
		unset($search['keyword']);
		if(!empty($keyword)){
			$keyword = $this->quoteLikeValue($keyword);
			$SQL.= " zh_name LIKE {$keyword} OR en_name LIKE {$keyword} ";
		}
		if(!empty($search['goods_one_id'])||!empty($search['goods_two_id'])||!empty($search['goods_three_id'])){
			$SQL.=empty($SQL)?" j.type=1 ":" AND j.type=1 ";
			$SQL.=empty($search['goods_one_id'])?"":" AND j.class_one_id={$search['goods_one_id']} ";
			$SQL.=empty($search['goods_two_id'])?"":" AND j.class_two_id={$search['goods_two_id']} ";
			$SQL.=empty($search['goods_three_id'])?"":" AND j.class_three_id={$search['goods_three_id']} ";
		}
		if(!empty($search['used_one_id'])||!empty($search['used_two_id'])||!empty($search['used_three_id'])){
			$SQL.=empty($SQL)?" j.type=2 ":" AND j.type=2 ";
			$SQL.=empty($search['used_one_id'])?"":" AND j.class_one_id={$search['used_one_id']} ";
			$SQL.=empty($search['used_two_id'])?"":" AND j.class_two_id={$search['used_two_id']} ";
			$SQL.=empty($search['used_three_id'])?"":" AND j.class_three_id={$search['used_three_id']} ";
		}
		$SQL=empty($SQL)?'':' WHERE '.$SQL;
		return $SQL;
	}
	/**
	 * 品牌总数
	 * @param		string		$keyword	搜索关键字
	 */
	public function getBrandCount($search)
	{

		$SQL = $this->getWhere($search);
		return (int)$this->queryScalar("SELECT COUNT(*) FROM goods_brand AS b
			LEFT JOIN goods_brand_join_class AS j ON j.brand_id=b.id {$SQL}");
	}
	
	/**
	 * 品牌列表
	 * @param		string		$keyword	搜索关键字
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getBrandList($search , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
	
		$SQL = $this->getWhere($search);
		
		return $this->queryAll("SELECT * FROM goods_brand AS b
			LEFT JOIN goods_brand_join_class AS j ON j.brand_id=b.id{$SQL} GROUP BY b.id ORDER BY rank DESC LIMIT {$offset},{$rows}");
	}
	
	/**
	 * 检查 品牌名称 是否重名
	 * @param		string		$name		品牌名称
	 * @param		string		$tag		中文品牌/英文品牌
	 * @param		int			$id			品牌ID
	 * @return		boolean
	 */
	public function checkName($name , $tag , $id)
	{
		if (!$name) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM goods_brand WHERE `{$tag}`={$this->quoteValue($name)} {$SQL}");
	}
	
	/**
	 * 根据ID得到品牌的信息
	 * @param		int			$id			品牌ID
	 */
	public function getBrandInfo($id)
	{
		if($row = $this->queryRow("SELECT * FROM goods_brand WHERE id={$id}"))
			$row['class']=$this->queryAll("SELECT type,class_three_id FROM goods_brand_join_class WHERE brand_id={$id}");

		return $row;
	}
	
	/**
	 * 添加一个品牌
	 * @param		array		$post		post
	 */
	public function create(array $post)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('goods_brand', array(
				'zh_name'	=> $post['zh_name'],
				'en_name'	=> $post['en_name'],
				'is_using'	=> (int)$post['is_using'],
				'rank'		=> (int)$post['rank'],
				'time'		=> time(),
				'logo'		=> empty($post['logo'])?'':$this->getPhotos($post['logo'] , 'brand'),
			));
			$brand_id = $this->getInsertId();
			if(!empty($post['goods_class'])){
				foreach($post['goods_class'] as $v){
					$arr=explode('-',$v);
					$this->insert('goods_brand_join_class',array(
						'type'			=>	1,
						'brand_id'		=>	$brand_id,
						'class_one_id'	=>	$arr[0],
						'class_two_id'	=>	$arr[1],
						'class_three_id'=>	$arr[2]
					));
				}
			}

			if(!empty($post['used_class'])){
				foreach($post['used_class'] as $v){
					$arr=explode('-',$v);
					$this->insert('goods_brand_join_class',array(
						'type'			=>	2,
						'brand_id'		=>	$brand_id,
						'class_one_id'	=>	$arr[0],
						'class_two_id'	=>	$arr[1],
						'class_three_id'=>	$arr[2]
					));
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	/**
	 * 编辑一个品牌
	 * @param		array		$post		post
	 * @param		int			$id			品牌ID
	 */
	public function modify(array $post , $id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('goods_brand_join_class','brand_id='.$id);
			$this->update('goods_brand', array(
				'zh_name'	=> $post['zh_name'],
				'en_name'	=> $post['en_name'],
				'is_using'	=> (int)$post['is_using'],
				'rank'		=> (int)$post['rank'],
				'logo'		=> empty($post['logo'])?'':$this->getPhotos($post['logo'] , 'brand'),
			) , "id={$id}");
			if(!empty($post['goods_class'])){
				foreach($post['goods_class'] as $v){
					$arr=explode('-',$v);
					$this->insert('goods_brand_join_class',array(
						'type'			=>	1,
						'brand_id'		=>	$id,
						'class_one_id'	=>	$arr[0],
						'class_two_id'	=>	$arr[1],
						'class_three_id'=>	$arr[2]
					));
				}
			}

			if(!empty($post['used_class'])){
				foreach($post['used_class'] as $v){
					$arr=explode('-',$v);
					$this->insert('goods_brand_join_class',array(
						'type'			=>	2,
						'brand_id'		=>	$id,
						'class_one_id'	=>	$arr[0],
						'class_two_id'	=>	$arr[1],
						'class_three_id'=>	$arr[2]
					));
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	/**
	 * 删除一个品牌
	 * @param		int			$id			品牌ID
	 * @see ExtModels::delete()
	 */
	public function deletes($id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('goods_brand_join_class','brand_id='.$id);
			$this->delete('goods_brand' , "id={$id}");

			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
}
