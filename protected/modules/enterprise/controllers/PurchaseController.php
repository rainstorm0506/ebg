<?php
/**
 * 企业集采管理 - 控制器
 * 
 * @author Jeson.Q
 */
class PurchaseController extends EnterpriseController
{
	//企业集采首页
	public function actionIndex()
	{
		$this->leftNavType = 'purchase';
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$form = ClassLoad::Only('PurchaseForm'); /* @var $form PurchaseForm */
		$searchPost = isset($_POST['PurchaseForm']) ? $_POST['PurchaseForm'] : array();

		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//查询订单列表
		$purchaseDataAll['purchaseData'] = $model->getList($searchPost, $offset , $page->pageSize);
		$purchaseDataAll['page'] = $page;
		$purchaseDataAll['form'] = $form;
		$purchaseDataAll['searchPost'] = $searchPost;
		//渲染试图
		$this->render('index' , $purchaseDataAll);
	}

	//企业集采详细页
	public function actionShowDetail()
	{
		$this->leftNavType = 'purchase';

		$pid = (string)$this->getParam('pid' , 0);
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */

		//设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getGoodsTotalNumber($pid);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 6;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		$purchaseData = $model->getActiveInfo($pid, $offset , $page->pageSize);
		//查询列表并 渲染试图
		$this->render('detail' , array(
			'purchaseData' => $purchaseData,
			'page' => $page,
			'goodsTree' => GlobalGoodsClass::getTree()
		));
	}

	//企业用户ajax 查询报价列表
	public function actionAjaxAbolish()
	{
		$gid = (int)$this->getParam('gid');
		$type = (int)$this->getParam('type');
		$priceStr = " ";
		$model = ClassLoad::Only('Purchase'); /* @var $model Purchase */
		$priceList = $model->getPriceList($gid, $type);
		if(!empty($priceList)){
			foreach ($priceList as $key => $val){
				$goodsLink = empty($val['goods_id']) ? $this->createFrontUrl('store/index' , array('mid'=>$val['merchant_id'])) : $this->createFrontUrl('goods/index' , array('id'=>$val['goods_id']));
				$priceStr .= '<tr>
								<td class="img"><img src="'.Views::imgShow($val['store_avatar']).'" width="40" height="40"></td>
								<td class="tl link">'.$val['store_name'].'</td>
								<td>'.$val['name'].'</td>
								<td>'.date('Y-m-d',$val['create_time']).'</td>
								<td>'.($val['num'] == -999 ? '无限库存' : $val['num']."个").'</td>
								<td colspan="2" class="mc">'.($type == 1 ? $val['rem_price'] : $val['price']).'</td>
								<td class="control"><a href="'.$goodsLink.'" target="_blank">查看</a></td>
							</tr>';
			}
		}else{
			$priceStr = "<tr><td style='text-align:center;color:red' colspan='8'>".($type == 1 ? "暂无e办公商品推荐！" : "暂无商家报价！")."</td></tr>";
		}
		echo $priceStr;
	}

	//企业删除订单操作
	public function actionDeleteOrder()
	{
		if($_POST)
		{
			$purchase = ClassLoad::Only('Purchase');/* @var $purchase Purchase */
			$purchase->deleteOrder($_POST);
		}
		$this->redirect (array (
			'purchase/index'
		));
	}

}