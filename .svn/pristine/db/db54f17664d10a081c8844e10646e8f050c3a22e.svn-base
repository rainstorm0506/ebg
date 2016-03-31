<?php Views::css('main.css'); ?>
<?php
	//$this->renderPartial('navigation'); 
?>
<style>
.detail a{color:red; font-weight:900;}
.o_goods a,.ordersns a:hover{text-decoration:underline;color:red}
</style>
	<table class="public-table" style="margin-top: 50px;">
		<thead>
			<tr>
				<th>时间</th>
				<th>活动类型</th>
				<th>订单编号</th>
				<th>订单金额</th>
				<th>订单状态</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $vo): ?>
			<tr>
				<td><?php echo date('Y-m-d H:i:s', $vo['create_time']); ?></td>
				<td><?php echo $vo['active_type']==1?'秒杀':($val['active_type']==2?'特价':'一折购')?></td>
				<td class="detail">
					<?php
						echo CHtml::link($vo['order_sn'], $this->createUrl('info', array('order_sn' => $vo['order_sn'])),array('target' => ''));
					?>
				</td>
				<td>¥<?php if(isset( $vo['order_money'] )) echo strpos('.00',$vo['order_money']) == -1 ? $vo['order_money'].'.00' : $vo['order_money'];?></td>
				<td><?php echo $model->getStatus($vo['order_status']);?></td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="5" class="else">没有数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>			
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