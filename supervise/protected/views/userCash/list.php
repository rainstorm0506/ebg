<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th>提现单号</th>
				<th>会员类型</th>
				<th>会员等级</th>
				<th>昵称</th>
				<th>手机号</th>
				<th>推荐码</th>
				<th>提现账号</th>
				<th>提现金额</th>
				<th>提现时间</th>
				<th>状态</th>
				<th width="118">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($list)):foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['snum']; ?></td>
				<td><?php echo $this->userType[$val['user_type']] ?></td>
				<td><?php echo GlobalUser::getUserLayerName($val['exp'], $val['user_type']) ?></td>
				<td><?php echo $val['nickname']; ?></td>
				<td><?php echo $val['phone']; ?></td>
				<td><?php echo $val['user_code']?></td>
				<td><?php echo $val['bank'].'-'.$val['subbranch'].'<br/>'.$val['account']; ?></td>
				<td><?php echo '￥'.$val['amount']?></td>
				<td><?php echo date('Y-m-d H:m:s',$val['with_time']) ?></td>
				<td><?php  
					if($val['cur_state']==0){
						echo '<font color="red">'.$this->withState[$val['cur_state']].'</font>'; 
					}else{
						echo '<font color="grey">'.$this->withState[$val['cur_state']].'</font>'; 
					}
				?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-1"></i><span>详情</span>', $this -> createUrl('userCash/logs', array('id' => $val['id'])));
					if($val['cur_state']==0){
						echo CHtml::link('<i class="btn-mod"></i><span>处理</span>', $this -> createUrl('userCash/verify', array('id' => $val['id'])));
					}
				?>
				</td>
			</tr>
			<?php endforeach; else: ?>
			<tr><td colspan="11" class="else">暂无提现数据</td></tr>
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