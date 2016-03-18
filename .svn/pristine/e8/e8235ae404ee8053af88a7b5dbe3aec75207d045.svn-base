<?php
	Views::css(array('shopping'));
	Yii::app()->getClientScript()->registerCss('cart.clos' , '
.shipping-address li.current a.this{background-color:#d00f2b;color:#FFF;display:inline-block;padding:0 7px}
.shipping-address li.current{border:2px solid #d00f2b}
.select-models q{color:#666;margin:0 0 0 12px;line-height:26px;display:none}
');
?>
<main class="shop-wrap">
	<ul class="shop-process">
		<li class="current first"><b></b><em>1</em><i></i><p>我的购物车</p></li>
		<li class="current"><b></b><em>2</em><i></i><p>确认订单信息</p></li>
		<li><b></b><em>3</em><i></i><p>成功提交订单</p></li>
	</ul>
	<?php $active = $this->beginWidget('CActiveForm' , array('id'=>'createOrders')); ?>
	<section class="order-wrap">
		<h3><strong>确认收货地址</strong></h3>
		<div class="shipping-address">
			<?php
				echo $active->hiddenField($form,'userAddressID',array('id'=>'userAddressID'));
				echo $active->hiddenField($form,'changeLock',array('value'=>$changeLock));
			?>
			<ul id="address"><li id="addAddress" class="last"><q>+</q><p>增加新地址</p></li></ul>
			<nav><a>显示全部收货地址</a></nav>
		</div>
		<h3>支付方式</h3>
		<nav class="shop-nav-1 select-models">
			<a val="1" class="current">在线支付<i></i></a>
			<a val="2">货到付款<i></i></a>
			<?php echo $active->hiddenField($form,'payType',array('id'=>'payType' , 'value'=>1)); ?>
		</nav>
		<h3>配送方式</h3>
		<nav class="shop-nav-1 select-models">
			<a val="1" key="give" class="current">市内配送<i></i></a>
			<a val="2" key="oneself">上门自提<i></i></a>
			<q>自提地址：成都市一环路南二段15号东华电脑城北楼104</q>
			<?php echo $active->hiddenField($form,'deliveryWay',array('id'=>'deliveryWay' , 'value'=>1)); ?>
		</nav>
		<h3><strong>确认订单信息</strong><span><?php echo CHtml::link('返回购物车修改',$this->createUrl('cart/index')); ?></span></h3>
		<div class="shop-tab-wrap">
			<table class="shop-tab">
				<colgroup>
					<col style="width:80px">
					<col style="width:auto">
					<col style="width:15%">
					<col style="width:12%">
					<col style="width:12%">
					<col style="width:12%">
				</colgroup>
				<thead>
					<tr>
						<th colspan="2"><span>商品</span></th>
						<th>规格</th>
						<th>单价(元) </th>
						<th>数量</th>
						<th>小计(元) </th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($goods as $mid => $merVal)
					{
						foreach ($merVal as $gv)
						{
							echo	'<tr><td><img src="'.Views::imgShow($gv['cover']).'" width="80" height="80"></td>' .
									'<td class="link">'.$gv['title'].'</td><td class="tl c-1">'.$gv['html_attrs'].'</td>' .
									'<td class="c-1">¥'.number_format($gv['final_price'],2).'</td><td class="c-1">'.$gv['amount'].'</td>' .
									'<td class="price">¥'.number_format($gv['final_total'],2).'</td></tr>';
						}
						echo 	'<tr><td class="add-note" colspan="6"><h6>添加订单备注</h6><div>' .
								$active->textField($form,"remark[{$mid}]",array('class'=>'tbox28 tbox28-1' , 'placeholder'=>'限45个字（定制类商品，请将购买需求在备注中做详细说明）')) .
								'<span>提示：请勿填写有关支付、收货、发票方面的信息</span></div></td></tr><tr class="tit"><td class="tl" colspan="6">'.GlobalMerchant::getStoreName($mid).'</td></tr>';
					}
				?>
				</tbody>
			</table>
		</div>
		<!-- 优惠券 -->
		<ul class="confirm-txt-list">
		<?php
			if ($privilege)
			{
				echo	'<li><h6>优惠券：</h6>'.
						$active->dropDownList($form , 'privilege' , $privilege , array('id'=>'privilege' , 'class'=>'sbox30' , 'style'=>'width:auto')) .
						'</li>';
			}

			if ($reduction)
			{
				echo	'<li><h6>满<i>空</i>减：</h6>'.
						$active->dropDownList($form , 'reduction' , $reduction , array('id'=>'reduction' , 'class'=>'sbox30' , 'style'=>'width:auto')) .
						'</li>';
			}
		?>
		</ul>
		<!-- 配送方式 -->
		<h3>结算信息</h3>
		<ul class="predict-order settlement-info">
			<li><p></p><h6>运费： </h6></li>
			<li><p></p><h6>优惠：</h6></li>
			<li><p class="total"></p><h6>总计：</h6></li>
		</ul>
		<ul class="pre-address settlement-info settlement-info-1"></ul>
	</section>
	<nav class="shop-nav"><?php echo CHtml::link('提交订单', null , array('class'=>'create-order btn-4')); ?></nav>
	<?php $this->endWidget(); ?>
</main>

<script>
jQuery.extend(
{
	formatFloat : function(src, pos)
	{
		var num = parseFloat(src).toFixed(pos);
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num))
			num = "0";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10)
			cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
			num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
		return (((sign)?'':'-') + num + '.' + cents);
	}
});

var addObj = null , globalMoney = {'freight':0 , 'favorable':0 , 'total':<?php echo $totals; ?>} , historyJSON = {};
function getUserAddress()
{
	$.ajax({
		'url'		: '<?php echo $this->createUrl('asyn/userAddress'); ?>',
		'dataType'	: 'html',
		'success'	: function(html)
		{
			$('#addAddress').siblings('li').remove();
			html = $.trim(html);
			if (html)
			{
				$('#addAddress').before(html);
				$('#address>li:eq(0)').click();

				var _current = $('#address>li.current') , _p = _current.children('p') , _code = '';
				$('#userAddressID').val(_current.attr('val'));
				_code = '<li>配送至：'+ _p.eq(1).text() + _p.eq(2).text() +'</li>' +
						'<li>收货人：'+ _p.eq(0).text() +'</li>';
				$('.pre-address').html(_code);
				editPrice();
			}else{
				$('#addAddress').click();
			}
		},
		'error'		: function()
		{
			layer.msg('地址请求失败!');
		}
	});
}

//修改价格
function editPrice()
{
	var _pge = $('#privilege').val()||0 , _ren = $('#reduction').val()||0;
	if (_pge)
	{
		_pge = _pge.split('|');
		_pge = _pge[1] ? parseFloat(_pge[1]) : 0;
	}
	if (_ren)
	{
		_ren = _ren.split('|');
		_ren = _ren[2] ? parseFloat(_ren[2]) : 0;
	}
	globalMoney.favorable = _pge + _ren;

	var _ft = globalMoney.freight , _tol = globalMoney.total;
	if ($('#deliveryWay').val() == 2)
		_ft = 0;

	_tol = _tol + _ft - globalMoney.favorable;
	$('.predict-order>li>p:eq(0)').text('¥ ' + $.formatFloat(_ft , 2));
	$('.predict-order>li>p:eq(1)').text('- ¥ ' + $.formatFloat(globalMoney.favorable,2));
	$('.predict-order>li>p:eq(2)').text('¥ ' + $.formatFloat(_tol,2))
}

$(document).ready(function()
{
	$('.confirm-txt-list').on('change' , 'select' , function(){editPrice()});

	$('.shipping-address')
	//添加
	.on('click' , 'li#addAddress' , function()
	{
		addObj && addObj.remove();

		addObj = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('asyn/address'); ?>"></iframe>');
		$('body').append(addObj);
	})
	//修改
	.on('click' , 'a.js-mod' , function()
	{
		addObj && addObj.remove();
		addObj = $('<iframe class="pop-iframe" src="'+$(this).attr('href')+'"></iframe>');
		$('body').append(addObj);
		return false;
	})
	//设为默认地址
	.on('click' , 'a.set-default' , function()
	{
		var e = this;
		$.ajax({
			'url'		: $(e).attr('href'),
			'dataType'	: 'json',
			'success'	: function(json)
			{
				getUserAddress();
			},
			'error'		: function(){layer.msg('请求失败!')}
		});
		return false;
	})
	//选择
	.on('click' , 'li[class!="last"]' , function()
	{
		if ($(this).hasClass('current'))
			return false;

		$(this).addClass('current').siblings('li.current').removeClass('current');
		$('#userAddressID').val($(this).attr('val'));

		var _p = $(this).children('p') , _code = '' , _dict = $(this).attr('dict');
		_code = '<li>配送至：'+ _p.eq(1).text() + _p.eq(2).text() +'</li>' +
				'<li>收货人：'+ _p.eq(0).text() +'</li>';
		$('.pre-address').html(_code);

		if ($.type(historyJSON[_dict]) == 'number')
		{
			globalMoney.freight = historyJSON[_dict];
			editPrice();
			return false;
		}

		$.ajax({
			'url'		: '<?php echo $this->createUrl('cart/orderFreight'); ?>',
			'data'		: {'dict':_dict , 'changeLock':'<?php echo $changeLock; ?>'},
			'dataType'	: 'json',
			'success'	: function(json)
			{
				if (json.code === 0)
				{
					historyJSON[_dict] = globalMoney.freight = json.data.money;
					editPrice();
				}else if (json.code === -1){
					window.location.href = json.message;
				}else{
					layer.msg(json.message);
				}
			},
			'error'		: function(){layer.msg('请求失败!')}
		});
	})
	//显示&隐藏全部收货地址
	.on('click' , 'nav>a' , function()
	{
		if ($(this).text() == '显示全部收货地址')
		{
			$(this).text('隐藏收货地址');
			$('#address>li').show();
		}else{
			$(this).text('显示全部收货地址');

			var _cur = $('#address>li.current');
			if (_cur.index() > 2)
				$('#address').prepend(_cur);
			$('#address>li[class!="last"]:gt(2)').hide();
		}
	});
	$('.select-models').on('click' , 'a' , function()
	{
		var _val = $(this).attr('val') , _key = $(this).attr('key');
		$(this).addClass('current').siblings('.current').removeClass('current');
		$(this).siblings(':hidden').val(_val);

		if (_key)
		{
			if (_key == 'oneself')
			{
				$(this).siblings('q').show();
			}else{
				$(this).siblings('q').hide();
			}
			editPrice();
		}
	});

	$('.create-order').click(function()
	{
		if (parseInt($('#userAddressID').val()||0 , 10) < 1)
		{
			layer.msg('请选择收货地址');
			return false;
		}
		if (parseInt($('#payType').val()||0 , 10) < 1)
		{
			layer.msg('请选择支付方式');
			return false;
		}
		if (parseInt($('#deliveryWay').val()||0 , 10) < 1)
		{
			layer.msg('请选择配送方式');
			return false;
		}
		$('#createOrders').submit();
		return false;
	});

	getUserAddress();
});
</script>