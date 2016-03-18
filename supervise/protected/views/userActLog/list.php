<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索用户ID、手机号码、昵称" value="<?php echo $keyword; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('userActLog/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('全部列表',$this->createUrl('userActLog/list'),Views::linkClass('userActLog','list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>

<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<thead>
			<tr>
				<th>会员类型</th>
				<th>会员名</th>
				<th>动作行为</th>
				<th>时间</th>
				<th>获得的积分</th>
				<th>获得的成长值</th>
				<th>获得的资金</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo isset($this->userType[$val['user_type']]) ? $this->userType[$val['user_type']] : ''; ?></td>
				<td><?php echo $val['user_name']; ?></td>
				<td><?php echo isset($action[$val['action_val']]) ? $action[$val['action_val']] : ''; ?></td>
				<td><?php echo date('Y-m-d H:i:s' , $val['time']); ?></td>
				<td><?php echo $val['fraction']; ?></td>
				<td><?php echo $val['exp']; ?></td>
				<td><?php echo $val['money']; ?></td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="7" class="else">当前没有日志</td></tr>
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