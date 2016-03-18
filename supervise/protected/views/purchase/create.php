<style type="text/css">
.hint {
	padding-left: 138px;
}
#PurchaseForm_dispatching,#PurchaseForm_price_require {
	width: 160px;
}

.user-merchant{width:40.5%;clear:both;margin:10px 0 0 110px;border-collapse:collapse;}
.user-merchant th{background-color:#FF9900;text-align:center;font-weight:400;color:#FFF;border:1px solid #FFF}
.user-merchant tr.this td{background-color:#ABCDEF}
.user-merchant td{text-align:center;background-color:#CCFF99;border:1px solid #FFF}
.user-merchant ._l{text-align:left;padding:0 0 0 5px}
.user-merchant .user-seek{cursor:pointer}
.user-merchant .user-seek.this{color:#00F}
.user-merchant td input{text-align:center;float:none}
.user-merchant td.txt{background-color:#FCFCFC}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '发布' : '发布'; ?> 采购单</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','action'=>'purchase.create','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li>
			<span> <em>*</em>标题：</span>
				<?php
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
		</li>
		<li><span> <em>*</em>联系人：</span>
				<?php
					$form->link_man = $form->link_man ? $form->link_man : (isset($info['link_man'])?$info['link_man']:'');
					echo $active->textField($form , 'link_man' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'link_man');
				?>
				<input type="hidden" name="PurchaseForm[company_name]" id="company_name" value="<?php echo isset($info['company_name'])?$info['company_name']:''?>">
		</li>
		<li>
			<h6><em>*</em>搜索代发布企业：</h6>
				<?php
					$form->merchant = isset($form->merchant) ? $form->merchant : '';
					echo $active->textField($form , 'merchant' , array('class'=>'tbox34' , 'id'=>'merchant' , 'placeholder'=>'输入企业姓名，编号，自动检索'));

					$form->merchant_id = empty($form->merchant_id) ? 0 : (int)$form->merchant_id;
					echo $active->hiddenField($form , 'merchant_id' , array('id'=>'merchant_id'));
				?>
				<table class="user-merchant"></table>
		</li>
		<li><span> <em>*</em>电话：</span>
				<?php
					$form->phone = $form->phone ? $form->phone : (isset($info['phone'])?$info['phone']:'');
					echo $active->textField($form , 'phone' , array('style' => 'width:120px' , 'class'=>'textbox'));
					echo $active->error($form , 'phone');
				?>
		</li>
		<li>
			<span>报价截至时间：</span>
			<?php
				$form->price_endtime = $form->price_endtime ? $form->price_endtime : (isset($info['price_endtime'])?date('Y-m-d H:i:s',$info['price_endtime']):'');
				$active->widget ( 'Laydate', array (
					'form' => $form,
					'id' => 'price_endtime',
					'name' => 'price_endtime',
					'class' => "tbox38 tbox38-1",
					'style' => 'width:200px'
			) );
				echo $active->error($form , 'price_endtime');
				?>
			
		</li>
		<li><span>期望收货时间：</span>
				<?php
					$form->wish_receivingtime = $form->wish_receivingtime ? $form->wish_receivingtime : (isset($info['wish_receivingtime'])?date('Y-m-d H:i:s',$info['wish_receivingtime']):'');
						$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'wish_receivingtime',
						'name' => 'wish_receivingtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
						echo $active->error($form , 'wish_receivingtime');
						
				?>
			</li>
		<li><span>招投标：</span>
				<?php
					$form->is_tender_offer = isset($form->is_tender_offer) ? (int)$form->is_tender_offer : (isset($info['is_tender_offer'])?(int)$info['is_tender_offer']:0);
					echo $active->radioButtonList($form , 'is_tender_offer' , array(1=>'是' , 0=>'否') , array('separator' => ''),array('style' => 'width:100px'));
					echo $active->error($form , 'is_tender_offer');
				?>
		</li>
		<li><span>面谈：</span>
				<?php
					$form->is_interview = isset($form->is_interview) ? (int)$form->is_interview : (isset($info['is_interview'])?(int)$info['is_interview']:0);
					echo $active->radioButtonList($form , 'is_interview' , array(1=>'是' , 0=>'否') , array('separator' => ''),array('style' => 'width:100px'));
					echo $active->error($form , 'is_interview');
				?>
		</li>	
		<li><span>报价需求：</span>
				<?php
					$form->price_require = isset($form->price_require) ? (int)$form->price_require : (isset($info['price_require'])?(int)$info['price_require']:0);
					echo $active->radioButtonList($form , 'price_require' , array(1=>'包含税价' , 0=>'不含税价') , array('separator' => ''),array('style' => 'width:120px'));
					echo $active->error($form , 'price_require');
				?>
		</li>
		<li><span>配送方式：</span>
				<?php
					$form->dispatching = isset($form->dispatching) ? (int)$form->dispatching : (isset($info['dispatching'])?(int)$info['dispatching']:0);
					echo $active->radioButtonList($form , 'dispatching' , array(1=>'市内配送' , 0=>'上门自提') , array('separator' => ''),array('style' => 'width:120px'));
					echo $active->error($form , 'dispatching');
				?>
		</li>
		<li><span>&nbsp;</span> 
				<input type='hidden' class='goodsinfomes' name='PurchaseForm[goods]' value=""/>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::link('返回' ,$this -> createUrl("/purchase.list"),array('class'=>'btn-1')); ?>
		</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
merchantID		= '<?php echo '0'; ?>' ,
merchantUrl		= '<?php echo $this->createUrl('company/searchKeyword'); ?>' ,


$(function($){

	//搜索商家
	$("body").delegate("#merchant", "change", function () 
// 	.on('change' , '#merchant' , function()
	{
		$('.user-merchant').empty().hide();
		$('#merchant_id').val(0);
		
		var keyword = $.trim($(this).val()) , code = '';
		if (keyword == '')
			return false;

		$.getJSON(merchantUrl , {'keyword':keyword} , function(json)
		{
			json = jsonFilterError(json);
			if (!$.isEmptyObject(json))
			{
				code =	'<tr><th style="width:50px" class="user-seek">选择</th><th style="width:150px">企业编号</th>' +
						'<th style="width:150px">企业名称</th><th style="width:150px">类型</th></tr>';

				for (var i in json)
				{
					if (i > 9)
					{
						code += '<tr><td colspan="4" class="txt">最多搜索10条信息 , 如果你搜索的企业不在其中 , 请输入更详细的关键词 !</td></tr>';
					}else{
						code += '<tr '+(i%2==0?'':'class="this"')+'><td><input type="radio" name="merID" companyName="'+json[i].com_name+'" value="'+json[i].uid+'"></td><td>'+json[i].uid+'</td><td>'+json[i].com_name+'</td><td class="_l">'+json[i].com_property+'</td></tr>';
					}
				}
				$('.user-merchant').html(code).show();
				merchantID && $('.user-merchant :radio[value="'+merchantID+'"]').click();
			}else{
				$('.user-merchant').html('<tr><td class="_l">搜索不到企业信息!</td></tr>').show();
			}
		});
	})

		//选择商家
		$("body").delegate(".user-merchant :radio", "click", function () 
// 		.on('click' , '.user-merchant :radio' , function()
		{
			$(this).closest('tr').siblings('tr:gt(0)').hide();
			$('.user-seek').text('重选').addClass('this');
			$('#merchant_id').val($(this).val());
			$('#company_name').val($(this).attr('companyName'));
		})
		//重新选择
		//.on('click' , '.user-merchant th.user-seek' , function()
		$("body").delegate(".user-merchant th.user-seek", "click", function () 
		{
			if ($(this).hasClass('this'))
			{
				$('#merchant_id').val(0);
				$(this).removeClass('this').text('选择');
				$(this).closest('tr').nextAll('tr').show().find('input:checked').attr('checked' , false);
			}
		})

	
	$('input.int-price').keyup(function(){
		var re = /[^-\d]*/g;
		$(this).val($(this).val().replace(re , ''));
	});
	//点击返回
	$('.goback').click(function(){
		window.location.href = '/purchase.list';
	});
	if (merchantID)
		$('#merchant').change();
	$('.selectRadio').click(function(){
		$('.hint .textbox').attr('disabled',true);
		$(this).next().attr('disabled',false);
	});
});
</script>
