<?php $this->renderPartial('navigation' , array('keyword'=>$keyword)); ?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="240">
		<thead>
			<tr>
				<th>公司名称</th>
				<th>简称</th>
				<th>地址</th>
				<th>状态</th>
				<th>联系人</th>
				<th>联系电话</th>
				<th>网站</th>
				<th>时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['firm_name']; ?></td>
				<td><?php echo $val['abbreviation']; ?></td>
				<td class="_tl"><?php echo $val['address']; ?></td>
				<td><?php echo $val['usable']?'可用':'不可用'; ?></td>
				<td><?php echo $val['contacts']; ?></td>
				<td><?php echo $val['tel']; ?></td>
				<td class="_tl"><?php echo $val['website']; ?></td>
				<td><?php echo date('Y-m-d H:i:s' , $val['time']); ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<span>运费明细列表</span>' , $this->createUrl('express/freightList' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('express/modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('express/delete' , array('id'=>$val['id'])) , array('class'=>'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="9" class="else">当前没有物流快递公司</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script>
var linkDeleteMessage = '删除物流公司时会删除区域设置的收费信息.<br>你还要删除吗?';
</script>