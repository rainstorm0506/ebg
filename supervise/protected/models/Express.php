<?php
/**
 * 物流快递运费
 *
 * @author 涂先锋
 */
class Express extends SModels
{
	/**
	 * 得到 物流快递公司 列表
	 * @param	string	$keyword		搜索关键词
	 */
	public function getExpressList($keyword)
	{
		$SQL = '';
		if ($keyword)
		{
			$keyword = $this->quoteLikeValue($keyword);
			$SQL = "WHERE firm_name LIKE {$keyword} OR abbreviation LIKE {$keyword} OR contacts LIKE {$keyword} OR tel LIKE {$keyword}";
		}
		return $this->queryAll("SELECT * FROM express {$SQL} ORDER BY rank DESC , id ASC");
	}
	
	/**
	 * 检查 物流快递公司名称 是否重名
	 * @param		string		$name		物流快递公司名称
	 * @param		int			$id			ID
	 */
	public function checkFirmName($name , $id)
	{
		if (!$name) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM express WHERE `firm_name`={$this->quoteValue($name)} {$SQL}");
	}
	
	/**
	 * 添加 物流快递公司
	 * @param		array		$post		post
	 */
	public function create(array $post)
	{
		$ary = array(
			'firm_name'		=> (string)$post['firm_name'],
			'abbreviation'	=> (string)$post['abbreviation'],
			'address'		=> (string)$post['address'],
			'usable'		=> (int)$post['usable'],
			'contacts'		=> (string)$post['contacts'],
			'tel'			=> (string)$post['tel'],
			'website'		=> (string)$post['website'],
			'rank'			=> (int)$post['rank'],
			'time'			=> time()
		);
		$this->insert('express' , $ary);
		return $this->getInsertId();
	}
	
	/**
	 * 编辑 物流快递公司
	 * @param		array		$post		post
	 * @param		int			$id			等级ID
	 */
	public function modify(array $post , $id)
	{
		$ary = array(
			'firm_name'		=> (string)$post['firm_name'],
			'abbreviation'	=> (string)$post['abbreviation'],
			'address'		=> (string)$post['address'],
			'usable'		=> (int)$post['usable'],
			'contacts'		=> (string)$post['contacts'],
			'tel'			=> (string)$post['tel'],
			'website'		=> (string)$post['website'],
			'rank'			=> (int)$post['rank'],
			'time'			=> time()
		);
	
		return $this->update('express' , $ary , 'id='.$id);
	}
	
	/**
	 * 得到物流快递公司的信息
	 * @param		int		$id		会员等级ID
	 * @return		array
	 */
	public function getExpressInfo($id)
	{
		if (!$id) return array();
		return $this->queryRow("SELECT * FROM express WHERE `id`={$this->quoteValue($id)}");
	}
	
	/**
	 * 删除 物流快递公司
	 */
	public function deletes($id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('express_freight' , "express_id={$id}");
			$this->delete('express' , "id={$id}");
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	
	/**
	 * 物流快递公司 的运费设置总数
	 * @param		int		$expId		物流快递公司ID
	 */
	public function getFreightCount($expId)
	{
		return (int)$this->queryScalar("SELECT COUNT(*) FROM express_freight WHERE express_id={$expId}");
	}
	
	/**
	 * 物流快递公司 的运费设置列表
	 * @param		int			$expId		物流快递公司ID
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getFreightList($expId , $offset , $rows , $total)
	{
		if (!$expId || !$total || $offset>=$total)
			return array();
		
		return $this->queryAll("SELECT * FROM express_freight WHERE express_id={$expId} ORDER BY rank DESC LIMIT {$offset},{$rows}");
	}
	
	/**
	 * 获得物流公司的快递费用信息
	 * @param		int		$fid		费用ID
	 * @param		int		$expId		物流快递公司ID
	 */
	public function getFreightInfo($fid , $expId)
	{
		return $this->queryRow("SELECT * FROM express_freight WHERE id={$fid} AND express_id={$expId}");
	}
	
	/**
	 * 物流公司的快递费用信息的设置
	 * @param		array		$post		post
	 * @param		int			$fid		费用ID
	 * @param		int			$expId		物流快递公司ID
	 */
	public function freightSetting(array $post , $fid , $expId)
	{
		$post['express_id']		= $expId;
		$post['dict_one_id']	= empty($post['dict_one_id']) ? 0 : (int)$post['dict_one_id'];
		$post['dict_two_id']	= empty($post['dict_two_id']) ? 0 : (int)$post['dict_two_id'];
		$post['dict_three_id']	= empty($post['dict_three_id']) ? 0 : (int)$post['dict_three_id'];
		$post['dict_four_id']	= empty($post['dict_four_id']) ? 0 : (int)$post['dict_four_id'];
		
// 		if ($post['dict_one_unify']>0)
// 			$post['dict_two_id'] = $post['dict_three_id'] = $post['dict_four_id'] = 0;
// 		elseif ($post['dict_two_unify']>0)
// 			$post['dict_three_id'] = $post['dict_four_id'] = 0;
// 		elseif ($post['dict_three_unify']>0)
// 			$post['dict_four_id'] = 0;
// 		unset($post['dict_one_unify'] , $post['dict_two_unify'] , $post['dict_three_unify']);
		
		if ($fid > 0)
			$this->update('express_freight', $post , "id={$fid}");
		else
			$this->insert('express_freight', $post);
	}
	
	/**
	 * 检验运费是否设置
	 * @param	int		$one_id			地区表1级ID
	 * @param	int		$two_id			地区表2级ID
	 * @param	int		$three_id		地区表3级ID
	 * @param	int		$four_id		地区表4级ID
	 * @param	int		$expId			快递ID
	 * @param	int		$fid			物流快递 - 收费ID
	 */
	public function checkFreSet($one_id , $two_id , $three_id , $four_id , $expId , $fid)
	{
		$SQL = $fid ? " AND id!={$fid}" : '';
		return (bool)$this->queryRow("
			SELECT id FROM express_freight 
			WHERE express_id={$expId} AND dict_one_id={$one_id} AND dict_two_id={$two_id}
			AND dict_three_id={$three_id} AND dict_four_id={$four_id} {$SQL}
		");
	}
	
	public function freightDelete($expId , $freId)
	{
		return parent::delete('express_freight' , "id={$freId} AND express_id={$expId}");
	}
}
