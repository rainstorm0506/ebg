<?php
/**
 * 兑换记录
 *
 * @author 谭甜
 */
class Convert extends SModels
{
	//得到列表的总数
	public function getListCount($search)
	{
		$SQL = $this->_getListSQL($search , 'count');
		return $SQL ? (int)$this->queryScalar($SQL) : 0;
    }
    //列表
    public function getList($search ,$offset , $rows , $total , array $schema = array()){
        if (!$total || $offset>=$total)
            return array();

        if ($SQL = $this->_getListSQL($search , 'list'))
            return $this->queryAll($SQL . " ORDER BY p.time DESC LIMIT {$offset},{$rows}");
        else
            return array();
    }
    private function _getListSQL(array $search , $type)
    {
        static $returned = array();
        if (isset($returned[$type]))
            return $returned[$type];

        $keyword = isset($search['keyword'])?$search['keyword']:'';
        unset($search['keyword']);
        $user=isset($search['user'])?$search['user']:'';
        unset($search['user']);
        $time=isset($search['time'])?strtotime($search['time']):'';
        unset($search['time']);
        $accept_time=isset($search['accept_time'])?strtotime($search['accept_time']):'';
        unset($search['accept_time']);
        $field = array(
            'id' , 'user_id' , 'goods_id' , 'points' , 'time' ,
            'delivery' , 'status', 'accept_time' , 'address' , 'describe'
        );
        $field = 'p.' . join(',p.', $field);

        $SQL = '';
        $wp = ' WHERE ';
        foreach (array_filter($search) as $k => $v)
        {
            $SQL .= $wp . "`{$k}`={$v}";
            $wp = ' AND ';
        }

        if ($user && !is_numeric($user))
        {
            $keyword = $this->quoteLikeValue($keyword);
            if($keyword)
                $SQL .= $wp . " (g.title LIKE {$keyword} OR g.goods_num LIKE {$keyword})";
            if($user)
                $SQL .= $wp . " (u.realname = {$user} )";
            if($time)
                $SQL .= $wp . " (p.time between {$time} AND $time+86400)";
            if($accept_time)
                $SQL .= $wp . " (p.accept_time between {$accept_time} AND $accept_time+86400)";

            $returned['count']  = "
                SELECT COUNT(*)
                FROM points_convert_code AS p
                LEFT JOIN user AS u ON u.id=p.user_id
                LEFT JOIN points_goods AS g ON g.id=p.goods_id
                {$SQL}";
            $returned['list']   = "
                SELECT {$field},u.phone,u.realname,g.title
                FROM points_convert_code AS p
                LEFT JOIN user AS u ON u.id=p.user_id
                LEFT JOIN points_goods AS g ON g.id=p.goods_id
                {$SQL}";
        }else{
            if ($user && is_numeric($user) && $user > 0)
            {
                $user = (int)$user;
                $SQL .= $wp . " (g.title LIKE {$keyword} OR g.goods_num LIKE {$keyword})";
                $SQL .= $wp . " (u.phone = {$user} )";
                $SQL .= $wp . " (p.time between {$time} AND $time+86400)";
                $SQL .= $wp . " (p.accept_time between {$accept_time} AND $accept_time+86400)";
            }

            $returned['count']  = "
                SELECT COUNT(*)
                FROM points_convert_code AS p
                LEFT JOIN user AS u ON u.id=p.user_id
                LEFT JOIN points_goods AS g ON g.id=p.goods_id
                {$SQL}";
            $returned['list']   = "
                SELECT {$field},u.phone,u.nickname,g.title
                FROM points_convert_code AS p
                LEFT JOIN user AS u ON u.id=p.user_id
                LEFT JOIN points_goods AS g ON g.id=p.goods_id
                {$SQL}";
        }
        return isset($returned[$type]) ? $returned[$type] : '';
    }
    //操作状态
    public function handle($id,$status){
        $arr=array('status'=>$status);
        if($arr['status']==1){
            $arr['accept_time']=time();
        }
        return $this->update('points_convert_code',$arr,'id='.$id);
    }
}