<?php
	$this->renderPartial('navigation');
	Yii::app()->clientScript->registerCoreScript('webUploader');
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style type="text/css">
#GoodsBrandForm_is_using{width:auto}
#GoodsBrandForm_is_using label{margin:0 20px 0 5px}

.webuploader-container{float:left;position:relative;z-index:0;text-align:center;border:1px solid #ccc;width:102px;height:36px;margin-right:20px}
.webuploader-container input{left:0;position:absolute;top:0;z-index:0;background-color:#FFF;opacity:0;height:100%;width:100%}
.webuploader-container p{color:#999;top:0;height:36px;line-height:36px}
.webuploader-container img{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;margin:0}
.webuploader-container b{position:absolute;top:5px;left:0;z-index:2;width:100%;font-weight:400; text-align:center}
.webuploader-container b.uploading{top:0;z-index:99;height:100%;background-color:#FFF;display:block;line-height:36px}
.webuploader-container a.preview-close{top:0;right:0;line-height:20px;padding:0 5px;font-size:16px;position:absolute;z-index:2;display:inline-block;cursor:pointer}
</style>
<fieldset class="public-wraper">
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 品牌</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span>全新商品分类：</span>
					<?php $form->goods_class = $form->goods_class ? $form->goods_class : array();?>
					<div style="overflow: hidden; width: 1200px;">
					<?php foreach($goods_class as $k => $v):?>
						<?php if(!empty($v['child'])):?>
						<p style="width: 100%; font-weight: 700;"><?php echo $v[0];?><span style="float: right; font-weight: 400; color: #999;" class="show_class" value="1">更多</span></p>
						<div style="height: 28px; overflow: hidden;">
							<ul style="overflow: hidden; width: 1200px;">
							<?php foreach($v['child'] as $k1 => $v1):?>
								<?php foreach($v1['child'] as $k2 => $v2):?>
									<li style="width: 300px; float: left; margin-top: 2px;">
										<input type="checkbox" style="margin-top: 8px;" name="GoodsBrandForm[goods_class][]"
											<?php echo in_array($k.'-'.$k1.'-'.$k2,$form->goods_class)?'checked':'';
													if(isset($info['class']))
														echo in_array(array('type'=>1,'class_three_id'=>$k2),$info['class'])?'checked':'';
											?>
											   value="<?php echo $k.'-'.$k1.'-'.$k2;?>"/>
										<span style="width: auto;">&nbsp;<?php echo $v1[0].'-'.$v2[0];?>&nbsp;&nbsp;</span>
									</li>
								<?php endforeach;?>
							<?php endforeach;?>
							</ul>
						</div>
					<?php endif;?>
					<?php endforeach;?>
				</div>
			</li>
			<li>
				<span>二手商品分类：</span>
				<div style="overflow: hidden; width: 1200px;">
					<?php $form->used_class = $form->used_class ? $form->used_class : array();?>
					<?php foreach($used_class as $k => $v):?>
						<?php if(!empty($v['child'])):?>
						<p style="float: left;width: 100%; font-weight: 700;"><?php echo $v[0];?><span style="float: right; font-size: 14px; font-weight: 400; color: #999;" class="show_class" value="1">更多</span></p>
						<div style=" width:1200px;height: 28px; overflow: hidden;">
							<ul style="overflow: hidden; width: 1200px;">
							<?php foreach($v['child'] as $k1 => $v1):?>
								<?php foreach($v1['child'] as $k2 => $v2):?>
									<li style="width: 300px; float: left;margin-top: 2px;">
										<input type="checkbox" style="margin-top: 8px;" name="GoodsBrandForm[used_class][]"
											<?php echo in_array($k.'-'.$k1.'-'.$k2,$form->used_class)?'checked':'';
												if(isset($info['class']))
													echo in_array(array('type'=>2,'class_three_id'=>$k2),$info['class'])?'checked':'';
											?>
											   value="<?php echo $k.'-'.$k1.'-'.$k2;?>"/>
										<span style="width: auto;">&nbsp;<?php echo $v1[0].'-'.$v2[0];?>&nbsp;&nbsp;</span>
									</li>
								<?php endforeach;?>
							<?php endforeach;?>
							</ul>
						</div>
						<?php endif;?>
						<br/>
					<?php endforeach;?>
				</div>
			</li>
			<li>
				<span>品牌中文名：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->zh_name = $form->zh_name ? $form->zh_name : (isset($info['zh_name'])?$info['zh_name']:'');
					echo $active->textField($form , 'zh_name' , array('class'=>'textbox' , 'style'=>'width:300px' , 'id'=>'zh_name'));
					echo $active->error($form , 'zh_name' , array('inputID'=>'zh_name'));
				?>
			</li>
			<li>
				<span>品牌英文名：</span>
				<?php
					$form->en_name = $form->en_name ? $form->en_name : (isset($info['en_name'])?$info['en_name']:'');
					echo $active->textField($form , 'en_name' , array('class'=>'textbox' , 'style'=>'width:300px' , 'id'=>'en_name'));
					echo $active->error($form , 'en_name' , array('inputID'=>'en_name'));
				?>
			</li>
			<li>
				<span>是否启用：</span>
				<?php
					$form->is_using = isset($form->is_using) ? (int)$form->is_using : (isset($info['is_using'])?(int)$info['is_using']:1);
					echo $active->radioButtonList($form , 'is_using' , array(1=>'启用' , 0=>'未启用') , array('separator' => ''));
					echo $active->error($form , 'is_using');
				?>
			</li>
			<li>
				<span><em>*</em> 排序 DESC：</span>
				<?php
					$form->rank = isset($form->rank) ? (int)$form->rank : (isset($info['rank'])?(int)$info['rank']:0);
					echo $active->textField($form , 'rank' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'rank');
				?>
				<div class="hint">注：按照从大到小排列，对于数字一样的排序，则谁先创建则谁在前面。</div>
			</li>
			<li class="logo_pic">
				<span>LOGO：</span>
				<div id="logo" class="_web_uploader" name="GoodsBrandForm[logo]"></div>
				<div class="hint">注：LOGO尺寸 102px * 36px。</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var
	comPicJSON = <?php echo json_encode(array('logo'=>empty($form->logo)?(isset($info['logo'])?$info['logo']:''):$form->logo)); ?>,
	formError = <?php echo json_encode($formError); ?>,
	imgDomain = '<?php echo Yii::app()->params['imgDomain']; ?>';
var __web_uploader = function(imgJson)
{
	var config = {
		pick: {id: 'div._web_uploader[name]',label: '<p>上传LOGO</p>'},
		swf: 'Uploader.swf',
		formData:{'width':102 , 'height':36},
		chunked: false,
		chunkSize: 512 * 1024,
		accept: {title: 'Images',extensions: 'gif,jpg,jpeg,bmp,png',mimeTypes: 'image/*'},
		server: '<?php echo Yii::app()->params['imgUploadSrc']; ?>',
		preview : '<?php echo Yii::app()->params['imgPreviewSrc']; ?>',
		disableGlobalDnd: true,
		fileSizeLimit: 5 * 1024 * 1024,			//验证文件总大小是否超出限制
		fileSingleSizeLimit: 5 * 1024 * 1024	//验证单个文件大小是否超出限制
	};

	//判断浏览器是否支持图片的base64
	var isSupportBase64 = (function()
	{
		var data = new Image() , support = true;
		data.onload = data.onerror = function()
		{
			if (this.width != 1 || this.height != 1)
				support = false;
		}
		data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
		return support;
	})();

	//-------------------------------事件绑定说明----------------------------------
	//	http://fex.baidu.com/webuploader/doc/index.html#WebUploader_Uploader_events
	var uploader = WebUploader.create(config);
	
	uploader
	//当验证不通过时触发
	.on('error', function(error)
	{
		var code = '上传错误';
		switch (error)
		{
			case 'Q_TYPE_DENIED'		: code = '文件类型不匹配'; break;
			case 'Q_EXCEED_NUM_LIMIT'	:
			case 'Q_EXCEED_SIZE_LIMIT'	: code = '只能上传'+config.fileNumLimit+'个文件'; break;
			case 'F_EXCEED_SIZE'		: code = '只能上传5M以内的图片'; break;
			case 'F_DUPLICATE'			: code = '此文件已上传'; break;
		}
		alert(code);
	})
	//当文件被加入队列以后触发 , 预览并上传
	.on('fileQueued', function(file)
	{
		var div = $('#rt_' + file.source.ruid);
		div.nextAll().remove();
		div.after('<b class="uploading">正在上传中...</b>');

		uploader.upload();
	})
	//当文件上传成功时触发
	.on('uploadSuccess' , function(file , json)
	{
		var container = $('#rt_' + file.source.ruid).parent() , code = '' , name = container.attr('name');
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();

		if (json.error != 0)
		{
			uploader.removeFile(file);
			alert(json.message);
			return false;
		}

		code = '<input type="hidden" name="'+name+'" value="'+json.src+'">';
		uploader.makeThumb(file, function(error , ret)
		{
			if (error)
			{
				code += '<b>预览错误</b>';
				container.prepend(code);
			}else{
				if (isSupportBase64)
				{
					code += '<img src="'+ret+'"><a class="preview-close">x</a>';
					container.prepend(code);

					container.children('a.preview-close').click(function(){
						uploader.removeFile(file);
					});
				}else{
					$.ajax(config.preview , {method: 'POST', data: ret, dataType:'json'}).done(function(response)
					{
						if (response.result)
						{
							code += '<img src="'+response.result+'"><a class="preview-close">x</a>';
							container.prepend(code);

							container.children('a.preview-close').click(function(){
								uploader.removeFile(file);
							});
						}else{
							code += '<b>预览出错</b>';
							container.prepend(code);
						}
					});
				}
			}
		});
	});

	if (!$.isEmptyObject(imgJson))
	{
		var _key = _obj = null , _code = _name = '';
		for (_key in imgJson)
		{
			if (imgJson[_key])
			{
				_obj = $('#' + _key);
				_name = _obj.attr('name')||'';
				_obj.prepend('<input type="hidden" value="'+imgJson[_key]+'" name="'+_name+'">' +
					'<div class="preview-set"><span></span></div>' +
					'<img src="'+imgDomain+imgJson[_key]+'"><a class="preview-close">x</a>');
			}
		}
	}
}

$(document).ready(function(){

	__web_uploader(comPicJSON);
	
	$('.logo_pic')
	//图片关闭
	.on('click' , 'a.preview-close' , function()
	{
		var container = $(this).parent();
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();
	});

	if (!$.isEmptyObject(formError))
	{
		var code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError)
		{
			for (b in formError[a])
			{
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		getLayer().alert(code);
	}
	//展开和收起分类
	$('.show_class').click(function(){
		var val=$(this).attr('value');
		if(val==1)
		{
			$(this).html('收起');
			$(this).attr('value',2)
			$(this).parent().next('div').css('height','auto');
		}
		if(val==2)
		{
			$(this).html('更多');
			$(this).attr('value',1)
			$(this).parent().next('div').css('height','28px');
		}
	})
});
</script>