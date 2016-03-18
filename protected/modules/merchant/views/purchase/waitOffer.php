	<?php
		Views::jquery();
		Views::js(array('jquery-placeholderPlug','jquery.validate'));
	?>
	<!-- 当前位置 -->
	<nav class="current-stie">
		<span><a href="/">首页</a></span><i>&gt;</i>
		<span><?php echo CHtml::link('商家中心' , $this->createUrl('home/index'));?></span><i>&gt;</i>
		<span><?php echo CHtml::link('集采管理' , $this->createUrl('index'));?></span><i>&gt;</i>
		<span>订单管理详情</span>
	</nav>
	<!-- main -->
	<main>
		<section class="order-state">
			<div>
				<span>订单号：<?php echo $purchaseInfo['purchase_sn'];?></span>
				<span>状态：
					<b><?php echo $purchaseInfo['status'] == 0 ? '结束报价' : ($purchaseInfo['status'] == 1 ? '已报价' : ($purchaseInfo['status'] == 2 ? '正在报价' : '等待报价'));?></b>
				</span>
			</div>
			<p>
			<?php echo $purchaseInfo['status'] == 0 ? '订单报价截止日期已到，改订单报价已结束，请选择其他订单报价。' : ($purchaseInfo['status'] == 1 ? '订单已给出报价，请您耐心等待客户的答复和选择，这边会尽快给你答复。' : ($purchaseInfo['status'] == 2 ? '订单正在报价中，请您在订单报价截止时间之前完成报价，否则预期订单会停止报价。' : '订单还未给出报价，请您在订单报价完成之前完成报价，否则预期订单会自动删除。'));?>
			</p>
		</section>
		<section class="mer-order-wrap">
			<!-- 订单列表 -->
			<fieldset class="form-list form-offer-list">
				<legend>等待报价</legend>
				<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','action'=>$this->createUrl(in_array($purchaseInfo['status'], array(0,1)) ? 'yesOffer' : 'index'),'enableAjaxValidation'=>true)); ?>
					<ul>
						<li><h6>联系人：</h6><b><?php echo $purchaseInfo['link_man'];?></b></li>
						<li><h6>标题：</h6><b><?php echo $purchaseInfo['title'];?></b></li>
						<li><h6>产品：</h6>
							<aside>
								<table class="goods-tab">
									<colgroup>
										<col width="20%">
										<col width="20%">
										<col width="10%">
										<col width="10%">
										<col width="15%">
										<col width="10%">
										<col width="auto">
									</colgroup>
									<thead>
										<tr>
											<th>产品名称</th>
											<th>所属分类</th>
											<th>数量</th>
											<th>单位</th>
											<th>产品描述</th>
											<th>报价</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($purchaseInfo['goods'])):foreach ($purchaseInfo['goods'] as $key => $val):?>
										<tr>
											<td><?php echo $val['name'];?></td>
											<td>
												<?php 
													if($val['class_one_id']) echo isset($goodsTree[$val['class_one_id']][0]) ? $goodsTree[$val['class_one_id']][0] : '';
													if($val['class_two_id']) echo "--".(isset($goodsTree[$val['class_one_id']]['child'][$val['class_two_id']][0]) ? $goodsTree[$val['class_one_id']]['child'][$val['class_two_id']][0] : '');
													if($val['class_three_id']) echo "--".(isset($goodsTree[$val['class_one_id']]['child'][$val['class_two_id']]['child'][$val['class_three_id']][0]) ? $goodsTree[$val['class_one_id']]['child'][$val['class_two_id']]['child'][$val['class_three_id']][0] : '');
												?>
											</td>
											<td><?php echo $val['num_min'].'-'.$val['num_max'];?></td>
											<td><?php echo $val['params']?></td>
											<td><?php echo $val['descript'];?></td>
											<td>
												<?php if($val['isPrice']):?>
												<label><?php echo $val['isPrice'];?></label>
												<?php else:?>
												<label style="color:red">未报价</label>
												<?php endif;?>
											</td>
											<td class="control">
												<?php if($purchaseInfo['status'] != 0):?>
													<?php if($val['isPrice'] && $val['isPrice'] != 0.00):?>
													<label style="color:green" class="allPrice">已报价</label>
													<?php else:?>
													<a class="mc" href="javascript:;" onclick="showPurchase(<?php echo $val['id'];?>);">报价</a>
													<?php endif;?>
												<?php else:?>
													<?php if($val['isPrice']):?>
													<label style="color:green">已报价</label>
													<?php else:?>
													<label style="color:gray">报价已结束</label>
													<?php endif;?>
												<?php endif;?>
											</td>
										</tr>
										<?php endforeach;endif;?>
									</tbody>
								</table>
							</aside>
						</li>
						<input type="hidden" name="closedNum" id="priceNum" value=""/>
						<li class="txt mb"><h6>报价截止时间：</h6><b> <?php echo date('Y-m-d H:i:s',$purchaseInfo['price_endtime']);?></b></li>
						<li class="txt mb"><h6>期望收货时间：</h6><b> <?php echo date('Y-m-d H:i:s',empty($purchaseInfo['wish_receivingtime']) ? time() : $purchaseInfo['wish_receivingtime']);?></b></li>
						<li class="txt mb"><h6>报价要求：</h6><b><?php if($purchaseInfo['price_require']==0){echo '不含税价';}else{echo '包含税价';}?></b></li>
						<li class="txt mb"><h6>是否投标：</h6><aside class="gray"><?php if($purchaseInfo['is_tender_offer']==0){echo '否';}else{echo '是';}?></aside></li>
						<li class="txt mb"><h6>是否面谈：</h6><aside class="gray"><?php if($purchaseInfo['is_interview']==0){echo '否';}else{echo '是';}?></aside></li>
						<li><h6>&nbsp;</h6><input class="btn-1 btn-1-11" type="submit" value="返回列表"></li>
					</ul>
				<?php $this->endWidget(); ?>
			</fieldset>
		</section>
	</main>
	
	<!-- 新增集采订单报价 -->
	<section class="pop-wrap" id="floatWraper" style="display: none">
		<header><h3>报价</h3><a id="close" href="javascript:;" onclick="$('#floatWraper,.mask').hide();"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>报价</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('showDetail',array('pid'=>$purchaseInfo['purchase_sn'])),'id'=>'addressForm')); ?>
				<div class="promt error msg promt-1" id="promt">请输入真实姓名</div>
				<ul>
					<li><h6>产品名称：</h6><b class="goods_1">华硕X552MJ2840</b></li>
					<li><h6>产品数量：</h6><b class="goods_2">3箱</b></li>
					<li><h6>产品描述：</h6><b class="goods_3">要13寸的</b></li>
					<input type="hidden" name="PurchaseForm[gid]" class="goods_id">
					<input type="hidden" name="PurchaseForm[priceGoodsNum]" id="priceGoodsNum">
					<input type="hidden" name="PurchaseForm[goodsNum]" id="goodsNum">
					<input type="hidden" name="PurchaseForm[goodsTitle]" id="goodsTitle">
					<li><h6>我的报价：</h6><input id="offer" name="PurchaseForm[price]" class="tbox34" type="text"></li>
					<li><h6>&nbsp;</h6><?php echo CHtml::submitButton('保存' , array('class'=>'btn-1 btn-1-3')),CHtml::resetButton('取消' , array('class'=>'btn-1 btn-1-4','onclick'=>"$('#floatWraper,.mask').hide();")); ?>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<div class="mask" id="maskbox" style="display: none"></div>
	<script>
		function showPurchase(gid){
			$('#priceGoodsNum').val($('.allPrice').length);
			if(gid){
				$.ajax({
					url:"/merchant/purchase/GoodsInfo",
					type:"POST",
					data:{gid:gid},
					success: function (data) {
						if(data){
							var goodsInfo = eval('('+data+')');
							$('.goods_1').html(goodsInfo.name);
							$('.goods_2').html(goodsInfo.num_min+'-'+goodsInfo.num_max);
							$('.goods_3').html(goodsInfo.descript);
							$('#goodsNum').val(goodsInfo.num_min+'-'+goodsInfo.num_max);
							$('.goods_id').val(goodsInfo.id);
							$('#goodsTitle').val(goodsInfo.name);
							$('#floatWraper').slideDown();
							$('.mask').show();
						}else{
							return false;
						}
					}
				});	
			}
			return false;
		}
		//点击其他位置关闭弹窗
		$(document).click(function (event){
			if(typeof(event)!= 'undefined'){
				var target = event.srcElement || event.target ;
				if( !$(target).parents('#floatWraper').is(":visible") && $("#floatWraper").is(":visible") && $(target).attr('id') != "floatWraper" ) {
					$("#floatWraper,.mask").slideUp();
				}
			}
		});
		$(function($){
			// ================================== 焦点特效
			$('#offer').placeholderPlug();
			var $form = $('#addressForm');
			$form.validate({
				rule : {
					offer : {
						required : '请输入商品报价',
					},
				},
				site : 'one',
				 way : 'one',
			   focus : true
			});
		});
	</script>
