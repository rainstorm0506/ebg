<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col width="500">
		<col width="150">
		<thead>
			<tr>
				<th>标识</th>
				<th>名称</th>
				<th>状态</th>
				<th>时间</th>
				<th>描述</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['pay_key']; ?></td>
				<td><?php echo $val['pay_name']; ?></td>
				<td><?php echo $val['status'] ? '开通' : '关闭'; ?></td>
				<td><?php echo date('Y-m-d H:i:s' , $val['time']); ?></td>
				<td><?php echo $val['describe']; ?></td>
				<td class="control-group">
				<?php
					if ($val['status'])
						echo CHtml::link('<span>关闭</span>' , $this->createUrl('paySetting/setting' , array('id'=>$val['id'] , 'code'=>0)));
					else
						echo CHtml::link('<span>开通</span>' , $this->createUrl('paySetting/setting' , array('id'=>$val['id'] , 'code'=>1)));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('paySetting/modify' , array('id'=>$val['id'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="6" class="else">当前没有 支付方式</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
