<?php Yii::app()->getClientScript()->registerCss('member.index',"
#person-order a{color:gray;}#person-order a:hover{color:#1987d4;text-decoration:underline;}
.o_times{margin-left: 0;float:left}
.o_span,.o_strong{margin-left: 45px;float:left}
.tl span{margin-left:45px}
.o_p a{color:#1b59ae}
.o_p a:hover{text-decoration:underline}
");?>
<section class="company-content">
	<div class="box-wrap">
		<!-- 订单标题 -->
		<table class="mer-tab-tit">
			<colgroup>
				<col width="auto">
				<col width="12%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="12%">
			</colgroup>
			<thead>
				<tr>
					<th class="tl">商品</th>
					<th>规格</th>
					<th>单价</th>
					<th>数量</th>
					<th>订单价格</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
		</table>
		<!-- 订单列表 -->
		<table class="mer-tab">
			<colgroup>
				<col width="12%">
				<col width="auto">
				<col width="12%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="12%">
			</colgroup>
			<?php if(isset($orderInfo)):?>
			<thead>
				<tr>
					<th colspan="8"><strong>订单号：<?php echo $orderInfo['order_sn'];?></strong><span>商家：<?php echo $orderInfo['store_name'];?></span><span>订单状态：<em class="mc"><?php echo $orderInfo['user_title'];?></em></span><a class="ico-del" href="javascript:;"></a><time><?php echo date('Y-m-d H:i:s',$orderInfo['create_time']);?></time></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($orderInfo['goods'])):foreach ($orderInfo['goods'] as $k => $v):?>
				<tr>
					<td><a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>" target="_blank" title="<?php echo $v['goods_title']; ?>"><img src="<?php echo Views::imgShow($v['goods_cover']); ?>" width="80" height="80"></a></td>
					<td class="tl"><a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>"><?php echo strlen($v['goods_title'])>40 ? mb_substr($v['goods_title'],0, 16,'utf-8')."..." : $v['goods_title'];?></a></td>
					<td class="gray">颜色：土豪金<br>尺寸：13寸</td>
					<td class="gray">￥<?php echo $v['unit_price'];?></td>
					<td class="gray"><?php echo $v['num'];?></td>
					<?php if($k == 0){ ?>
					<td rowspan="<?php echo count($orderInfo['goods'])?>" class="mc lbor"><b>￥<?php echo $orderInfo['order_money']?></b></td>
					<td rowspan="<?php echo count($orderInfo['goods'])?>" class="lbor gray"><?php echo $orderInfo['user_title']?></td>
					<?php }?>
					<td class="lbor gray">
						<?php if(empty($v['is_evaluate'])):?>
						<?php echo CHtml::link('立即评价' , $this->createUrl('comment/CommentPage' , array('oid'=>$orderInfo['order_sn'],'gid'=>$v['id'])),array('class' => 'btn-1 btn-1-7 mb5px'))."<br/>";?>
						<?php else:?>
						<span style="text-decoration:underline">已评价</span>
						<?php endif;?>
					</td>		
				</tr>
				<?php endforeach;endif;?>
				
			</tbody>
			<?php endif;?>
		</table>
	</div>
</section>