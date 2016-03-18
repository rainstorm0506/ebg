<?php
class Subscibe extends ApiModels{
	
	//设置关注
	public function getRestgz($form)
	{
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$uid = $this->getMerchantID();
			$this->delete("user_collect",'user_id='.$uid);
			$data = $form->collect;
			$time = time();
			foreach ($data as $val)
			{
				$arr=array(
						'type'				=>$val['type'],
						'user_id'			=>$uid,
						'collect_id'		=>$val['id'],
						'collect_time'		=>$time,
				);
				$this->insert('user_collect' , $arr);
			} 
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	

	//我的关注列表
	public function getTestFocus()
	{
		$uid = $this->getMerchantID();
		$data= array();
		$classify = $this->queryAll("SELECT * FROM supply_class AS s left JOIN user_collect AS u ON s.id= u.collect_id where u.user_id={$uid} and u.type=5");
		if(!empty($classify) || !empty($brandlist))
		{
			foreach ($classify as $v){
				$arr=array('id'=>$v['id'],'type'=>$v['type'],'title'=>$v['title']);
				$data[]=$arr;
			}
		}else{
			return array();
		}
		
		
		$brandlist = $this->queryAll("SELECT * FROM goods_brand AS b left JOIN user_collect AS u ON b.id= u.collect_id where u.user_id={$uid} and u.type=4");
		if(!empty($classify) || !empty($brandlist))
		{
			foreach ($brandlist as $val){
				$arr1=array('id'=>$val['id'],'type'=>$val['type'],'title'=>$val['zh_name']);
				$data[]=$arr1;
			}
		}else{
			return array();
		}
		
		return $data;
	}
	//关注列表
	public function getList()
	{
		$data=array();
		$tmp = $this->queryAll("SELECT id,title,5 AS type FROM supply_class");
		foreach ($tmp as $val)
		{
			$data[]=array('id'=>(int)$val['id'],'title'=>$val['title'],'type'=>5);
		}
		foreach (GlobalBrand::getList(1) as $k => $v)
			$data[] = array('id'=>$k , 'title'=>GlobalBrand::getBrandName($k , 1) , 'type'=>4);
		
		return $data;
	}
	
	//删除关注
	public function getDelete($form)
	{
		$data = $form->collect;
		$uid = $this->getMerchantID();
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			foreach ($data as $val)
			{
				$this->delete("user_collect","user_id ={$uid} and collect_id={$val['id']} and type={$val['type']}" );
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	//包含选中的
	public function getValid()
	{
		$arr1 = $this->getList();
		$arr2=$this->getTestFocus();
		
		foreach ($arr1 as $k=>$v)
		{
			$arr1[$k]['status']=2;
			foreach ($arr2 as $k1=>$v1)
			{
				if($v['type']==$v1['type'] && $v['id']==$v1['id'])
				{
					$arr1[$k]['status'] = 1;
				}
			}
		}
		
		return $arr1;
	}
}