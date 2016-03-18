<?php $this->renderPartial('navigation'); ?>
<fieldset class="form-list-34 crbox18-group mt30px">
	<h1 class="title">编辑 商品分类</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'formWrap'))); ?>
		<ul>
			<li class="gcShow">
				<h6><em>*</em> 所属分类：</h6>
				<?php
					$rn = '<span>'.GlobalGoodsClass::getClassName($info['root_id']).'</span>';
					$pn = '<span>'.GlobalGoodsClass::getClassName($info['parent_id']).'</span>';
					switch ((int)$info['tier'])
					{
						case 1 : echo '<span>一级分类</span>'; break;
						case 2 : echo $rn; break;
						case 3 : echo $rn.'<i>-</i>'.$pn; break;
					}
				?>
			</li>

			<li>
				<h6><em>*</em> 分类名称：</h6>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->title = isset($form->title) ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('class'=>'tbox38'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<h6><em>*</em> 前端显示：</h6>
				<aside class="dh">
				<?php
					$form->is_show = isset($form->is_show) ? (int)$form->is_show : (isset($info['is_show'])?(int)$info['is_show']:1);
					echo $active->radioButtonList($form , 'is_show' , array(1=>'启用' , 0=>'未启用') , array('separator' => ''));
					echo $active->error($form , 'is_show');
				?>
				</aside>
			</li>
			<li>
				<h6>价格区间：</h6>
				<aside class="price-section">
					<div class="hint" style="padding:0 0 0 5px">注：如果不设定，前端则不会显示。</div>
					<div class="section-label"></div>
					<i class="clear"></i>
					<a class="section-adds">添加新的区间</a>
					<?php echo $active->error($form , 'price'); ?>
				</aside>
			</li>
			<li>
				<h6>&nbsp;</h6>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1','style'=>'margin:0 10px 0 0')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var jsonPrice = <?php echo json_encode($form->price); ?>;
var priceName = {s:'GoodsClassForm[price][s][]' , e:'GoodsClassForm[price][e][]'};
var initPrice = <?php echo json_encode($price); ?>;

$(document).ready(function(){
	$('.price-section')
	//添加新的区间
	.on('click' , 'a.section-adds' , function()
	{
		var _code = '' , lastNode = $('.section-label>label:last>input:eq(1)') , vs = '' , _node = '';
		if (lastNode.length && lastNode.length > 0)
		{
			vs = lastNode.val() ? parseInt(lastNode.val()||0 , 10) + 1 : '';
			_node = '<a>删除</a>';
		}else{
			vs = 1;
		}

		_code = '<label><input class="tbox38 int-price" type="text" name="'+priceName.s+'" value="'+vs+'">' +
				'<span>-</span><input class="tbox38 int-price" type="text" name="'+priceName.e+'">' +
				'<span>元</span>'+_node+'</label>';
		$('.section-label').append(_code);
	})
	//删除
	.on('click' , '.section-label>label>a' , function()
	{
		var
			self = $(this).closest('label'),
			prev = self.prev('label').children('input:eq(1)'),
			next = self.next('label').children('input:eq(0)');

		self.remove();
		if (prev.length && prev.length>0)
		{
			prev = prev.val();
			prev = prev ? parseInt(prev , 10) : '';
			prev && next.val(prev + 1);
		}
	})
	//第一个input
	.on('change' , '.section-label>label>input[name="GoodsClassForm[price][s][]"]' , function()
	{
		var _index = $(this).index('.section-label input') , prev , next;
		if (_index === 0)
		{
			$(this).val(1);
		}else{
			prev = $('.section-label input:eq('+(_index-1)+')');
			next = $('.section-label input:eq('+(_index+1)+')');
			sVal = 0;
			if (prev.length && prev.length>0)
			{
				prev = prev.val();
				prev = prev ? parseInt(prev , 10) : '';
				if (prev)
				{
					sVal = prev + 1;
					$(this).val(sVal);
				}
			}
			if (next.length && next.length>0)
			{
				_n = next.val();
				_n = _n ? parseInt(_n , 10) : '';
				if (_n && _n <= sVal)
					next.change();
			}
		}
	})
	//第二个input
	.on('change' , '.section-label>label>input[name="GoodsClassForm[price][e][]"]' , function()
	{
		var _index = $(this).index('.section-label input') , prev , next;
		prev = $('.section-label input:eq('+(_index-1)+')');
		next = $('.section-label input:eq('+(_index+1)+')');
		sVal = parseInt($(this).val() , 10);
		if (prev.length && prev.length>0)
		{
			prev = prev.val();
			prev = prev ? parseInt(prev , 10) : '';
			if (prev && sVal <= prev)
			{
				sVal = prev + 1;
				$(this).val(sVal);
			}
		}
		if (next.length && next.length>0)
		{
			_n = parseInt(next.val()||0 , 10);
			next.change();
		}
	});

	
	!function(){
		//重置
		$('input:reset').click(function(){window.location.reload();});
		//价格区间
		if (!$.isEmptyObject(jsonPrice) && !$.isEmptyObject(jsonPrice.s))
		{
			var i , _code = '' , _x = false;
			for (i in jsonPrice.s)
			{
				if (!jsonPrice.s[i] && !jsonPrice.e[i])
					continue;

				_code += '<label><input class="tbox38 int-price" type="text" name="'+priceName.s+'" value="'+jsonPrice.s[i]+'">' +
						'<span>-</span><input class="tbox38 int-price" type="text" name="'+priceName.e+'" value="'+jsonPrice.e[i]+'">' +
						'<span>元</span>'+(_x?'<a>删除</a>':'')+'</label>';

				_x = true;
			}
			$('.section-label').append(_code);
		}else{
			if (!$.isEmptyObject(initPrice))
			{
				var i , _code = '' , _x = false;
				for (i in initPrice)
				{
					_code += '<label><input class="tbox38 int-price" type="text" name="'+priceName.s+'" value="'+initPrice[i].price_start+'">' +
								'<span>-</span><input class="tbox38 int-price" type="text" name="'+priceName.e+'" value="'+initPrice[i].price_end+'">' +
								'<span>元</span>'+(_x?'<a>删除</a>':'')+'</label>';
						_x = true;
				}
				$('.section-label').append(_code);
				initPrice = null;
			}else{
				$('a.section-adds').click();
			}
		}
	}();
});
</script>