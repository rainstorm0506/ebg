<?php
	Views::css(array('shopping'));
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>

<?php if ($goods): ?>
<main class="shop-wrap">
	<ul class="shop-process">
		<li class="current first"><b></b><em>1</em><i></i><p>我的购物车</p></li>
		<li><b></b><em>2</em><i></i><p>确认订单信息</p></li>
		<li><b></b><em>3</em><i></i><p>成功提交订单</p></li>
	</ul>
	<header class="shop-tit">
		<h2>我的购物车</h2>
		<aside>
			<span>已选商品（不含运费）：<strong class="cart-select-total">￥0</strong></span>
			<?php echo CHtml::link('结算', $this->createUrl('cart/settle') , array('class'=>'btn-1 btn-1-2 cart-closing')); ?>
		</aside>
	</header>
	<!-- 订单列表 -->
	<table class="shop-tab">
		<colgroup>
			<col style="width:50px">
			<col style="width:80px">
			<col style="width:auto">
			<col style="width:13%">
			<col style="width:13%">
			<col style="width:10%">
			<col style="width:10%">
			<col style="width:10%">
		</colgroup>
		<thead>
			<tr>
				<th><input class="check-all" type="checkbox"></th>
				<th class="tl"><label for="check-all" style="cursor:pointer">全选</label></th>
				<th class="tl"><span>商品</span></th>
				<th class="tl">规格</th>
				<th>数量</th>
				<th>单价(元) </th>
				<th>小计(元) </th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$_html_total_price = array();
			foreach ($goods as $mid => $merVal)
			{
				echo	'<tr class="tit"><td><input type="checkbox" class="mers" mid="'.$mid.'"></td>'.
						'<td class="tl" colspan="7">'.GlobalMerchant::getStoreName($mid).'</td></tr>';
				
				foreach ($merVal as $k => $gv)
				{
					$route = $gv['type'] == 1 ? 'goods/index' : 'used/intro';
					echo	'<tr><td><input type="checkbox" class="monomer" value="'.$k.'" mid="'.$mid.'"></td><td>'.
							'<a target="_blank" href="'.$this->createUrl($route,array('id'=>$gv['id'])).'">'.
							'<img src="'.Views::imgShow($gv['cover']).'" width="80" height="80"></a></td><td class="link">'.
							'<a target="_blank" href="'.$this->createUrl($route,array('id'=>$gv['id'])).'">'.$gv['title'].'</a></td>'.
							'<td class="tl c-1">'.$gv['html_attrs'].'</td><td><span class="calculate"><a>-</a>'.
							'<input class="tbox24" type="text" key="'.$k.'" max="'.$gv['final_stock'].'" value="'.$gv['amount'].'">'.
							'<a>+</a></span></td><td class="c-1">¥'.number_format($gv['final_price'],2).'</td>'.
							'<td class="price">¥'.number_format($gv['final_total'],2).'</td>'.
							'<td class="del">'.CHtml::link('删除', $this->createUrl('cart/delete',array('key'=>$k))).'</td></tr>';
					$_html_total_price[$k] = $gv['final_total'];
				}
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td><input class="check-all" type="checkbox"></td>
				<td colspan="2" class="tl c-1">
					<label for="check-all" style="cursor:pointer">全选</label>
					<?php echo CHtml::link('删除' , $this->createUrl('cart/delete') , array('class'=>'del')); ?>
				</td>
				<td class="total" colspan="5">支付总额：<strong class="cart-select-total">￥0</strong></td>
			</tr>
			<?php
				foreach ($privilege as $val)
					echo "<tr><td class='shop-fav' colspan='8'>{$val['title']} (满{$val['order_min_money']}元，送{$val['privilege_money']}元优惠券)<span>优惠券</span></td></tr>";

				foreach ($reduction as $val)
				{
					if (!empty($val['child']))
					{
						echo "<tr><td class='shop-fav' colspan='8'>{$val['title']} (";
						$_wx = '';
						foreach ($val['child'] as$vc)
						{
							echo $_wx."满{$vc['expire']}元立减{$vc['minus']}元";
							$_wx = '，';
						}
						echo ")<span>满减</span></td></tr>";
					}
				}

				echo "<tr><td class='shop-fav' colspan='8'>满{$freeFreight}元免运费<span>免运费</span></td></tr>";
			?>
		</tfoot>
	</table>
	<nav class="shop-nav">
	<?php
		echo CHtml::link('立即结算<b>&gt;</b>', $this->createUrl('cart/settle') , array('class'=>'btn-4 cart-closing'));
		echo CHtml::link('<b>&lt;</b>继续购物', $this->createUrl('class/index') , array('class'=>'btn-4 btn-4-1'));
	?>
	</nav>
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

$(document).ready(function()
{
	var _last_amout = {} , __amoutOut = null , total_price = <?php echo json_encode($_html_total_price); ?> , select_goods = <?php echo json_encode($select); ?>;
	function change_amount(amount , key , tdObj)
	{
		if (_last_amout[key] && _last_amout[key] == amount)
			return false;

		_last_amout[key] = amount;
		__amoutOut && window.clearTimeout(__amoutOut);
		//0.3s后请求服务器
		__amoutOut = window.setTimeout(function()
		{
			$.ajax({
				'url'		: '<?php echo $this->createUrl('cart/changeAmount'); ?>',
				'data'		: {'amount':amount , 'key':key},
				'dataType'	: 'json',
				'success'	: function(json)
				{
					if (json.code === 0)
					{
						total_price[key] = json.data.final_total;
						select_money();

						tdObj
							.next('td').html('¥' + json.data.final_price)
							.next('td').html('¥' + json.data.final_total);
					}
				},
				'error'		: function(){layer.msg('请求失败!')}
			});
		} , 300);
	}

	function select_money()
	{
		if ($.isEmptyObject(total_price))
			return false;

		var _total_money = 0;
		for (var _x in select_goods)
		{
			if (total_price[_x])
			{
				$('.shop-tab :checkbox[value="'+_x+'"]').attr('checked' , true);
				_total_money += total_price[_x];
			}
		}
		$('.cart-select-total').html('¥' + $.formatFloat(_total_money,2));
	}
	
	function create_form(className , href)
	{
		$('form.'+className).remove();
		var _html = '';
		for (var _p in select_goods)
			_html += '<input type="hidden" name="goods['+_p+']" value="1">';
		if (_html)
		{
			var form = $('<form class="'+className+'" method="post" action="'+href+'">'+_html+'</form>');
			$('body').append(form);
			form.submit();
		}
	}

	$('.shop-tab')
	//增加 & 减少
	.on('click' , '.calculate>a' , function()
	{
		var
			$text = $(this).siblings(':text:eq(0)') ,
			_v = parseInt($text.val()||0 , 10) ,
			_max = parseInt($text.attr('max')||0,10),
			_av = 1;

		if ($(this).index() === 0)
		{
			if (_v > _max && _max > 0)
			{
				$text.val(_av = _max);
			}else if (_v > 1){
				$text.val(_av = (_v - 1));
			}else{
				$text.val(_av = 1);
			}
		}else{
			if (_max == -999)
			{
				$text.val(_av = (_v + 1));
			}else if (_max > 0){
				if (_v < _max)
					$text.val(_av = (_v + 1));
				else
					$text.val(_av = _max);
			}else{
				$text.val(_av = 1);
			}
		}
		change_amount(_av , $text.attr('key') , $(this).closest('td'));
	})
	.on('keyup' , '.calculate>:text' , function()
	{
		var re = /[^\d]*/g , _t = '';
		_t = $(this).val().replace(re , '');
		$(this).val(_t == '' ? 1 : _t);
	})
	.on('change' , '.calculate>:text' , function(){
		var _v = parseInt($(this).val()||0 , 10) , _max = parseInt($(this).attr('max')||0,10);
		if (_max != -999)
		{
			if (_max < 1)
			{
				_v = 1;
			}else if (_v > _max){
				_v = _max;
			}
		}
		if (_v < 1) _v = 1;
		$(this).val(_v);
		change_amount(_v , $(this).attr('key') , $(this).closest('td'));
	})
	//全选
	.on('click' , 'label[for="check-all"]' , function()
	{
		$('.check-all:checkbox:eq(0)').click();
	})
	.on('click' , '.check-all:checkbox' , function()
	{
		if ($(this).is(':checked'))
		{
			for (var _dx in total_price)
				select_goods[_dx] = 1;

			$('.shop-tab :checkbox').prop('checked' , true);
		}else{
			$('.shop-tab :checkbox').prop('checked' , false);
			select_goods = {};
		}
		select_money();
	})
	//商家
	.on('click' , '.mers:checkbox' , function()
	{
		var mid = $(this).attr('mid') , checked = $(this).is(':checked');
		$('.shop-tab .monomer:checkbox[mid="'+mid+'"]').each(function(){
			var key = $(this).val();
			if (checked)
			{
				select_goods[key] = 1;
				$(this).prop('checked' , true);
			}else{
				delete select_goods[key];
				$(this).prop('checked' , false);
			}
		});
		select_money();
	})
	//单选
	.on('click' , '.monomer:checkbox' , function()
	{
		var mid = $(this).attr('mid') , key = $(this).val();
		if ($(this).is(':checked'))
		{
			select_goods[key] = 1;
		}else{
			delete select_goods[key];
		}
		select_money();
	})
	//删除选中的商品
	.on('click' , 'a.del' , function()
	{
		if ($.isEmptyObject(select_goods))
			return false;

		create_form('all-delete' , $(this).attr('href'));
		return false;
	});

	//立即结算
	$('a.cart-closing').click(function()
	{
		if ($.isEmptyObject(select_goods))
		{
			layer.msg('请选择要购买的商品!');
			return false;
		}

		create_form('form-cart-closing' , $(this).attr('href'));
		return false;
	});

	//初始化执行
	select_money();
});
</script>
<?php else: ?>
<main class="shop-wrap">
	<ul class="shop-process">
		<li class="current first"><b></b><em>1</em><i></i><p>我的购物车</p></li>
		<li><b></b><em>2</em><i></i><p>确认订单信息</p></li>
		<li><b></b><em>3</em><i></i><p>成功提交订单</p></li>
	</ul>
	<header class="shop-tit"><h2>我的购物车</h2></header>
	<table class="shop-tab">
		<colgroup>
			<col style="width:50px">
			<col style="width:80px">
			<col style="width:auto">
			<col style="width:13%">
			<col style="width:13%">
			<col style="width:10%">
			<col style="width:10%">
			<col style="width:10%">
		</colgroup>
		<thead>
			<tr>
				<th><input id="checkall" type="checkbox"></th>
				<th class="tl"><label for="checkall">全选</label></th>
				<th class="tl"><span>商品</span></th>
				<th class="tl">规格</th>
				<th>数量</th>
				<th>单价(元) </th>
				<th>小计(元) </th>
				<th>操作</th>
			</tr>
		</thead>
	</table>
	<section class="shop-empty">
		<i></i>
		<article>
			<h5>您的购物车中没有商品，您可以：</h5>
			<p>1、如果您还未登录，可能导致购物车为空，请 <?php echo CHtml::link('登录', $this->createUrl('home/login')); ?></p>
			<p>2、<?php echo CHtml::link('立即选购商品<b>&gt;&gt;</b>', $this->createUrl('class/index')); ?></p>
		</article>
	</section>
</main>
<?php endif; ?>