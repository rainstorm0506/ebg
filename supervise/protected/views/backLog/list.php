<?php
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style type="text/css">
/*body{min-width:auto;}*/
</style>
<?php $this->renderPartial('navigation',array('navShow'=>true)); ?>
<div class="public-wraper">
	<table class="public-table">
		<col width="80px">
		<col width="15%">
		<col width="15%">
		<col width="15%">
		<col width="auto">
		<col width="150px">
		<thead>
			<tr>
				<th>管理员id</th>
				<th>部门-管理员</th>
				<th>请求动作</th>
				<th>动作响应ID</th>
				<th>操作时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['gid']; ?></td>
				<td><?php echo $val['gov_name']; ?></td>
				<td><?php echo $val['route']; ?></td>
				<td><?php echo $val['response_id']; ?></td>
				<td><?php echo $val['time']?date('Y-m-d H:i:s',$val['time']):''; ?></td>
				<td>
					<span class="look">查看改变数据</span>
					<div style="display: none; position: absolute; right: 200px;top: 100px; width: 500px; height: auto; background:#fcefa1;">
					<?php
						var_export($this->jsonDnCode($val['change_data']));
					?>
					</div>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="6" class="else">当前没有</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
	<div class="page">
		<?php
		$pageConfig = Yii::app()->params['pages'];
		$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'] , array('pages'=>$page)));
		$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'] , array('pages'=>$page)));
		?>
	</div>
</div>
<script>
	$('.look').click(function()
	{
		var htm=$(this).next('div').html();
		getLayer().alert(htm);
//		$(this).next('div').css('display','block');
	});
//	$('.close').click(function()
//	{
//		$(this).parent('div').css('display','none');
//	});
</script>