<?php
/**
 * Description of CompanyUser
 *企业会员模块
 * @author Administrator
 */
class CompanyUser extends WebApiModels{
    /*
     * 获取当前企业会员的 集采订单列表
     */
    public function getPurchaseList()
    {
        $sql = "SELECT po.id,po.purchase_sn,po.title,po.price_endtime,po.wish_receivingtime,po.is_closed,po.memo "
                . "FROM purchase_order AS po LEFT JOIN user as u ON u.id=po.user_id "
                . "WHERE po.user_id={$this->getUid()} AND u.user_type=2 AND po.is_split=1";
        return $this->queryAll($sql);
    }
    /*
     * 将当前 企业会员的 集采入库
     */
    //获取 当前企业用户的 企业数据
    private function getComNameByUid()
    {
        $sql = "SELECT *  FROM `user_company` WHERE uid={$this->getUid()} LIMIT 1";
        return $this->queryRow($sql);
    }
    public function addPurchase($form)
    {
        $user_com_info = $this->getComNameByUid();
        
        $transaction = Yii::app()->getDb()->beginTransaction();
        $data_info = array(
            'purchase_sn'=> GlobalOrders::getOrderSN(),
            'phone'=>$form->phone,
            'user_id'=>  $this->getUid(),
            'file_data'=> json_encode($form->file_data),
            'link_man'=>$form->link_man,
            'company_name'=>!empty($user_com_info) ? $user_com_info['com_name'] : "",
            'is_tender_offer'=>$form->is_tender_offer,
            'is_interview'=>$form->is_interview,
            'create_time'=>  time(),
        );
        try{
            $this->insert("purchase_order", $data_info);
            $transaction->commit();
            return true;
        }catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    /*
     * 删除 企业订单
     */
    public function delPurchase($id=0)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try{
           $this->delete('purchase_order', "user_id=:user_id and id=:id",array(':user_id'=>  $this->getUid(),':id'=>$id));
           $transaction->commit();
           return true;
        } catch (Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
    /*
     * 企业==》采购订单详情
     * @param id 当前集采订单ID编号
     */
    public function getPurchaseGoodsInfo($id,$type)
    {
        $purchase_sn = $this->getPurSn($id,$type);
        $sql = "select * from purchase_goods where purchase_sn='{$purchase_sn}'";
        return $this->queryAll($sql);
    }
    //通过集采订单Id编号
    private function getPurSn($id=0,$type=2)
    {
        $con = $type==2 ? "" : " AND user_id={$this->getUid()}";    //判断当前 集采订单详情 是否是个人(2：不是)
        $sql = "SELECT purchase_sn FROM purchase_order WHERE id={$id} {$con} LIMIT 1";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['purchase_sn'] : "";
    }
    /*
     * 获取 某一集采需求的 拆分详情
     */
    public function getDetailSplit($purchase_sn,$title)
    {
        $sql = "SELECT pg.name,pg.num_min,pg.num_max,pg.id,po.price_endtime FROM `purchase_goods` AS pg LEFT JOIN `purchase_order` AS po ON po.purchase_sn=pg.purchase_sn WHERE po.`purchase_sn`='{$purchase_sn}'";
        $info = $this->queryAll($sql);
        $temp['infos'] = array();
        $temp['title'] = "";
        $temp['price_endtime'] = "";
        if(!empty($info)){
            foreach ($info as &$row){
                $time = date("Y-m-d H:i:s",$row['price_endtime']);
                $temp['infos'][] = $row;
            }
            $temp['title'] = $title;
            $temp['price_endtime'] = $time;
            return $temp;
        }else{
            return array();
        }
    }
    /*
     * 获取 报价详情
     * @param type int 1:查看平台推荐；2:商家报价
     */
    public function  getOfferDetail($pg_id,$type=2)
    {
        $sql = "SELECT pp.*,ppd.merchant_id,ppd.is_price,ppd.addtime,g.title,g.cover"
                . " FROM purchase_price AS pp LEFT JOIN purchase_goods AS pg ON pg.id=pp.pg_id"
                . " LEFT JOIN purchase_price_detail AS ppd ON pg.purchase_sn=ppd.purchase_sn"
                . " LEFT JOIN goods AS g ON pp.goods_id=g.id"
                //. " WHERE pp.type={$type} AND pp.pg_id={$pg_id}";
                . " WHERE pp.type={$type} AND pp.pg_id={$pg_id}";
        $info = $this->queryAll($sql);
        if(!empty($info)){
            foreach ($info as &$row) {
                $row['attr_str'] = $this->getAttrVal($row['goods_id']);
                $row['store_name'] = $this->getStoreName($row['merchant_id']);
            }
        }else{
            return array();
        }
        //return $temp;
        return $info;
    }
    //根据merchant_id得到对应的 店铺的名字
    private function getStoreName($mid)
    {
        $sql = "SELECT store_name FROM user_merchant WHERE uid={$mid} LIMIT 1";
        $res = $this->queryRow($sql);
        return !empty($res) ? $res['store_name'] : "";
    }
    //根据goods_id得到对应的 属性值
    private function getAttrVal($goods_id)
    {
        $sql = "SELECT attrs_1_value,attrs_2_value,attrs_3_value FROM goods_join_attrs WHERE goods_id={$goods_id}";
        $res = $this->queryRow($sql);
        //属性字符串
        $attr_str  = !empty($res) ? $res['attrs_1_value']." ".$res['attrs_2_value']." ".$res['attrs_3_value'] : "";
        return $attr_str;
    } 
}
