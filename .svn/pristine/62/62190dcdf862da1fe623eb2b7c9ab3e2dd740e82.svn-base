<?php Views::css('orders'); ?>
<?php
	$flag = false;
	$freight_money = 0;
	$this->renderPartial('navigation',array('searchPost'=>$searchPost)); 
?>
	<header class="order-tit">
		<h2>由父订单号：<em class="c_d22238"><?php echo $order_sn;?></em> 折分的订单</h2>
	</header>
	<section class="order-wraper-2">
		<!-- 订单信息 -->
		<table class="tab-list-1 mb30px tc">
			<colgroup>
				<col style="width:20%">
				<col style="width:auto">
				<col style="width:15%">
				<col style="width:15%">
				<col style="width:10%">
			</colgroup>
			<?php foreach ($order as $val){ ?>
			<thead>
				<tr class="tit">
					<th>商品名称</th>
					<th>价格</th>
					<th>数量</th>
					<th>子订单编号</th>
					<th style="width:18%">操作</th>
				</tr>
			</thead>
			
			<tbody>
			<?php if($val['is_pay'] == 0) $flag= true; foreach ($val['goods'] as $key => $vals){ ?>
				<tr>
					<?php $freight_money += $val['freight_money'];?>
					<td align="left"><?php echo $vals['title'];?></td>
					<td>¥<?php echo $vals['unit_price'];?></td>
					<td><?php echo $vals['num'];?></td>
					<?php if($key == 0){?>
					<td rowspan="<?php echo count($val['goods']);?>" style="border:1px solid #ccc;"><?php echo $val['order_sn'];?></td>
					<td rowspan="<?php echo count($val['goods']);?>" style="border:1px solid #ccc;">
						<a class="c_1a66b3" href="order.edit?order_sn=<?php echo $val['order_sn'];?>">订单详情</a>
						<br/><span>订单状态：<label style='color:red'><?php echo isset( $val['merchant_title'] ) ? $val['merchant_title'] : ''; ?></label></span>
					</td>	
					<?php }?>
				</tr>
				<?php }?>
			</tbody>
			
			<?php }?>
		</table>
		<!-- ｜订单信息 -->
		<h3 class="tit-1">订单信息</h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:33%">
				<col style="width:33%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<td>订单号：<span class="c_d22238"><?php echo $orderInfo['order_sn'];?></span></td>
					<td>付款状态：<?php echo $flag ? "<span style='color:red'>未付款</span>" : "<span style='color:green'>已付款</span>";?></td>
					<td>付款方式：<?php echo $orderInfo['pay_port'] == 1 ? '支付宝支付' : ($orderInfo['pay_port'] == 2 ? '银联网上支付' : ($orderInfo['pay_port'] == 3 ? '财付通' : '货到付款'));?></td>
				</tr>
				<tr>
					<td>下单时间：<?php echo date('Y-m-d H:i:s',$orderInfo['create_time']);?></td>
					<td>运费：￥ <?php echo $freight_money;?>.00</td>
					<td>配送方式：<?php echo $orderInfo['delivery_way'] == 2 ? '上门自提' : '市内配送'; ?></td>
				</tr>
				<tr>
					<td>拆分原因：订多在多个商家购买多个库房拆分分开配送</td>
					<td>拆分时间 ：<?php echo date('Y-m-d H:i:s',$orderInfo['create_time']);?></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<!-- ｜订单信息 -->
		<h3 class="tit-1">收货人信息</h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<td>收货人：<?php echo $orderInfo['cons_name'];?></td>
					<td>联系方式：<?php echo $orderInfo['cons_phone'];?></td>
				</tr>
				<tr>
					<td colspan="2">收货地址：<?php echo $orderInfo['cons_address'];?></td>
				</tr>
			</tbody>
		</table>
	</section>

