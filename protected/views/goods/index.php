<nav class="current-stie">
<?php
	Yii::app()->getClientScript()->registerCoreScript('layer');
	Yii::app()->getClientScript()->registerCss('goods-comment' , '
		.js-box .comment-page{margin:0;padding:15px 0;border:1px solid #ddd;border-top:none}
		.goods-collection{color:#00F}');

	$_gjHtml = '';

	echo '<span>'.CHtml::link('首页' , $this->createUrl('class/index')).'</span>';
	if ($goods['is_self'] == 1)
		echo '<i>&gt;</i><span>'.CHtml::link('e办公专区',$this->createUrl('class/self')).'</span>';
	echo '<i>&gt;</i><span>'.CHtml::link(GlobalGoodsClass::getClassName($goods['class_one_id']),$this->createUrl('class/list',array('id'=>$goods['class_one_id']))).'</span>';
	echo '<i>&gt;</i><span>'.CHtml::link(GlobalGoodsClass::getClassName($goods['class_two_id']),$this->createUrl('class/list',array('id'=>$goods['class_two_id']))).'</span>';
	echo '<i>&gt;</i><span>'.CHtml::link(GlobalGoodsClass::getClassName($goods['class_three_id']),$this->createUrl('class/list',array('id'=>$goods['class_three_id']))).'</span>';
	echo '<i>&gt;</i><em>品牌：</em><span>'.GlobalBrand::getBrandName($goods['brand_id'] , 1).'</span>';
?>
</nav>
<main>
	<section class="goods-info-wrap">
		<div class="pic-dis-wrap">
			<figure id="bigImg">
				<img src="<?php echo Views::imgShow($goods['cover']); ?>"><b></b>
				<?php echo isset($goods['tag_id']) ? GlobalGoodsTag::displayTag($goods['tag_id']) : ''; ?>
			</figure>
			<nav id="changeBtn">
			<?php
				if (!empty($pic['main']))
				{
					foreach ($pic['main'] as $src)
						echo CHtml::link('<img src="'.Views::imgShow($src).'" data-src="'.Views::imgShow($src).'">',null);
				}
			?>
			</nav>
			<footer class="share-collect">
				<em>评论（<?php echo $goods['discuss']; ?>条）</em>
				<span>
					<i class="ico-11"></i>
					<?php $this->widget('CollectWidget', array('id' => $goods['id'] , 'type'=>1 , 'text'=>'收藏商品' , 'class'=>'goods-collection')); ?>
					(<q><?php echo $goods['collect']; ?></q>人气)
				</span>
				<span><?php $this->widget('ShareWidget', array('title'=>$goods['title'] , 'pic'=>Views::imgShow($goods['cover']) , 'src'=>$this->createAbsoluteUrl('goods/index' , array('id'=>$goods['id'])))); ?></span>
			</footer>
		</div>
		<aside class="goods-info-content">
			<?php
				echo "<h2>" . (($goods['is_self'] == 1) ? GlobalGoodsTag::displaySelfGoods($goods['tag_id'] , 1) : '') . "<span>{$goods['title']}</span>";
				echo (isset($goods['tag_id']) ? GlobalGoodsTag::displayTag($goods['tag_id'] , true) : '') . '</h2>';
				echo $goods['vice_title'] ? "<h3>{$goods['vice_title']}</h3>" : '';
			?>
			<section class="goods-info-price" id="goodsPriceBox">
				<hgroup>
					<h6 class="j-2">市<q>空</q>场<q>空</q>价</h6>
					<h6>零<q>空</q>售<q>空</q>价</h6>
					<?php echo $this->viewsUserID ? '<h6>您的VIP价</h6>' : ''; ?>
					<h6>最低购买量</h6>
				</hgroup>
				<aside><div class="slide"><div class="wrap"></div></div></aside>
			</section>
			<?php echo $this->viewsUserID ? '' : '<p>（请登录后查看是否享受VIP价格）</p>'; ?>
			<ul id="goodsInfo">
				<?php
					$_idx = 0;
					foreach ($attrs as $ak => $av)
					{
						$_idx++;
						echo '<li><h6>'.$av['title'].'</h6><nav class="choice-list per-attrs">';
						foreach ($av['child'] as $bk => $bv)
							echo '<a index="'.$_idx.'" code="'.$bk.'">'.$bv.'<i></i></a>';
						echo '</nav></li>';
					}
				?>
				<li>
					<h6>数<q>空空</q>量</h6>
					<div id="calculate" class="calculate calculate-a"><a>-</a><input class="tbox24" type="text" value="1"><a>+</a></div>
					<span class="num" id="stock" val="<?php echo $goods['stock']; ?>"><?php echo $goods['stock']==-999 ? '无限库存' : ('库存'.$goods['stock'].'件'); ?></span>
					<em class="mc">78免运费</em>
				</li>
				<li class="tbor">
					<h6>&nbsp;</h6>
					<?php
						echo CHtml::link('立即购买',$this->createUrl('cart/promptly',array('gid'=>$goods['id'],'type'=>1)),array('class'=>'btn-5 cart-join'));
						echo CHtml::link('<i></i>加入购物车',$this->createUrl('cart/join',array('gid'=>$goods['id'],'type'=>1)),array('class'=>'btn-1 btn-1-3 cart-join'));
						if($this->getUserType() == '3'){
							echo CHtml::link('复制',$this->createUrl('merchant/goods/copyExec',array('id'=>$goods['id'])),array('class'=>'btn-5','style'=>'width:80px;margin-left:15px;'));
						}
					?>
				</li>
				<li class="last">
					<h6>店<q>空空</q>铺</h6>
					<?php if ($store['store_join_qq']): ?>
					<a class="ico-1-wrap" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_join_qq']; ?>&site=qq&menu=yes">
						<i></i><span>联系店主</span>
					</a>
					<?php endif; ?>
					<span class="ico-15-wrap"><i></i><em><?php echo $store['store_name']; ?></em></span>
					<span class="ico-14-wrap"><i></i><em>地址：<?php echo $store['store_address']; ?></em></span>
				</li>
			</ul>
		</aside>
		<div class="clear"></div>
	</section>

	<section class="clearfix">
		<aside class="aisde-store">
			<h2><?php echo $store['store_name']; ?></h2>
			<ul class="store-list">
				<li class="ico-star-wrap-1">
					<h6>评价：</h6>
					<?php
						for ($_gx = 1; $_gx <=5; $_gx++)
							echo $store['store_grade']>=$_gx ? '<i class="current"></i>':'<i></i>';
					?>
				</li>
				<li><h6>店主：</h6><span><?php echo $store['mer_name']; ?></span></li>
				<?php if ($store['store_join_qq']): ?>
				<li>
					<h6>联系：</h6>
					<a class="ico-1-wrap" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_join_qq']; ?>&site=qq&menu=yes">
						<i></i><span>联系店主</span>
					</a>
				</li>
				<?php endif; ?>
				<li><h6>保证金：</h6><i class="ico-dep-1"></i><span class="ico-dep-wrap"><i></i><em><?php echo (int)$store['mer_ensure_money']; ?>元</em></span></li>
				<li><h6>经营年限：</h6><span><?php echo ceil(intval(date('Y')) - $store['bus_start_year']); ?>年</span></li>
			</ul>
			<nav class="stroe-btn-wrap">
			<?php
				echo CHtml::link('进入店铺' , $this->createUrl('store/index',array('mid'=>$store['uid'])) , array('class'=>'btn-6'));
				$this->widget('CollectWidget', array('id' => $store['uid'] , 'type'=>2));
			?>
			</nav>
			<?php echo GlobalGoodsClass::getGoodsHtml(); ?>
		</aside>
		<section class="detail-right-wrap" id="detailBox">
			<nav class="detail-nav" id="detailNav">
				<a class="current">商品详情</a>
				<?php echo empty($args['title']) ? '' : '<a>商品参数</a>'; ?>
				<a class="goods-discuss">商品评价<?php echo $goods['discuss']>0 ? ('（<b>'.$goods['discuss'].'条</b>）') : ''; ?></a>
			</nav>
			<article class="js-box editor-goods"><?php echo $goods['content']; ?></article>
			<?php if (!empty($args['title'])): ?>
			<div class="js-box dn">
				<table class="tab-param">
					<colgroup>
						<col style="width:20%">
						<col style="width:auto">
					</colgroup>
					<tbody>
					<?php
						foreach ($args['title'] as $_k => $_v)
						{
							if (empty($args['name'][$_k]) || empty($args['value'][$_k]))
								continue;

							echo '<tr><td class="tit" colspan="2">'.$_v.'</td></tr>';
							foreach ($args['name'][$_k] as $vk => $vv)
								echo '<tr><td>'.$vv.'</td><td>'.$args['value'][$_k][$vk].'</td></tr>';
						}
					?>
					</tbody>
				</table>
				<?php
					if ($goodsJoin)
					{
						$_gjHtml = '<h3 class="goods-tit">最佳组合</h3><ul class="pic-list-1 pic-list-1-2 js-comments">';
						foreach ($goodsJoin as $gs)
						{
							$_gjHtml .=
								'<li><figure><a href="'.$this->createUrl('goods/index' , array('id'=>$gs['id'])).'">' .
								'<img src="'.Views::imgShow($gs['cover']).'" width="184"></a></figure><div class="name">' .
								CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))).'</div><p>' .
								GlobalGoods::getPrice($gs['base_price'],$gs['user_layer_ratio'],$gs['min_price'],$gs['max_price']).'</p></li>';
						}
						$_gjHtml .= '</ul>';
						echo $_gjHtml;
					}
				?>
			</div>
			<?php endif; ?>
			<div class="js-box dn"><div id="goodsComment"></div><?php echo $_gjHtml ?></div>
		</section>
	</section>
</main>
<?php Views::js(array('jquery.scaleImagePlug' , 'jquery-calculate')); ?>
<script>
var
	goodsPic	= <?php echo json_encode($pic); ?>,
	retailPrice	= <?php echo $goods['retail_price']; ?>,
	basePrice	= <?php echo $goods['base_price']; ?>,
	stock		= <?php echo $goods['stock']; ?>,
	goodsAttrs	= <?php echo json_encode($goodsAttrs); ?>,
	attrJdPrice	= <?php echo json_encode($jd_price); ?>,
	JdPrice		= <?php echo $goods['jd_price']; ?>,
	jd_id		= 0,
	attrsIndex	= <?php echo $_idx; ?>,
	attrsVas	= {},
	amountRatio	= <?php echo json_encode($amountRatio); ?>,
	userIsLogin	= <?php echo $this->viewsUserID?1:0; ?>,
	userRatio	= <?php echo $userRatio; ?>,
	imgDomain	= '<?php echo Yii::app()->params['imgDomain']; ?>',
	_page_init	= true,
	_comment	= {'page':1};

function setGoodsPrice()
{
	var _html = '';
	if ($.isEmptyObject(amountRatio) || $.isEmptyObject(amountRatio.s) || $.isEmptyObject(amountRatio.e) || $.isEmptyObject(amountRatio.p))
	{
		_pe = (basePrice * userRatio / 100).toFixed(2);
		if(JdPrice)
		{
			$('.j-2').html('京<q>空</q>东<q>空</q>价');
			_html+= '<div><p><s class="j-1">￥'+JdPrice+'</s></p>';
		}
		else
		{
			_html += '<div><p><s class="j-1">￥'+retailPrice+'</s></p>';
		}

		if (userIsLogin)
			_html += '<p class="p-2"><b>￥</b><strong>'+basePrice+'</strong></p><p class="p-1"><b>￥</b><strong>'+_pe+'</strong></p>';
		else
			_html += '<p class="p-1"><b>￥</b><strong>'+basePrice+'</strong></p>';
		_html += '<p>1件</p></div>';
	}else{
		for (var _x in amountRatio.s)
		{
			_pe = (basePrice * Math.min(userRatio,amountRatio.p[_x]) / 100).toFixed(2);
			if(JdPrice)
			{
				$('.j-2').html('京<q>空</q>东<q>空</q>价');
				_html+= '<div><p><s class="j-1">￥'+JdPrice+'</s></p>';
			}
			else
			{
				_html += '<div><p><s class="j-1">￥'+retailPrice+'</s></p>';
			}
			if (userIsLogin)
				_html += '<p class="p-2"><b>￥</b><strong>'+basePrice+'</strong></p><p class="p-1"><b>￥</b><strong>'+_pe+'</strong></p>';
			else
				_html += '<p class="p-1"><b>￥</b><strong>'+basePrice+'</strong></p>';
			_html += '<p>'+amountRatio.s[_x]+'-'+amountRatio.e[_x]+'件</p></div>';
		}
	}

	$('#goodsPriceBox>aside>.slide>.wrap').html(_html);
}

function getUnidCount(obj){var x = 0;for (var _i in obj) x++; return x}
function goods_comment(page)
{
	if ($.isEmptyObject(_comment[page]))
	{
		$.ajax({
			'url'		: '<?php echo $this->createUrl('goods/comment' , array('gid'=>$goods['id'])); ?>',
			'data'		: {'page':page},
			'dataType'	: 'html',
			'success'	: function(html){_comment[page] = html;$('#goodsComment').html(html)},
			'error'		: function()
			{
				$('#goodsComment').html('没有数据');
			}
		});
	}else{
		$('#goodsComment').html(_comment[page]);
	}
}

$(document).ready(function()
{
	//加入购物车 & 立即购买
	$('.cart-join[href]').click(function()
	{
		var e = $(this);
		//登录弹窗
		if (userIsLogin == 0)
		{
			window.top.userLoginPop && window.top.userLoginPop.remove();
			window.top.userLoginPop = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('asyn/login'); ?>"></iframe>');
			$('body').append(window.top.userLoginPop);
			return false;
		}

		if (attrsIndex > 0 && (getUnidCount(attrsVas) != attrsIndex))
		{
			layer.msg('请选择商品属性!');
			return false;
		}

		$.ajax({
			'url'		: e.attr('href'),
			'data'		: $.extend({'amount':$('#calculate>:text:eq(0)').val()}, attrsVas),
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

	$('.goods-info-wrap')
	.on('click' , '#changeBtn>a' , function()
	{
		var $img = $(this).children('img')
		$(this).parent().prev().children('img').attr({'src':$img.attr('src'),'data-src':$img.attr('data-src')});
		$(this).addClass('current').siblings('.current').removeClass('current');
		return false;
	})
	//属性点击
	.on('click' , '.per-attrs>a' , function()
	{
		var _idx = $(this).attr('index') , _code = $(this).attr('code');
		$(this).addClass('current').siblings('.current').removeClass('current');

		if (_page_init === false)
		{
			var _pic = [] , _x = null , _html = '';
			if (!$.isEmptyObject(goodsPic[_code]))
			{
				_pic = goodsPic[_code];
			}else if (!$.isEmptyObject(goodsPic.main)){
				_pic = goodsPic.main;
			}
			for (_x in _pic)
				_html += '<a><img data-src="'+imgDomain+_pic[_x]+'" src="'+imgDomain+_pic[_x]+'"></a>';
			$('#changeBtn').html(_html).children('a:eq(0)').click();
		}

		//价格显示
		if (attrsIndex > 0 && !$.isEmptyObject(goodsAttrs))
		{
			attrsVas['attrs_'+_idx+'_unite_code'] = _code;

			if (getUnidCount(attrsVas) != attrsIndex)
				return false;

			switch (attrsIndex)
			{
				case 1:
				for (var _xz in goodsAttrs)
				{
					if (!attrsVas.attrs_1_unite_code)
						continue;

					if (goodsAttrs[_xz].attrs_1_unite_code == attrsVas.attrs_1_unite_code)
					{
						basePrice = goodsAttrs[_xz].base_price;
						stock = goodsAttrs[_xz].stock;
						JdPrice = goodsAttrs[_xz].jd_price;
					}
				}
				break;

				case 2:
				for (var _xz in goodsAttrs)
				{
					if (!attrsVas.attrs_1_unite_code || !attrsVas.attrs_2_unite_code)
						continue;
					if (
						goodsAttrs[_xz].attrs_1_unite_code == attrsVas.attrs_1_unite_code &&
						goodsAttrs[_xz].attrs_2_unite_code == attrsVas.attrs_2_unite_code
					){
						basePrice = goodsAttrs[_xz].base_price;
						stock = goodsAttrs[_xz].stock;
						JdPrice = goodsAttrs[_xz].jd_price;
					}
				}
				break;

				case 3:
				for (var _xz in goodsAttrs)
				{
					if (!attrsVas.attrs_1_unite_code || !attrsVas.attrs_2_unite_code || !attrsVas.attrs_3_unite_code)
						continue;
					if (
						goodsAttrs[_xz].attrs_1_unite_code == attrsVas.attrs_1_unite_code &&
						goodsAttrs[_xz].attrs_2_unite_code == attrsVas.attrs_2_unite_code &&
						goodsAttrs[_xz].attrs_3_unite_code == attrsVas.attrs_3_unite_code
					){
						basePrice = goodsAttrs[_xz].base_price;
						stock = goodsAttrs[_xz].stock;
						JdPrice = goodsAttrs[_xz].jd_price;
					}
				}
				break;
			}
		}else{
			//无属性价格
			attrsVas = {};
		}
		$('#stock').attr('val',stock).text(stock==-999?'无限库存':('库存'+stock+'件'));
		setGoodsPrice();
	});
	$('#navList h5,#navList h6').click(function()
	{
		if($(this).parent().hasClass('default'))
			return false;
		if($(this).next().css('display') === 'none'){
			$(this).addClass('current').parent().addClass('current')
		}else{
			$(this).removeClass('current').parent().removeClass('current')
		}
		$(this).next().slideToggle();
	});

	$('#goodsComment').on('click' , '.comment-page>a[href]' , function()
	{
		var href = ($(this).attr('href')||'').split('&page=') , page = 1;
		if (href && href[1] && parseInt(href[1] , 10))
			page = parseInt(href[1] , 10);

		_comment.page = page;
		goods_comment(page);
		return false;
	});

	//切换
	$('#detailNav>a').click(function()
	{
		var _iex = $(this).index() , _dboxChild = $(this).closest('#detailBox').children('.js-box');
		$(this).addClass('current').siblings('.current').removeClass('current');
		_dboxChild.hide();
		_dboxChild.eq(_iex).show();

		if ($(this).hasClass('goods-discuss'))
			goods_comment(_comment.page);
	});

	// ================================== 图片放大镜
	$('#bigImg').scaleImagePlug();
	// ================================== 选择数量
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

	!function()
	{
		//所有的第一属性选中
		$('.per-attrs').each(function(){$(this).children('a:eq(0)').click()});
		_page_init = false;

		setGoodsPrice();
	}();
});
</script>