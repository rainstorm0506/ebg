<?php

/**
 * This is the model class for table "point_goods".
 *
 * The followings are the available columns in table 'point_goods':
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $point
 * @property integer $stock
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $type
 */
class PointGoods extends BaseActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'point_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('point, stock, sort_order, is_active, type', 'numerical', 'integerOnly'=>true),
			array('title, image', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, image, point, stock, sort_order, is_active, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'image' => 'Image',
			'point' => 'Point',
			'stock' => 'Stock',
			'sort_order' => 'Sort Order',
			'is_active' => 'Is Active',
			'type' => 'Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('point',$this->point);
		$criteria->compare('stock',$this->stock);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PointGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getList($k = '')
	{
		if (empty(Yii::app()->params['interfaceStatus'])) {
			if ($k) {
                $criteria = new CDbCriteria();
                $criteria->condition = 'id = :id OR title like :title';
                $criteria->params = array(':id' => $k, ':title' => "%" . $k . "%");
                $count = $this->count($criteria);
                $pager = new CPagination($count);
                $pager->pageSize = 1;
                $pager->applyLimit($criteria);
                $list = $this->findAll($criteria);
			} else {
                $criteria = new CDbCriteria();
                $count = $this->count($criteria);
                $pager = new CPagination($count);
                $pager->pageSize = 1;
                $pager->applyLimit($criteria);
				$list = $this->findAll($criteria);
			}
            return array(
                'pager' => $pager,
                'list' => $list
            );

		} else {
			return DataInterface::getData();//todo 要实现这里
		}
	}
}
