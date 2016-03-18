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
		<tr>
			<td><?php echo $express['firm_name']; ?></td>
			<td><?php echo $express['abbreviation']; ?></td>
			<td class="_tl"><?php echo $express['address']; ?></td>
			<td><?php echo $express['usable']?'可用':'不可用'; ?></td>
			<td><?php echo $express['contacts']; ?></td>
			<td><?php echo $express['tel']; ?></td>
			<td class="_tl"><?php echo $express['website']; ?></td>
			<td><?php echo date('Y-m-d H:i:s' , $express['time']); ?></td>
			<td class="control-group">
			<?php
				echo CHtml::link('<i class="btn-mod"></i><span>添加区域运费</span>',$this->createUrl('express/freightSetting' , array('id'=>$express['id'])));
			?>
			</td>
		</tr>
	</tbody>
</table>