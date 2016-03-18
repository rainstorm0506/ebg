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
        $sql = "SELECT po.id,po.title,po.price_endtime,po.wish_receivingtime,po.is_closed,po.memo "
                . "FROM purchase_order AS po LEFT JOIN user as u ON u.id=po.user_id "
                . "WHERE po.user_id={$this->getUid()} AND u.user_type=2";
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
}
