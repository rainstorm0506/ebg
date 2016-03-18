<?php $this->renderPartial('navigation',array('form'=>$form));  ?>
<style>
	#plcz{text-align: center; width:120px; height:40px; margin: 20px 0 0 8px; border: #009f95 solid 1px;}
	.cz{margin:0 4px 0 10px;}
	.tj{position:relative;left:100px;padding:4px 20px 4px 20px; background:#009f95;color:#fff;font-weight:700; font-size: 16px; border-radius: 4px;}
</style>
<div class="public-wraper">
	<table class="public-table">
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
				<th>编号</th>
				<th>兑换时间</th>
				<th>兑换商品</th>
				<th>配送方式</th>
				<th>会员信息</th>
                <th>领取状态</th>
                <th>领取时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td>
					<?php echo $val['id']; ?>
				</td>
				<td><?php echo date('Y-m-d H:i:s',$val['time']); ?></td>
				<td><?php echo $val['title']; ?><br/><?php echo $val['describe'];?></td>
				<td><?php echo $val['delivery']==1?'市内配送':($val['delivery']==2?'上门自取':''); ?></td>
                <td><?php echo $val['nickname'].' '.$val['phone'].'<br/>'.$val['address']; ?></td>
                <td><?php echo $val['status']==1?'已领取':($val['status']==2?'未领取':($val['status']==3?'未配送':'')); ?></td>
                <td><?php echo $val['status']==1?date('Y-m-d H:i:s',$val['accept_time']):''; ?></td>
				<td class="control-group">
				<?php
                if($val['status']==2){
                    echo CHtml::link('<i class="btn-mod"></i><span>已领取</span>' , $this->createUrl('handle' , array('id'=>$val['id'],'status'=>1)));
                }elseif($val['status']==3){
                    echo CHtml::link('<i class="btn-mod"></i><span>已配送</span>' , $this->createUrl('handle' , array('id'=>$val['id'],'status'=>2)));
                }else{
                    echo '';
                }
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="9" class="else">没 有 更 多 兑 换 记 录</td></tr>
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