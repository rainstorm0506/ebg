<?php Yii::app()->getClientScript()->registerCoreScript('layer'); ?>

<nav class="current-stie"><span><?php echo CHtml::link('首页' , $this->createUrl('class/index')); ?></span><i>&gt;</i><span><?php echo CHtml::link('积分商城',$this->createUrl('credits/index')); ?></span><i>&gt;</i><span>积分商城详情</span></nav>
<!-- main -->
<main>
    <section class="goods-info-wrap">
        <div class="pic-dis-wrap">
            <figure><img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $intro['cover'];?>"></figure>
        </div>
        <aside class="goods-info-content">
            <h2><?php echo $intro['title'];?></h2>
            <dl class="dl-1 dl-1-0">
                <dt>所需积分</dt>
                <dd class="p-1"><strong><?php echo $intro['points'];?></strong><b>分（仅<?php echo $intro['person']==1?'个人':' ';echo $intro['merchant']==1?'商家':' ';echo $intro['company']?'企业':' '?>用户）</b></dd>
            </dl>
            <ul id="goodsInfo">
	            <?php if(isset($attr) && is_array($attr)):?>
                <?php foreach($attr as $k=>$v):?>
                <li><h6><?php echo GlobalPoints::getAttrName($k);?></h6>
                    <nav class="choice-list">
                        <?php foreach($v as $k1=>$v1):?>
                        <a href="javascript:;" class="gattr" group="<?php echo $k;?>" value="<?php echo GlobalPoints::getAttrName($k1);?>"><?php echo GlobalPoints::getAttrName($k1);?><i></i></a>
                        <?php endforeach;?>
                    </nav>
                </li>
                <?php endforeach;?>
	            <?php endif;?>
                <li class="tbor tbor-0"><h6>&nbsp;</h6>
                    <?php
                    if($intro['stock']<1 && $intro['stock']!=-999){
	                    echo '<a class="btn-1 btn-1-3">缺货</a>';
                    }else{
	                    if($intro['points']>$user['fraction']){
		                    echo '<a class="btn-1 btn-1-3">积分不足</a>';
	                    }else{
		                    if($user['user_type']==1){
			                    if($intro['person']==1){
				                    echo CHtml::link('立即兑换', '' , array('class'=>'btn-3 btn-1 btn-1-3'));
			                    }else{
				                    echo '<a class="btn-1 btn-1-3">不能兑换此商品</a>';
			                    }
		                    }elseif($user['user_type']==2){
			                    if($intro['company']==1){
				                    echo CHtml::link('立即兑换', '' , array('class'=>'btn-3 btn-1 btn-1-3'));
			                    }else{
				                    echo '<a class="btn-1 btn-1-3">不能兑换此商品</a>';
			                    }
		                    }elseif($user['user_type']==3){
			                    if($intro['merchant']==1){
				                    echo CHtml::link('立即兑换', '' , array('class'=>'btn-3 btn-1 btn-1-3'));
			                    }else{
				                    echo '<a class="btn-1 btn-1-3">不能兑换此商品</a>';
			                    }
		                    }else{
			                    echo '<a class="btn-1 btn-1-3">不能兑换此商品</a>';
		                    }
	                    }
                    }

                    ?>
                </li>
            </ul>
            <footer class="share-collect share-collect-1">
<!--                <span><i class="ico-10"></i>分享</span>-->
				<span><?php $this->widget('ShareWidget', array('title'=>$intro['title'] , 'pic'=>Views::imgShow($intro['cover']) , 'src'=>$this->createAbsoluteUrl('credits/intro' , array('id'=>$intro['id'])))); ?></span>
                <span><i class="ico-11"></i></span>
            </footer>
        </aside>
        <div class="clear"></div>
    </section>
    <!-- 侧边栏 -->
    <section>
        <h3 class="detail-nav">商品详情</h3>
        <article class="editor-goods js-box">
            <?php echo $intro['content'];?>
        </article>
    </section>
</main>
<?php
	Views::js('jquery.scaleImagePlug');
	Views::js('jquery-picChangePlug');
	Views::js('jquery.choiceCurrent');
	Views::js('jquery-calculate');
?>
<script>
    // ================================== 选择当前样式
    var attrsVas	= {},
        data	    = {},
	    num         =<?php echo count($attr);?>,
        goodsAttrs	= <?php echo json_encode($intro['attrs']); ?>;

    function getUnidCount(obj){var x = 0;for (var _i in obj) x++; return x}
    $(document).ready(function() {
    	$('#goodsInfo .choice-list a').choiceCurrent();
        $('.gattr').click(function(){
            var _group=$(this).attr('group'),
                _value=$(this).attr('value');
                    attrsVas[_group]=_value;

        })
        $('.btn-3').click(function(){
	        if(num==0){
		        window.location.href='<?php echo $this->createUrl('credits/goods',array('id'=>$intro['id']));?>';
	        }
	        if(num>0){
		        if($.isEmptyObject(attrsVas) || getUnidCount(attrsVas)<num){
			        layer.msg('请选择完商品属性!');
			        return false;
		        }
	        }



            attrsVas['id'] =<?php echo $intro['id'];?>;
            var url = '<?php echo $this->createUrl('credits/promptly'); ?>';
            $.getJSON(url , attrsVas , function(json)
            {
                if (json.code === 0)
                {
                    if (json.data.src)
                    {
                        window.location.href = json.data.src;
                    }else{
                        layer.msg('未知错误!');
                    }
                }
	            if(json.code === 2)
	            {
		            layer.msg(json.message);
	            }
            })
        })
    })
</script>
