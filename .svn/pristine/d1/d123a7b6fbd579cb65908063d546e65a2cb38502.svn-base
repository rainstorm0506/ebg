<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * deal goods comments
 */
class Comments extends WebApiModels{
    /*
     * 获取 所有的 某个商品下面的评论
     */
    public function getAllComments($gid)
    {
        $sql = "SELECT om.content,om.public_time,om.src,om.goods_score,"
                . "u.phone,u.face,og.goods_attrs "
                . "FROM order_comment AS om LEFT JOIN user AS u ON u.id=om.user_id "
                . "LEFT JOIN order_goods AS og ON om.order_sn=og.order_sn "
                . "WHERE om.goods_id={$gid} AND om.is_show=1";
        $res = $this->queryAll($sql);
        if(!empty($res)){
            foreach ($res as &$row) {
                $row['src'] = $this->jsonDnCode($row["src"]);
                $row['goods_attrs'] = $this->getAttrsHtml($this->jsonDnCode($row["goods_attrs"]));
                $row['face'] = $this->jsonDnCode($row["face"]);
                $row['public_time'] = date("Y-m-d H:i;s",$row['public_time']);
            }
            return $res;
        }else{
            return array();
        }
    }
    /*
     * 根据goods_attrs得到 attrs的html
     */
    private function getAttrsHtml($attrs = array())
    {
        $attr_html = "";
        foreach ($attrs as $row) {
            $attr_html.= $row[1]."  ".$row[2];
        }
        return $attr_html;
    }
}

