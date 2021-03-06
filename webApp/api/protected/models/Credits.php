<?php
class Credits extends WebApiModels
{
	/**
	 * 积分商城列表
	 */
	public function getList($search , $pageNow , $pageSize)
	{
		$pageNow=($pageNow-1)*$pageSize;
		$SQL = "WHERE shelf_id=1101";

		// 综合排序
		$orderBy = "ORDER BY create_time DESC";
		// 排序
		if($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			switch($search['order'])
			{
				// 价格
				case 'points' :
					$orderBy = " ORDER BY points {$by}";
					break;
				// 销量
				case 'detail' :
					$orderBy = " ORDER BY sales {$by}";
				// 上架时间
				case 'putaway' :
					$orderBy = " ORDER BY shelf_time {$by}";
					break;
			}
		}
		return $this->queryAll("SELECT id,title,cover,points,person,merchant,company,sales FROM points_goods {$SQL} {$orderBy} LIMIT {$pageNow},{$pageSize}");
	}
	/**
	 * 商品详情
	 */
	public function getInfo($id)
	{
		$row = $this->queryRow("SELECT * FROM points_goods WHERE id={$id}");
		return $row;
	}
        /*
         * 积分兑换
         */
        public function setConvert($form)
        {
            if(empty($form))
                return false;
            $goods_info = $this->getConvertInfo($form->id);
            if(empty($goods_info))
                return false;
            $points = !empty($goods_info) ? $goods_info['points'] : 0;
            $data_info = array(
                'user_id'=>  $this->getUid(),
                'goods_id'=>  $form->id,
                'points'=>  $points,
                'time'=>  time(),
                'status'=>  3,  //开始兑换时,status为3
                'address_id'=>  $form->address_id,  
                'delivery'=>  $form->delivery           //2=上门自取，1=市内配送
            );
            $transaction = Yii::app()->getDb()->beginTransaction();
            try{
                $this->insert('points_convert_code', $data_info);
                //更新 当前用户的积分
                $sql = "UPDATE user SET fraction=fraction-{$points} WHERE id={$this->getUid()}";
                $this->execute($sql);
                //更新 兑换商品的库存
                if($goods_info['stock']!=-999){
                    $class_one_id = !empty($form->class_one_id) ? " AND class_one_id={$form->class_one_id}" : "";
                    $class_two_id = !empty($form->class_two_id) ? " AND class_two_id={$form->class_two_id}" : "";
                    $class_three_id = !empty($form->class_three_id) ? " AND class_three_id={$form->class_three_id}" : "";
                    $sql = "UPDATE points_goods SET stock=stock-1 WHERE id={$form->id} {$class_one_id} {$class_two_id} {$class_three_id}";
                    $this->execute($sql);
                }
                $transaction->commit();
                return true;
            }catch(Exception $exc) {
                $transaction->rollback();
                return false;
            }
        }
        //根据 兑换商品ID,得到兑换 商品的详情
        private function getConvertInfo($id)
        {
            if(empty($id))
                return array();
            $sql = "SELECT class_one_id,class_two_id,class_three_id,points,stock FROM points_goods WHERE shelf_id=1101 AND id={$id} LIMIT 1";
            return $this->queryRow($sql);
        }
        /*
         * 积分兑换 商品 确认兑换
         */
        public function setConConvert($id)
        {
            $update_info = array(
                'accept_time'=>  time(),
                'status'=>1
            );
            $transaction = Yii::app()->getDb()->beginTransaction();
            try {
                $this->update('points_convert_code', $update_info," id={$id} AND user_id={$this->getUid()}");
                $transaction->commit();
                return true;
            } catch (Exception $exc) {
                $transaction->rollback();
                return false;
            }
        }
}