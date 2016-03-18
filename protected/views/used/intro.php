<nav class="current-stie">
<?php
	Yii::app()->getClientScript()->registerCoreScript('layer');
	Yii::app()->getClientScript()->registerCss('goods-comment' , '.goods-collection{color:#00F}');
	
	echo '<span>'.CHtml::link('首页' , $this->createUrl('class/index')).'</span>';
	echo '<i>&gt;</i><span>'.CHtml::link('二手市场',$this->createUrl('used/index')).'</span>';
	echo '<i>&gt;</i><em>品牌：</em><span>'.GlobalBrand::getBrandName($intro['brand_id'] , 1).'</span>';
?>
</nav>

<main>
	<section class="goods-info-wrap">
		<div class="pic-dis-wrap">
			<figure id="bigImg">
				<img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $intro['cover'];?>"><b></b>
				<?php echo isset($intro['tag_id']) ? GlobalGoodsTag::displayTag($intro['tag_id']) : ''; ?>
			</figure>
			<nav id="changeBtn">
			<?php
				if (!empty($intro['img']))
				{
					foreach ($intro['img'] as $v)
					{
						$_ax = $v['src']==$intro['cover'] ? array('class'=>'current') : array();
						echo CHtml::link('<img src="'.Views::imgShow($v['src']).'" data-src="'.Views::imgShow($v['src']).'">',null,$_ax);
					}
				}
			?>
			</nav>
			<footer class="share-collect">
				<span>
					<i class="ico-11"></i>
					<?php $this->widget('CollectWidget', array('id' => $intro['id'] , 'type'=>3 , 'text'=>'收藏商品' , 'class'=>'goods-collection')); ?>
					(<?php echo $intro['collect'];?>人气)
				</span>
<!--				<span><i class="ico-10"></i>分享</span>-->
				<span><?php $this->widget('ShareWidget', array('title'=>$intro['title'] , 'pic'=>Views::imgShow($intro['cover']) , 'src'=>$this->createAbsoluteUrl('used/intro' , array('id'=>$intro['id'])))); ?></span>
			</footer>
		</div>
		<aside class="goods-info-content second-goods-info">
			<h2>
			<?php
				echo ($intro['is_self'] == 1) ? GlobalGoodsTag::displaySelfGoods($intro['tag_id'] , 1) : '';
				echo '<span>'.$intro['title'].'</span>';
				echo isset($intro['tag_id']) ? GlobalGoodsTag::displayTag($intro['tag_id'] , 2) : '';
			?>
			</h2>
			<h3 style="color: #ff8a00;"><?php echo $intro['lightspot'];?></h3>
			<dl class="dl-1">
				<dt>转&nbsp;卖&nbsp;价</dt>
				<dd class="p-1"><b>￥</b><strong><?php echo $intro['sale_price'];?></strong></dd>
				<dt>原<q>空空</q>价</dt>
				<dd class="p-3"><s>￥<?php echo $intro['buy_price'];?></s></dd>
			</dl>
			<ul id="goodsInfo">
				<li class="mb-1">
					<h6>成色</h6>
					<p>
						<?php echo GlobalUsedGoods::getUseTime($intro['use_time']);?>
					</p>
				</li>
				<li class="mb-1">
					<h6>所在地区</h6>
					<p>
					<?php
						echo join(' - ' , array(
								GlobalDict::getAreaName($intro['dict_one_id']),
								GlobalDict::getAreaName($intro['dict_two_id'],$intro['dict_one_id']),
								GlobalDict::getAreaName($intro['dict_three_id'],$intro['dict_one_id'],$intro['dict_two_id'])
							));
					?>
					</p>
				</li>
				<li class="mb-1"><h6>销<q>空空</q>量</h6><span class="mc mr5px"><?php echo $intro['detail'];?></span><em>件</em></li>
				<li>
					<h6>数<q>空空</q>量</h6>
					<div id="calculate" class="calculate calculate-a"><a>-</a><input class="tbox24" type="text" value="1"><a>+</a></div>
					<span class="num">库存<?php echo $intro['stock'];?>件</span>
					<em class="mc">78免运费</em>
				</li>
				<li class="tbor">
					<h6>&nbsp;</h6>
					<?php
						echo CHtml::link('立即购买',$this->createUrl('cart/promptly',array('gid'=>$intro['id'],'type'=>2)),array('class'=>'btn-5 cart-join'));
						echo CHtml::link('<i></i>加入购物车',$this->createUrl('cart/join',array('gid'=>$intro['id'],'type'=>2)),array('class'=>'btn-1 btn-1-3 cart-join'));
					?>
				</li>
				<li class="last">
					<h6>店<q>空空</q>铺</h6>
					<?php if (!empty($intro['merchant']['store_join_qq'])): ?>
					<a class="ico-1-wrap" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $intro['merchant']['store_join_qq']; ?>&site=qq&menu=yes">
						<i></i><span>联系店主</span>
					</a>
					<?php endif; ?>
					<span class="ico-15-wrap"><i></i><em><?php echo $intro['merchant']['store_name'];?></em></span>
					<span class="ico-14-wrap"><i></i><em>地址：<?php echo $intro['merchant']['store_address'];?></em></span>
				</li>
			</ul>
		</aside>
		<div class="clear"></div>
	</section>
	<section class="clearfix">
		<aside class="aisde-store">
			<h2><?php echo $intro['merchant']['store_name'];?></h2>
			<ul class="store-list">
				<li class="ico-star-wrap-1">
					<h6>评价：</h6>
					<?php
						if(empty($intro['merchant']['store_grade'])){
							for ($_gx = 1; $_gx <=5; $_gx++)
								echo '<i></i>';
						}else{
							for ($_gx = 1; $_gx <=5; $_gx++)
								echo $intro['merchant']['store_grade']>=$_gx ? '<i class="current"></i>':'<i></i>';
						}
					?>
				</li>
				<li><h6>店主：</h6><span><?php echo $intro['merchant']['mer_name'];?></span></li>
				<?php if (!empty($intro['merchant']['store_join_qq'])): ?>
				<li>
					<h6>联系：</h6>
					<a class="ico-1-wrap" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $intro['merchant']['store_join_qq']; ?>&site=qq&menu=yes">
						<i></i><span>联系店主</span>
					</a>
				</li>
				<?php endif; ?>
				<li><h6>保证金：</h6><i class="ico-dep"></i><span class="ico-dep-wrap"><i></i><em><?php echo empty($intro['merchant']['mer_ensure_money'])?'':(int)$intro['merchant']['mer_ensure_money']; ?>元</em></span></li>
				<li><h6>经营年限：</h6><span><?php echo empty($intro['merchant']['bus_start_year'])?'':date('Y')-$intro['merchant']['bus_start_year'];?>年</span></li>
			</ul>
			<nav class="stroe-btn-wrap">
			<?php
				if(isset($intro['merchant']['uid']))
					echo CHtml::link('进入店铺' , $this->createUrl('store/index',array('mid'=>$intro['merchant']['uid'])) , array('class'=>'btn-6'));

				$this->widget('CollectWidget', array('id' => $intro['merchant']['uid'] , 'type'=>2));
			?>
			</nav>
			<?php echo GlobalGoodsClass::getGoodsHtml(); ?>
		</aside>
		<section class="detail-right-wrap">
			<h3 class="detail-nav">商品详情</h3>
			<article class="editor-goods js-box"><?php echo $intro['content'];?></article>
		</section>
	</section>
</main>
<?php Views::js(array('jquery.scaleImagePlug' , 'jquery-calculate')); ?>
<script>
var
	stock		= <?php echo $intro['stock']; ?>,
	userIsLogin	= <?php echo $this->viewsUserID?1:0; ?>,
	imgDomain	= '<?php echo Yii::app()->params['imgDomain']; ?>';

$(document).ready(function(){
	$('#bigImg').scaleImagePlug();
	$('#calculate').calculate({callback:function(e , val){
		if (stock == -999)
			return true;

		if (stock >=0 && val > stock)
		{
			e.val(stock);
			return false;
		}
		return true;
	}});

	$('.goods-info-wrap').on('click' , '#changeBtn>a' , function()
	{
		var $img = $(this).children('img')
		$(this).parent().prev().children('img').attr({'src':$img.attr('src'),'data-src':$img.attr('data-src')});
		$(this).addClass('current').siblings('.current').removeClass('current');
		return false;
	});

	//加入购物车 & 立即购买
	$('.cart-join[href]').click(function()
	{
		if(stock<1){
			layer.msg('商品已售罄!');
			return false;
		}
		var e = $(this);
		//登录弹窗
		if (userIsLogin == 0)
		{
			window.top.userLoginPop && window.top.userLoginPop.remove();
			window.top.userLoginPop = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('asyn/login'); ?>"></iframe>');
			$('body').append(window.top.userLoginPop);
			return false;
		}

		$.ajax({
			'url'		: e.attr('href'),
			'data'		: {'amount':$('#calculate>:text:eq(0)').val()},
			'dataType'	: 'json',
			'error'		: function(){layer.msg('请求失败!')},
			'success'	: function(json)
			{
				if (json.code === 0)
				{
					if (json.data.src)
					{
						window.location.href = json.data.src;
					}else if (json.data.total){
						$('#cartCount').text(json.data.total).show();
						layer.msg('商品已加入购物车!');
					}else{
						layer.msg('未知错误!');
					}
				}else{
					layer.msg(json.message);
				}
			}
		});
		return false;
	});
});
</script>