<style>
select{font-size: 14px;font-family: Microsoft YaHei;color: #666;height: 31px;}
.navigation span {float: left;margin: 0 0 0 30px;}
.tbox38{height: 30px;}
.selectButtons li{line-height: 8px}
</style>
<div class="navigation">
	<form class="form-wraper" id="search" action="/supervise/order.list" method="post">	
	<span>
		<div>
			<span style="margin-left:0px;height:30px;line-height:30px">下单时间：</span>
				<?php 
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => ClassLoad::Only('OrderForm'),
						'attribute' => 'starttime',
						'language' => 'zh_cn',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						)
						,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'class' => 'tbox38 tbox38-1',
								'readonly' => false,
								'value' => $searchPost['starttime']
						) 
				));
			?>
			<i>—</i>
			<?php
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => ClassLoad::Only('OrderForm'),
						'attribute' => 'endtime',
						'language' => 'zh_cn',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						)
						,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'class' => 'tbox38 tbox38-1',
								'readonly' => false,
								'value' => $searchPost['endtime']
						) 
				));
			?>
		</div>
	</span>
	<span>订单类型：
		<select name='OrderForm[is_self]'>
			<option value='' selected="selected">---请选择---</option>;
			<option value='1' <?php if($searchPost['is_self'] == 1)echo "selected='selected'"?>>自营订单</option>;
			<option value='2' <?php if($searchPost['is_self'] == 2)echo "selected='selected'"?>>第三方订单</option>;
		</select>
	</span>
	<span>
		<input type="text" name='OrderForm[keyword]' class="search-keyword" tag="支持搜索订单号、商家名称、收货人手机号码" style="width:340px" value="<?php echo $searchPost['keyword']; ?>">
		<?php echo CHtml::link('查询' , 'javascript:;', array('onclick'=>"$('#search').submit();",'class'=>'searchs-button')); ?>
	</span>
	</form>
	<ul>
		<li><?php echo CHtml::link('订单 列表' , $this->createUrl('order/list') , Views::linkClass('order' , 'list')); ?></li>
	</ul>
	<i class="clear"></i> 
</div>
<div class="navigation">
	<form class="form-wraper" id="search" action="/supervise/order.list" method="post">	
	<span>
	<ul class="selectButtons" style="margin: -15px 0px">
		<?php 
			$statusObject = ClassLoad::Only('Status')->getList(1);
			foreach ($statusObject as $key => $val){
				echo "<li>".CHtml::link($val['user_title'] , $this->createUrl('order/list', array('status'=>$val['id'])) ,array('style'=> !empty($button_search) && $button_search == $val['id'] ? "color:red" : "color:rgb"))."</li>";
			}
		?>
		<li><input type="checkbox" class="checkboxss" style="width:50px;heigth:50px"/>&nbsp;显示商品</li>
	</ul>
	
	</span>
	</form>
	<i class="clear"></i> 
</div>
<script>
$('document').ready(function(){
	$('.o_goods').parent().hide();
	$('.checkboxss').click(function(){
			if($(this).is(":checked")){
				$('.o_goods').parent().show();
			}else{
				$('.o_goods').parent().hide();
			}
	});
});
</script>