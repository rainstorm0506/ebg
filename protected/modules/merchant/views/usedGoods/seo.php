<?php Views::css(array('merchant')); ?>
<section class="merchant-content merchant-content-b">
	<fieldset class="form-list form-list-36 add-goods-form crbox18-group">
		<h1 class="title" style="color:red;font-size:18px;text-align:center">二手商品 - 设定SEO关键词</h1>
		<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'formWrap'))); ?>
			<ul>
				<li class="gcShow"><h6><em>*</em> 商品名称：</h6><?php echo $info['title']; ?></li>
				<li>
					<h6>title：</h6>
					<?php
						CHtml::$errorContainerTag = 'span';
						$form->seo_title = isset($form->seo_title) ? $form->seo_title : (isset($seo['seo_title'])?$seo['seo_title']:'');
						echo $active->textField($form , 'seo_title' , array('class'=>'tbox38' , 'style'=>'width:60%'));
					?>
				</li>
				<li>
					<h6>keywords：</h6>
					<?php
						$form->seo_keywords = isset($form->seo_keywords) ? $form->seo_keywords : (isset($seo['seo_keywords'])?$seo['seo_keywords']:'');
						echo $active->textArea($form , 'seo_keywords' , array('class'=>'tbox38','style'=>'width:60%;height:120px;line-height:20px'));
					?>
				</li>
				<li>
					<h6>description：</h6>
					<?php
						$form->seo_description = isset($form->seo_description) ? $form->seo_description : (isset($seo['seo_description'])?$seo['seo_description']:'');
						echo $active->textArea($form , 'seo_description' , array('class'=>'tbox38','style'=>'width:60%;height:120px;line-height:20px'));
					?>
				</li>
				<li>
					<h6>&nbsp;</h6>
					<?php echo CHtml::submitButton('提交' , array('class'=>'btn-2')),CHtml::resetButton('重置' , array('class'=>'btn-2')); ?>
				</li>
			</ul>
		<?php $this->endWidget(); ?>
	</fieldset>
</section>