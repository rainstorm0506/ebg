<?php Views::css('orders'); ?>
<?php
	$model = ClassLoad::Only('ActOrder'); /* @var $model Order */
?>
	<header class="order-tit" style="margin-top:10px">
		<h2><a href="order.list">活动</a> <b>&gt;</b> 订单详情</h2><input type='hidden' id='ordersn' value="<?php echo $info['order_sn'];?>">
		<a class="btn-3" href="<?php echo $this->createUrl('actOrder/list');?>">返回</a>
		<a class="btn-2 popen" href="javascript:;" id='system_memo'>系统内部备注</a>
		<?php if(in_array($info['order_status'], array(101,103,105,115))){?><a class="btn-2 popen" href="javascript:;" id='abolish'>取消订单</a><?php }?>
		<?php if($info['order_status'] == 108){?><a class="btn-2 popen" href="javascript:;" id='back_money'>确认财务退款完成</a><?php }?>
		<?php if($info['order_status'] == 102){?><a class="btn-2 popen" href="javascript:;" id='show_abolish'>查看订单取消原因</a><?php }?>
		<?php if($info['order_status'] == 104){?><a class="btn-2 popen" href="javascript:;" id='option_abolish'>处理取消订单申请</a><?php }?>
		<?php if($info['order_status'] == 10){?><a class="btn-2 popen" href="javascript:;" id='pay'>手动确认付款</a><?php }?>
		<?php if($info['order_status'] == 21){?><a class="btn-2 popen" href="javascript:;" id='send'>发货</a><?php }?>
		<?php if($info['order_status'] == 20){?><a class="btn-2 popen" href="javascript:;" id='finish_prepare'>确认备货完成</a><?php }?>
	</header>
	<section class="order-wraper">
		<!-- 流程 -->
		<ul class="process-list" style="width:1210px">
			<li class="<?php if($info['order_status'] > 10)echo 'pass';else if($info['order_status'] == 10) echo 'current';?>"><div><p>提交订单</p><time><?php echo date('Y-m-d H:i:s',$info['create_time']); ?></time></div><b><i></i></b></li>
			<?php if ($info['order_status']!=41){?>
			<li class="<?php echo $info['order_status']>10?'pass':'';?>"><div><p>已付款</p><time><?php if(!empty($info['pay_time']))echo date('Y-m-d H:i:s',$info['pay_time']);?></time></div><b><i></i></b></li>
			<?php }?>
			<?php if($info['order_status'] == 41){?>			
			<li class="pass"><div><p>已取消订单</p><time><?php echo $model->getOrderLogTime(41, $info['order_sn']); ?></time></div></li>
			<?php }elseif( $info['order_status'] == 50 || $info['order_status'] == 51 ){?>
			<li class="<?php if($info['order_status']==50)echo 'current';else echo 'pass'?>"><div><p>申请退款</p><time><?php echo $model->getOrderLogTime(50, $info['order_sn']); ?></time></div><b><i></i></b></li>
			<li class="<?php if($info['order_status'] == 51)echo 'current';?>"><div><p>已退款</p></div></li>
			<?php }else{?>
			<li class="<?php if($info['order_status'] > 20)echo 'pass';else if($info['order_status'] == 20) echo 'current';?>"><div><p>待备货</p><time><?php echo $model->getOrderLogTime(20, $info['order_sn']); ?></time></div><b><i></i></b></li>
			<li class="<?php if($info['order_status'] > 21 && $info['order_status'] !=21)echo 'pass';else if($info['order_status'] == 21) echo 'current';?>"><div><p>备货完成</p><time><?php echo $model->getOrderLogTime(21, $info['order_sn']); ?></time></div><b><i></i></b></li>
			<li class="<?php if($info['order_status'] > 30 && $info['order_status'] !=30)echo 'pass';else if($info['order_status'] == 30) echo 'current';?>"><div><p>已发货</p><time><?php echo $model->getOrderLogTime(30, $info['order_sn']); ?></time></div><b><i></i></b></li>
			<li class="<?php if($info['order_status'] == 40)echo 'current';?>"><div><p>已完成</p><time><?php echo $model->getOrderLogTime(40, $info['order_sn']); ?></time></div></li>
			<?php }?>			
		</ul>
		<!-- 订单信息 -->
		<h3 class="tit-1">订单信息<label><a href='#log' class="orderLogs" >[ 订单操作日志 ]</a></label></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:33%">
				<col style="width:33%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<td>订单号：	<?php echo $info['order_sn']; ?></td>
					<td>付款方式：<?php echo $info['pay_type'] == 1 ? '在线支付' : '货到付款';?></td>
					<td>付款状态：<?php echo $info['is_pay'] == '1'? '<span style="color:green">已支付</span>' : '<span class="c_d22238">未支付</span>'; ?><?php if($info['is_pay'] == '1'){?><span><a href='#paylog' class="pay_log">[ 订单支付日志 ]</a></span><?php }?></td>
				</tr>
				<tr>
					<td>下单时间：<?php echo date('Y-m-d H:i:s',$info['create_time']); ?></td>
					<td>配送方式：<?php echo $info['delivery_way'] == 2 ? '上门自提' : '市内配送'; ?></td>
					<td>订单状态：<span class="c_d22238"><?php echo $info['order_status_string'];?></span></td>					
				</tr>
				<tr>
					<td>用户ID：<?php echo $info['user_id']; ?></td>
					<td>用户名：<?php echo $info['nickname']; ?></td>
					<td>用户手机号：<?php echo $info['phone']; ?></td>					
				</tr>
				<?php if(!empty($info['express_no'])){?>
				<tr>
					<td>发货单号：<span class="c_d22238"><?php echo $info['express_no']; ?></span></td>
					<td>物流公司名称：<?php echo $info['firm_name']; ?></td>			
				</tr>
				<?php }?>
			</tbody>
		</table>
		<!-- 订单详情 -->
		<h3 class="tit-1">订单详情</h3>
		<table class="tab-list-1 mb30px tc">
			<colgroup>
				<col style="width:auto">
				<col style="width:11%">
				<col style="width:11%">
				<col style="width:11%">
				<col style="width:11%">
			</colgroup>
			<thead>
				<tr class="tit">
					<th colspan="5" ><strong>商家：<?php echo $info['store_name'].($info['is_self'] == 1 ? '(<span style="color:#33cc33">自营</span>)':'');?></strong><strong style="margin-left: 200px">商家联系电话：<label style="color:red"><?php echo $info['mer_phone'];?></label></strong><b>商家地址：<?php echo $info['store_address'];?></b></th>
				</tr>
				<tr>
					<th class="tl">商品</th>
					<th>规格</th>
					<th>数量</th>
					<th>单价</th>
					<th>小计</th>
				</tr>
			</thead>
			<?php $totalNum = 0;?>
			<?php foreach($info['goods'] as $val){ $unitPrice = 0;?>
			<tbody>
				<tr>
					<td class="tl"><a href="" target="_blank" title="<?php echo $val['title']?>"><img src="<?php echo isset($val['goods_cover']) ? Views::imgShow($val['goods_cover']) : '';?>" width="80" height="80"><span><?php echo $val['title'];?></span></a></td>
					<td>
						<?php 
							if(!empty($val['goods_attrs']) && $val['goods_attrs'] != '[]'){
								$goodsAttr = json_decode($val['goods_attrs'],true);
								if(!empty($goodsAttr)){
									foreach ($goodsAttr as $value){
										echo ''.$value[1].' : '.$value[2]."<br>";
									}
								}
							}
						?>
					</td>
					<td><?php echo $val['num'];?></td>
					<td>¥<?php echo $val['unit_price'];?></td>
					<td class="b">¥<?php $unitPrice = (int)$val['num']*$val['unit_price']; $totalNum += $unitPrice; echo strpos($unitPrice,'.')>0 ? $unitPrice : $unitPrice.'.00';?></td>
				</tr>
			</tbody>
			<?php }?>
			<tfoot>
				<tr>			
					<td colspan="5" class="tab-note">
						<p>用户备注：<?php echo $info['user_remark'];?></p>
						<div style="margin:0 20px">实际订单价格：<b class="c_d22238" style="text-decoration: underline">￥<?php echo $info['order_money'];?></b></div>
						<div>总计：<b class="c_d22238">￥<?php echo strpos($info['goods_money'],'.')>0 ? $info['goods_money'] : $info['goods_money'].".00";?></b></div>
						<div style="margin-right: 20px">运费：￥<?php echo $info['freight_money'];?></div>
					</td>	
				</tr>
			</tfoot>
		</table>
		<!-- 收货人信息 -->
		<h3 class="tit-1">收货人信息</h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<td>收货人：<?php echo $info['cons_name'];?></td>
					<td>联系方式：<?php echo $info['cons_phone'];?></td>
				</tr>
				<tr>
					<td colspan="2">收货地址：<?php echo $info['cons_address'];?></td>
				</tr>
			</tbody>
		</table>
		<!-- 收货人信息 -->
		
		<h3 class="tit-1" id="paylog">订单支付日志</h3>
		<table class="tab-list-1 tab-list-1-1">
			<colgroup>
				<col style="width:20%">
				<col style="width:20%">
				<col style="width:20%">
				<col style="width:20%">
				<col style="width:auto">
			</colgroup>
			<thead>
				<tr class="tit tl">
					<th>交易号</th>
					<th>支付时间</th>
					<th>支付的金额</th>
					<th>支付方式</th>
					<th>确认方式</th>
				</tr>
			</thead>
			<tbody>
				<?php if($info['pay_logs']){foreach($info['pay_logs'] as $key => $val){?>
				<tr <?php if($key == 0) echo "style='color:red'";?>>
					<td><?php echo $val['trade_no'];?></td>
					<td><?php echo date('Y-m-d H:i:s',$val['pay_time']);?></td>
					<td><?php echo $val['pay_money'].'元';?></td>
					<td><?php echo $val['pay_port'] == '1'?'支付宝':($val['pay_port'] == '2'?'银联':($val['pay_port'] == '3'?'财付通':($val['pay_port'] == '4'?'货到付款':'邮政汇款')));?></td>
					<td><?php echo $val['pay_verify'] == '1' ? '程序确认' : '人工确认';?></td>
				</tr>
				<?php }}else{?>
				<tr>
					<td colspan="5" style="color:red" align="center">暂无数据</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		
		<!-- 商家物流信息 -->
		
		<h3 class="tit-1" style="margin-top:40px">订单物流费用</h3>
		<table class="tab-list-1 tab-list-1-1">
			<colgroup>
				<col style="width:33.3%">
				<col style="width:33.3%">
				<col style="width:auto">
			</colgroup>
			<thead>
				<tr class="tit tl">
					<th>物流公司</th>
					<th>运单号</th>
					<th>费用</th>
				</tr>
			</thead>
			<tbody>
				<?php if($info){?>
				<tr>
					<td><?php echo $info['firm_name']?></td>
					<td><?php echo $info['express_no'];?></td>
					<td><?php echo $info['merchant_money'],'元'?></td>
				</tr>
				<?php }else{?>
				<tr>
					<td colspan="5" style="color:red" align="center">暂无数据</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		
		<h3 class="tit-1" id="log" style="margin-top:40px">订单操作日志</h3>
		<table class="tab-list-1 tab-list-1-1">
			<colgroup>
				<col style="width:15%">
				<col style="width:45%">
				<col style="width:20%">
				<col style="width:10%">
				<col style="width:auto">
			</colgroup>
			<thead>
				<tr class="tit tl">
					<th>处理时间</th>
					<th>处理信息</th>
					<th>备注</th>
					<th>操作人类型</th>
					<th>操作人</th>
				</tr>
			</thead>
			<tbody>
				<?php if($info['logs']){foreach($info['logs'] as $key => $val){?>
				<tr <?php if($key == 0) echo "style='color:red'";?>>
					<td><?php echo date('Y-m-d H:i:s',$val['time']);?></td>
					<td><?php echo $val['logs'];?></td>
					<td><?php echo $val['memo'];?></td>
					<td><?php echo $val['operate_type'] == '1'?'用户':($val['operate_type'] == '2'?'商家':'系统');?></td>
					<td><?php echo $val['true_name'];?></td>
				</tr>
				<?php }}else{?>
				<tr>
					<td colspan="5" style="color:red" align="center">暂无数据</td>
				</tr>
				<?php }?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5"><h5><b>系统备注</b>：</h5><span style="margin-left:90px;color:red"><?php echo !empty($info['system_remark']) ? $info['system_remark'] : '暂无数据'; ?></span></td>
				</tr>
			</tfoot>
		</table>
	</section>
<script type="text/javascript">
$(function($){
	$('a.popen').click(function(){
		var e = $(this);
		var url = "<?php echo $this->createUrl('actOrder/showOption');?>?type="+e.attr('id')+"&order_sn="+$('#ordersn').val()+"&goods_id="+e.attr('goods_id');
		window.top.layerIndexs = getLayer().open({
			'type'			: 2,
			'title'			: e.text(),
			'shadeClose'	: true,
			'shade'			: 0.4,
			'area'			: ['580px', '50%'],
			'content'		: url,
			'end'			: function(){window.location.reload();}
		});
		return false;
	});
});
</script>