<?php
$this->renderPartial ( 'navigation' );
?>

<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<col>
		<col width="200">
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>状态id</th>
				<th>类型</th>
				<th>前置状态</th>
				<th>用户-状态名</th>
				<th>商家-状态名</th>
				<th>后台-状态名</th>
				<th>商家提示</th>
				<th>后台提示</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($globalStatus as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['type']; ?></td>
				<td><?php echo $val['pre_status']; ?></td>
				<td><?php echo $val['user_title']; ?></td>
				<td><?php echo $val['merchant_title']; ?></td>
				<td><?php echo $val['back_title']; ?></td>

				<td><?php echo $val['merchant_describe']; ?></td>
				<td><?php echo $val['back_describe']; ?></td>

				<td class="control-group">
				<?php
				echo CHtml::link ( '<i class="btn-mod"></i><span>设置</span>', $this->createUrl ( 'status/edit', array (
						'id' => $val ['id'] 
				) ) );
				?>
				</td>
			</tr>
			<?php endforeach; if (!$globalStatus): ?>
			<tr>
				<td colspan="9" class="else">当前没有状态数据</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
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
