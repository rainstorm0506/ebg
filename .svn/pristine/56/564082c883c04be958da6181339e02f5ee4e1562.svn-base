<?php
Yii::app()->clientScript->registerCoreScript('layer');
Yii::app()->getClientScript()->registerCss('goods.index' , '
.mer-search select{margin:0 12px 0 0}
.mer-search .m-2{width:6em}
.goods-tab td.else{padding:200px 0}
');
?>
<section class="merchant-content merchant-content-a">
	<!-- 搜索框 -->
	<form method="get" class="mer-search">
		<div>
			<span>商品分类：</span>
			<?php
				echo CHtml::dropDownList('class_one_id',0,array(''=>'一级分类'),array('class'=>'sbox30'));
				echo CHtml::dropDownList('class_two_id',0,array(''=>'二级分类'),array('class'=>'sbox30'));
				echo CHtml::dropDownList('class_three_id',0,array(''=>'三级分类'),array('class'=>'sbox30'));
				echo CHtml::dropDownList('shelf_id',$shelf_id,CMap::mergeArray(array(''=>' - 上下架状态 - '),$shelfStatus),array('class'=>'sbox30'));
				echo CHtml::dropDownList('status_id',$status_id,CMap::mergeArray(array(''=>' - 审核状态 - '),$verifyStatus),array('class'=>'sbox30'));
			?>
		</div>
		<div>
		<?php
			echo '<span>创建时间：</span>';
			$this->widget('Laydate' ,
				array(
					'name'			=> 'timeStart' ,
					'class'			=> 'tbox28 tbox28-2',
					'isTime'		=> false,
					'value'			=> $timeStart,
					'placeholder'	=> '开始时间',
				)
			);
			echo '<i>-</i>';
			$this->widget('Laydate' ,
				array(
					'name'			=> 'timeEnd' ,
					'class'			=> 'tbox28 tbox28-2',
					'isTime'		=> false,
					'value'			=> $timeEnd,
					'placeholder'	=> '结束时间',
				)
			);
			echo '<span class="m-2">关键字搜索：</span>';
			echo CHtml::textField('keyword' , $keyword , array('class'=>'tbox28 tbox28-3','placeholder'=>'商品标题、副标题、货号'));
			echo CHtml::submitButton('搜索' , array('class'=>'btn-1 btn-1-7' , 'name'=>null));
		?>
		</div>
	</form>
	<table class="goods-tab">
		<colgroup>
			<col width="15">
			<col>
			<col width="15%">
			<col width="15%">
			<col width="8%">
			<col width="8%">
			<col width="18%">
		</colgroup>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th class="tl">商品名</th>
				<th>品牌</th>
				<th>货号</th>
				<th>上架状态</th>
				<th>审核状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($list as $val): ?>
			<tr>
				<td></td>
				<td class="tl"><?php echo String::utf8Truncate($val['title'],22); ?></td>
				<td><?php echo GlobalBrand::getBrandName($val['brand_id']); ?></td>
				<td><?php echo $val['goods_num']; ?></td>
				<td><?php echo $val['shelf_id']==410?'<i class="ico-yes"></i>':'<i class="ico-no"></i>'; ?></td>
				<?php
					switch ($val['status_id'])
					{
						case 400 : echo '<td class="gray">待审核</td>'; break;
						case 401 : echo '<td class="gc">已通过</td>'; break;
						case 402 : echo '<td class="mc">未通过</td>'; break;
						default : echo '<td></td>'; break;
					}
				?>
				<td class="control">
				<?php
					echo CHtml::link('SEO' , $this->createUrl('seo' , array('id'=>$val['id']))).' <i>|</i> ';
					echo CHtml::link('查看' , $this->createUrl('show' , array('id'=>$val['id']))).' <i>|</i> ';
					echo CHtml::link('编辑' , $this->createUrl('modify' , array('id'=>$val['id'])) , array('class'=>'goods-modify')).' <i>|</i> ';
					echo CHtml::link('删除' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class'=>'u-del'));
				?>
				</td>
			</tr>
		<?php endforeach; if (!$list): ?>
			<tr><td colspan="7" class="else">当前没有商品</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php $this->widget('WebListPager', array('pages' => $page)); ?>
</section>

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
	$('a.u-del').click(function(){
		var _url = $(this).attr('href');
		layer.confirm
		(
			'你确认删除此商品吗？',
			function()
			{
				window.location.href = _url;
				return true;
			}
		);
		return false;
	});

	$('a.goods-modify').click(function()
	{
		var _url = $(this).attr('href');
		layer.confirm
		(
			'编辑的商品提交后<br>商品将会［下架］和［重新审核］<br>你还编辑商品吗？',
			function()
			{
				window.location.href = _url;
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