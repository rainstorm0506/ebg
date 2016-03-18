<?php $this->renderPartial('navigation'); ?>
<style type="text/css">
.form-wraper li > span{width:150px}
.attrs ul{border:1px solid #CCC;padding:20px 0 20px 0;margin:0 0 20px 0;width:550px}
.attrs b{font-weight:400;margin:0 0 0 20px;color:#333}
.form-wraper .attrs input{width:150px}
.form-wraper .attrs .int-price{width:80px;text-align:center}
.attrs input.error{color:#F00}
.attrs .errorMessage{padding:0 0 0 100px}
.attrs .heads{border-bottom:1px dotted #CCC;padding:0 0 10px 0}
.attrs .heads input{width:300px}
.attrs a{display:inline-block;border:1px solid #999;border-radius:5px;padding:0 12px;background-color:#EEE;cursor:pointer}
.attrs a:hover{background-color:#999}
.attrs a.dels{border:0 none;background-color:#FFF;color:#00F}
.attrs-delete{margin:0 0 0 90px}
.attrs .txt{color:#F00;margin:0 0 0 10px}
.gcShow span{font-size:14px}
.gcShow{padding:0 0 0 20px}
.gcShow i{margin:0 25px;font-style:normal;color:#F00;font-weight:900;font-size:20px}
#class_two_id{margin:0 20px}
</style>
<fieldset class="public-wraper">
	<h1 class="title">添加/编辑 商品分类属性</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 所属分类：</span>
				<?php
					if ($oneID>0 && $twoID>0 && $threeID>0)
					{
						echo '<div class="gcShow"><span>'.GlobalGoodsClass::getClassName($oneID).'</span><i>-</i>';
						echo '<span>'.GlobalGoodsClass::getClassName($twoID).'</span><i>-</i>';
						echo '<span>'.GlobalGoodsClass::getClassName($threeID).'</span></div>';
						$form->class_one_id = $oneID;
						echo $active->hiddenField($form , 'class_one_id');
						$form->class_two_id = $twoID;
						echo $active->hiddenField($form , 'class_two_id');
						$form->class_three_id = $threeID;
						echo $active->hiddenField($form , 'class_three_id');
					}else{
						CHtml::$errorContainerTag = 'span';
						$form->class_one_id = isset($form->class_one_id) ? (int)$form->class_one_id : $oneID;
						echo $active->dropDownList($form , 'class_one_id' , array(''=>' - 请选择 - ') , array('id'=>'class_one_id','class'=>'sbox40'));
						$form->class_two_id = isset($form->class_two_id) ? (int)$form->class_two_id : $twoID;
						echo $active->dropDownList($form , 'class_two_id' , array(''=>' - 请选择 - ') , array('id'=>'class_two_id','class'=>'sbox40'));
						$form->class_three_id = isset($form->class_three_id) ? (int)$form->class_three_id : $threeID;
						echo $active->dropDownList($form , 'class_three_id' , array(''=>' - 请选择 - ') , array('id'=>'class_three_id','class'=>'sbox40'));
						echo $active->error($form , 'class_error');
						CHtml::$errorContainerTag = 'div';
					}
					$txt = array('一' , '二' , '三');
				?>
			</li>
			<li>
				<span><em>*</em> 设置商品分类属性：</span>
				<div class="attrs">
					<div class="txt">注：同一分类最多允许设置三组属性，如果分类下不需要属性直接删除即可。</div>
					<?php for ($i=0 ; $i < 3 ; $i++): ?>
					<ul>
						<li>
							<span>属性组 (<?php echo isset($txt[$i])?$txt[$i]:''; ?>)：</span>
							<a class="attrs-add">添加属性值</a>
							<a class="attrs-delete">删除属性组数据</a>
						</li>
						<li class="heads">
							<span>属性名称：</span>
							<?php
								$form->title[$i] = isset($form->title[$i]) ? $form->title[$i] : (isset($info[$i]['title'])?$info[$i]['title']:'');
								echo $active->textField($form , "title[{$i}]" , array('class'=>'textbox'));
								echo $active->error($form , "title[{$i}]");

								if (isset($form->val[$i]))
									$form->val[$i] = array_values($form->val[$i]);

								$attrChild = isset($info[$i]['child'])?array_values($info[$i]['child']):array();
								$xg = max(count($attrChild) , 2 , (isset($form->val[$i]) && is_array($form->val[$i]))?count($form->val[$i]):0);
								#$xg = max(count($attrChild) , 2 , (isset($form->val[$i]) && is_array($form->val[$i]))?max(array_keys($form->val[$i]))+1:0);
							?>
						</li>
						<?php for ($x=0 ; $x < $xg ; $x++): ?>
						<li>
							<span>属性值 (<i><?php echo $x+1; ?></i>)：</span>
							<?php
								$form->val[$i][$x] = isset($form->val[$i][$x]) ? $form->val[$i][$x] : (isset($attrChild[$x]['title'])?$attrChild[$x]['title']:'');
								echo $active->textField($form , "val[{$i}][{$x}]" , array('class'=>'textbox'));
								echo '<b>排序：</b>';
								$form->rank[$i][$x] = isset($form->rank[$i][$x]) ? (int)$form->rank[$i][$x] : (isset($attrChild[$x]['rank'])?(int)$attrChild[$x]['rank']:($x+1));
								echo $active->textField($form , "rank[{$i}][{$x}]" , array('class'=>'textbox int-price'));
								echo '<a class="dels">删除</a>';
							?>
						</li>
						<?php endfor; ?>
						<?php echo $active->error($form , "val_error[{$i}]"); ?>
					</ul>
					<i class="clear"></i>
					<?php endfor; ?>
				</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var goodsClass = {
	'class_one_id'		: <?php echo $form->class_one_id; ?> ,
	'class_two_id'		: <?php echo $form->class_two_id; ?>,
	'class_three_id'	: <?php echo $form->class_three_id; ?>
};
var jsonData = <?php echo json_encode($tree); ?>;
function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
function selectvaluation(id , json , child_id)
{
	var code = i = '';
	for (i in json)
		code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
	$('#'+id).html('<option value=""> - 请选择 - </option>' + code);
}

$(document).ready(function(){
	//区域选择
	$('#class_one_id').change(function(){
		selectReset('class_two_id');
		selectReset('class_three_id');
		var thisID = $(this).val();
		if (thisID && !$.isEmptyObject(jsonData[thisID].child))
		{
			selectvaluation('class_two_id' , jsonData[thisID].child , goodsClass.class_two_id);
			if (goodsClass.class_two_id > 0)
				$('#class_two_id').change();
		}
	});

	$('#class_two_id').change(function(){
		selectReset('class_three_id');
		var thisID = $(this).val() , oneID = $('#class_one_id').val();
		if (oneID && thisID && !$.isEmptyObject(jsonData[oneID].child[thisID].child))
			selectvaluation('class_three_id' , jsonData[oneID].child[thisID].child , goodsClass.class_three_id);
	});

	if (!$.isEmptyObject(jsonData))
		selectvaluation('class_one_id' , jsonData , goodsClass.class_one_id);

	if (goodsClass.class_one_id > 0)
		$('#class_one_id').change();

	$('.attrs').delegate('a.attrs-add' , 'click' , function(){
		var topUL	= $(this).closest('ul') , index	= $(this).index('a.attrs-add');
		
		var maxRank = (function(){
				var t = 0;
				topUL.find('input.int-price').each(function(i,n){t = Math.max(t , parseInt($(n).val() || 0 , 10))});
				return t + 1;
			})();
		var num = (function(){
				var t = 0;
				topUL.find('span>i').each(function(i,n){t = Math.max(t , parseInt($(n).text() || 0 , 10))});
				return t + 1;
			})();

		topUL.append('<li><span>属性值 (<i>'+num+'</i>)：</span><input type="text" name="GoodsAttrsForm[val]['+index+'][]" class="textbox">' +
			'<b>排序：</b><input type="text" value="'+maxRank+'" name="GoodsAttrsForm[rank]['+index+'][]" class="textbox int-price"><a class="dels">删除</a></li>');
		$(this).blur();
	}).delegate('a.attrs-delete' , 'click' , function(){
		var topUL = $(this).closest('ul');
		topUL.children('li.heads').children('input').val('');
		topUL.children('li.heads').nextAll('li').remove();
		topUL.children('li:eq(0)').children('a.attrs-add').click().click();
		$(this).blur();
	}).delegate('a.dels' , 'click' , function(){
		var topUL = $(this).closest('ul');
		$(this).parent('li').remove();
		if (topUL.children('li.heads').next('li').size() <= 0)
			topUL.children('li:eq(0)').children('a.attrs-add').click();
	});

	$('input:reset').click(function(){window.location.reload();});
});
</script>