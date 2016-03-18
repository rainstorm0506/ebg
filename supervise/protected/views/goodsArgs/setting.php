<?php $this->renderPartial('navigation'); ?>
<style type="text/css">
.form-wraper li > span{width:150px}
.attrs ul{border:1px solid #CCC;padding:20px 0 20px 0;margin:0 0 20px 0;width:700px}
.attrs b{font-weight:400;margin:0 0 0 20px;color:#333}
.form-wraper .attrs input{width:180px}
.attrs input.error{color:#F00}
.attrs .errorMessage{padding:0 0 0 100px}
.attrs .heads{border-bottom:1px dotted #CCC;padding:0 0 10px 0}
.attrs .heads input{width:448px}
.attrs a{display:inline-block;border:1px solid #999;border-radius:5px;padding:0 12px;background-color:#EEE;cursor:pointer}
.attrs a:hover{background-color:#999}
.attrs a.dels{border:0 none;background-color:#FFF;color:#00F}
.attrs-delete{margin:0 0 0 254px}
.gcShow span{font-size:14px}
.gcShow{padding:0 0 0 20px}
.gcShow i{margin:0 25px;font-style:normal;color:#F00;font-weight:900;font-size:20px}
#class_two_id{margin:0 20px}
.form-wraper li em.x{margin:0;color:#000}
input.default-val{color:#666}
input.default-val.this{color:#000}
</style>
<fieldset class="public-wraper">
	<h1 class="title">添加/编辑 商品分类参数</h1>
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

					$klg = max(
						(isset($form->title) && is_array($form->title))?count($form->title):0 ,
						(isset($info) && is_array($info))?count($info):0 ,
						1
					);
				?>
			</li>
			<li>
				<span><em>*</em> 设置分类参数：</span>
				<div class="attrs">
					<?php for ($i=0 ; $i < $klg ; $i++): ?>
					<ul px="<?php echo $i; ?>">
						<li>
							<span>参数组 (<em class="x" num="<?php echo $i+1; ?>"></em>)：</span>
							<a class="attrs-add">添加参数</a>
							<a class="attrs-delete">删除参数组数据</a>
						</li>
						<li class="heads">
							<span>参数组名称：</span>
							<?php
								$form->title[$i] = isset($form->title[$i]) ? $form->title[$i] : (isset($info[$i]['title'])?$info[$i]['title']:'');
								echo $active->textField($form , "title[{$i}]" , array('class'=>'textbox'));

								if (isset($form->argsName[$i]))
									$form->argsName[$i] = array_values($form->argsName[$i]);

								$attrChild = isset($info[$i]['child'])?array_values($info[$i]['child']):array();
								$xg = max(count($attrChild) , 1 , (isset($form->argsName[$i]) && is_array($form->argsName[$i]))?count($form->argsName[$i]):0);
							?>
						</li>
						<?php for ($x=0 ; $x < $xg ; $x++): ?>
						<li>
							<span>(<i><?php echo $x+1; ?></i>) 参数名：</span>
							<?php
								$form->argsName[$i][$x] = isset($form->argsName[$i][$x]) ? $form->argsName[$i][$x] : (isset($attrChild[$x]['title'])?$attrChild[$x]['title']:'');
								echo $active->textField($form , "argsName[{$i}][{$x}]" , array('class'=>'textbox'));
								echo '<b>默认值：</b>';
								$form->argsVal[$i][$x] = isset($form->argsVal[$i][$x]) ? $form->argsVal[$i][$x] : (empty($attrChild[$x]['value'])?$defVal:$attrChild[$x]['value']);
								echo $active->textField($form , "argsVal[{$i}][{$x}]" , array('class'=>'textbox default-val'));
								echo '<a class="dels">删除</a>';
							?>
						</li>
						<?php endfor; ?>
					</ul>
					<?php endfor; ?>
					<?php echo $active->error($form , 'last_error' , array('style'=>'padding:0;margin:-20px 0 10px 0')); ?>
					<a class="add-group"> + 添加参数组 + </a>
				</div>
			</li>
			<li style="margin:30px 0 0 0">
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
function numberToUpper(n)
{
	if (!/^(0|[1-9]\d*)(\d+)?$/.test(n))
		return false;

	var unit = "千百十亿千百十万千百十元角分", str = "";
	n += "00";
	var p = n.indexOf('.');
	if (p >= 0)
		n = n.substring(0, p) + n.substr(p+1, 2);
	unit = unit.substr(unit.length - n.length);
	for (var i=0; i < n.length; i++)
		str += '零一二三四五六七八九'.charAt(n.charAt(i)) + unit.charAt(i);
	return str.replace(/零(千|百|十|角)/g, '零')
		.replace(/(零)+/g, '零')
		.replace(/零(万|亿|元)/g, "$1")
		.replace(/(亿)万|一(十)/g, "$1$2")
		.replace(/^元零?|零分/g, "")
		.replace(/元$/g, '');
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

	//转换成中文大写
	$('em.x').each(function(i , n){$(n).text(numberToUpper($(n).attr('num')))});

	$('.attrs').delegate('a.attrs-add' , 'click' , function(){
		var topUL	= $(this).closest('ul') , index	= topUL.attr('px')||0 , num = (function(){
			var t = 0;
			topUL.find('span>i').each(function(i,n){t = Math.max(t , parseInt($(n).text() || 0 , 10))});
			return t + 1;
		})();

		topUL.append('<li><span>(<i>'+num+'</i>) 参数名：</span><input type="text" name="GoodsArgsForm[argsName]['+index+'][]" class="textbox">' +
			'<b>默认值：</b><input type="text" value="<?php echo $defVal; ?>" name="GoodsArgsForm[argsVal]['+index+'][]" class="textbox default-val"><a class="dels">删除</a></li>');
		$(this).blur();
	}).delegate('a.attrs-delete' , 'click' , function(){
		var div = $(this).closest('div.attrs');
		$(this).closest('ul').remove();
		if (div.children('ul').size() <= 0)
			$('a.add-group').click();
	}).delegate('a.dels' , 'click' , function(){
		var topUL = $(this).closest('ul');
		$(this).parent('li').remove();
		if (topUL.children('li.heads').next('li').size() <= 0)
			topUL.children('li:eq(0)').children('a.attrs-add').click();
	}).delegate('a.add-group' , 'click' , function(){
		//+ 添加参数组 + 
		var
			attrs = $(this).closest('div.attrs') ,
			emVal = (function(){
				var t = 0;
				attrs.find('em.x').each(function(i,n){t = Math.max(t , parseInt($(n).attr('num') || 0 , 10))});
				return t;
			})();

		$(this).blur().before('<ul px="'+emVal+'"><li><span>参数组 (<em class="x" num="'+(emVal+1)+'">'+numberToUpper(emVal+1)+'</em>)：</span>' +
			'<a class="attrs-add">添加参数</a><a class="attrs-delete">删除参数组数据</a></li><li class="heads">' +
			'<span>参数组名称：</span><input type="text" name="GoodsArgsForm[title]['+emVal+']" class="textbox"></li>' +
			'<li><span>(<i>1</i>) 参数名：</span>' +
			'<input type="text" value="" name="GoodsArgsForm[argsName]['+emVal+'][]" class="textbox"><b>默认值：</b>' +
			'<input type="text" value="<?php echo $defVal; ?>" name="GoodsArgsForm[argsVal]['+emVal+'][]" class="textbox default-val">' +
			'<a class="dels">删除</a></li></ul>');
	}).delegate('input.default-val' , 'focus' , function(){
		if ($(this).val() == '<?php echo $defVal; ?>')
			$(this).val('').addClass('this');
	}).delegate('input.default-val' , 'blur' , function(){
		if ($(this).val() == '')
			$(this).val('<?php echo $defVal; ?>').removeClass('this');
	});

	$('input:reset').click(function(){window.location.reload();});
});
</script>