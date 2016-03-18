<style type="text/css">
	.navigation form select{border:1px solid #CCC;text-align:center;width:120px; margin-right:8px; height: 30px;}
</style>
<div class="navigation">
<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<?php
				echo CHtml::dropDownList('class',(empty($_GET['class'])?0:(int)$_GET['class']),CMap::mergeArray(array(''=>' - 分类 - '),$class));
				echo CHtml::dropDownList('shelf',(empty($_GET['shelf'])?0:(int)$_GET['shelf']),CMap::mergeArray(array(''=>' - 上下架状态 - '),$shelfStatus),array('class'=>'she'));
				echo CHtml::dropDownList('verify',(empty($_GET['verify'])?0:(int)$_GET['verify']),CMap::mergeArray(array(''=>' - 审核状态 - '),$verifyStatus),array('class'=>'sta'));
			?>
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索商品名称、商家名、商品ID、商品货号" style="width:320px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
<?php endif;?>
	<ul>
		<li><?php echo CHtml::link('添加二手商品' , $this->createUrl('create') , Views::linkClass('usedgoods' , 'create')); ?></li>
		<li><?php echo CHtml::link('二手商品列表' , $this->createUrl('list') , Views::linkClass('usedgoods' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>
<script>
	$(document).ready(function()
	{
		//提交
		$('.navigation').on('click' , '.search-button' , function()
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
	})
</script>