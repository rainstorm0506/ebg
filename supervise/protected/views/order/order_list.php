<?php Views::css('main.css'); ?>
<?php
	$this->renderPartial('navigation',array('searchPost'=>$searchPost,'button_search' => $button_search)); 
?>
<style>
.detail a{color:#1b59ae}
.o_goods a,.ordersns a:hover{text-decoration:underline;color:red}
</style>
	<!-- title -->
	<section class="order-wraper-1">
		<!-- 搜索 -->
		<!-- 订单详情 -->
		<?php foreach ($order as $keys => $vals){?>
		<table class="tab-list-1 mt30px">
			<colgroup>
				<col style="width:auto">
				<col style="width:20%">
				<col style="width:15%">
				<col style="width:18%">
			</colgroup>
			<?php 
				$val = $vals['info'];
				if($is_search){
			?>
			<tbody>
				<tr class="tab-ctit">
					<td colspan="4" class="tl">
						<span>商家：<?php echo isset( $val['store_name'] ) ? $val['store_name'].($val['is_self'] == 1 ? '(<label style="color:#33cc33">自营</label>)':'(<label style="color:blue">第三方</label>)') : '';?></span>
						<span>订单编号：
							<label style="color:#F00">
							<?php
								echo CHtml::link($val['order_sn'], $this->createUrl('edit', array('order_sn' => $val['order_sn'])),array('target' => ''));
							?>
							</label>
						</span>
						<span>订单金额：<?php echo isset( $val['order_money'] ) ? $val['order_money'] : '';?></span>
						<?php if(isset( $val['parent_order_sn'] )){?>
						<p class='detail' style="float:right">
							<?php
							if($val['parent_order_sn'])echo CHtml::link('查看父级订单拆分详情  <i>&gt;</i>', $this->createUrl('childOrderList', array('class'=>'c_1b59ae','id' => isset($val['id']) ? $val['id'] : '','order_sn' => $val['parent_order_sn'] )),array('target' => ''));
							?>
						</p>
						<?php }?>
					</td>
				</tr>
				<?php foreach ($vals['goods'] as $key => $valc){ ?>
				<tr>
					<td class="o_goods"><a href="<?php echo $valc['goods_type'] ==2 ? '/used/intro?id='.$valc['goods_id'] : '/goods?id='.$valc['goods_id'];?>" target="_blank" title="<?php echo $valc['title'];?>"><img src="<?php echo isset($valc['goods_cover']) ? Views::imgShow($valc['goods_cover']) : '';?>" width="80" height="80"><span><?php echo isset( $valc['title'] ) ? (strlen($valc['title'])>46 ? String::utf8Truncate($valc['title'] , 46 , $etc = '...'): $valc['title']) : '';?></span></a></td>
					<td>
						<p>价格：¥<?php echo isset( $valc['unit_price'] ) ? $valc['unit_price'] : '';?></p>
						<p>数量：<?php echo isset( $valc['num'] ) ? $valc['num'] : '';?></p>
					</td>
					<?php if($key == 0){ ?>
					<td rowspan="<?php echo count(isset( $vals['goods'] ) ? $vals['goods'] : '')?>" style="border:1px solid #ccc;" align='center'>
						<p>支付方式：<?php if(isset( $val['pay_port'] )) echo $val['pay_port'] == 1 ? '支付宝支付' : ($val['pay_port'] == 2 ? '银联网上支付' : ($val['pay_port'] == 3 ? '财付通' : '货到付款'));?></p>
						<p><i class="vh">空</i>收货人：<?php echo isset( $val['cons_name'] ) ? $val['cons_name'] : '';?></p>
						<p><i class="vh">空</i>运费：¥<?php echo isset( $val['freight_money'] ) ? $val['freight_money'] : '';?></p>
					</td>
					<?php } if($key == 0){ ?>
					<td class="tab-control-1 tc" rowspan="<?php echo count(isset( $vals['goods'] ) ? $vals['goods'] : '')?>">
						<?php
							echo CHtml::link('订单详情 ', $this->createUrl('edit', array('order_sn' => $val['order_sn'])),array('target' => ''));
						?>
						<br/><br/><span><label style='color:black'>订单状态：</label><label style='color:red'><?php echo isset( $val['back_title'] ) ? $val['back_title'] : ''; ?></label></span>
					</td>
					<?php }?>
				</tr>
				<?php } ?>
			</tbody>
			<?php }else{?>
			<thead>
				<tr class="tab-atit">
					<th colspan="4">
						<time> <?php echo date('Y-m-d H:i:s', isset( $val['create_time'] ) ? $val['create_time'] : time());?> </time>
						<strong class="ordersns">订单编号：
						<?php
							if(isset( $vals['childrenOrder'] ))
								echo CHtml::link($keys, $this->createUrl('childOrderList', array('order_sn' => $keys)),array('target' => ''));
							else
								echo CHtml::link($keys, $this->createUrl('edit', array('order_sn' => $keys)),array('target' => ''));
						?>
						</strong>
						<span>订单金额：<em class="c_d22238">¥<?php if(isset( $val['order_money'] )) echo strpos('.00',$val['order_money']) == -1 ? $vals['order_money'].'.00' : $val['order_money'];?></em></span>
						
						<?php if(isset( $vals['childrenOrder'] )){?>
						<p class='detail'>
							<?php
								echo CHtml::link('查看拆分详情  <i>&gt;</i>', $this->createUrl('childOrderList', array('class'=>'c_1b59ae','order_sn' => $keys)),array('target' => ''));
							?>
						</p>
						<?php }else{?>
						<span>订单状态：<em class="c_d22238"><?php echo isset( $val['back_title'] ) ? $val['back_title'] : ''; ?></em></span>
						<span>收货人：<em class="c_d22238"><?php echo isset( $val['cons_name'] ) ? $val['cons_name'] : ''; ?></em></span>
						
						<p class='detail'>
							<span>商家：<?php echo isset( $val['store_name'] ) ? $val['store_name'].($val['is_self'] == 1 ? '(<label style="color:#33cc33">自营</label>)':'(<label style="color:blue">第三方</label>)') : '';?></span>
						</p>
						<?php }?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset( $vals['childrenOrder'] )){foreach ($vals['childrenOrder'] as $vals){ ?>
				<tr class="tab-ctit">
					<td colspan="4" class="tl"><span>商家：<?php echo isset( $vals['store_name'] ) ? $vals['store_name'].($val['is_self'] == 1 ? '(<label style="color:#33cc33">自营</label>)':'(<label style="color:blue">第三方</label>)') : '';?></span>
						<span>子订单编号：
						<?php
							echo CHtml::link($vals['order_sn'], $this->createUrl('edit', array('order_sn' => $vals['order_sn'])),array('target' => ''));
						?>
						</span>
					</td>
				</tr>
				<?php foreach ($vals['goods'] as $key => $valc){ ?>
				<tr>
					<td class="o_goods"><a href="<?php echo $valc['goods_type'] ==2 ? '/used/intro?id='.$valc['goods_id'] : '/goods?id='.$valc['goods_id'];?>" target="_blank" title="<?php echo $valc['title'];?>"><img src="<?php echo isset($valc['goods_cover']) ? Views::imgShow($valc['goods_cover']) : '';?>" width="80" height="80"><span><?php echo isset( $valc['title'] ) ? (strlen($valc['title'])>46 ? String::utf8Truncate($valc['title'] , 46 , $etc = '...'): $valc['title']) : '';?></span></a></td>
					<td>
						<p>价格：¥<?php echo isset( $valc['unit_price'] ) ? $valc['unit_price'] : '';?></p>
						<p>数量：<?php echo isset( $valc['num'] ) ? $valc['num'] : '';?></p>
					</td>
					<?php if($key == 0){ ?>
					<td rowspan="<?php echo count( isset($vals['goods']) ? $vals['goods'] : '' )?>" style="border:1px solid #ccc;" align='center'>
						<p>支付方式：<?php echo $vals['pay_type'] == 1 ? '在线支付' : '货到付款';?></p>
						<p><i class="vh">空</i>收货人：<?php echo isset( $vals['cons_name']) ? $vals['cons_name'] : '';?></p>
						<p><i class="vh">空</i>运费：¥<?php echo isset( $vals['freight_money']) ? $vals['freight_money'] : '';?></p>
					</td>
					<?php } if($key == 0){ ?>
					<td class="tab-control-1 tc" rowspan="<?php echo count(isset( $vals['goods'] ) ? $vals['goods'] : '')?>">
						<?php
							echo CHtml::link('订单详情 ', $this->createUrl('edit', array('order_sn' => $vals['order_sn'])),array('target' => ''));
						?>
						<br/><br/><span><label style='color:black'>订单状态：</label><label style='color:red'><?php echo isset( $vals['back_title'] ) ? $vals['back_title'] : ''; ?></label></span>
						
					</td>
					<?php }?>
				</tr>
				<?php } ?>
				<?php }}else{ if(isset($vals['goods']))foreach ($vals['goods'] as $key => $valc){ ?>
				<tr>
					<td class="o_goods"><a href="<?php echo $valc['goods_type'] ==2 ? '/used/intro?id='.$valc['goods_id'] : '/goods?id='.$valc['goods_id'];?>" target="_blank" title="<?php echo $valc['title'];?>"><img src="<?php echo isset($valc['goods_cover']) ? Views::imgShow($valc['goods_cover']) : '';?>" width="80" height="80"><span><?php echo isset( $valc['title'] ) ? (strlen($valc['title'])>46 ? String::utf8Truncate($valc['title'] , 46 , $etc = '...'): $valc['title']) : '';?></span></a></td>
					<td>
						<p>价格：¥<?php echo isset( $valc['unit_price'] ) ? $valc['unit_price'] : '';?></p>
						<p>数量：<?php echo isset( $valc['num'] ) ? $valc['num'] : '';?></p>
					</td>
					<?php if($key == 0){ ?>
					<td rowspan="<?php echo count( isset($vals['goods']) ? $vals['goods'] : '' )?>" style="border:1px solid #ccc;" align='center'>
						<p>支付方式：<?php echo $val['pay_type'] == 1 ? '在线支付' : '货到付款';?></p>
						<p><i class="vh">空</i>收货人：<?php echo isset( $val['cons_name']) ? $val['cons_name'] : '';?></p>
						<p><i class="vh">空</i>运费：¥<?php echo isset( $val['freight_money']) ? $val['freight_money'] : '';?></p>
					</td>
					<?php } if($key == 0){ ?>
					<td class="tab-control-1 tc" rowspan="<?php echo count(isset( $vals['goods'] ) ? $vals['goods'] : '')?>">
						<?php
							echo CHtml::link('订单详情 ', $this->createUrl('edit', array('order_sn' => $val['order_sn'])),array('target' => ''));
						?>
						<br/><br/><span><label style='color:black'>订单状态：</label><label style='color:red'><?php echo isset( $val['back_title'] ) ? $val['back_title'] : ''; ?></label></span>
						
					</td>
					<?php }?>
				</tr>
				<?php }} ?>
			</tbody>
			<?php }?>
		</table>
		<?php }?>
	
	<div class="page">
		<?php
		$pageConfig = Yii::app ()->params ['pages'];
		$this->widget ( 'SuperviseListPager', CMap::mergeArray ( $pageConfig ['CLinkPager'], array (
			'pages' => $page 
		) ) );
		$this->widget ( 'CListPager', CMap::mergeArray ( $pageConfig ['CListPager'], array (
			'pages' => $page 
		) ) );
		?>
	</div>
	</section>