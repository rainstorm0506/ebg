<?php
$this->renderPartial ( 'navigation' );
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'edit' ? '添加' : '编辑'; ?> 内容</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em>*</em> 内容标题：</span>
				<?php
				$form->title = $form->title ? $form->title : (isset ( $info ['title'] ) ? $info ['title'] : '');
				echo $active->textField ( $form, 'title', array (
					'style' => 'width:40%',
					'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'title' );
				?>
		</li>
		<li><span><em>*</em> 所属分类：</span>
				<?php
				$typeArr = array ();
				$form->type = $form->type ? $form->type : (isset ( $info ['type'] ) ? $info ['type'] : '');
				// 查询文章分类列表
				$typeObject = ClassLoad::Only ( 'ContentType' )->getList ();
				$typeArr[0] = "---请选择---";
				foreach ( $typeObject as $key => $val ) {
					$typeArr [$val['id']] = $val['name'];
				}
				echo $active->dropDownList ( $form, 'type', $typeArr, array (
					'style' => 'width:20%',
					'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'type' );
				?>
		</li>
		<li class='radios'><span>是否显示：</span>
				<?php
				$form->is_show = $form->is_show ? $form->is_show : (isset ( $info ['is_show'] ) ? $info ['is_show'] : '1');
				echo $active->radioButton ( $form, 'is_show', array (
					'class' => 'selectRadio',
					'value' => 1,
					'style' => 'margin-top:7px' 
				) ) . '<span style="width:10px">是</span>';
				echo $active->radioButton ( $form, 'is_show', array (
					'class' => 'selectRadio',
					'value' => 0,
					'style' => 'margin-left:30px;margin-top:7px' 
				) ) . '<span style="width:10px">否</span>';
				?>
				<div class="hint"></div>
		</li>
		<li class='radios'><span>前端底部显示：</span>
				<?php
				$form->foot_show = $form->foot_show ? $form->foot_show : (isset ( $info ['foot_show'] ) ? $info ['foot_show'] : '1');
				echo $active->radioButton ( $form, 'foot_show', array (
					'class' => 'selectRadio',
					'value' => 1,
					'style' => 'margin-top:7px' 
				) ) . '<span style="width:10px">是</span>';
				echo $active->radioButton ( $form, 'foot_show', array (
					'class' => 'selectRadio',
					'value' => 0,
					'style' => 'margin-left:30px;margin-top:7px' 
				) ) . '<span style="width:10px">否</span>';
				?>
				<div class="hint"></div>
		</li>
		<li><span><em>*</em> 排序：</span>
				<?php
				$form->orderby = $form->orderby ? $form->orderby : (isset ( $info ['orderby'] ) ? $info ['orderby'] : '');
				echo $active->textField ( $form, 'orderby', array (
					'style' => 'width:40%',
					'class' => 'textbox' 
				) );
				?>
				<span style="width:210px">(<label style="color:red">注</label>：从小到大排序，越小越靠前!)</span>
		</li>
		<li><span><em>*</em> 内容详细：</span>	
				<aside style="line-height:18px;display:block;width:80%;height:auto">
				<?php
					$form->content = $form->content ? $form->content : (isset ( $info ['content'] ) ? $info ['content'] : '');
					$active->widget('UEditor' , array('form'=>$form , 'name'=>'content' , 'id'=>'content', 'options'=>array('style'=>'width:90%')));
					echo $active->error($form , 'content' , array('inputID'=>'content'));
				?>
				</aside>
		</li>
		<li style="margin: 20px"><span>&nbsp;</span>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ? '确认添加':'提交修改' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
		</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
var radio = <?php echo isset($info['is_show'])?$info['is_show']:0;?>;
$(function(){
	$('.radios input[type=hidden]').val(radio);
	//是否显示
	$('.selectRadio').click(function(){
		$('.radios input[type=hidden]').val($(this).val());
	});
	//点击返回
	$('.goback').click(function(){
		history.go(-1);
	});
})
</script>
