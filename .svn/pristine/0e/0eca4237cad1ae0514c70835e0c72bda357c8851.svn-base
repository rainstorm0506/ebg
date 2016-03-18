<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper" style="margin-top:20px">
	<?php $this->renderPartial('freightPublic' , array('express'=>$express)); ?>
	<h1 class="title" style="margin:10px 0 0 0">
		区域收费明细
		<?php echo CHtml::link('添加区域运费',$this->createUrl('express/freightSetting' , array('id'=>$express['id'])) , array('style'=>'font-size:16px;color:#00F;margin:0 0 0 20px')); ?>
	</h1>
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="200">
		<thead>
			<tr>
				<th>省</th>
				<th>市</th>
				<th>县</th>
				<th>乡镇</th>
				<th>首重</th>
				<th>首重收费</th>
				<th>间隔重量</th>
				<th>间隔重量的收费</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo GlobalDict::getAreaName($val['dict_one_id']); ?></td>
				<td><?php echo GlobalDict::getAreaName($val['dict_two_id'] , $val['dict_one_id']); ?></td>
				<td><?php echo GlobalDict::getAreaName($val['dict_three_id'] , $val['dict_one_id'] , $val['dict_two_id']); ?></td>
				<td><?php echo GlobalDict::getAreaName($val['dict_four_id'] , $val['dict_one_id'] , $val['dict_two_id'] , $val['dict_three_id']); ?></td>
				<td><?php echo $val['default_weight']; ?>KG</td>
				<td><?php echo $val['default_price']; ?>元</td>
				<td><?php echo $val['interval_weight']; ?>KG</td>
				<td><?php echo $val['interval_price']; ?>元</td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('express/freightSetting' , array('id'=>$express['id'] , 'fid'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('express/freightDelete' , array('id'=>$express['id'] , 'fid'=>$val['id'])) , array('class'=>'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="9" class="else">当前没有设置运费</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script>
var linkDeleteMessage = '你确认删除此地区的收费吗?';
</script>