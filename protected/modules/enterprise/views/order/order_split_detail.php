<?php 
$flag = false;
Yii::app()->getClientScript()->registerCss('member.detail',"
.order_tbody tr td{text-align:center;}
.order_tbody tr td a:hover{color:#1987d4;text-decoration:underline;}
");?>
<nav class="current-stie">
	<span><?php echo CHtml::link('首页' , '/');?></span><i>&gt;</i>
	<span><?php echo CHtml::link('订单列表' , $this->createUrl('order/index'));?></span><i>&gt;</i>
	<span>订单拆分详情</span>
</nav>
<main>
	<h2 class="order-list-tit"><em class="mc"><?php echo $order_sn;?></em> 折分的订单:</h2>
	<section class="order-list-wrap">
		<!-- 订单信息 -->
		<table class="tab-order-list mb30px tc">
			<colgroup>
				<col style="width:auto">
				<col style="width:20%">
				<col style="width:15%">
				<col style="width:15%">
				<col style="width:10%">
			</colgroup>
			<?php foreach ($order as $val):?>
			<thead>
				<tr class="tit">
					<th>商品名称</th>
					<th>价格</th>
					<th>数量</th>
					<th>子订单编号</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody class="order_tbody">
				<?php if($val['is_pay'] == 0) $flag= true; foreach ($val['goods'] as $key => $vals):?>
				<tr>
					<td style="text-align:left" ><a href="<?php echo $vals['goods_type'] ==2 ? '/used/intro?id='.$vals['goods_id'] : '/goods?id='.$vals['goods_id'];?>" target="_blank" title="<?php echo $vals['goods_title']; ?>"><?php echo strlen($vals['goods_title'])>60 ? mb_substr($vals['goods_title'],0, 28,'utf-8')."..." : $vals['goods_title'];?></a></td>
					<td>¥<?php echo $vals['unit_price'];?></td>
					<td><?php echo $vals['num'];?></td>
					<?php if($key == 0):?>
					<td rowspan="<?php echo count($val['goods']);?>"><?php echo $val['order_sn'];?></td>
					<td rowspan="<?php echo count($val['goods']);?>"><?php echo CHtml::link('订单详情' , $this->createUrl('order/detail',array('oid'=>$val['order_sn'])), array('class'=>'g-c'));?></td>
					<?php endif;?>
				</tr>
				<?php endforeach;?>
			</tbody>
			<?php endforeach;?>
		</table>
		<!-- ｜订单信息 -->
		<h3 class="order-tit">订单信息</h3>
		<table class="tab-order-list mb30px">
			<colgroup>
				<col style="width:33%">
				<col style="width:33%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<td>订单号：<span class="mc"><?php echo $orderInfo['order_sn'];?></span></td>
					<td>付款状态：<?php echo $flag ? "<span style='color:red'>未付款</span>" : "<span style='color:green'>已付款</span>";?></td>
					<td>付款方式：<?php echo $orderInfo['pay_port'] == 1 ? '支付宝支付' : ($val['pay_port'] == 2 ? '银联网上支付' : ($orderInfo['pay_port'] == 3 ? '财付通' : '货到付款'));?></td>
				</tr>
				<tr>
					<td>下单时间：<?php echo date('Y-m-d H:i:s',$orderInfo['create_time']);?></td>
					<td>运费：￥ <?php echo $orderInfo['freight_money'];?></td>
					<?php if($orderInfo['delivery_way'] == 2):?>
					<td>自提地址：成都市一环路南二段15号东华电脑城北楼104</td>
					<?php else:?>
					<td>配送方式：市内配送</td>
					<?php endif;?>
				</tr>
				<tr>
					<td>拆分原因：订多在多个商家购买多个库房拆分分开配送</td>
					<td>拆分时间 ：<?php echo date('Y-m-d H:i:s',$orderInfo['create_time']);?></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<!-- ｜订单信息 -->
		<h3 class="order-tit">收货人信息</h3>
		<table class="tab-order-list">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<?php $addrArr = json_decode($orderInfo['addressee_shoot'],true);?>
				<tr>
					<td>收货人：<?php echo $addrArr['consignee'];?></td>
					<td>联系方式：<?php echo $addrArr['phone'];?></td>
				</tr>
				<tr>
					<td colspan="2">收货地址：<?php echo $addrArr['address'];?></td>
				</tr>
			</tbody>
		</table>
	</section>
</main>
