<?php
/**
 * 积分商城
 */
class CreditsController extends WebController
{
	public function actionIndex()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('credits' , 0));

		$model		= ClassLoad::Only('Credits');/* @var $model Credits */
		$search		= array(
			'order'		=> (string)$this->getQuery('o'),
			'by'		=> (string)$this->getQuery('by'),
		);

		if ($search['order'] && !in_array($search['order'] , array('price' , 'sales' , 'putaway')))
			$this->error('请选择正确的排序!');

		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;
		$user = $this->getUser();

		$this->render('index' , array_merge(array(
			'page'          => $page,
			'user'          => $user,
			'list'          => $model->getList($search,$page->getOffset() , $page->getLimit() , $page->getItemCount()),
		), $search));
	}

	public function actionIntro()
	{
		$user = $this->getUser();
		
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$this->setPageSeo(GlobalSEO::getSeoInfo('pg' , $id));

		$model=ClassLoad::Only('Credits');/* @var $model Credits */

		$intro=$model->intro($id);
		$attr=$this->jsonDnCode($intro['attrs']);
		$this->render('intro', array(
			'intro' =>$intro,
			'attr'  =>$attr['attrs'],
			'user'  =>$user
		));
	}
	//兑换
	public function actionGoods()
	{
		$form = ClassLoad::Only('ConvertForm');/* @var $form ConvertForm */
		$this->exitAjaxPost($form );
		
		if(!$id=$this->getQuery('id'))
			$this->error('错误的商品id');
		
		$str='';
		if(isset($_SESSION['points']['goods'])){
			foreach($_SESSION['points']['goods'] as $v){
				$str.=$v.' ';
			}
		}

		$model=ClassLoad::Only('Credits');/* @var $model Credits */
		if($this->isPost() && isset($_POST['ConvertForm']))
		{
			$form->attributes = $_POST['ConvertForm'];
			if($form->validate() && $model->convertCode($_POST['ConvertForm']))
			{
				GlobalUser::setReflushUser($this->getUser() , 1);
				$this->redirect(array('member/myExchange/index'));
			}
		}

		$goods=$model->intro($id);
		$this->render('goods',array(
			'goods'             => $goods,
			'form'              => $form,
			'user'              => $this->getUser(),
			'attr'              => $str
		));
	}

	public function actionPromptly()
	{
		$id=(int)$_GET['id'];
		unset($_GET['id']);
		$row=$this->_cartJoin($id);
		if($row==-1){
			$this->jsonOutput(2 , '该属性商品已售罄！');
		}
		$this->jsonOutput(0 , array('src'=>$this->createUrl('credits/goods',array('id'=>$id))));
	}
	
	private function _cartJoin($goods_id)
	{
		$pars=$_GET;
		$arr=array();
		foreach($pars as $k=>$v){
			$arr[]=$v;
		}
		$model = ClassLoad::Only('Credits');/* @var $model Credits */
		$row=$model->getAttrStock($goods_id,$arr);
		if($row['stock']<1){
			return -1;
		}
		
		$seArray = array();
		$session = Yii::app()->session;
		if (isset($session['points']))
		{
			$seArray = new ArrayObject(Yii::app()->session['points']);
			$seArray = $seArray->getArrayCopy();
		}
		
		$key					= md5(serialize(array_slice($pars , 1)));
		$seArray['goods']		= $pars;
		$seArray['attr_id']		= isset($row['key_code'])?$row['key_code']:'';
		$seArray['select']		= 1;
		$session['points']		= $seArray;
		return count($seArray['goods']);
	}
}