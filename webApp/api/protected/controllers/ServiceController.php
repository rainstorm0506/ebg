<?php
/**
 * Description of ServiceController
 * 服务中心
 * @author Administrator
 */
class ServiceController extends WebApiController{
    /*
     * 展示当前 所有的服务类型==》列表
     */
    public function actionConTypeLists()
    {
        $model = ClassLoad::Only('Service');    /* @var $model Service */
        $info = $model->getContentTypeLists();
        if(!empty($info))
        {
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"未找到数据!");
        }
    }
    /*
     * 展示 具体某项服务下面的 内容
     */
    public function actionGetDetail()
    {
        $id = (int)  $this->getPost('id');
        $model = ClassLoad::Only('Service');    /* @var $model Service */
        if(!empty($id)){
            if($info = $model->getDetail($id)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"未找到相应的数据!");
            }
        }else{
            $this->jsonOutput(1,"错误的请求数据!");
        }
    }
}
