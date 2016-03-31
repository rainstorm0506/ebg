<?php $this->renderPartial('navigation',array('form'=>$form,'activeform'=>$activeform,'start'=>$start,'end'=>$end)); ?>
<?php Yii::app ()->getClientScript ()->registerCoreScript ( 'layer' );?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th>活动编号</th>
				<th>促销类型</th>
				<th>促销名称</th>
				<th>会员类别</th>
				<th>促销时间</th>
				<th>状态</th>
				<th width="250">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['type']; ?></td>
				<td><?php echo $val['title']; ?></td>
				<td><?php echo $val['exp']; ?></td>
				<td><?php foreach($val['date'] as $item)
					{
						echo date('Y.m.d',$item['starttime']).'-'.date('Y.m.d',$item['endtime']).'<br/>';
					}
					?>
				</td>
				<td><?php echo $val['status'];?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>', $this -> createUrl('active/DeleteAct', array('id' => $val['id'])),array('class' => 'link-delete' , 'message'=>'你确认删除此活动吗?'));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>', $this -> createUrl('active/EditAct', array('id' => $val['id'])),array('class' => 'link-delete' , 'message'=>'你确认修改吗?'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="9" class="else">当前没有活动数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="page">
<?php $pageConfig=Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'], array('pages'=>$page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages'=>$page)));
?>
</div>