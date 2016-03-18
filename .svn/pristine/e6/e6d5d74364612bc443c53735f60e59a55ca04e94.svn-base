<?php
/**
 * 会员等级设定
 *
 * @author 涂先锋
 */
class UserLayerSet extends SModels
{
	/**
	 * 得到 会员等级设定 列表
	 * @param	int		$type		用户类型
	 */
	public function getList($type)
	{
		$SQL = $type>0 ? "WHERE user_type={$type}" : '';
		return $this->queryAll("SELECT * FROM user_layer_setting {$SQL} ORDER BY user_type,start_exp ASC");
	}
	
	/**
	 * 检查 等级名称 是否重名
	 * @param		string		$name			等级名称
	 * @param		int			$id				等级ID
	 * @param		int			$userType		用户类型
	 */
	public function checkName($name , $id , $userType)
	{
		if (!$name) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM user_layer_setting WHERE user_type={$userType} AND `name`={$this->quoteValue($name)} {$SQL}");
	}
	
	/**
	 * 添加 会员等级
	 * @param		array		$post		post
	 */
	public function create(array $post)
	{
		$ary = array(
			'user_type'		=> (int)$post['user_type'],
			'name'			=> (string)$post['name'],
			'start_exp'		=> (int)$post['start_exp'],
			'end_exp'		=> (int)$post['end_exp'],
			'describe'		=> (string)$post['describe'],
			'goods_rate'	=> (double)$post['goods_rate'] * 0.01,
			'fraction_rate'	=> (double)$post['fraction_rate'] * 0.01,
			'exp_rate'		=> (double)$post['exp_rate'] * 0.01,
			'money_rate'	=> (double)$post['money_rate'] * 0.01,
			'free_freight'	=> (double)$post['free_freight'],
		);
		$this->insert('user_layer_setting' , $ary);
		return $this->getInsertId();
	}
	
	/**
	 * 编辑 会员等级
	 * @param		array		$post		post
	 * @param		int			$id			等级ID
	 */
	public function modify(array $post , $id = 0)
	{
		$ary = array(
			'user_type'		=> (int)$post['user_type'],
			'name'			=> (string)$post['name'],
			'start_exp'		=> (int)$post['start_exp'],
			'end_exp'		=> (int)$post['end_exp'],
			'describe'		=> (string)$post['describe'],
			'goods_rate'	=> (double)$post['goods_rate'] * 0.01,
			'fraction_rate'	=> (double)$post['fraction_rate'] * 0.01,
			'exp_rate'		=> (double)$post['exp_rate'] * 0.01,
			'money_rate'	=> (double)$post['money_rate'] * 0.01,
			'free_freight'	=> (double)$post['free_freight'],
		);
	
		return $this->update('user_layer_setting' , $ary , 'id='.$id);
	}
	
	/**
	 * 得到会员等级的信息
	 * @param		int		$id		会员等级ID
	 * @return		array
	 */
	public function getInfo($id)
	{
		if (!$id) return array();
		return $this->queryRow("SELECT * FROM user_layer_setting WHERE `id`={$this->quoteValue($id)}");
	}
	
	/**
	 * 删除 会员等级
	 */
	public function deletes($id)
	{
		return $this->delete('user_layer_setting' , 'id='.$id);
	}
	
	/**
	 * 获取用户动作行为的键值对
	 * 
	 * @param		int			$actVal		用户动作行为的值
	 */
	public function getUserAction($actVal = 0)
	{
		$cacheName = 'user_action';
		#CacheBase::clear($cacheName);
		if (!($cache = CacheBase::get($cacheName)))
		{
			$cache = array();
			if ($record = $this->queryAll("SELECT action_val,action_name FROM user_action_setting"))
			{
				foreach ($record as $val)
					$cache[$val['action_val']] = $val['action_name'];
				CacheBase::set($cacheName , $cache , 86400);
			}
		}
		
		if ($actVal === 0)
			return $cache;
		else
			return isset($cache[$actVal]) ? $cache[$actVal] : '';
	}
}
