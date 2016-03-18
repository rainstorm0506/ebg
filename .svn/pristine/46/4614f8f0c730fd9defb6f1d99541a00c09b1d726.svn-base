<style type="text/css">
.selects div{padding:5px 0 0 0;border-bottom:1px solid #CCC}
.selects span{font-size:16px;background-color:#000;color:#FFF;padding:2px 5px}
.selects dd span{font-size:12px;padding:0 2px;margin:0 5px 0 0}
.selects dl{clear:both;margin:15px 10px 10px 0}
.selects dt{font-size:12px;font-weight:300;display:block}
.selects dd{display:inline-block;margin:0 0 0 35px}
.selects dd label{margin:0}
</style>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title">查看角色</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li><span>角色名称：</span><?php echo $info['title']; ?></li>
			<li><span>角色说明：</span><?php echo $info['explain']; ?></li>
			<li>
				<span>拥有的权限：</span>
				<div class="selects">
				<?php
					foreach ($this->getBackField() as $v)
					{
						$str = '<div><span>'.$v['title'].'</span>';
						$one = '';
						if (!empty($v['child']))
						{
							foreach ($v['child'] as $child)
							{
								$temp = $two = '';
								$temp = '<dl><dt><b>'.$child['title'].'</b></dt>';
								if (!empty($purviews[$child['id']]))
								{
									$i = 0;
									foreach ($purviews[$child['id']] as $key => $val)
									{
										if(!empty($info['purviews'][$key]))
											$two .= '<dd><span>'.(++$i).'</span>'.$val.'</dd>';
									}
								}
								$temp .= $two . '</dl>';
								$one .= $two ? $temp : '';
							}
						}
						echo $one ? ($str . $one . '</div>') : '';
					}
				?>
				</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::button(' 返 回 ' , array('id'=>'goBack' , 'class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
$(function($){
	$('#goBack').click(function(){
		var u = document.referrer;
		window.location.href = u ? u : '<?php echo $this->createUrl('purviewGroup/list'); ?>';
	});
});
</script>
