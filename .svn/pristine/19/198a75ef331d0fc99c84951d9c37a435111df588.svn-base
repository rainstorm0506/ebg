<?php

/**
 * 活动 模型类
 * @author GL
 * 
 * @table content
 */
class Active extends SModels {
	/**
	 * 获取活动列表
	 * */
	public function getActiveList($data,$start,$end,$offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
		{
			return array();
		}
		$userlayer 		= ClassLoad::Only("UserLayerSet");
		$sql 			= 'SELECT l.* FROM `act_list` as l LEFT JOIN `act_attribute` as at ON l.`id` = at.aid';
		$where 			= ' WHERE 1=1';
		if($data)
		{
			if($data['title'])
			{
				$where .= ' AND l.`title` LIKE "%'. $data['title'] .'%"';
			}
			if($data['id'])
			{
				$where .= ' AND l.`id`= '.$data['id'];
			}
			if($data['type'])
			{
				$where .= ' AND l.`type`= '.$data['type'];
			}
			if($data['status'])
			{
				$where .= ' AND l.`display`= '.$data['status'];
			}
			if(!empty($start))
			{
				$where .= ' AND (at.`starttime` > '.$start.' or at.`starttime` = '.$start.')';
			}
			if(!empty($end))
			{
				$where .= ' AND (at.`endtime` < '.$end.' or at.`endtime` = '.$end.')';
			}
		}
		$where .= ' GROUP BY l.id ORDER BY l.id DESC ';
		$sql  .= $where.' LIMIT '.$offset.','.$rows;
		$list = $this->queryAll($sql);

		foreach($list as $key=>$val)
		{
			if($val['type'] == 1)
			{
				$list[$key]['type'] = '秒杀';
			}
			if($val['type'] == 2)
			{
				$list[$key]['type'] = '折购';
			}
			if($val['type'] == 3)
			{
				$list[$key]['type'] = '特价专区';
			}
			if($val['userexp']=='all' && $val['companyexp']=='all')
			{
				$list[$key]['exp'] = '所有人可参加';
			}
			if($val['userexp']=='all' && $val['companyexp']=='no')
			{
				$list[$key]['exp'] = '所有个人会员可参加';
			}
			if($val['userexp']=='no' && $val['companyexp']=='all')
			{
				$list[$key]['exp'] = '所有企业会员可参加';
			}
			if($val['userexp']=='no' && $val['companyexp'] !='all' && $val['companyexp'] !='no')
			{
				$info = $userlayer->getInfo($val['companyexp']);
				$list[$key]['exp'] = $info['name'];
			}
			if($val['companyexp']=='no' && $val['userexp'] !='all' && $val['userexp'] !='no')
			{
				$info = $userlayer->getInfo($val['userexp']);
				$list[$key]['exp'] = $info['name'];
			}
			if($val['display'] == 1)
			{
				$list[$key]['status'] = '未开始';
			}
			if($val['display'] == 2)
			{
				$list[$key]['status'] = '已开始';
			}
			if($val['display'] == 3)
			{
				$list[$key]['status'] = '已结束';
			}
			$times = $this->queryAll('SELECT starttime,endtime FROM `act_attribute`  WHERE aid = '.$val['id']);
			$list[$key]['date'] = $times;
		}

		return $list;
	}
	/**
	 * 获取活动总数
	 * */
	public function getCountItem($data,$start,$end)
	{
		$where 			= ' WHERE 1=1';
		if($data)
		{
			if($data['title'])
			{
				$where .= ' AND l.`title` LIKE "%'. $data['title'] .'%"';
			}
			if($data['id'])
			{
				$where .= ' AND l.`id`= '.$data['id'];
			}
			if($data['type'])
			{
				$where .= ' AND l.`type`= '.$data['type'];
			}
			if($data['status'])
			{
				$where .= ' AND l.`display`= '.$data['status'];
			}
			if(!empty($start))
			{
				$where .= ' AND (at.`starttime` > '.$start.' or at.`starttime` = '.$start.')';
			}
			if(!empty($end))
			{
				$where .= ' AND (at.`endtime` < '.$end.' or at.`endtime` = '.$end.')';
			}
		}
		$where .= ' GROUP BY l.id ORDER BY l.id DESC ';
		$count =  $this->queryAll('SELECT l.`id` FROM `act_list` as l LEFT JOIN `act_attribute` as at ON l.`id` = at.aid'.$where);

		return count($count);
	}
	/**
	 * 添加活动
	 * */
	public function add($data)
	{

		$this -> insert('act_list',$data);

		return $this->getInsertId();
	}
	/**
	 * 编辑活动
	 * */
	public function updateact($data,$id)
	{
		return $this->update('act_list' , $data , 'id='.$id);
	}
	/**
	 * 添加活动商品
	 * */
	public function addactgoods($aid,$gid,$price,$nums,$onece)
	{
		$data = array();
		$data['aid'] = $aid;
		$i = 1;
		foreach($gid as $key=>$val)
		{
			$data['gid']      = $val;
			$data['limitnum'] = $onece[$key];
			$data['num'] 	  = $nums[$key];
			$data['price']    = $price[$key];
			if($this->insert('act_goodsbyactive',$data))
			{
				$i++;
			}
		}
		if($i == count($gid))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * 编辑活动商品
	 * */
	public function updateactgoods($aid,$gid,$price,$nums,$onece)
	{
		$this->delete('act_goodsbyactive','aid='.$aid);
		$data = array();
		$data['aid'] = $aid;
		$i = 1;
		foreach($gid as $key=>$val)
		{
			$data['gid']      = $val;
			$data['limitnum'] = $onece[$key];
			$data['num'] 	  = $nums[$key];
			$data['price']    = $price[$key];
			if($this->insert('act_goodsbyactive',$data))
			{
				$i++;
			}
		}
		if($i == count($gid))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * 添加活动属性
	 * */
	public function addactattr($aid,$start,$end,$attr='')
	{
		$data = array();
		$data['aid'] = $aid;
		foreach($start as $key=>$val)
		{
			$data['starttime'] = strtotime($val);
			$data['endtime']  = strtotime($end[$key]);
			$this->insert('act_attribute',$data);
		}
		if(!empty($attr))
		{
			$data = array();
			$data['aid'] = $aid;
			$data['attrname'] = $attr['name'];
			$data['attrval']  = $attr['val'];
			$this->insert('act_attrname',$data);
		}
	}
	/**
	 * 修改活动属性
	 * */
	public function updateactattr($aid,$start,$end,$attr='')
	{
		$this->delete('act_attribute','aid='.$aid);
		$data = array();
		$data['aid'] = $aid;
		foreach($start as $key=>$val)
		{
			if(!empty($val) && !empty($end[$key]))
			{
				$data['starttime'] = strtotime($val);
				$data['endtime']  = strtotime($end[$key]);
				$this->insert('act_attribute',$data);
			}

		}
		if(!empty($attr))
		{
			$this->delete('act_attrname ','aid='.$aid);
			$data = array();
			$data['aid'] = $aid;
			$data['attrname'] = $attr['name'];
			$data['attrval']  = $attr['val'];
			$this->insert('act_attrname',$data);
		}
	}
	/**
	 * 获取商品分类
	 * */
	public function getGoodsClass($parentid)
	{
		$sql = "SELECT id,title FROM `goods_class` ";
		if(!empty($parentid))
		{
			$where  = ' Where parent_id = '.$parentid;
		}
		else
		{
			$where  = ' Where parent_id = 0';
		}
		$sql .= $where;
		$list =  $this->queryAll($sql);
		$res = array();
		foreach($list as $key=>$val)
		{
			$res[$val['id']] = $val['title'];
		}

		return $res;
	}
	/**
	 * 获取活动信息及相关信息
	 * */
	public function getActiveInfo($id)
	{
		$sql   = 'SELECT * FROM `act_list` WHERE `id` = '.$id;
		$info  = $this->queryRow($sql);

		$goods = $this->queryAll('SELECT * FROM `act_goodsbyactive` WHERE aid = '.$id);
		$time  = $this->queryAll('SELECT * FROM `act_attribute` WHERE aid = '.$id);
		$attr  = $this->queryAll('SELECT * FROM `act_attrname`  WHERE aid = '.$id);
		if($goods)
		{
			foreach($goods as $key=>$val)
			{
				$res = $this->queryRow('SELECT title,original_price,stock FROM `act_goods` WHERE `id`='.$val['gid']);
				if($res)
				{
					$goods[$key]['title']  			= $res['title'];
					$goods[$key]['original_price']  = $res['original_price'];
					$goods[$key]['stock'] 			= $res['stock'];
				}

			}
		}

		$attrstr = array();
		if($attr)
		{
			foreach($attr as $val)
			{
				$attrstr[$val['attrname']] = $val['attrval'];
			}
			if($info['type'] == 1)
			{
				$select = array();
				for($i = 1;$i < 25;$i++)
				{
					$select[$i-1] = $i;
				}
				$attrstr['select'] = $select;
			}

		}
		$info['goods'] = $goods;
		$info['time']  = $time;
		$info['attr']  = $attrstr;

		return $info;
	}
	/**
	 * 删除活动
	 * */
	public function delAct($id)
	{
		$this->delete('act_list','id='.$id);
		$this->delete('act_goodsbyactive','aid='.$id);
		$this->delete('act_attribute','aid='.$id);
		$this->delete('act_attrname','aid='.$id);

		return true;
	}
}
