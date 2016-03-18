<?php
$this->renderPartial('navigation');?>
<style type="text/css">
    .purviews{margin:10px 10px 20px 10px}
    .purviews dd{height:40px;line-height:40px;overflow:hidden; border-bottom:1px dotted #CCC;}
    .purviews dt .errorMessage{margin:10px 0 0 0}
    .purviews label{display:inline-block;width:120px;margin:0}
    .show table.Modify .purviews label input{margin:5px}
    .purviews a{margin:0 10px}
    table.list th{background-color:#FBFBD7; padding:1em;}
    table.list td{padding:0 0.5em; border-bottom:1px dashed #CCC; height:30px;line-height:30px}
    .btn-re{color: #ffffff; border: solid #4c4c4c 1px; border-radius: 10px; background: #ff0000; position: relative;left: 50px;}
</style>
<h2 class="tit-2">积分商城商品详情</h2>
<fieldset class="form-list-34 form-list-34-1 crbox18-group">
    <ul>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;"><i>*</i>商品名称：</h6>
           <?php echo $intro['title'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品分类：</h6>
            <?php echo GlobalGoodsClass::getClassName($intro['class_three_id']);?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品品牌：</h6>
            <?php echo GlobalBrand::getBrandName($intro['brand_id']);?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品货号：</h6>
            <?php echo $intro['goods_num'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">兑换积分：</h6>
            <?php echo $intro['points'];?> 元
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">库存：</h6>
            <?php echo $intro['stock']==-999?'无限库存':$intro['stock'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">销量：</h6>
            <?php echo $intro['sales'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">重量：</h6>
            <?php echo $intro['weight'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">是否上架：</h6>
            <?php echo $intro['shelf_id']==1101?'上架':'下架';?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品属性：</h6>
            <aside class="goods-property">
	            <?php if(!empty($intro['attrs']['attrs']) && is_array($intro['attrs']['attrs'])):?>
                <table class="tab-goods tab-goods-1">
                    <colgroup>
                        <col style="width:20%">
                        <col style="width:20%">
                        <col style="width:20%">
                        <col style="width:20%">
                        <col style="width:auto">
                    </colgroup>
                    <thead>
                    <tr>
                        <?php foreach($intro['attrs']['attrs'] as $k=>$val):?>
                        <th style="background:#fffbe3;"><?php echo GlobalPoints::getAttrName($k)?GlobalPoints::getAttrName($k):'';?></th>
                       <?php endforeach;?>
                        <th style="background:#fffbe3;">重量</th>
                        <th style="background:#fffbe3;">库存</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($attr as $v):?>
                        <tr>
                            <?php echo empty($v['attrs_1_value'])?'':'<td>'.$v['attrs_1_value'].'</td>';?>
                            <?php echo empty($v['attrs_2_value'])?'':'<td>'.$v['attrs_2_value'].'</td>';?>
                            <?php echo empty($v['attrs_3_value'])?'':'<td>'.$v['attrs_3_value'].'</td>';?>
                            <td><?php echo isset($v['weight'])?$v['weight']:'';?> kg</td>
                            <td><?php echo isset($v['stock'])?($v['stock']==-999?'无限库存':$v['stock'].'件'):'';?> </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
	            <?php endif;?>
            </aside>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品内容：</h6>
                <?php echo $intro['content'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品图片：</h6>
            <?php foreach($intro['img'] as $v):?>
                <div style="width:150px; height:150px; float:left; margin-right:10px;">
                <img style="width:150px; height:150px;" src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $v;?>"/>
                <?php if($v==$intro['cover']):?>
                    <span style="padding: 0 57px; color:#6ce26c; font-size:18px; position:relative;bottom:29px; left:10px;background: #333;opacity: 0.9;">主图</span>
                <?php endif;?>
                </div>
            <?php endforeach;?>
        </li>
        <li>
            <h6>&nbsp;</h6>
            <?php
            echo CHtml::link('<i></i><span class="btn-1">返回列表</span>' , $this->createUrl('list' ));
            ?>
        </li>
    </ul>
</fieldset>
