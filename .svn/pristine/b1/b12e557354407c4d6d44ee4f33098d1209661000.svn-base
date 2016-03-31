<?php
class ActFree extends SModels
{
	/**
	 * 得到列表的总数
	 */
	public function getGoodsCount()
	{
		return (int)$this->queryScalar("SELECT COUNT(*) FROM act_buy_free");
	}
	/**
	 * 列表
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getList($offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();

			return $this->queryAll("SELECT * FROM act_buy_free LIMIT {$offset},{$rows}");
	}
	/**
	 * 检查标题
	 */
	public function checkTitle($title , $id)
	{
		$SQL = $id>0 ? " AND id!={$id}" : '';
		return $title && (boolean)$this->queryRow("SELECT id FROM act_buy_free WHERE `title`={$this->quoteValue($title)} {$SQL}");
	}
	/**
	 * 添加零元购
	 */
	public function create($post=array())
	{
		$data=array(
			'title'		=> $post['title'] ,
			'userexp'	=> $post['userexp'],
			'companyexp'=> $post['companyexp'],
			'purlimit'	=> $post['userlimitce'] ,
			'day_limit'	=> $post['userdaylimit'],
			'remark'	=> $post['remark'],
			'rank'		=> $post['rank'],
			'time'		=> time()
		);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->insert('act_buy_free' , $data);
			$aid=$this->getInsertId();

			foreach($post['gids'] as $k=>$v)
			{
				$row=array(
					'aid'		=>	$aid ,
					'gid'		=>	$v ,
					'condition'	=>	$post['price'][$k],
					'stock'		=>	$post['nums'][$k],
					'user_limit'=>	$post['onece'][$k]
				);
				$this->insert('act_free_extend' , $row);
			}

			foreach($post['start'] as $k=>$v)
			{
				$arr=array(
					'aid'		=>	$aid ,
					'start_time'=>	strtotime($v),
					'end_time'	=>	strtotime($post['end'][$k])
				);
				$this->insert('act_free_time' , $arr);
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
	}
	/**
	 * 活动详情
	 */
	public function getInfo($id)
	{
		$row=$this->queryRow("SELECT * FROM act_buy_free WHERE id={$id}");
		$row['times']=$this->queryAll("SELECT start_time,end_time FROM act_free_time WHERE aid={$id}");
		$row['goods']=$this->queryAll("SELECT a.gid,a.condition,a.stock,g.title,a.user_limit,g.stock AS gstock,g.original_price FROM act_free_extend AS a LEFT JOIN act_goods AS g ON g.id=a.gid WHERE aid={$id}");
		return $row;
	}
	/**
	 * 删除
	 */
	public function clear($id)
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('act_buy_free' , 'id='.$id);
			$this->delete('act_free_extend' , 'aid='.$id);
			$this->delete('act_free_time' , 'aid='.$id);

			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
	}
	/**
	 * 修改
	 */
	public function modify($post)
	{
		$aid=$post['id'];
		$data=array(
			'title'		=> $post['title'] ,
			'userexp'	=> $post['userexp'],
			'companyexp'=> $post['companyexp'],
			'purlimit'	=> $post['userlimitce'] ,
			'day_limit'	=> $post['userdaylimit'],
			'remark'	=> $post['remark'],
			'rank'		=> $post['rank'],
			'time'		=> time()
		);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//删除零元购商品
			$this->delete('act_free_extend' , 'aid='.$aid);
			//删除零元购时间段
			$this->delete('act_free_time' , 'aid='.$aid);

			//修改零元购
			$this->update('act_buy_free' , $data , 'id='.$aid);

			//添加零元购商品
			foreach($post['gids'] as $k=>$v)
			{
				$row=array(
					'aid'		=>	$aid ,
					'gid'		=>	$v ,
					'condition'	=>	$post['price'][$k],
					'stock'		=>	$post['nums'][$k],
					'user_limit'=>	$post['onece'][$k]
				);
				$this->insert('act_free_extend' , $row);
			}

			//添加零元购时间段
			foreach($post['start'] as $k=>$v)
			{
				$arr=array(
					'aid'		=>	$aid ,
					'start_time'=>	strtotime($v),
					'end_time'	=>	strtotime($post['end'][$k])
				);
				$this->insert('act_free_time' , $arr);
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
	}
}