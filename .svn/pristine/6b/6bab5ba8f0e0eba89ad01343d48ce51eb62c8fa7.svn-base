<?php
Views::css('merchant1');
$this->renderPartial('navigation');?>
<section class="pop-wrap pop-mer" id="sec">
    <header><h3>二手商品信息</h3>
        <?php
            echo CHtml::link('' , $this->createUrl('list' ),array('id'=>'close'));
        ?>
    </header>
    <fieldset class="form-list form-list-36 add-goods-form crbox18-group">
        <legend>复制商品</legend>
        <form>
            <ul>
                <li class="mb0px">
	                <h6><i>*</i>商品名称：</h6>
	                <?php echo $intro['title'];?>
                </li>
	            <li>
		            <h6><i>*</i>亮点：</h6>
		            <?php echo $intro['lightspot'];?>
	            </li>
                <li>
	                <h6><i>*</i>商品分类：</h6>
                <?php
	                echo join('-' , array(
		                GlobalUsedClass::getClassName($intro['class_one_id']),
		                GlobalUsedClass::getClassName($intro['class_two_id']),
		                GlobalUsedClass::getClassName($intro['class_three_id'])
	                ));
                ?>
                </li>
                <li>
	                <h6><i>*</i>货号：</h6>
	                <?php echo $intro['goods_num'];?>
                </li>
                <li><h6>商品标签：</h6><?php echo GlobalGoodsTag::getTagName($intro['tag_id']); ?></li>
                <li>
	                <h6><i>*</i>商品品牌：</h6>
	                <?php echo GlobalBrand::getBrandName($intro['brand_id']);?>
                </li>
	            <li>
		            <h6><i>*</i>新旧成色：</h6>
		            <?php echo GlobalUsedGoods::getUseTime($intro['use_time']);?>
	            </li>
                <li>
	                <h6><i>*</i>原买价：</h6>
	                <?php echo $intro['buy_price'];?> 元
                </li>
                <li>
	                <h6><i>*</i>现售价：</h6>
	                <?php echo $intro['sale_price'];?> 元
                </li>
                <li>
	                <h6><i>*</i>库存：</h6>
	                <?php echo $intro['stock'];?>件
                </li>
                <li>
	                <h6><i>*</i>重量：</h6>
	                <?php echo $intro['weight'];?>
                </li>
                <li>
	                <h6><i>*</i>销量：</h6>
	                <?php echo $intro['detail'];?>
                </li>
                <li>
	                <h6><i>*</i>上架状态：</h6>
	                <?php echo $intro['shelf_id']==1001?'上架':($intro['shelf_id']==1002?'下架':'');?>
                </li>
                <li>
                    <h6><i>*</i>商品图片：</h6>
                    <aside class="goods-pic-list">
                        <?php foreach($intro['img'] as $v):?>
                            <figure>
                                <img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $v;?>" width="118" height="118"/>
                                <?php if($v==$intro['cover']):?>
                                    <span style="background: #333;opacity: 0.9;">主图</span>
                                <?php endif;?>
                            </figure>
                        <?php endforeach;?>
                    </aside>
                </li>
                <li class="txt">
                    <h6><i>*</i>商品详情：</h6>
                    <aside>
                        <?php echo $intro['content'];?>
                    </aside>
                </li>
                <li><h6>&nbsp;</h6>
                <?php
                    echo CHtml::link('<i></i><span class="btn-1 btn-1-3">返回</span>' , $this->createUrl('list' ));
                ?>
                </li>

            </ul>
        </form>
    </fieldset>
</section>
<div class="mask" id="maskbox"></div>



