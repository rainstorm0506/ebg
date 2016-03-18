

<table class="table">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>商品图片</th>
        <th>商品名称</th>
        <th>需要积分</th>
        <th>库存</th>
        <th>排序</th>
        <th>上下架</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($result['list'] as $val): ?>
        <tr>
            <td><?php echo $val['id']; ?></td>
            <td><img src="<?php echo $val['image'] ?>" /></td>
            <td>
                <?php
                echo $val['title'];
                ?>
            </td>
            <td><?php echo $val['point']; ?></td>
            <td><?php echo $val['stock']; ?></td>
            <td><?php echo $val['sort_order']; ?></td>
            <td><?php echo $val['is_active'] ? '是' : '否'; ?></td>
            <td class="control-group">
                <?php
                echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>', $this->createUrl('edit', array('id' => $val['id'])));
                echo CHtml::link('<i class="btn-mod"></i><span>删除</span>', $this->createUrl('del', array('id' => $val['id'])));
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>
            <?php
            $this->widget('CLinkPager',array(
                    'header'=>'',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '末页',
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'pages' => $result['pager'],
                    'maxButtonCount'=>10
                )
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>