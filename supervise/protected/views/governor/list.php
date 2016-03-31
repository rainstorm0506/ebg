<?php $this->renderPartial('navigation' , array('keyword'=>$keyword));?>
<div class="public-wraper">
	<table class="public-table">
		<col width="60px">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="200">
		<thead>
			<tr>
				<th>ID</th>
				<th>编号</th>
				<th>部门</th>
				<th>帐号</th>
				<th>真实姓名</th>
				<th>性别</th>
				<th>最近登录时间</th>
				<th>当前管理员状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['number']; ?></td>
				<td><?php echo $val['branch_name']; ?></td>
				<td><?php echo $val['account']; ?></td>
				<td><?php echo $val['true_name']; ?></td>
				<td><?php switch ($val['sex']){case 1:echo '男士';break;case 2:echo '女士';break;default:echo '保密';}; ?></td>
				<td><?php echo $val['login_time'] ? date('Y-m-d H:i:s' , $val['login_time']) : '未登录'; ?></td>
                                <td>
                                    <?php
                                        if($val['status']==1){
                                              echo "<b style='color:red;font-weight:bold;'>启用状态</b>";
                                          }else{
                                              echo "<b style='color:blue;font-weight:bold;'>禁用状态</b>";
                                          }
                                      ?>
                                </td>
				<td class="control-group">
				<?php
					if ($val['id'] == Yii::app()->getUser()->getId())
					{
						echo '-';
					}else{
                                                if($val['status']==1){
                                                    echo CHtml::link('<span>禁用</span>' , $this->createUrl('governor/handle' , array('id'=>$val['id'],'flag'=>2)));
                                                }else{
                                                    echo CHtml::link('<span>启用</span>' , $this->createUrl('governor/handle' , array('id'=>$val['id'],'flag'=>1)));
                                                }
						echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('governor/modify' , array('id'=>$val['id'])));
						echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('governor/clear' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
					}
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="8" class="else">当前没有 管理员</td></tr>
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
var linkDeleteMessage = '你确认删除此账号吗 ?';
</script>