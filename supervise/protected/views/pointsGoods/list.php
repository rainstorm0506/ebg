<?php $this->renderPartial('navigation',array('navShow'=>true));  ?>
<style>
    #plcz{text-align: center; width:120px; height:40px; margin: 20px 0 0 8px; border: #009f95 solid 1px;}
    .cz{margin:0 4px 0 10px;}
    .tj{position:relative;left:100px;padding:4px 20px 4px 20px; background:#009f95;color:#fff;font-weight:700; font-size: 16px;}
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
	        <col>
            <col width="200">
            <thead>
            <tr>
                <th>商品图片</th>
                <th>商品名称</th>
                <th>商品货号</th>
                <th>兑换积分</th>
                <th>库存</th>
                <th>排序</th>
                <th>是否上架</th>
	            <th>SEO设定</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $val): ?>
                <tr>
                    <td>
                        <img width="50" height="50" src="<?php echo Yii::app()->params['imgDomain']; ?><?php echo $val['cover']?>"/>
                    </td>
                    <td><?php echo $val['title']; ?></td>
                    <td><?php echo $val['goods_num']; ?></td>
                    <td><?php echo $val['points']; ?></td>
                    <td>
                    <?php
	                    if($val['stock']==-999){
		                    echo '无限库存';
	                    }else{
		                    echo $val['stock'];
	                    }
                    ?>
                    </td>
                    <td><?php echo $val['rank'];?></td>
                    <td>
						<a>
							<span class="she" value="<?php echo $val['shelf_id'];?>" id="<?php echo $val['id'];?>" title="点击操作上下架">
								<?php echo $val['shelf_id']==1101?'上架':($val['shelf_id']==1102?'下架':''); ?>
							</span>
						</a>
                    </td>
	                <td>
	                <?php
		                $sk = $val['seo_title'] ? '<span class="seo set-yes">(已设置)</span>' : '<span class="seo set-not">(未设置)</span>';
		                echo CHtml::link($sk , $this->createUrl('seo' , array('id'=>$val['id'])));
	                ?>
	                </td>
                    <td class="control-group">
                        <?php
	                        echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])));
	                        echo CHtml::link('<i class="btn-mod"></i><span>详情</span>' , $this->createUrl('intro' , array('id'=>$val['id'])));
	                        echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
                        ?>
                    </td>
                </tr>
            <?php endforeach; if (!$list): ?>
                <tr><td colspan="9" class="else">没 有 更 多 兑 换 商 品</td></tr>
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
    var shelf='',
        rank='',
        opt={},
        url='<?php echo $this->createUrl('pointsGoods/handleShelf');?>';
    $('.she').click(function(){
        shelf=$(this).attr('value');
        _id=$(this).attr('id');
        opt['shelf']=shelf;
        opt['id']=_id;
        $.getJSON(url , opt , function(json)
        {
            if(json.data['shelf']==1102){
                $('#'+_id).attr('value',1102);
                $('#'+_id).html('下架');
            }
            if(json.data['shelf']==1101){
                $('#'+_id).attr('value',1101);
                $('#'+_id).html('上架');
            }
        })
    })
</script>