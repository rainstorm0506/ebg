<?php
class Usercenter extends WebApiModels{
    //用户 进入个人中心 展示的用户相关信息
    public function getUserInfo()
    {
        $data['baseinfo'] = $this->getBaseInfo();   //个人基础信息
        $data['col_store_num'] = $this->getColNum(2);   //店铺收藏
        $data['col_goods_num'] = $this->getColNum(1);   //商品收藏
        $data['wait_pay'] = $this->getOrdersNum(101);    //待付款的订单 数量
        $data['wait_delivery'] = $this->getOrdersNum(103);    //待发货的订单 数量
        $data['wait_goods'] = $this->getOrdersNum(106);    //待收货的订单 数量
        $data['finish'] = $this->getOrdersNum(107);    //已完成的订单 数量
        $data['youhui'] = $this->getYouhuiNum();    //获取 我的优惠券的 数量
        $data['waitcomnum'] = $this->getWaitComNum();    //获取 待评价的 数量
        return $data;
    }
    //获取 用户的基本信息
    private function getBaseInfo()
    {
        $sql = "SELECT
                    u.phone,
                    u.exp,
                    u.money,
                    u.status_id,
                    u.user_code,
                    u.nickname,
                    u.face,
                    u.fraction,
                    uls.name,
                    ij.user_code,
                    udi.realname,
                    udi.sex,
                    udi.address,
                    udi.birthday,
                    udi.dict_one_id,
                    udi.dict_two_id,
                    udi.dict_three_id
            FROM
                    user AS u
            LEFT JOIN user_layer_setting AS uls ON u.user_type = uls.user_type
            LEFT JOIN import_json AS ij ON u.id = ij.uid
            LEFT JOIN user_detail_info AS udi ON u.id = udi.user_id
            WHERE
                    u.id ={$this->getUid()}
            LIMIT 1 ";
        $res = $this->queryRow($sql);
        //重新 构造数据地区Id编号 获得 地区名字
        if(!empty($res)){
            $res['dict_one_name'] = $this->getDictName(1, $res['dict_one_id']);
            $res['dict_two_name'] = $this->getDictName(2, $res['dict_two_id']);
            $res['dict_three_name'] = $this->getDictName(3, $res['dict_three_id']);  
            return $res;
        }else{
            return array();
        }
    }
    //获取当前 用户的 店铺收藏/商品收藏 数量
    private function getColNum($type)
    {
        $sql = "SELECT count(collect_id) as col_store_num FROM user_collect WHERE user_id={$this->getUid()} AND type={$type} GROUP BY collect_id";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['col_store_num'] : 0;
    }
    //获取 当前 用户的各种订单类型的数量
    private function getOrdersNum($status_id)
    {
        $sql = "SELECT count(id) as order_num FROM status WHERE type=1 AND id={$status_id} LIMIT 1";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['order_num'] : 0;
    }
    //获取 优惠数量
    private function getYouhuiNum()
    {
        $sql = "SELECT count(apu.id) as num FROM activities_privilege_user as apu LEFT JOIN activities_privilege AS ap ON apu.activities_id=ap.id WHERE user_id={$this->getUid()}";
        $res = $this->queryAll($sql);
        return !empty($res) && isset($res[0]['num']) ? $res[0]['num'] : 0;
    }
    //获取 待评价的数量
    private function getWaitComNum()
    {
        $sql = "SELECT count(og.id) as num FROM orders AS o "
                . "LEFT JOIN order_goods AS og ON o.order_sn=og.order_sn "
                . "LEFT JOIN order_comment AS om ON om.order_sn=og.order_sn "
                . "WHERE o.user_id={$this->getUid()} AND o.order_status_id=107 AND og.is_evaluate=0";  //已完成(order_status_id=107) 订单评论
        $res = $this->queryAll($sql);
        return !empty($res) && isset($res[0]['num']) ? $res[0]['num'] : 0;
    }
    /*
     * 升级 为企业用户
     */
    public function userToCom($form)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try
        {
            $insert_info = array(
                'uid'=>  $this->getUid(),
                'com_name'=>$form->com_name,
                'dict_one_id'=>$form->dict_one_id,
                'dict_two_id'=>$form->dict_two_id,
                'dict_three_id'=>$form->dict_three_id,
                'com_address'=>$form->com_address,
                'com_num'=>$form->com_num,
                'com_property'=>$form->com_property,
                'com_license'=>$form->com_license,
                'com_tax'=>$form->com_tax,
                'com_org'=>$form->com_org,
                'com_license_timeout'=>$form->com_license_timeout,
            );
                $this->insert('user_company' , $insert_info);
                $transaction->commit();
                return true;
        }catch(Exception $e){
                $transaction->rollBack();
        }
        return false;        
    }
    /*
     * 个人中心==》我的订单
     */
    public function getOrderInfo(CPagination $page,$_p,$type)
    {
        //0:全部；101:待付款；106:待收货；103：代发货；107:已完成(待评价)；
        if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                return array();   
        //$SQL_TYPE = $type==0 ? "": "AND s.id={$type}";
        $SQL_TYPE = $type==0 ? "": "AND o.order_status_id={$type}";
        $where = $type==107 ? " AND og.is_evaluate=0" : "";
        $sql = "SELECT
                    o.*, um.store_name,
                    og.goods_cover,
                    og.num,
                    og.goods_attrs,
                    g.title,
                    g.retail_price,
                    g.base_price,
                    g.id as goods_id
            FROM
                    orders AS o
            LEFT JOIN user_merchant AS um ON o.merchant_id = um.uid
            LEFT JOIN status AS s ON o.order_status_id = s.id
            LEFT JOIN order_goods AS og ON o.order_sn = og.order_sn
            LEFT JOIN goods AS g ON og.goods_id = g.id
            WHERE
                    o.user_id ={$this->getUid()} AND s.id={$type} {$SQL_TYPE} LIMIT {$page->getOffset()},{$page->getLimit()}";
        $res = $this->queryAll($sql);
        $res = $this->getAttrs($res);
        return $res;
    }
    /*
     * 获取 我的订单的 总的条数 用于分页
     */
    public function getAllNum()
    {
        $sql = "SELECT
                        count(o.order_sn) as all_num
                FROM
                        orders AS o
                LEFT JOIN user_merchant AS um ON o.merchant_id = um.uid
                LEFT JOIN order_goods AS og ON o.order_sn = og.order_sn
                LEFT JOIN goods AS g ON og.goods_id = g.id
                WHERE
                        o.user_id ={$this->getUid()}";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['all_num'] : 0;
    }
    /*
     * 获取 个人中心=》我的评论
     * type=1（已评价）；type=2（待评价）
     */
    public function getComments($type,$page,$_p)
    {
        if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                return array();          
        $is_evaluate = $type==1 ? "AND og.is_evaluate=1" : "AND og.is_evaluate=0";
        
        //$sql = "SELECT og.*,IFNULL(om.reply_content,'') as reply_content FROM orders AS o "
        $sql = "SELECT og.*,IFNULL(om.content,'') as content FROM orders AS o "
                . "LEFT JOIN order_goods AS og ON o.order_sn=og.order_sn "
                . "LEFT JOIN order_comment AS om ON om.order_sn=og.order_sn "
                . "WHERE o.user_id={$this->getUid()} AND o.order_status_id=107 {$is_evaluate} LIMIT {$page->getOffset()},{$page->getLimit()}";  //已完成(order_status_id=107) 订单评论
        $res = $this->queryAll($sql);
        $res = $this->getAttrs($res);
        return $res;
    }
    //提交 某个订单的评价
    public function setComment($form)
    {
        $insert_info = array(
                'order_sn'=>    $form->order_sn,
                'user_id'=>     $this->getUid(),
                'goods_id'=>    $form->goods_id,
                'merchant_id'=> $this->getMidByGid($form->goods_id),
                'content'=>     $form->content,
                'public_time'=> time(),
                'src'=>         $form->src==null ? json_encode(array()): json_encode($form->src),   //
                'goods_score'=> $form->goods_score,
        );
        $update_info = array('is_evaluate'=>1);
        $transaction = Yii::app()->getDb()->beginTransaction();
        try {
            $this->insert('order_comment', $insert_info);
            //更新order_goods中order_sn和goods_id等于当前的is_evaluate
            $this->update('order_goods', $update_info,"order_sn='".$form->order_sn."' and id=".$form->goods_id);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    //根据goods_id得到商家id
    private function getMidByGid($gid=0)
    {
        $sql = "SELECT merchant_id FROM goods WHERE id={$gid} LIMIT 1";
        $res = $this->queryRow($sql);
        return !empty($res) && isset($res['merchant_id']) ? $res['merchant_id'] : "";
    }
    //处理 订单goods_attr转换成字符串attrs
    private function getAttrs($res = array())
    {
        if(!empty($res)){
            foreach ($res as &$row) {
                $row['goods_attrs'] = (array)json_decode($row['goods_attrs']);
                if(!empty($row['goods_attrs'])){
                    $attr = "";
                    foreach ($row['goods_attrs'] as $item) {
                        $attr .= $item[1].":";
                        $attr .= $item[2].";";
                    }
                    $row['attrs'] = $attr;                    
                }else{
                    $row['attrs'] = ""; 
                }
                unset($row['goods_attrs']);
            }
            return $res;
        }else{
            return false;
        }          
    }
    /*
     * 个人中心==》获取我的 收货地址
     */
    public function getMyAddress()
    {
        $sql = "SELECT * FROM user_address WHERE user_id={$this->getUid()}";
        $res = $this->queryAll($sql);
        if(!empty($res)){
            //根据 地区ID编号 得到当前地区的名字
            foreach ($res as &$row) {
                $row['dict_on_name'] = $this->getDictName(1, $row['dict_one_id']);
                $row['dict_two_name'] = $this->getDictName(2, $row['dict_two_id']);
                $row['dict_three_name'] = $this->getDictName(3, $row['dict_three_id']);
            }
            return $res;
        }else{
            return false;
        }
    }
    //根据 地区ID编号得到当前地区的 名字
    private function getDictName($type,$id)
    {
        if($id!=""){
            $key = "";
            switch ($type) {
                case 1:
                    $key = 'one_id';
                    break;
                case 2:
                    $key = 'two_id';
                    break; 
                case 3:
                    $key = 'three_id';
                    break;             
            }
            $sql = "SELECT name from dict WHERE {$key}={$id}";
            $res = $this->queryRow($sql);
            return !empty($res) ? $res['name'] : "";            
        }else{
            return "";
        }
    }
    /*
     * 添加 收货地址
     */
    public function setAddress($form)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try {
            $data_info = array(
                'user_id'=>  $this->getUid(),
                'consignee'=>  $form->consignee,
                'dict_one_id'=>  $form->dict_one_id,
                'dict_two_id'=> $form->dict_two_id,
                'dict_three_id'=>  $form->dict_three_id,
                'address'=>  $form->address,
                'phone'=>  $form->phone,
                'is_default'=>  $form->is_default
            );
            //判断 当前是 更新/还是添加
            $id = 0;
            if($form->id){  //更新操作
                $id = $form->id;
                $this->update('user_address', $data_info,"id={$id}");
            }else{  //执行添加
                $this->insert('user_address', $data_info);
                $id = $this->getInsertId();                
            }
            //如果 有 提交默认地址,应更新 当前user_id对应的其他 收货地址
            if($form->is_default==1){
                $sql = "update user_address set is_default=0 where id!={$id} and user_id={$this->getUid()}";
                $res = $this->execute($sql);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    //删除收货地址
    public function delAddress($id=0)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try{
            $this->delete('user_address', "id=".$id." AND user_id=".$this->getUid());
            $transaction->commit();
            return true;
        }catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    /*
     * 得到 当前用户输入的 旧密码是否匹配
     */
    public function comparePwd($input_pwd)
    {
        $sql = "SELECT password FROM user WHERE id={$this->getUid()} LIMIT 1";
        $res = $this->queryRow($sql);

        $input_pwd = GlobalUser::hashPassword($input_pwd);
        if(!empty($res))
            return $res['password']==$input_pwd ? true : false;
    }
    /*
     * 执行 修改当前用户的密码
     */
    public function updatePwd($new_pwd)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        $update_info = array(
            'password'=>  GlobalUser::hashPassword($new_pwd)
        );
        //执行更新操作
        try {
            $this->update('user', $update_info,"id={$this->getUid()}");
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    /*
     * 个人中心==》个人兑换 记录列表
     */
    public function getConverts(CPagination $page,$_p)
    {
        if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                return array();          
        $sql = "SELECT pcc.*,pg.title,pg.cover,pg.points FROM points_convert_code AS pcc LEFT JOIN points_goods AS pg ON pcc.goods_id=pg.id WHERE pcc.user_id={$this->getUid()} LIMIT {$page->getOffset()},{$page->getLimit()}";
        return $this->queryAll($sql);
    }
    public function getConvertsNum()
    {
        $sql = "SELECT count(pcc.id) as all_num FROM points_convert_code AS pcc LEFT JOIN points_goods AS pg ON pcc.goods_id=pg.id WHERE pcc.user_id={$this->getUid()}";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['all_num'] : 0;
    }
    /*
     * 个人中心==》我的优惠券
     */
    public function getMyac(CPagination $page,$_p)
    {
        if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                return array();           
        $sql = "SELECT apu.*,ap.use_starttime,ap.use_endtime,ap.privilege_money,ap.is_used FROM activities_privilege_user as apu LEFT JOIN activities_privilege AS ap ON apu.activities_id=ap.id WHERE user_id={$this->getUid()} LIMIT {$page->getOffset()},{$page->getLimit()}";
        return $this->queryAll($sql);
    }
    public function getMyacNum()
    {
        $sql = "SELECT count(apu.id) as all_num FROM activities_privilege_user as apu LEFT JOIN activities_privilege AS ap ON apu.activities_id=ap.id WHERE user_id={$this->getUid()}";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['all_num'] : 0;
    }
    /*
     * 个人中心==》我的体现记录列表
     */
    public function getMyWithdraw(CPagination $page,$_p)
    {
        if (!$page->getItemCount() || $page->getOffset()>$page->getItemCount() || $_p > $page->getPageCount())
                return array();           
        $sql = "SELECT wr.*,wa.uid FROM withdraw_account AS wa LEFT JOIN withdraw_record AS wr ON wa.id=wr.aid WHERE wa.uid={$this->getUid()} LIMIT {$page->getOffset()},{$page->getLimit()}";
        return $this->queryAll($sql);
    }
    public function getWithdrawCount()
    {
        $sql = "SELECT count(wr.id) as all_num FROM withdraw_account AS wa LEFT JOIN withdraw_record AS wr ON wa.id=wr.aid WHERE wa.uid={$this->getUid()}";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['all_num'] : 0;
    }
    /*
     * 个人中心==》提交 提现申请
     */
    public function setWithdraw($form)
    {
        $sql = "SELECT money FROM user WHERE id={$this->getUid()}";
        $res = $this->queryRow($sql);
        $money = !empty($res) ? $res['money'] : 0;
        $user_info = $this->getBaseInfo();

        if($form->amount<=$money){  //提现金额未超出余额
            $transaction = Yii::app()->db->beginTransaction();
            try {
                //withdraw_account插入记录
                $this->insert('withdraw_account', array(
                    'uid'=>  $this->getUid(),
                    'bank'=>  $form->bank,
                    'account'=>  $form->account,
                    'realname'=>  $user_info['realname']
                ));
                $aid = $this->getInsertId();    //会员提现账户主键
                $this->insert('withdraw_record', array(
                    'aid'=>$aid,
                    'amount'=>$form->amount,
                    'with_time'=>  time(),
                    'cur_state_time'=>  time(),
                    'snum'=> GlobalUser::getCurrentSnum(),   //提现流水账号
                ));
                $transaction->commit();
                return true;
            } catch (Exception $e) {
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 个人中心==》获取 店铺/商品收藏
     */
    public function getMyCollects($type=1)
    {
        if($type==2){   //查询出 商品信息
            $sql = "SELECT um.store_avatar,um.uid,g.collect FROM user_collect AS uc LEFT JOIN goods AS g ON uc.collect_id=g.id LEFT JOIN user_merchant AS um ON um.uid=g.merchant_id WHERE uc.`type`={$type} AND uc.user_id={$this->getUid()}";
            return $this->queryAll($sql);            
        }else{  //查询 当前的 店铺信息
            $sql = "SELECT uc.collect_time,g.cover,g.title,g.vice_title,g.base_price,g.retail_price,g.collect,g.id FROM user_collect AS uc LEFT JOIN goods AS g ON uc.collect_id=g.id WHERE uc.`type`={$type} AND uc.user_id={$this->getUid()}";
            return $this->queryAll($sql);
        }
    }
    /*
     * 个人中心==》修改电话号码
     */
    public function getExistPhone($phone)
    {
        $sql = "SELECT id FROM user WHERE phone={$phone}";
        $res = $this->queryRow($sql);
        return empty($res) ? 1 : 0;
    }
    public function setPhone($phone)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try{
            $this->update('user', array('phone'=>$phone), "id={$this->getUid()}");
            $transaction->commit();
            return true;
        }catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
