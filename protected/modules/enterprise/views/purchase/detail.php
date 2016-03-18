<?php
Views::css('default');
Views::jquery();
Views::js(array('jquery.addbor'));
?>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">集采订单详情</header>
			<!-- tab list -->
			<div class="jican-wrap-1">
				<table class="tab-proc tab-proc-1" id="tabPro">
					<colgroup>
						<col style="width:40px">
						<col style="width:auto">
						<col style="width:18%">
						<col style="width:15%">
						<col style="width:15%">
						<col style="width:12%">
						<col style="width:12%">
					</colgroup>
					<thead>
						<tr>
							<th class="tab-proc-tit" colspan="7">
								<span>采购单名称：<?php echo $purchaseData['title'] ? $purchaseData['title'] : '暂无';?></span>
								<span>发布日期：<?php echo date('Y-m-d',$purchaseData['create_time']);?></span>
								<span>截止日期：<?php echo date('Y-m-d',$purchaseData['price_endtime']);?></span>
								<aside>共有 <strong><?php echo $purchaseData['merchantNum'];?></strong> 个商家报价</aside>
							</th>
						</tr>
						<tr>
							<th class="tl" colspan="2">产品详细列表名称</th>
							<th>所属分类</th>
							<th>数量</th>
							<th>产品描述</th>
							<th>e办公推荐</th>
							<th>商家报价</th>
						</tr>
					</thead>
					<tbody class="tbody-1">
						<?php if(isset($purchaseData['goods']) && $purchaseData['goods']):foreach ($purchaseData['goods'] as $key => $val):?>
						<tr>
							<td class="tl link" colspan="2" style="padding-left: 30px"><?php echo $val['name'];?></td>
							<td>
								<?php 
									if($val['class_one_id']) echo $goodsTree[$val['class_one_id']][0];
									if($val['class_two_id']) echo "--".$goodsTree[$val['class_one_id']]['child'][$val['class_two_id']][0];
									if($val['class_three_id']) echo "--".$goodsTree[$val['class_one_id']]['child'][$val['class_two_id']]['child'][$val['class_three_id']][0];
								?>
							</td>
							<td><?php echo $val['num_min'].'-'.$val['num_max'];?></td>
							<td><?php echo $val['descript'];?></td>
							<td class="control">
								<?php echo CHtml::link('查看' , 'javascript:;',array('class' => 'selectPrice', 'types'=>1, 'gid' => $val['id']));?>
							</td>
							<td class="control">
								<?php echo CHtml::link('查看' , 'javascript:;',array('class' => 'selectPrice', 'types'=>2, 'gid' => $val['id']));?>
							</td>
						</tr>
						<?php endforeach;else:?>
						<tr>
							<td colspan="7" style="text-align:center;color:red;">无相关商品数据！</td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			<!-- pager -->
			<?php $this->widget('WebListPager', array('pages' => $page)); ?>
		</section>
	</main>
	<!-- 商家报价 -->

	<!-- 提示 -->
	<section class="pop-promt pop-promt-proc" id="pricePop" style="overflow:scroll;display:none">
		<a class="close-btn-2" id="close" href="javascript:;" onclick="$('#pricePop,.mask').hide()" ></a>
		<div class="proc-pop">
			<!-- tab list -->
			<table class="tab-proc tab-proc-1" id="tabPro">
				<colgroup>
					<col style="width:40px">
					<col style="width:auto">
					<col style="width:20%">
					<col style="width:12%">
					<col style="width:12%">
					<col style="width:12%">
					<col style="width:10%">
					<col style="width:15%">
				</colgroup>
				<thead>
					<tr>
						<th colspan="2" id="systems">报价商家</th>
						<th>商品名称</th>
						<th id="times">报价时间</th>
						<th id="maney">数量</th>
						<th colspan='2' id="prices">报价</th>
						<th id="links">链接</th>
					</tr>
				</thead>
				<tbody class="tbody-1" id="priceGoods">
				</tbody>
			</table>
		</div>
	</section>
	<div class="mask" style="display:none"></div>
<script>
	//查看报价弹窗
	$('.selectPrice').click(function(){
		var gid = $(this).attr('gid');
		var types = $(this).attr('types');
		if(gid){
			$.ajax({
				url:"enterprise.purchase.ajaxAbolish",
				type:"POST",
				data:{gid:gid,type:types},
				success: function (data) {
					if(data){
						if(types == 1){
							$('#systems').html('推荐商家');
							$('#times').html('推荐时间');
							$('#prices').html('价格');
							$('#maney').html('库存');
							$('#links').html('查看');
						}else{
							$('#systems').html('报价商家');
							$('#times').html('报价时间');
							$('#prices').html('报价');
							$('#maney').html('数量');
							$('#links').html('店铺链接');
						}
						$('#priceGoods').html(data);
						$('#pricePop').slideDown();
						$('.mask').show();
					}else{
						alert("操作失败！请稍后重试...");
					}
				}
			});
		}
		return false;
	});
	$(document).click(function (event){
		if(typeof(event)!= 'undefined'){
			var target = event.srcElement || event.target ;
			if( !$(target).parents('#pricePop').is(":visible") && $("#pricePop").is(":visible") && $(target).attr('id') != "pricePop" ) {
				$("#pricePop,.mask").slideUp();
			}
		}
	});
</script>
