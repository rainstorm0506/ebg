<?php $this->renderPartial('navigation'); ?>
<style>
	.dh{padding-top: 12px;}
	.section-adds{
		vertical-align:baseline;
		background-color:#eaeaea;
		border:1px solid #ccc;
		border-radius: 5px;
		cursor: pointer;
		display: inline-block;
		font-weight: 400;
		height: 30px;
		line-height: 30px;
		padding: 6px 16px;
	}
	.price-section label {
		clear: both;
		display: block;
		height: 38px;
		line-height: 38px;
		padding: 0 0 15px;
	}
	.price-section label input {  text-align: center;  width: 145px;  }
	.price-section label span {  float: left;  margin: 0 10px 0 5px;  display: inline-block;  }
</style>
<fieldset class="form-list-34 crbox18-group mt30px">
	<h1 class="title">添加 商品分类</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'formWrap'))); ?>
	<ul>
		<li>
			<h6><em>*</em> 所属分类：</h6>
		<?php
			CHtml::$errorContainerTag = 'span';
			$form->one_id = isset($form->one_id) ? (int)$form->one_id : 0;
			echo $active->dropDownList($form , 'one_id' , array(''=>' - 请选择 - ') , array('id'=>'one_id','class'=>'sbox40 ajax-class'));

			$form->two_id = isset($form->two_id) ? (int)$form->two_id : 0;
			echo $active->dropDownList($form , 'two_id' , array(''=>' - 请选择 - ') , array('id'=>'two_id','class'=>'sbox40 mlr10px'));
		?>
			<div class="hint">注：如果不选择分类，则是一级分类。</div>
		</li>

		<li>
			<h6><em>*</em> 分类名称：</h6>
		<?php
			$form->title = isset($form->title) ? $form->title : '';
			echo $active->textField($form , 'title' , array('class'=>'tbox38'));
			echo $active->error($form , 'title');
		?>
		</li>
		<li>
			<h6><em>*</em> 前端显示：</h6>
			<aside class="dh" style="height: 20px;">
			<?php
				$form->is_show = isset($form->is_show) ? (int)$form->is_show : 1;
				echo $active->radioButtonList($form , 'is_show' , array(1=>'启用' , 0=>'未启用') , array('separator' => ''));
				echo $active->error($form , 'is_show');
			?>
			</aside>
		</li>
		<li>
			<h6>价格区间：</h6>
			<aside class="price-section">
				<div class="hint" style="padding:0 0 0 5px">注：如果不设定价格，前端则不会显示。</div>
				<div class="section-label"></div>
				<i class="clear"></i>
				<a class="section-adds" style="display: inline-block;">添加新的区间</a>
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
	var goodsClass = {'one_id' : <?php echo $form->one_id; ?> ,'two_id' : <?php echo $form->two_id; ?>};
	var jsonTree = <?php echo json_encode($tree); ?>;
	var jsonPrice = <?php echo json_encode($form->price); ?>;
	var priceName = {s:'UsedClassForm[price][s][]' , e:'UsedClassForm[price][e][]'};
	function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
	function selectvaluation(id , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
		$('#'+id).html('<option value=""> - 请选择 - </option>' + code);
	};

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

		//区域选择
		$('#one_id').change(function(){
			var oneId = $('#one_id').val();
			if (oneId && !$.isEmptyObject(jsonTree[oneId].child))
				selectvaluation('two_id' , jsonTree[oneId].child , goodsClass.two_id);
		});

		!function(){
			//分类
			if (!$.isEmptyObject(jsonTree))
				selectvaluation('one_id' , jsonTree , goodsClass.one_id);
			if (goodsClass.one_id > 0)
				$('#one_id').change();
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
				$('a.section-adds').click();
			}
		}();
	});
</script>