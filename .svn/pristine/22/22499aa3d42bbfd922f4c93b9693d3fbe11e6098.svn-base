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
    .btn-4{margin-right: 20px;}
</style>
<h2 class="tit-2">二手商品  审核</h2>
<fieldset class="form-list-34 form-list-34-1 crbox18-group">
    <ul>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;"><i>*</i>商品名称：</h6>
            <?php echo $intro['title'];?>
        </li>
	    <li>
		    <h6 style="margin-right: 20px; margin-left: 50px;"><i>*</i>亮点：</h6>
		    <?php echo $intro['lightspot'];?>
	    </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">店铺名称：</h6>
	        <?php echo GlobalMerchant::getStoreName($intro['merchant_id']);?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">所在地：</h6>
	        <?php echo GlobalDict::getAreaName($intro['class_one_id']);?>
	        <?php
	        echo join('-' , array(
		        GlobalDict::getAreaName($intro['dict_one_id']),
		        GlobalDict::getAreaName($intro['dict_two_id'],$intro['dict_one_id']),
		        GlobalDict::getAreaName($intro['dict_three_id'],$intro['dict_one_id'],$intro['dict_two_id'])
	        ));
	        ?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品分类：</h6>
	        <?php
	        echo join('-' , array(
		        GlobalUsedClass::getClassName($intro['class_one_id']),
		        GlobalUsedClass::getClassName($intro['class_two_id']),
		        GlobalUsedClass::getClassName($intro['class_three_id'])
	        ));
	        ?>
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
            <h6 style="margin-right: 20px; margin-left: 50px;">新旧成色：</h6>
	        <?php echo GlobalUsedGoods::getUseTime($intro['use_time']);?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">原买价：</h6>
            <?php echo $intro['buy_price'];?> 元
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">现售价：</h6>
            <?php echo $intro['sale_price'];?> 元
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">库存：</h6>
            <?php echo $intro['stock'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">销量：</h6>
            <?php echo $intro['detail'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">重量：</h6>
            <?php echo $intro['weight'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">最后入库时间：</h6>
            <?php echo date('Y-m-d H:i:s',$intro['last_time']);?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">是否上架：</h6>
            <?php echo $intro['shelf_id']==1001?'上架':'下架';?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品状态：</h6>
            <?php echo $intro['status_id']==1011?'待审核':($intro['status_id']==1013?'审核成功':'审核失败');?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品详情：</h6>
            <?php echo $intro['content'];?>
        </li>
        <li>
            <h6 style="margin-right: 20px; margin-left: 50px;">商品图片：</h6>
            <?php foreach($intro['img'] as $v):?>
                <div style="width:100px; height:150px; float:left; margin-right:10px;">
                    <img style="width:100px; height:150px;" src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $v;?>"/>
                    <?php if($v==$intro['cover']):?>
                        <span style="padding: 0 32px; color:#6ce26c; font-size:18px; position:relative;bottom:28px; left:10px;background: #333;opacity: 0.9;">主图</span>
                    <?php endif;?>
                </div>
            <?php endforeach;?>
        </li>
        <li>
            <h6>&nbsp;</h6>
            <?php
            if($intro['status_id']==1011||$intro['status_id']==1014)
                echo CHtml::link('<i></i><span class="btn-4">审核通过</span>' , $this->createUrl('audi',array('id'=>$intro['id'],'status'=>1)));
            if($intro['status_id']==1011||$intro['status_id']==1013)
                echo CHtml::link('<i></i><span class="btn-4">审核失败</span>' , $this->createUrl('audi',array('id'=>$intro['id'],'status'=>2)));
            if($intro['status_id']==1014||$intro['status_id']==1013)
                echo CHtml::link('<i></i><span class="btn-4">返回</span>' , $this->createUrl('list'));
            ?>
        </li>
    </ul>
</fieldset>
