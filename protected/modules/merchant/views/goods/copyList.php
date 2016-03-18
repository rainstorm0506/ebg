<?php
	Yii::app()->clientScript->registerCoreScript('layer');
?>
<main>
	<section class="merchant-content merchant-content-a">
		<form method="get" class="mer-search">
			<span>分类：</span>
			<?php
				echo CHtml::dropDownList('class_one_id',0,array(''=>'一级分类'),array('class'=>'sbox30'));
				echo CHtml::dropDownList('class_two_id',0,array(''=>'二级分类'),array('class'=>'sbox30'));
				echo CHtml::dropDownList('class_three_id',0,array(''=>'三级分类'),array('class'=>'sbox30'));
				echo CHtml::textField('keyword' , $keyword , array('class'=>'tbox28','style'=>'width:250px;margin:0 10px 0 0','placeholder'=>'商品标题、副标题、货号'));
				echo CHtml::submitButton('搜索' , array('class'=>'btn-1 btn-1-7' , 'name'=>null));
			?>
		</form>

		<table class="goods-tab goods-tab-1">
			<colgroup>
				<col width="15">
				<col>
				<col>
				<col width="12%">
				<col width="12%">
			</colgroup>
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th class="tl">商品名</th>
					<th>商家</th>
					<th>品牌</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $val): ?>
				<tr>
					<td></td>
					<td class="tl"><?php echo String::utf8Truncate($val['title'],26); ?></td>
					<td><?php echo GlobalMerchant::getStoreName($val['merchant_id']); ?></td>
					<td><?php echo GlobalBrand::getBrandName($val['brand_id']); ?></td>
					<td class="control">
					<?php
						echo CHtml::link('查看' , $this->createUrl('copyShow' , array('id'=>$val['id']))).' <i>|</i> ';
						echo CHtml::link('复制' , $this->createUrl('copyExec' , array('id'=>$val['id'])) , array('class'=>'goods-copy'));
					?>
					</td>
				</tr>
			<?php endforeach; if (!$list): ?>
				<tr><td colspan="5" class="else">当前没有商品</td></tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php $this->widget('WebListPager', array('pages' => $page)); ?>
	</section>
</main>
<script>
var classJSON = <?php echo json_encode($class); ?> , classInit = {
	'class_one_id'		: <?php echo $class_one_id; ?>,
	'class_two_id'		: <?php echo $class_two_id; ?>,
	'class_three_id'	: <?php echo $class_three_id; ?>,
};
function selectReset(evt , val){evt.html('<option selected="selected" value="">'+val+'</option>')}
function selectvaluation(evt , json , child_id , val)
{
	var code = i = '';
	if (!$.isEmptyObject(json))
	{
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
	}
	evt.html('<option value="">'+val+'</option>' + code);
}

$(document).ready(function()
{
	$('a.goods-copy').click(function()
	{
		var e = this;
		layer.confirm
		(
			'复制后的商品将会［下架］和［重新审核］<br>你还复制商品吗？',
			function()
			{
				window.location.href = $(e).attr('href');
				return true;
			}
		);
		return false;
	});

	$('.mer-search')
	.on('change' , 'select[name="class_one_id"]' , function()
	{
		var thisID = parseInt($(this).val() || 0 , 10) , nextSelect = $('.mer-search select[name="class_two_id"]');
		selectReset(nextSelect , '二级分类');
		selectReset($('.mer-search select[name="class_three_id"]') , '三级分类');

		if (thisID && !$.isEmptyObject(classJSON[thisID].child))
		{
			selectvaluation(nextSelect , classJSON[thisID].child , classInit.class_two_id , '二级分类');
			if (classInit.class_two_id > 0)
				nextSelect.change();
		}
	})
	.on('change' , 'select[name="class_two_id"]' , function()
	{
		var
			oneID = parseInt($('.mer-search select[name="class_one_id"]').val() || 0 , 10) ,
			thisID = parseInt($(this).val() || 0 , 10) ,
			nextSelect = $('.mer-search select[name="class_three_id"]');

		selectReset(nextSelect , '三级分类');
		if (oneID && thisID && !$.isEmptyObject(classJSON[oneID].child[thisID].child))
			selectvaluation(nextSelect , classJSON[oneID].child[thisID].child , classInit.class_three_id , '三级分类');
	})
	.on('click' , '.search-button' , function()
	{
		$(this).parent('form').submit();
	});

	//给 class_one_id 赋值
	selectvaluation($('.mer-search select[name="class_one_id"]') , classJSON , classInit.class_one_id , '一级分类');
	$('.mer-search select[name="class_one_id"]').change();
});
</script>