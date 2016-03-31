<?php
/**
 * 管理员
 *
 * @author 涂先锋
 */
class Governor extends SModels
{
	/**
	 * 得到管理员列表
	 * @param		string		$keyword	搜索关键字
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getGoverList($keyword , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();

		$keyword = $this->quoteLikeValue($keyword);
		return $this->queryAll("
			SELECT g.* , b.name AS branch_name
			FROM back_governor AS g
			INNER JOIN back_governor_branch AS b ON g.branch_id=b.id
			WHERE g.account LIKE {$keyword} OR g.true_name LIKE {$keyword} OR g.number LIKE {$keyword} OR b.name LIKE {$keyword}
			LIMIT {$offset},{$rows}");
	}

	/**
	 * 得到列表的总数
	 * @param		string		$keyword	搜索关键字
	 */
	public function getGoverCount($keyword)
	{
		$keyword = $this->quoteLikeValue($keyword);
		return (int)$this->queryScalar("
			SELECT COUNT(*)
			FROM back_governor AS g
			INNER JOIN back_governor_branch AS b ON g.branch_id=b.id
			WHERE g.account LIKE {$keyword} OR g.true_name LIKE {$keyword} OR g.number LIKE {$keyword} OR b.name LIKE {$keyword}
		");
	}

	/**
	 * 获得管理员信息
	 * @param	int		$id		管理员ID
	 */
	public function getGovernorInfo($id = 0)
	{
		if ($row = $this->queryRow("SELECT * FROM back_governor WHERE id=" .(int)$id))
			$row['roles'] = $this->jsonDnCode($row['roles']);
		return $row;
	}

	/**
	 * 添加管理员
	 * @param		array		$post		post
	 */
	public function create(array $post)
	{
		$purviews = array();
		foreach ($post['purviews'] as $key => $v)
		{
			if ((int)$v)
				$purviews[] = (int)$key;
		}

		$this->insert('back_governor' , array(
			'account'	=> $post['account'],
			'password'	=> $this->hashPassword($post['password']),
			'branch_id'	=> (int)$post['branch_id'],
			'true_name'	=> $post['true_name'],
			'number'	=> $post['number'],
			'sex'		=> isset($post['sex']) ? (int)$post['sex'] : 0,
			'remark'	=> isset($post['remark']) ? $post['remark'] : '',
			'roles'		=> json_encode($purviews),
			'time'		=> time()
		));
		return $this->getInsertId();
	}

	/**
	 * 编辑管理员
	 * @param		array		$post		post
	 * @param		int			$id			管理员ID
	 */
	public function modify(array $post , $id = 0)
	{
		$purviews = array();
		foreach ($post['purviews'] as $key => $v)
		{
			if ((int)$v)
				$purviews[] = (int)$key;
		}

		$ary = array();
		if ($post['password'])
			$ary['password']	= $this->hashPassword($post['password']);

		$ary['true_name']		= $post['true_name'];
		$ary['branch_id']		= (int)$post['branch_id'];
		$ary['number']			= $post['number'];
		$ary['sex']				= isset($post['sex']) ? (int)$post['sex'] : 0;
		$ary['remark']			= isset($post['remark']) ? $post['remark'] : '';
		$ary['roles']			= json_encode($purviews);

		return $this->update('back_governor' , $ary , 'id='.$id);
	}

	/**
	 * 删除管理员
	 * @param		int			$id			管理员ID
	 */
	public function clear($id)
	{
		return $this->delete('back_governor' , 'id='.$id);
	}

	/**
	 * 修改我的密码
	 * @param		array		$post		post
	 * @param		int			$id			管理员ID
	 */
	public function editToPassword(array $post , $uid = 0)
	{
		$this->update('back_governor' , array(
			'password' => $this->hashPassword($post['password_new2'])
		) , 'id='.$uid);
	}

	/**
	 * 得到管理员的信息
	 * @param		string		$account		用户名称
	 * @return		array
	 */
	public function getUserInfo($account)
	{
		if (!$account) return array();
		return $this->queryRow("SELECT * FROM back_governor WHERE `account`={$this->quoteValue($account)} LIMIT 0,1");
	}

	/**
	 * 检查给定的密码是否正确
	 * @param		string		$writePassword		密码(明文字符串)
	 * @param		string		$dbPassword			数据库中存储加密后的密码值
	 * @return		boolean
	 */
	public function validatePassword($writePassword , $dbPassword)
	{
		return GlobalUser::validatePassword($writePassword, $dbPassword);
	}

	/**
	 * 生成的密码散列
	 * @param		string		$password		密码(明文字符串)
	 * @return		string
	 */
	public function hashPassword($password)
	{
		return GlobalUser::hashPassword($password);
	}

	/**
	 * 检查 管理员帐号 是否重名
	 * @param		string		$account		帐号
	 * @param		int			$id				管理员ID
	 */
	public function checkAccount($account , $id)
	{
		if (!$account) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM back_governor WHERE `account`={$this->quoteValue($account)} {$SQL}");
	}

	/**
	 * 检查 管理员编号
	 * @param		string		$number		 编号
	 * @param		int			$id			管理员ID
	 */
	public function checkNumber($number , $id)
	{
		if (!$number) return false;
		$SQL = $id ? "AND id!={$id}" : '';
		return (boolean)$this->queryRow("SELECT id FROM back_governor WHERE `number`={$this->quoteValue($number)} {$SQL} LIMIT 0,1");
	}

	/**
	 * 检查 当前密码
	 * @param		string		$password		密码
	 * @param		int			$id				管理员ID
	 * @return		boolean
	 */
	public function checkPasswordCurrent($password)
	{
		if (!$password) return false;
		$user = Yii::app()->getUser()->getName();
		return $this->validatePassword($password, $user['password']);
	}

	/**
	 * 更新管理员的登录时间
	 * @param	int		$uid		管理员ID
	 */
	public function userLoginTime($uid)
	{
		return $this->update('back_governor' , array('login_time'=>time()) , "id={$uid}");
	}

	/**
	 * 获得管理员的权限
	 * @param		array		$pids		角色ID数组
	 */
	public function getUserPurviews(array $pids)
	{
		if (!$pids) return array();

		$dataTemp = array();
		$dataTemp = $this->queryAll("SELECT fields,purviews FROM back_role WHERE id IN (".implode(',', $pids).")");

		$res = array('fields'=>array() , 'purviews'=>array());
		foreach ($dataTemp as $vs)
		{
			$temp = $this->jsonDnCode($vs['fields']);
			$res['fields'] = CMap::mergeArray($res['fields'] , $temp && is_array($temp) ? $temp : array());

			$temp = $this->jsonDnCode($vs['purviews']);
			$res['purviews'] = array_merge($res['purviews'] , $temp && is_array($temp) ? $temp : array());
		}
		return $res;
	}

	//后台的导航栏
	public function getBackField()
	{
		$cacheName = 'backField';
		CacheBase::clear($cacheName);
		 if (!($cache = CacheBase::get($cacheName)))
		{ 
			$cache = array();
			if ($record = $this->queryAll("SELECT * FROM back_role_fields ORDER BY rank ASC"))
			{   
				foreach ($record as $val)
				{
					$val['params'] = $this->jsonDnCode($val['params']);
					$val['params'] = is_array($val['params']) ? $val['params'] : array();

					$val['show_css'] = $this->jsonDnCode($val['show_css']);
					$val['show_css'] = is_array($val['show_css']) ? $val['show_css'] : array();

					if ($val['parent_id'])
						$cache[$val['parent_id']]['child'][$val['id']] = $val;
					else
						$cache[$val['id']] = isset($cache[$val['id']]) ? $cache[$val['id']] : $val;
				}
				CacheBase::set($cacheName , $cache , 0);
			}
		}
		return $cache;
	}
}
