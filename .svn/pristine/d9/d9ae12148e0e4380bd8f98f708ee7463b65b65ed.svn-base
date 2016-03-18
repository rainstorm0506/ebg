<?php Yii::app()->getClientScript()->registerCss('member.detail',"
.tl a:hover{color:#1987d4;text-decoration:underline;}#tbodys tr td{text-align:center}
.tit a:hover{text-decoration:underline;color:red}
");?>
<?php $model = ClassLoad::Only('Order'); /* @var $model Order */ ?>
<nav class="current-stie">
	<span><?php echo CHtml::link('首页' , '/');?></span><i>&gt;</i>
	<span><?php echo CHtml::link('订单列表' , $this->createUrl('order/index'));?></span><i>&gt;</i>
	<span>订单详情</span>
</nav>
<ul class="process-list-order">
	<li class="<?php if($info['order_status_id'] > 101)echo 'pass';else if($info['order_status_id'] == 101) echo 'current';?>"><div><p>提交订单</p><time><?php echo date('Y-m-d H:i:s',$info['create_time']); ?></time></div><b><i class="t-r"></i></b></li>
	<?php if($info['order_status_id'] == 102):?>
	<li class="pass"><div><p>订单取消</p><time><?php echo $model->getOrderLogTime(102, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="current"><div><p>完成</p><time><?php echo $model->getOrderLogTime(102, $info['order_sn']); ?></time></div></li>
	<?php elseif( $info['order_status_id'] == 104 || $info['order_status_id'] == 108 || $info['order_status_id'] == 110):?>
	<li class="pass"><div><p>已付款</p><time><?php if(!empty($info['pay_time']))echo date('Y-m-d H:i:s',$info['pay_time']);?></time></div><b><i></i></b></li>
	<li class="<?php if(in_array($info['order_status_id'], array(110,108)))echo 'pass';else echo 'current'?>"><div><p>申请取消订单</p><time><?php echo $model->getOrderLogTime(103, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="<?php if($info['order_status_id'] == 110)echo 'pass';else if($info['order_status_id'] == 108) echo 'current'?>"><div><p>等待退款</p><time><?php echo $model->getOrderLogTime(108, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="<?php if($info['order_status_id'] == 110)echo 'current';?>"><div><p>完成</p><time><?php echo $model->getOrderLogTime(110, $info['order_sn']); ?></time></div></li>
	<?php elseif($info['pay_type'] == 2):?>
	<li class="<?php if(in_array($info['order_status_id'], array(107,106,103,109,115)))echo 'pass';else if($info['order_status_id'] == 105) echo 'current';?>"><div><p>进行备货</p><time><?php echo $model->getOrderLogTime(105, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="<?php if($info['order_status_id'] > 105 && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 115) echo 'current';?>"><div><p>备货完成</p><?php echo $model->getOrderLogTime(106, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if(in_array($info['order_status_id'], array(109,107)) && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 106) echo 'current';?>"><div><p>已发货等待收货</p><time><?php echo $model->getOrderLogTime(106, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="<?php if(in_array($info['order_status_id'], array(107)) && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 109) echo 'current';?>"><div><p>客户确认收货并付款</p><time><?php echo $model->getOrderLogTime(109, $info['order_sn']); ?></time></div><b><i></i></b></li>
	<li class="<?php if(in_array($info['order_status_id'], array(107)) && $info['order_status_id'] !=115)echo 'current';?>"><div><p>完成</p><time><?php echo $model->getOrderLogTime(107, $info['order_sn']); ?></time></div></li>
	<?php else:?>
	<li class="<?php if($info['order_status_id'] > 103)echo 'pass';else if($info['order_status_id'] == 103) echo 'current';?>"><div><p>已付款</p><?php echo $model->getOrderLogTime(103, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if($info['order_status_id'] > 105)echo 'pass';else if($info['order_status_id'] == 105) echo 'current';?>"><div><p>备货中</p><?php echo $model->getOrderLogTime(106, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if($info['order_status_id'] > 105 && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 115) echo 'current';?>"><div><p>备货完成</p><?php echo $model->getOrderLogTime(106, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if($info['order_status_id'] > 106  && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 106) echo 'current';?>"><div><p>等待发货</p><?php echo $model->getOrderLogTime(106, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if($info['order_status_id'] > 106 && $info['order_status_id'] !=115)echo 'pass';else if($info['order_status_id'] == 107) echo 'current';?>"><div><p>客户确认收货</p><?php echo $model->getOrderLogTime(107, $info['order_sn']); ?></div><b><i class="t-r"></i></b></li>
	<li class="<?php if($info['order_status_id'] == 107)echo 'current';?>"><div><p>完成</p></div></li>
	<?php endif;?>
</ul>
<h3 class="order-tit">订单信息</h3>
<table class="tab-order-list mb30px">
	<colgroup>
		<col style="width:33%">
		<col style="width:33%">
		<col style="width:auto">
	</colgroup>
	<tbody class="tl">
		<tr>
			<td>订单号：<span class="mc"><?php echo $info['order_sn']; ?></span></td>
			<td>付款状态：<?php echo $info['is_pay'] == '1'? '已支付' : '未支付'; ?></td>
			<td>付款方式：<?php echo $info['pay_type'] == 1 ? '在线支付' : '货到付款';?></td>
		</tr>
		<tr>
			<td>订单状态：<span class="g-c"><?php if($info['pay_type'] == 2 && $info['order_status_id'] == 103)echo "已完成"; elseif($info['pay_type'] == 2 && $info['order_status_id'] == 101)echo '等待备货'; else echo $info['user_title']; ?></span></td>
			<?php if($info['delivery_way'] == 2):?>
			<td colspan="2">自提地址：成都市一环路南二段15号东华电脑城北楼104</td>
			<?php else:?>
			<td>配送方式：市内配送</td>
			<td>快递单号：<span class="mc"><?php echo empty($info['express_no']) ? '暂无' : $info['express_no']."( ".$info['firm_name']." )";?></span></td>
			<?php endif;?>
		</tr>
	</tbody>
</table>
<!-- 订单详情 -->
<h3 class="order-tit">订单详情</h3>
<table class="tab-order-list mb30px tc">
	<colgroup>
		<col style="width:auto">
		<col style="width:11%">
		<col style="width:11%">
		<col style="width:11%">
		<col style="width:11%">
		<col style="width:11%">
	</colgroup>
	<thead>
		<tr class="tit">
			<th colspan="6"><strong>商家：<?php echo CHtml::link($info['store_name'] , $this->createFrontUrl('store/index' , array('mid'=>$info['uid'])), array('target'=>'_blank'))?></strong><b>商家地址：<?php echo $info['store_address'];?></b></th>
		</tr>
		<tr>
			<th class="tl">商品</th>
			<th>规格</th>
			<th>数量</th>
			<th>单价</th>
			<th>运费</th>
			<th>小计</th>
		</tr>
	</thead>
	
	<tbody id="tbodys">
		<?php foreach($info['goods'] as $key => $val): $unitPrice = 0;?>
		<tr>
			<td class="tl" style="text-align:left"><a href="<?php echo $val['goods_type'] ==2 ? '/used/intro?id='.$val['goods_id'] : '/goods?id='.$val['goods_id'];?>" target="_blank" title="<?php echo $val['goods_title'];?>"><img src="<?php echo Views::imgShow($val['goods_cover']); ?>" width="80" height="80"><span><?php echo strlen($val['goods_title'])>78 ? mb_substr($val['goods_title'],0, 26,'utf-8')."..." : $val['goods_title'];?></span></a></td>
			<td>
				<?php 
					$goodsAttr = json_decode($val['goods_attrs'],true);
					foreach ($goodsAttr as $value){
						echo $value[1].' : '.$value[2]."<br>";
					}
				?>
			</td>
			<td><?php echo $val['num'];?></td>
			<td>¥<?php echo $val['unit_price'];?></td>
			<?php if($key == 0):?>
			<td rowspan="<?php echo count($info['goods']);?>">￥<?php echo $info['freight_money'];?></td>
			<?php endif;?>
			<td class="b">¥<?php $unitPrice = (int)$val['num']*$val['unit_price']; echo $unitPrice;?></td>
		</tr>
		<?php endforeach;?>	
	</tbody>

	<tfoot>
		<tr>
			<td colspan="6" class="tab-note">
				<p>用户备注：<?php echo $info['user_remark'];?></p>
				<div>实付金额：<b class="mc">￥<?php echo strpos($info['order_money'],'.')>0 ? $info['order_money'] : $info['order_money'].".00";?></b></div>
				<div style="margin:0 20px">优惠金额：<b>￥<?php echo strpos($info['discount_money'],'.')>0 ? $info['discount_money'] : $info['discount_money'].".00";?></b></div>
				<div>总商品金额：<b>￥<?php echo strpos($info['goods_money'],'.')>0 ? $info['goods_money'] : $info['goods_money'].".00";?></b></div>
			</td>
		</tr>
	</tfoot>
</table>
<!-- 收货人信息 -->
<h3 class="order-tit">收货人信息</h3>
<table class="tab-order-list mb30px">
	<colgroup>
		<col style="width:50%">
		<col style="width:auto">
	</colgroup>
	<tbody>
		<?php $addrArr = json_decode($info['addressee_shoot'],true);?>
		<tr>
			<td>收货人：<?php echo $addrArr['consignee'];?></td>
			<td>联系方式：<?php echo $addrArr['phone'];?></td>
		</tr>
		<tr>
			<td colspan="2">收货地址：<?php echo $info['cons_address'];?></td>
		</tr>
	</tbody>
</table>
<!-- 收货人信息 -->
<h3 class="order-tit">订单日志</h3>
<table class="tab-order-list tab-order-list-1">
	<colgroup>
		<col style="width:30%">
		<col style="width:50%">
		<col style="width:auto">
	</colgroup>
	<thead>
		<tr class="tit tl">
			<th>处理时间</th>
			<th>处理信息</th>
			<th>操作人</th>
		</tr>
	</thead>
	<tbody>
		<?php if($info['logs']){foreach($info['logs'] as $key => $val){?>
		<tr <?php if($key == 0) echo "style='color:red'";?>>
			<td><?php echo date('Y-m-d H:i:s',$val['time']);?></td>
			<td><?php echo $val['logs'];?></td>
			<td><?php echo $val['operate_type'] == '1'?'用户':($val['operate_type'] == '2'?'商家':'系统');?></td>
		</tr>
		<?php }}else{?>
		<tr>
			<td colspan="3" style="color:red" align="center">暂无数据</td>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3"><h5>备注：</h5><span style="margin-left:50px;color:red"><?php echo !empty($info['system_remark']) ? $info['system_remark'] : '暂无数据'; ?></span></td>
		</tr>
	</tfoot>
</table>

