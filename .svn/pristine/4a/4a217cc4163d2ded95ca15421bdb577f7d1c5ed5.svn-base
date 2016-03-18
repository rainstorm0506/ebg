<?php
echo '<div class="navigation"><ul>';
echo '<li>' . CHtml::link ( '添加 属性', $this->createUrl ( 'edit' ), Views::linkClass ( 'branch', 'create' ) ) . '</li>';
echo '<li>' . CHtml::link ( '属性 列表', $this->createUrl ( 'list' ), Views::linkClass ( 'branch', 'list' ) ) . '</li>';
echo '</ul><i class="clear"></i></div>';
?>
<div class="search-form">
	<div class="span3">
		<form method="GET" class="form-search pull-right"
			action="<?php echo $this->createUrl('list'); ?>">
			<div class="input-append">
			<?php
				echo CHtml::textField ( 'keyword', $this->getParam ( 'keyword' ), array (
					'id' => 'appendedInputButton',
					'class' => 'span3',
					'placeholder' => 'id或属性名称,可以模糊搜索' 
				)) ?>
			<button class="btn" type="submit">搜索</button>
			</div>
		</form>
	</div>
</div>
<table class="public-table" id="publicTable">
	<thead>
		<tr>
			<th>订单ID</th>
			<th>订单编号</th>
			<th>订单总价</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($goodsComment as $val): ?>
		<tr>
			<td><?php echo $val['id']; ?></td>
			<td>
				<?php
					echo CHtml::link ( $val ['order_sn'], $this->createUrl ( 'commentList', array (
						'id' => $val ['order_sn'] 
					) ), array (
						'target' => '' 
					) );
				?>
			</td>
			<td><?php echo $val['total_price']; ?></td>
			<td class="control-group">
				<?php
					echo CHtml::link ( '<i class="btn-mod"></i><span>评论列表</span>', $this->createUrl ( 'commentList', array (
						'id' => $val ['order_sn'] 
					) ), array (
						'target' => '' 
					) );
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>

