<?php

class AddCommentForm extends WebApiForm{
    public $goods_score,$src,$content,$order_sn,$goods_id;
    //基本验证
    public function rules()
    {
        return array(
            array('goods_score,content,src,order_sn,goods_id', 'safe'),
        );
    }    
    public function validateForm()
    {
        $flag = true;
        if($this->goods_score==""){
            $this->addError('goods_score', '评论星级不能为空');
            $flag = false;
        }
        if($this->content==""){
            $this->addError('content', '评论内容不能为空');
            $flag = false;
        }        
        return $flag;
    }
}
