<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
	Views::css(array('public'));
	Views::jquery();
	Views::js(array('html5'));
	Yii::app()->getClientScript()->registerCss('default' , 'a,input[type="submit"]{cursor:pointer}');
?>
</head>
<body class="bg-null">
<style type="text/css">
.pop-wrap{width:820px;margin:-210px 0 0 -412.5px}
#addressForm select{width:19.5%;margin:0 10px 0 0}
#addressForm .tbox34-1{width:80%}
</style>
	<section class="pop-wrap" id="floatWraper">
		<header><h3><?php echo $adds ? '编辑收货地址' : '新增收货地址'; ?></h3><a id="close"></a></header>
		<fieldset class="form-list form-list-36">
			<?php $active = $this->beginWidget('CActiveForm' , array('id'=>'addressForm')); ?>
			<div class="promt error msg promt-1" id="promt">请输入真实姓名</div>
			<ul>
				<li>
					<h6>收货人姓名：</h6>
					<?php
						$form->consignee = isset($form->consignee) ? $form->consignee : (isset($adds['consignee'])?$adds['consignee']:'');
						echo $active->textField($form,'consignee',array('id'=>'consignee','class'=>'tbox34','placeholder'=>'请输入真实姓名'));
					?>
				</li>
				<li class="mb10px">
					<h6>收货人手机：</h6>
					<?php
						$form->phone = isset($form->phone) ? $form->phone : (isset($adds['phone'])?$adds['phone']:'');
						echo $active->textField($form,'phone',array('id'=>'phone','class'=>'tbox34','placeholder'=>'请输入收货人手机号'));
					?>
				</li>
				<li>
					<h6>收货人地区：</h6>
					<?php
						$form->dictOneId = isset($form->dictOneId) ? (int)$form->dictOneId : (isset($adds['dict_one_id'])?(int)$adds['dict_one_id']:0);
						$form->dictTwoId = isset($form->dictTwoId) ? (int)$form->dictTwoId : (isset($adds['dict_two_id'])?(int)$adds['dict_two_id']:0);
						$form->dictThreeId = isset($form->dictThreeId) ? (int)$form->dictThreeId : (isset($adds['dict_three_id'])?(int)$adds['dict_three_id']:0);
						$form->dictFourId = isset($form->dictFourId) ? (int)$form->dictFourId : (isset($adds['dict_four_id'])?(int)$adds['dict_four_id']:0);

						echo $active->dropDownList($form , 'dictOneId' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'dictOneId','class'=>'sbox36 ajax-dict'));
						echo $active->dropDownList($form , 'dictTwoId' , array(''=>' - 请选择 - ') , array('id'=>'dictTwoId','class'=>'sbox36 ajax-dict'));
						echo $active->dropDownList($form , 'dictThreeId' , array(''=>' - 请选择 - ') , array('id'=>'dictThreeId' , 'class'=>'sbox36 ajax-dict'));
						echo $active->dropDownList($form , 'dictFourId' , array(''=>' - 请选择 - ') , array('id'=>'dictFourId' , 'class'=>'sbox36'));
					?>
				</li>
				<li>
					<h6>收货人地址：</h6>
					<?php
						$form->address = isset($form->address) ? $form->address : (isset($adds['address'])?$adds['address']:'');
						echo $active->textField($form,'address',array('id'=>'address','class'=>'tbox34 tbox34-1','placeholder'=>'请输入正确收货人地址'));
					?>
				</li>
				<li class="dh crbox18-group">
					<h6>&nbsp;</h6>
					<label>
						<?php
							$form->is_default = isset($form->is_default) ? (int)$form->is_default : (isset($adds['is_default'])?(int)$adds['is_default']:0);
							echo $active->checkBox($form,'is_default',array('id'=>'is_default'));
						?>
						<i>设为默认收货地址</i>
					</label>
				</li>
				<li>
					<h6>&nbsp;</h6>
					<?php
						echo CHtml::submitButton('保存' , array('class'=>'btn-1 btn-1-3'));
						echo CHtml::resetButton('重填' , array('class'=>'btn-1 btn-1-4' , 'id'=>'reset'));
					?>
				</li>
			</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<div class="mask" id="maskbox"></div>
</body>
</html>
<?php Views::js(array('jquery.validate')); ?>
<script>
$(document).ready(function()
{
	var
	dictOld = {
		'dictOneId' : <?php echo $form->dictOneId; ?> ,
		'dictTwoId' : <?php echo $form->dictTwoId; ?> ,
		'dictThreeId' : <?php echo $form->dictThreeId; ?> ,
		'dictFourId' : <?php echo $form->dictFourId; ?>
	};

	function selectReset(obj){obj.html('<option selected="selected" value=""> - 请选择 - </option>')}
	function selectvaluation(obj , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
		obj.html('<option value=""> - 请选择 - </option>' + code);
	}
	function jsonFilterError(json)
	{
		if (json.code == 0)
			return json.data;
		else
			alert(json.message);
	}

	$('#close').click(function()
	{
		(window.parent != window) && window.parent.addObj.remove();
	});

	var $form = $('#addressForm');
	$form
	//区域选择
	.on('change' , 'select.ajax-dict' , function()
	{
		var e = this , val = $(e).val() , dict = {'dictOneId':0,'dictTwoId':0,'dictThreeId':0,'dictFourId':0};
		$(e).nextAll('select').each(function(){selectReset($(this));$(this).show();});
		dict.dictOneId = $('#dictOneId').val();
		dict.dictTwoId = $('#dictTwoId').val();
		dict.dictThreeId = $('#dictThreeId').val();
		dict.dictFourId = $('#dictFourId').val();

		if (val)
		{
			var nextSelect = $(e).next('select') , nextID = nextSelect.attr('id');
			$.getJSON('<?php echo $this->createUrl('asyn/dictChild'); ?>' , dict , function(json)
			{
				json = jsonFilterError(json);
				if ($.isEmptyObject(json))
				{
					$(e).nextAll('select').hide();
				}else{
					selectvaluation(nextSelect , json , dictOld[nextID]);
					dictOld[nextID] > 0 && nextSelect.change();
				}
			});
		}
	})
	.on('change' , 'select' , function()
	{
		if ($(this).val() == '')
			$('#promt').text('请选择收货人地区').show();
		else
			$('#promt').text('').hide();
	})
	.on('click' , '#reset' , function()
	{
		$form.find('.promt').hide();
		$form.find('*').removeClass('error-input')
	})
	.validate({
		rule : {
			consignee : {required : '请输入真实姓名'},
			address : {required : '请输入正确收货人地址'},
			phone : {required : '请输入收货人手机号',mobile : '手机号码不合法'}
		},
		site : 'one',
		way : 'one',
		focus : true
	});

	if (dictOld.dictOneId > 0)
		$('#dictOneId').change();

	<?php if ($formError): ?>
	(function(){
		var formError = <?php echo json_encode($formError); ?> , code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError)
		{
			for (b in formError[a])
			{
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		layer.alert(code);
	})();
	<?php endif; ?>
});
</script>