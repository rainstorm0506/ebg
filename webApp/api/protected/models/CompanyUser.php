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
}
