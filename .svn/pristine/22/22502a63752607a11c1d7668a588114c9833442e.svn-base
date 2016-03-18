<?php
$this->renderPartial ( 'navigation' );
?>
<style type='text/css'>
.public-wraper ul li span {
	width: 150px;
}
</style>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class='title'>编辑状态信息</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em></em> 类型：</span>
				<?php
				if (isset ( $info ['type'] )) {
					
					switch ($info ['type']) {
						case 1 :
							echo '正向订单';
							break;
						case 2 :
							echo '退货(反向订单)';
							break;
						case 3 :
							echo '换货';
							break;
						case 4 :
							echo '商品';
							break;
						case 5 :
							echo '个人用户';
							break;
						case 6 :
							echo '企业用户';
							break;
						case 7 :
							echo '商家';
							break;
						default :
							echo '暂为定义';
					}
				}
				echo $form->type = $form->type ? $form->type : (isset ( $info ['type'] ) ? $info ['type'] : '');
				
				?>
			</li>
		<li><span><em>*</em> 用户显示的状态名：</span>
				<?php
				$form->user_title = $form->user_title ? $form->user_title : (isset ( $info ['user_title'] ) ? $info ['user_title'] : '');
				echo $active->textField ( $form, 'user_title', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'user_title' );
				?>
			</li>
		<li><span><em>*</em> 商家显示的状态名：</span>
				<?php
				$form->merchant_title = $form->merchant_title ? $form->merchant_title : (isset ( $info ['merchant_title'] ) ? $info ['merchant_title'] : '');
				echo $active->textField ( $form, 'merchant_title', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'merchant_title' );
				?>
			</li>
		<li><span><em>*</em> 后台显示的状态名：</span>
				<?php
				$form->back_title = $form->back_title ? $form->back_title : (isset ( $info ['back_title'] ) ? $info ['back_title'] : '');
				echo $active->textField ( $form, 'back_title', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'back_title' );
				?>
			</li>
		<li><span><em>*</em> 用户提示：</span>
				<?php
				$form->user_describe = $form->user_describe ? $form->user_describe : (isset ( $info ['user_describe'] ) ? $info ['user_describe'] : '');
				echo $active->textField ( $form, 'user_describe', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'user_describe' );
				?>
			</li>
		<li><span><em>*</em> 商家提示：</span>
				<?php
				$form->merchant_describe = $form->merchant_describe ? $form->merchant_describe : (isset ( $info ['merchant_describe'] ) ? $info ['merchant_describe'] : '');
				echo $active->textField ( $form, 'merchant_describe', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'merchant_describe' );
				?>
			</li>
		<li><span><em>*</em> 后台提示：</span>
				<?php
				$form->back_describe = $form->back_describe ? $form->back_describe : (isset ( $info ['back_describe'] ) ? $info ['back_describe'] : '');
				echo $active->textField ( $form, 'back_describe', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'back_describe' );
				?>
			</li>

		<li><span>&nbsp;</span>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ? '添加广告':'提交修改' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>

$(function(){
	$('.goback').click(function(){
		location.href = history.go(-1);
	});
});
</script>
