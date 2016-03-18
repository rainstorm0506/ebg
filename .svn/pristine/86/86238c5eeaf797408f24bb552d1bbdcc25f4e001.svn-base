<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>标识</th>
				<th>位置</th>
				<th>状态</th>
				<th>page title</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $k => $v): ?>
			<tr>
				<td><?php echo $k; ?></td>
				<td><?php echo $v; ?></td>
				<td>
				<?php
					$x = GlobalSEO::getSeoInfo($k,0);
					echo empty($x) ? '<span class="seo set-not">(未设置)</span>' : '<span class="seo set-yes">(已设置)</span>';
				?>
				</td>
				<td><?php echo isset($x['seo_title'])?$x['seo_title']:''; ?></td>
				<td class="control-group">
				<?php echo CHtml::link('<span>设置SEO</span>' , $this->createUrl('seo/set' , array('code'=>$k))); ?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="4" class="else">当前没有 公共的seo设置</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
