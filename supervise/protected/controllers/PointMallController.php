<?php

/**
 * 积分商城产品管理
 * Class PointMallController
 * @author Panda
 *
 */
class PointMallController extends SController
{

    public $layout = 'main_new';

    public function __construct($id, $module)
    {
        parent::__construct($id, $module);
    }

    public function actionList()
    {
        $k = $this->getParam('keyword');
        $result = PointGoods::model()->getList($k);
        $this->render('list', array('result' => $result));
    }

    public function actionEdit()
    {
        $id = $this->getParam('id');
        if (isset($_POST)) {
            $this->save();
            $this->redirect($this->createUrl('pointMall/list'));
        }
        $data = PointGoods::model()->findByPk($id);

        $this->render('from', array(
            'data' => $data,
            'action' => $this->createUrl('pointMall/edit')
        ));
    }

    private function save()
    {
        $sql_data = array(
            'title' => $this->getParam('title'),
            'point' => $this->getParam('point'),
            'stock' => $this->getParam('stock'),
            'sort_order' => $this->getParam('sort_order'),
            'is_active' => $this->getParam('is_active'),
        );

        $id = $this->getParam('id');
        if ((int)$id) {
            $model = PointGoods::model()->findByPk($id);
            $model->setAttributes($sql_data);
            $model->update();
        } else {
            PointGoods::model()->save(false, $sql_data);
        }
    }

    public function actionDel()
    {
        $id = $this->getParam('id');
        if ($id) {
            $model = PointGoods::model()->deleteByPk($id);
        }
        $this->redirect($this->createUrl('pointMall/list'));
    }

    public function actionAdd()
    {
        if ($_POST) {
            $this->save();
            $this->redirect($this->createUrl('pointMall/list'));
        }
        $this->render('from', array('action' => $this->createUrl('pointMall/add')));
    }
}