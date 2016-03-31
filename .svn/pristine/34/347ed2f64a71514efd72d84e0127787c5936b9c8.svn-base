<div class="navigation">
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'active-form','method'=>'GET','enableAjaxValidation'=>true,'htmlOptions'=>array('class'=>'form-wraper','style'=>'height:40px;'))); ?>
	<span class="seacrhlist" style="float: left;padding-top: 30px;">
		<span>
		活动标题：<?php
			$activeform->title = !empty($form['title'])?$form['title']:'';
			echo $active->textField($activeform , 'title' , array('class'=>'textbox','maxlength'=>'11'));?>
		</span>
		<span>
		活动编号：<?php
			$activeform->id = !empty($form['id'])?$form['id']:'';
			echo $active->textField($activeform , 'id' , array('class'=>'textbox','maxlength'=>'11'));?>
		</span>
		<span>
		促销类型：<?php
			$activeform->type = !empty($form['type'])?$form['type']:'';
			echo $active->dropDownList ( $activeform, 'type', CMap::mergeArray ( array (
					''=>'--------'
			), array(
					'1'=> '秒杀',
					'2'=> '1折购',
					'3'=> '特价专区',
			) ), array (
					'class' => 'sbox32'
			) );
			?>
		</span>
		<span>
		状态：<?php
			$activeform->status = !empty($form['status'])?$form['status']:'';
			echo $active->dropDownList ( $activeform, 'status', CMap::mergeArray ( array (
					''=>'--------'
			), array(
					'1'=> '未开始',
					'2'=> '已开始',
					'3'=> '已结束',
			) ), array (
					'class' => 'sbox32'
			) );
			?>
		</span>
		<br/>
		<span>促销时间：<?php
			$active->widget ( 'Laydate', array (
					'id' => 'start',
					'name' => 'start',
					'class' => "tbox38 tbox38-1",
					'style' => 'width:120px;height:30px;',
					'placeholder'=>'开始时间',
					'value'=>(!empty($start)?date('Y-m-d H:i:s',$start):'') ,
			) );
			?><i>—</i><?php
			$active->widget ( 'Laydate', array (
					'id' => 'end',
					'name' => 'end',
					'class' => "tbox38 tbox38-1",
					'style' => 'width:120px;height:30px',
					'placeholder'=>'结束时间',
					'value'=>(!empty($end)?date('Y-m-d H:i:s',$end):''),
			) );
			?>
		</span>
		<span><input type="submit" value="搜索" class="btn-2"></span>
	</span>
	<?php $this->endWidget(); ?>
	<ul>
		<li><?php echo CHtml::link('添加活动' , $this->createUrl('active/AddAct') , Views::linkClass('user' , 'create')); ?></li>
		<li><?php echo CHtml::link('全部列表' , $this->createUrl('active/index') , Views::linkClass('user' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>