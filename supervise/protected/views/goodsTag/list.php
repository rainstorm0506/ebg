<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col width="200">
		<thead>
			<tr>
				<th>ID</th>
				<th>名称</th>
				<th>使用状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['name']; ?></td>
				<td><?php echo ($val['display'] == 1) ? '启用' : '停用'; ?></td>
				<td class="control-group">
				<?php
					if($val['display'] == 1)
					{
						echo CHtml::link('<span>停用</span>' , $this->createUrl('goodsTag/updatet' , array('val'=>0 , 'id'=>$val['id'])) , array('class'=>'tag-set'));
					}else{
						echo CHtml::link('<span>启用</span>' , $this->createUrl('goodsTag/updatet' , array('val'=>1 , 'id'=>$val['id'])) , array('class'=>'tag-set'));
					}
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="4" class="else">当前没有 商品标签管理列表</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<script>
$(document).ready(function()
{
	$('a.tag-set').click(function()
	{
		$.ajax({
			'type'		: 'get',
			'dataType'	: 'json',
			'url'		: $(this).attr('href'),
			'error'		: function(){getLayer().msg('ajax请求错误!')},
			'success'	: function(json){
				if (json.code == 0)
				{
					window.location.reload();
				}else{
					getLayer().msg(json.message);
				}
			}
		});
		return false;
	});
});
</script>