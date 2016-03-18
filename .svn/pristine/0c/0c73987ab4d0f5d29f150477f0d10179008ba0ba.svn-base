<?php $this->renderPartial('navigation',array('navShow'=>true)); ?>
<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col width="200">
		<thead>
			<tr>
                <th>类型</th>
                <th>电脑城名称</th>
                <th>楼层</th>
                <th>店铺编号</th>
                <th>所在地</th>
                <th>排序</th>
				<th width="10%">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
                <td>
                <?php
                    if($val['parent_id']!=0){
                        if(empty($val['title'])){
                            echo '楼层';
                        }else{
                            echo '店铺';
                        }
                    }else{
                        echo '电脑城';
                    }
                ?>
                </td>
                <td><?php echo $val['parent_id']==0?$val['title']:$val['parent']; ?></td>
                <td><?php echo $val['storey'];?></td>
                <td><?php echo $val['parent_id']==0?'':$val['title']; ?></td>
                <td><?php echo $val['one'].'-'.$val['two'].'-'.$val['three'];?></td>
                <td><?php echo $val['rank']; ?></td>
                <td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])));
//                    echo CHtml::link('<i class="btn-mod"></i><span>详情</span>' , $this->createUrl('intro' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('clear' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="6" class="else">没 有 更 多 店 铺</td></tr>
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
var linkDeleteMessage = '你确认删除吗?';
</script>