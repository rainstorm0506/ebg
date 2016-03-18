<?php $this->renderPartial('navigation'); ?>
<style type="text/css">
.dicts{line-height:26px}
.dicts input{margin:0 6px 0 20px;vertical-align:baseline}
input.double-price{width:40px;text-align:center;float:none}
h1.title a{font-size:16px;color:#00F;margin:0 0 0 20px}
form select{width:260px}
form li b{font-weight:300;margin:0 0 0 20px;color:#F00}
</style>
<div class="public-wraper" style="margin-top:20px">
	<?php $this->renderPartial('freightPublic' , array('express'=>$express)); ?>
	<h1 class="title" style="margin:10px 0 0 0">
		设置区域运费
		<?php echo CHtml::link('<span>返回</span>',$this->createUrl('express/freightList' , array('id'=>$express['id']))); ?>
	</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 省/直辖市：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->dict_one_id = isset($form->dict_one_id) ? (int)$form->dict_one_id : (isset($freight['dict_one_id'])?(int)$freight['dict_one_id']:0);
					echo $active->dropDownList($form , 'dict_one_id' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'one_id','class'=>'ajax-dict'));
					echo $active->error($form , 'dict_one_id');
					#$form->dict_one_unify = isset($form->dict_one_unify) ? (int)$form->dict_one_unify : (empty($freight['dict_two_id'])?0:1);
					#echo '<label class="dicts">'.$active->checkBox($form , 'dict_one_unify').'全省使用统一运费</label>';
				?>
				<b>如果不选择下级数据 , 则表示 (全省) 使用统一运费</b>
			</li>
			<li>
				<span>市：</span>
				<?php
					$form->dict_two_id = isset($form->dict_two_id) ? (int)$form->dict_two_id : (isset($freight['dict_two_id'])?(int)$freight['dict_two_id']:0);
					echo $active->dropDownList($form , 'dict_two_id' , array(''=>' - 请选择 - ') , array('id'=>'two_id','class'=>'ajax-dict'));
					#$form->dict_two_unify = isset($form->dict_two_unify) ? (int)$form->dict_two_unify : (empty($freight['dict_three_id'])?0:1);
					#echo '<label class="dicts">'.$active->checkBox($form , 'dict_two_unify').'全市使用统一运费</label>';
				?>
				<b>如果不选择下级数据 , 则表示 (全市) 使用统一运费</b>
			</li>
			<li>
				<span>县/区：</span>
				<?php
					$form->dict_three_id = isset($form->dict_three_id) ? (int)$form->dict_three_id : (isset($freight['dict_three_id'])?(int)$freight['dict_three_id']:0);
					echo $active->dropDownList($form , 'dict_three_id' , array(''=>' - 请选择 - ') , array('id'=>'three_id','class'=>'ajax-dict'));
					#$form->dict_three_unify = isset($form->dict_three_unify) ? (int)$form->dict_three_unify : (empty($freight['dict_four_id'])?0:1);
					#echo '<label class="dicts">'.$active->checkBox($form , 'dict_three_unify').'全县/区使用统一运费</label>';
				?>
				<b>如果不选择下级数据 , 则表示 (全县/区) 使用统一运费</b>
			</li>
			<li>
				<span>乡镇/街道：</span>
				<?php
					$form->dict_four_id = isset($form->dict_four_id) ? (int)$form->dict_four_id : (isset($freight['dict_four_id'])?(int)$freight['dict_four_id']:0);
					echo $active->dropDownList($form , 'dict_four_id' , array(''=>' - 请选择 - ') , array('id'=>'four_id'));
				?>
			</li>
			<li>
				<span><em>*</em> 首重：</span>
				<?php
					$form->default_weight = isset($form->default_weight) ? (double)$form->default_weight : (isset($freight['default_weight'])?(double)$freight['default_weight']:0);
					echo $active->textField($form , 'default_weight' , array('class'=>'textbox double-price')) . '<span style="float:none;margin:0 5px">KG /</span>';

					$form->default_price = isset($form->default_price) ? (double)$form->default_price : (isset($freight['default_price'])?(double)$freight['default_price']:0);
					echo $active->textField($form , 'default_price' , array('class'=>'textbox double-price')) . '元';
				?>
			</li>

			<li>
				<span><em>*</em> 间隔重量：</span>
				<?php
					$form->interval_weight = isset($form->interval_weight) ? (double)$form->interval_weight : (isset($freight['interval_weight'])?(double)$freight['interval_weight']:0);
					echo $active->textField($form , 'interval_weight' , array('class'=>'textbox double-price')) . '<span style="float:none;margin:0 5px">KG /</span>';

					$form->interval_price = isset($form->interval_price) ? (double)$form->interval_price : (isset($freight['interval_price'])?(double)$freight['interval_price']:0);
					echo $active->textField($form , 'interval_price' , array('class'=>'textbox double-price')) . '元';
				?>
			</li>
			<li>
				<span><em>*</em> 排序 DESC：</span>
				<?php
					$form->rank = isset($form->rank) ? (int)$form->rank : (isset($freight['rank'])?(int)$freight['rank']:0);
					echo $active->textField($form , 'rank' , array('class'=>'textbox int-price','style'=>'width:125px'));
					echo $active->error($form , 'rank');
				?>
				<div class="hint">注：按照从大到小排列，对于数字一样的排序，则谁先创建则谁在前面。</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo $active->error($form , 'checkFreSet'); ?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</div>

<script>
var dict = {
		'one_id'	: 0 ,
		'two_id'	: 0 ,
		'three_id'	: 0 ,
		'four_id'	: 0
	} , dictOld = {
		'one_id'	: <?php echo $form->dict_one_id; ?> ,
		'two_id'	: <?php echo $form->dict_two_id; ?> ,
		'three_id'	: <?php echo $form->dict_three_id; ?> ,
		'four_id'	: <?php echo $form->dict_four_id; ?>
	};

function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
function selectvaluation(id , json , child_id)
{
	var code = i = '';
	for (i in json)
		code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
	$('#'+id).html('<option value=""> - 请选择 - </option>' + code);
}

$(document).ready(function(){
	//区域选择
	$('select.ajax-dict').change(function(){
		var e = this , id = $(e).attr('id') , val = $(e).val();
		selectReset('four_id');
		switch (id)
		{
			case 'one_id' :
				selectReset('two_id');
				selectReset('three_id');
				dict = {'one_id':val , 'two_id':0 , 'three_id':0};
			break;
			case 'two_id' :
				selectReset('three_id');
				dict = {'one_id':dict.one_id , 'two_id':val , 'three_id':0};
			break;
			case 'three_id' :
				dict = {'one_id':dict.one_id , 'two_id':dict.two_id , 'three_id':val};
			break;
		}

		$.getJSON('<?php echo $this->createUrl('dict/getUnidList'); ?>' , dict , function(json){
			json = jsonFilterError(json);
			$('#four_id').parent('li').show();

			switch (id)
			{
				case 'one_id' :
					if (val)
					{
						selectvaluation('two_id' , json , dictOld.two_id);
						dictOld.two_id > 0 && $('#two_id').change();
					}
				break;
				case 'two_id' :
					if (val)
					{
						selectvaluation('three_id' , json , dictOld.three_id);
						dictOld.three_id > 0 && $('#three_id').change();
					}
				break;
				case 'three_id' :
					if ($.isEmptyObject(json))
					{
						$('#four_id').parent('li').hide();
					}else{
						$('#four_id').parent('li').show();
						val && selectvaluation('four_id' , json , dictOld.four_id);
					}
				break;
			}
		});
	});

	if (dictOld.one_id > 0)
		$('#one_id').change();

	$('input:reset').click(function(){window.location.reload();});
	
	//统一运费设置
	// $('.dicts>:checkbox').click(function(){
	// 	var boxIndex = $(this).index('.dicts>:checkbox');
	// 	$('form select').removeAttr('disabled');

	// 	if ($(this).is(':checked'))
	// 	{
	// 		$('.dicts>:checkbox:gt('+boxIndex+')').removeAttr('checked');
	// 		$('.dicts>:checkbox:lt('+boxIndex+')').removeAttr('checked');
	// 		$('form select:gt('+boxIndex+')').attr('disabled' , true);
	// 	}
	// });
});
</script>