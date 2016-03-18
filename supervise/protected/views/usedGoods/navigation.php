<style type="text/css">
	.navigation form select{border:1px solid #CCC;text-align:center;width:120px; margin-right:8px; height: 30px;}
</style>
<div class="navigation">
<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<select name="class_one"><option value=""> - 一级分类 - </option></select>
			<select name="class_two"><option value=""> - 二级分类 - </option></select>
			<select name="class_three"><option value=""> - 三级分类 - </option></select>
			<?php
				echo CHtml::dropDownList('shelf',(empty($_GET['shelf'])?0:(int)$_GET['shelf']),CMap::mergeArray(array(''=>' - 上下架状态 - '),$shelfStatus),array('class'=>'she'));
				echo CHtml::dropDownList('verify',(empty($_GET['verify'])?0:(int)$_GET['verify']),CMap::mergeArray(array(''=>' - 审核状态 - '),$verifyStatus),array('class'=>'sta'));
			?>
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索商品名称、商家名、商品ID、商品货号" style="width:320px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
<script>
	var classJSON = <?php echo json_encode($class); ?> , classInit = {
		'class_one'		: <?php echo empty($_GET['class_one'])?0:(int)$_GET['class_one']; ?>,
		'class_two'		: <?php echo empty($_GET['class_two'])?0:(int)$_GET['class_two']; ?>,
		'class_three'	: <?php echo empty($_GET['class_three'])?0:(int)$_GET['class_three']; ?>
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
		$('.navigation')
			.on('change' , 'select[name="class_one"]' , function()
			{
				var thisID = parseInt($(this).val() || 0 , 10) , nextSelect = $('.navigation select[name="class_two"]');
				selectReset(nextSelect , ' - 二级分类 - ');
				selectReset($('.navigation select[name="class_three"]') , ' - 三级分类 - ');

				if (thisID && !$.isEmptyObject(classJSON[thisID].child))
				{
					selectvaluation(nextSelect , classJSON[thisID].child , classInit.class_two , ' - 二级分类 - ');
					if (classInit.class_two > 0)
						nextSelect.change();
				}
			})
			.on('change' , 'select[name="class_two"]' , function()
			{
				var
					oneID = parseInt($('.navigation select[name="class_one"]').val() || 0 , 10) ,
					thisID = parseInt($(this).val() || 0 , 10) ,
					nextSelect = $('.navigation select[name="class_three"]');

				selectReset(nextSelect , ' - 三级分类 - ');
				if (oneID && thisID && !$.isEmptyObject(classJSON[oneID].child[thisID].child))
					selectvaluation(nextSelect , classJSON[oneID].child[thisID].child , classInit.class_three , ' - 三级分类 - ');
			})
			.on('click' , '.search-button' , function()
			{
				$(this).parent('form').submit();
			});

		$('.navigation').on('change','.she',function(){
			var _she=$(this).val();
			_html='';
			if(_she==1001){
				_html+='<option value=""> - 审核状态 - </option>';
				_html+='<option value="1013" selected>审核成功</option>';
			}else{
				_html+='<option value=""> - 审核状态 - </option>';
				_html+='<option value="1011">待审核</option>';
				_html+='<option value="1013">审核成功</option>';
				_html+='<option value="1014">审核失败</option>';
			}
			$('.sta').html(_html);
		})
		//给 class_one 赋值
		selectvaluation($('.navigation select[name="class_one"]') , classJSON , classInit.class_one , ' - 一级分类 - ');
		$('.navigation select[name="class_one"]').change();
	})
</script>
<?php endif;?>
	<ul>
		<li><?php echo CHtml::link('添加二手商品' , $this->createUrl('create') , Views::linkClass('usedgoods' , 'create')); ?></li>
		<li><?php echo CHtml::link('二手商品列表' , $this->createUrl('list') , Views::linkClass('usedgoods' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>