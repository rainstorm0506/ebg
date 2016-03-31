<?php
/**
 * Description of CommentController
 * 商品评论管理
 * @author Administrator
 */
class CommentController extends WebApiController{
    /*
     * 获取 某个商品下面的所有 评论
     */
    public function actionAllComments()
    {
        $goods_id = (int)  $this->getPost("gid");
        if(empty($goods_id))
            $this->jsonOutput (1,"商品ID参数错误");
        $model = ClassLoad::Only('Comments');   /* @var $model Comments */
        if($info = $model->getAllComments($goods_id)){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"未找到相关数据!");
        }
    }
}
