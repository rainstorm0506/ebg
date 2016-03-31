<?php
/**
 * Description of Service
 * 商品服务内容==》模型层
 * @author Administrator
 */
class Service extends WebApiModels{
    /*
     * 得到所有的 服务类型
     */
    public function getContentTypeLists()
    {
        $sql = "SELECT id,name FROM content_type ORDER BY `orderby`";
        $res = $this->queryAll($sql);
        return !empty($res) ? $this->getSons($res) : array();
    }
    //根据 父分类获取其子分类 的列表
    private function getSons(&$rows)
    {
        foreach ($rows as &$row) {
            $type = $row['id'];
            
            $sql = "SELECT id,title FROM content WHERE `type`={$type}";
            $res = $this->queryAll($sql);
            $row['child'][] = !empty($res) ? $res : array();
        }
        //处理 数组
        foreach ($rows as &$item){
            $item['child'] = $item['child'][0];
        }
        return $rows;
    }
    /*
     * 获取具体的 某个分类下的文章
     */
    public function getDetail($id)
    {
        $sql = "SELECT * FROM content WHERE `id`={$id} ORDER BY `orderby`";
        return $this->queryAll($sql);
    }
}
