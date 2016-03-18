<style>
</style>

<div class="public-wraper" style="margin-top:20px">
<table class="public-table">
		<col width="8%"><col><col><col><col>
		<thead><tr><th>所属分类</th><th>商品标题</th><th>商家</th><th>报价</th></tr></thead>
		<?php foreach ($goodinfo as $key=>$row){?>
		<tr>
			<td><?php echo $row['brand']?$row['brand']:'';?>
			
					<?php 
						if($row['class_one_id']) echo isset($tree[$row['class_one_id']][0]) ? $tree[$row['class_one_id']][0] : '';
						if($row['class_two_id']) echo "--".(isset($tree[$row['class_one_id']]['child'][$row['class_two_id']][0]) ? $tree[$row['class_one_id']]['child'][$row['class_two_id']][0] : '');
						if($row['class_three_id']) echo "--".(isset($tree[$row['class_one_id']]['child'][$row['class_two_id']]['child'][$row['class_three_id']][0]) ? $tree[$row['class_one_id']]['child'][$row['class_two_id']]['child'][$row['class_three_id']][0] : '');
					?>
			</td>
			<td class="_tl"><?php echo $row['title']?$row['title']:'';?></td>
			<td><?php echo $row['mer_name']?$row['mer_name']:'';?></td>
			<td><?php echo $row['price']?$row['price']:'';?></td>
		</tr>
		<?php }?>
	</table>
</div>
<script>
</script>