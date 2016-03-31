<?php 
Views::js('jquery.editable-select.min');
?>
<style>
select{font-size: 14px;font-family: Microsoft YaHei;color: #666;height: 31px;}
.navigation span {float: left;margin: 0 0 0 30px;}
.tbox38{height: 30px;}
.selectButtons li{line-height: 8px}
input.es-input{padding-right:20px!important;height:30px;width:220px;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAICAYAAADJEc7MAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAG2YAABzjgAA4DIAAIM2AAB5CAAAxgwAADT6AAAgbL5TJ5gAAABGSURBVHjaYvz//z8DOYCJgUzA0tnZidPK8vJyRpw24pLEpwnuVHRFhDQxMDAwMPz//x+OOzo6/iPz8WFGuocqAAAA//8DAD/sORHYg7kaAAAAAElFTkSuQmCC) right center no-repeat}
input.es-input:focus{-webkit-border-bottom-left-radius:0;-moz-border-radius-bottomleft:0;border-bottom-left-radius:0;-webkit-border-bottom-right-radius:0;-moz-border-radius-bottomright:0;border-bottom-right-radius:0}
.es-list{display:block;position:absolute;padding:0;margin:0;border:1px solid #d1d1d1;display:none;z-index:1000;background:#fff;max-height:160px;overflow-y:auto;-moz-box-shadow:0 2px 3px #ccc;-webkit-box-shadow:0 2px 3px #ccc;box-shadow:0 2px 3px #ccc}
.es-list li{display:block;padding:5px 10px;margin:0}
.es-list li.selected{background:#f3f3f3}

</style>
<div class="navigation">
	<form class="form-wraper" id="search" action="<?php echo $this->createUrl('actOrder/list');?>" method="post">	
	<span>
		<div>
			<span style="margin-left:0px;height:30px;line-height:30px">下单时间：</span>
				<?php 
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => ClassLoad::Only('OrderForm'),
						'id' =>'starttime',
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
						'id'=>'endtime',
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
	<span>活动类型：
		<select name='OrderForm[active_type]' id="active_type">
			<option value='' selected="selected">---请选择---</option>
			<option value='1' <?php if($searchPost['active_type'] == 1)echo "selected='selected'"?>>秒杀</option>
			<option value='2' <?php if($searchPost['active_type'] == 2)echo "selected='selected'"?>>特价</option>
			<option value='3' <?php if($searchPost['active_type'] == 3)echo "selected='selected'"?>>一折购</option>
		</select>
	</span>
	<span>
		<input type="text" name='OrderForm[keyword]' id="search_keyword" class="search-keyword" tag="支持搜索订单号" style="width:340px" value="<?php echo $searchPost['keyword']; ?>">
	</span>
	<span>
		<?php echo CHtml::link('查询' , 'javascript:;', array('onclick'=>"$('#search').submit();",'class'=>'searchs-button')); ?>
	</span>
	
	</form>
	<ul>
		<li><?php echo CHtml::link('订单 列表' , $this->createUrl('actOrder/list') , Views::linkClass('order' , 'list')); ?></li>
	</ul>
	<i class="clear"></i> 
</div>
<div class="navigation">
	<form class="form-wraper" id="search" action="<?php echo $this->createUrl('actOrder/list');?>" method="post">	
	<span>
	<ul class="selectButtons" style="margin: -15px 0px">
		<?php 
			$status = ClassLoad::Only('ActOrder')->status();
			foreach ($status as $key => $val){
				echo "<li>".CHtml::link($val , $this->createUrl('actOrder/list', array('status'=>$key)) ,array('style'=> !empty($button_search) && $button_search == $key ? "color:red" : "color:rgb"))."</li>";
			}
		?>
	</ul>
	
	</span>
	</form>
	<i class="clear"></i> 
</div>