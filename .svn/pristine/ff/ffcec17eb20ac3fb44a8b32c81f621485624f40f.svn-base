<style type="text/css">
	.navigation form select{border:1px solid #CCC;text-align:center;width:130px; margin-right:8px; height: 30px;}
</style>
<div class="navigation">
	<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<select name="goods_class_one"><option value=""> - 全新一级分类 - </option></select>
			<select name="goods_class_two"><option value=""> - 全新二级分类 - </option></select>
			<select name="goods_class_three"><option value=""> - 全新三级分类 - </option></select>
			<select style="margin-left: 20px;" name="used_class_one"><option value=""> - 二手一级分类 - </option></select>
			<select name="used_class_two"><option value=""> - 二手二级分类 - </option></select>
			<select name="used_class_three"><option value=""> - 二手三级分类 - </option></select>
			<input type="text" name="keyword" class="search-keyword" placeholder="支持 搜索 品牌中文名、品牌英文名" style="width:220px;margin-left: 20px;" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
<script>
	var goodsClassJSON = <?php echo json_encode($goodsClass); ?> , goodsClassInit = {
		'goods_class_one'		: <?php echo empty($_GET['goods_class_one'])?0:(int)$_GET['goods_class_one']; ?>,
		'goods_class_two'		: <?php echo empty($_GET['goods_class_two'])?0:(int)$_GET['goods_class_two']; ?>,
		'goods_class_three'		: <?php echo empty($_GET['goods_class_three'])?0:(int)$_GET['goods_class_three']; ?>
	};
	var usedClassJSON = <?php echo json_encode($usedClass); ?> , usedClassInit = {
		'used_class_one'		: <?php echo empty($_GET['used_class_one'])?0:(int)$_GET['used_class_one']; ?>,
		'used_class_two'		: <?php echo empty($_GET['used_class_two'])?0:(int)$_GET['used_class_two']; ?>,
		'used_class_three'		: <?php echo empty($_GET['used_class_three'])?0:(int)$_GET['used_class_three']; ?>
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
			//全新分类
			.on('change' , 'select[name="goods_class_one"]' , function()
			{
				var thisID = parseInt($(this).val() || 0 , 10) , nextSelect = $('.navigation select[name="goods_class_two"]');
				selectReset(nextSelect , ' - 全新二级分类 - ');
				selectReset($('.navigation select[name="goods_class_three"]') , ' - 全新三级分类 - ');

				if (thisID && !$.isEmptyObject(goodsClassJSON[thisID].child))
				{
					selectvaluation(nextSelect , goodsClassJSON[thisID].child , goodsClassInit.goods_class_two , ' - 全新二级分类 - ');
					if (goodsClassInit.goods_class_two > 0)
						nextSelect.change();
				}
			})
			.on('change' , 'select[name="goods_class_two"]' , function()
			{
				var
					oneID = parseInt($('.navigation select[name="goods_class_one"]').val() || 0 , 10) ,
					thisID = parseInt($(this).val() || 0 , 10) ,
					nextSelect = $('.navigation select[name="goods_class_three"]');

				selectReset(nextSelect , ' - 全新三级分类 - ');
				if (oneID && thisID && !$.isEmptyObject(goodsClassJSON[oneID].child[thisID].child))
					selectvaluation(nextSelect , goodsClassJSON[oneID].child[thisID].child , goodsClassInit.goods_class_three , ' - 全新三级分类 - ');
			})

			//二手分类
			.on('change' , 'select[name="used_class_one"]' , function()
			{
				var thisID = parseInt($(this).val() || 0 , 10) , nextSelect = $('.navigation select[name="used_class_two"]');
				selectReset(nextSelect , ' - 二手二级分类 - ');
				selectReset($('.navigation select[name="used_class_three"]') , ' - 二手三级分类 - ');

				if (thisID && !$.isEmptyObject(usedClassJSON[thisID].child))
				{
					selectvaluation(nextSelect , usedClassJSON[thisID].child , usedClassInit.used_class_two , ' - 二手二级分类 - ');
					if (usedClassInit.used_class_two > 0)
						nextSelect.change();
				}
			})
			.on('change' , 'select[name="used_class_two"]' , function()
			{
				var
					oneID = parseInt($('.navigation select[name="used_class_one"]').val() || 0 , 10) ,
					thisID = parseInt($(this).val() || 0 , 10) ,
					nextSelect = $('.navigation select[name="used_class_three"]');

				selectReset(nextSelect , ' - 二手三级分类 - ');
				if (oneID && thisID && !$.isEmptyObject(usedClassJSON[oneID].child[thisID].child))
					selectvaluation(nextSelect , usedClassJSON[oneID].child[thisID].child , usedClassInit.used_class_three , ' - 二手三级分类 - ');
			})
			.on('click' , '.search-button' , function()
			{
				$(this).parent('form').submit();
			});

		//给 goods_class_one 赋值
		selectvaluation($('.navigation select[name="goods_class_one"]') , goodsClassJSON , goodsClassInit.goods_class_one , ' - 全新一级分类 - ');
		$('.navigation select[name="goods_class_one"]').change();

		//给 used_class_one 赋值
		selectvaluation($('.navigation select[name="used_class_one"]') , usedClassJSON , usedClassInit.used_class_one , ' -  二手一级分类 - ');
		$('.navigation select[name="used_class_one"]').change();
	})
</script>
<?php endif;?>
	<ul>
		<li><?php echo CHtml::link('添加 品牌' , $this->createUrl('create') , Views::linkClass('goodsBrand' , 'create')); ?></li>
		<li><?php echo CHtml::link('品牌 列表' , $this->createUrl('list') , Views::linkClass('goodsBrand' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>