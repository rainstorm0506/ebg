<?php
/**
 * 二手商品
 *
 * @author 谭甜
 */
class UsedGoods extends SModels
{
    //得到列表的总数
     
    public function getListCount($search)
    {
        $SQL = $this->_getListSQL($search , 'count');
        return $SQL ? (int)$this->queryScalar($SQL) : 0;
        //return (int)$this->queryScalar("SELECT COUNT(*) FROM used_goods ORDER BY id DESC");
    }
    //获取分类对应属性
    public function getattr($id){
        $row=$this->getattrgroup($id);
        $data=array();
        foreach ($row as $k => $v) {
            $arr=$v;
            $arr['son']=$this->getattrvalue($v['unite_code']);
            $data[]=$arr;
        }
        return $data;
    }
    /**
     * 验证分类id是否正确
     */
    public function exist($id){
        return $this->queryRow("SELECT * FROM used_class WHERE id={$id}");
    }
    /**
     * 分类对应属性组
     */
    public function getattrgroup($id){
        return $this->queryAll("SELECT * FROM used_class_attrs WHERE used_class_id='".$id."' AND parent_unite_code='' ORDER BY rank ASC");
    }
    /**
     * 属性组对应属性值
     */
    public function getattrvalue($id){
        return $this->queryAll("SELECT * FROM used_class_attrs WHERE parent_unite_code='".$id."' ORDER BY rank DESC");
    }
    //列表
     
    public function getList($search ,$offset , $rows , $total , array $schema = array()){
        if (!$total || $offset>=$total)
            return array();

        if ($SQL = $this->_getListSQL($search , 'list'))
            return $this->queryAll($SQL . " ORDER BY g.last_time DESC LIMIT {$offset},{$rows}");
        else
            return array();
    }    
    private function _getListSQL(array $search , $type)
    {
        $merchant=$this->getUser();
        static $returned = array();
        if (isset($returned[$type]))
            return $returned[$type];

	    $SEOCODE = $search['SEOCODE'];
	    $keyword = $search['keyword'];
	    unset($search['keyword'] , $search['SEOCODE']);
        
        $field = array(
            'id' , 'merchant_id' , 'class_one_id' , 'class_two_id' , 'class_three_id' , 'brand_id' , 'title' ,
            'shelf_id' , 'status_id', 'delete_id' , 'goods_num' , 'cover' , 'last_time'
        );
        $field = 'g.' . join(',g.', $field);
        
        $SQL = ' WHERE g.delete_id=0 AND g.merchant_id='.$merchant['id'] ;
        $wp = '';

        foreach (array_filter($search) as $k => $v)
        {
	        $wp = ' AND ';
            $SQL .= $wp . " g.{$k}={$v}";

        }
        if ($keyword && !is_numeric($keyword))
        {
            $keyword = $this->quoteLikeValue($keyword);
            
            $SQL .= $wp . " AND g.title LIKE {$keyword} OR g.goods_num LIKE {$keyword}";
            
            $returned['count']  = "
                SELECT COUNT(*)
                FROM used_goods AS g
                {$SQL}";
            $returned['list']   = "
                SELECT {$field},seo.seo_title
                FROM used_goods AS g
                LEFT JOIN seo ON seo.code='{$SEOCODE}' AND seo.id=g.id
                {$SQL}";
        }else{
            if ($keyword && is_numeric($keyword) && $keyword > 0)
            {
                $keyword = (int)$keyword;
                $SQL .= $wp . "(g.id={$keyword} OR g.goods_num LIKE {$keyword})";
            }
            
            $returned['count']  = "SELECT COUNT(*) FROM used_goods AS g {$SQL}";
            $returned['list']   = "SELECT {$field},seo.seo_title FROM used_goods AS g
            LEFT JOIN seo ON seo.code='{$SEOCODE}' AND seo.id=g.id
            {$SQL}";
        }
        return isset($returned[$type]) ? $returned[$type] : '';
    }
    /**
     * 检查标题
     */
    public function checkTitle($title , $id)
    {
        $SQL = $id>0 ? " AND id!={$id}" : '';
        return $title && (boolean)$this->queryRow("SELECT id FROM used_goods WHERE `title`={$this->quoteValue($title)} {$SQL}");
    }
    /**
    *检查商品货号
    */
    public function checkGoodsNum($goods_num)
    {
        return $goods_num && (boolean)$this->queryRow("SELECT id FROM used_goods WHERE `goods_num`={$this->quoteValue($goods_num)}");
    }
    /**
     * 获得默认的商品货号
     */
    public function getDefaultNum()
    {
        #-------------------------此数组请勿改动-----------------------------------------------
        $range = array(0=>2 , 1=>4 , 2=>6 , 3=>1 , 4=>5 , 5=>9 , 6=>7 , 7=>3 , 8=>0 , 9=>8);
        
        $code = 'CP'.mt_rand(0,9);
        foreach (str_split(time()) as $k => $v)
            $code .= (($k && $k % 4 == 0) ? mt_rand(0, 9) : '') . $range[$v];
        return $code;
    }
    /**
    *添加商品
    */
    public function create($data){
        $merchant=$this->getUser();
        if(!$data)
            return false;

        $goodsNum = trim($data['goods_num']);
        $goodsNum = $goodsNum ? $goodsNum : $this->getDefaultNum();
        $arr=array(
            'title'         =>$data['title'],
	        'lightspot'     =>$data['lightspot'],
            'merchant_id'   =>$merchant['id'],
            'is_self'       =>$this->getSelf($merchant['id']),
	        'class_one_id'  =>$data['class_one_id'],
	        'class_two_id'  =>$data['class_two_id'],
	        'class_three_id'=>$data['class_three_id'],
            'goods_num'     =>$goodsNum,
			'brand_id'		=>$data['brand_id'],
			'tag_id'		=> (int)$data['tag_id'],
	        'status_id'     =>1011,
			'shelf_id'		=>1002,
            'use_time'      =>$data['use_time'],
            'buy_price'     =>$data['buy_price'],
            'sale_price'    =>$data['sale_price'],
            'stock'         =>$data['stock'],
            'weight'        =>$data['weight'],
            'dict_one_id'   =>$data['dict_one_id'],
            'dict_two_id'   =>$data['dict_two_id'],
            'dict_three_id' =>$data['dict_three_id'],
            'cover'         =>$this->getPhotos($data['cover'] , 'used_goods' , $merchant['id']),
            'content'       =>$data['content'],
            'create_time'   =>time(),
            'last_time'     =>time(),
			'rank'			=>$data['rank']
        );
        $transaction = Yii::app()->getDb()->beginTransaction();
        try
        {
            //商品
            $this->insert('used_goods' , $arr);
            $used_id = $this->getInsertId();
            //商品图片组
            foreach($data['img'] as $k=>$v)
            {
                if(!empty($v)){
                    $arr1=array(
                        'src'       =>$this->getPhotos($v , 'used_goods' , $merchant['id']),
                        'rank'      =>$k,
                        'used_id'   =>$used_id
                    );
                    $this->insert('used_goods_photo' , $arr1);
                }
                    
            }
			//添加分词
	        GlobalSplitWord::setWord(3 , $used_id , array($data['title']) , true);
	        //商品版本
	        $this->insert('used_goods_versions' , array(
		        'goods_id'		=> $used_id,
		        'vers_num'		=> time(),
		        'vers_text'		=> serialize(array(
			        'goods'			=> $arr,
			        'picture'		=> $data['img']
		        ))
	        ));

        $transaction->commit();
            return true;
        }catch(Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    //获得商家自营状态
    private function getSelf($id){
        $row=$this->queryColumn("SELECT is_self FROM user_merchant WHERE uid=$id");
        return $row[0];
    }
    /**
    *商家删除商品
    */
    public function clear($id){
		$transaction = Yii::app()->getDb()->beginTransaction();
		$goods=$this->intro($id);
		try
		{
			$this->update('used_goods',array('delete_id'=>1019),'id='.$id);

			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
    }
    /**
    *商品详情
    */
    public function intro($id){
        //商品基本信息
        $arr=$this->queryRow("SELECT g.*,m.store_name,m.mer_no,m.mer_name,m.is_self FROM used_goods AS g
            LEFT JOIN user_merchant AS m ON m.uid=g.merchant_id
            WHERE g.id={$id}");
        //商品图片
        $arr['img']=$this->queryColumn("SELECT `src` FROM used_goods_photo WHERE used_id={$id}");
        //商品所在地
        $arr['address']=$this->queryAll("SELECT name FROM dict WHERE id={$arr['dict_one_id']} 
            OR id={$arr['dict_two_id']} OR id={$arr['dict_three_id']}");
        return $arr;
    }
    //状态更改
    public function status($id,array $data){
        return $this->update('used_goods',$data,'id='.$id);
    }
    //获取上架id
    public function getShelfStatus(){
        return array('1001'=>'上架','1002'=>'下架');
    }
    //获取审核状态id
    public function getVerifyStatus(){
        return array('1011'=>'待审核','1013'=>'审核成功','1014'=>'审核失败');
    }
    //获取商品状态
    public function getStatus($id){
        $row=$this->queryColumn("SELECT status_id FROM used_goods WHERE id={$id}");
        return $row[0];
    }
    //修改
	public function modify($data){
		if(!$data)
			return false;

		$merchant=$this->getUser();
		
		$used_id = $data['id'];
		$old=$this->intro($used_id);
		if(strpos($data['cover'],'used_goods') === false){     //使用绝对等于 
			$src=$this->getPhotos($data['cover'] , 'used_goods' , $merchant['id']);
		}else{ 
			$src=$data['cover'];
		}
		$status=$this->getStatus($used_id);
		$arr=array(
			'title'         =>$data['title'],
			'merchant_id'   =>$merchant['id'],
			'lightspot'     =>$data['lightspot'],
			'class_one_id'  =>$data['class_one_id'],
			'class_two_id'  =>$data['class_two_id'],
			'class_three_id'=>$data['class_three_id'],
			'brand_id'      =>$data['brand_id'],
			'tag_id'		=> (int)$data['tag_id'],
			'status_id'     =>1011,
			'use_time'      =>$data['use_time'],
			'buy_price'     =>$data['buy_price'],
			'sale_price'    =>$data['sale_price'],
			'stock'         =>$data['stock'],
			'weight'        =>$data['weight'],
			'dict_one_id'   =>$data['dict_one_id'],
			'dict_two_id'   =>$data['dict_two_id'],
			'dict_three_id' =>$data['dict_three_id'],
			'cover'         =>$src,
			'content'       =>$data['content'],
			'last_time'     =>time(),
			'rank'			=>$data['rank']
		);
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//商品
			$this->update('used_goods' , $arr,'id='.$used_id);

			//商品图片组
			$this->delete('used_goods_photo','used_id='.$used_id);
			foreach($data['img'] as $k=>$v)
			{
				if(!empty($v)){
					if(strpos($v,'used_goods') === false){     //使用绝对等于 
						$src=$this->getPhotos($v , 'used_goods' , $merchant['id']);
					}else{ 
						$src=$v;
					}
					$arr1=array(
						'src'		=>	$src,
						'rank'		=>	$k,
						'used_id'	=>	$used_id
					);
					$this->insert('used_goods_photo' , $arr1);
				}
			}
			//编辑分词
			GlobalSplitWord::delWord(3 , $used_id);
			GlobalSplitWord::setWord(3 , $used_id , array($data['title']) , true);
			//商品版本
			$this->insert('used_goods_versions' , array(
				'goods_id'		=> $used_id,
				'vers_num'		=> time(),
				'vers_text'		=> serialize(array(
					'goods'			=> $arr,
					'picture'		=> $data['img']
				))
			));

		$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
    //批量操作
    public function batch($data){
        if(!$data)
            $this->error('错误操作');
        
        $transaction = Yii::app()->getDb()->beginTransaction();
        try{
            foreach($data['id'] as $v){
                if($data['shelf']==1001 || $data['shelf']==1002){
                    $shelf=array(
                        'shelf_id'      =>  $data['shelf'],
                        'shelf_time'    =>  time()
                    );
                    $this->shelf($v,$shelf);
                }
            }
            $transaction->commit();
                return true;
        }catch(Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    //上下架
    private function shelf($id,$shelf){
        return $this->update('used_goods',$shelf,'id='.$id);
    }
}