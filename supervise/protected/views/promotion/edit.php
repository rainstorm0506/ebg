<?php
Yii::app()->clientScript->registerCoreScript('webUploader');

$this->renderPartial('navigation');$typeInfo = $typeDataArr = array();
$goodsClassId = $selectAllData['goodsClassInfo'];
$typeDataArr = $selectAllData['typeDataArr'];
?>
<style type="text/css">
.webuploader-container{float:left;position:relative;z-index:0;text-align:center;border:1px solid #ccc;height:142px;width:142px;margin-right:20px}
.webuploader-container input{left:0;position:absolute;top:0;z-index:0;background-color:#FFF;opacity:0;height:100%;width:100%}
.webuploader-container i{color:#e1e1e1;display:block;font-size:60px;margin-top:20px;height:60px;line-height:60px;font-style:normal}
.webuploader-container p{color:#999;top:-10px}
.webuploader-container img{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;margin:0}
.webuploader-container b{position:absolute;top:55px;left:0;z-index:2;width:100%;font-weight:400; text-align:center}
.webuploader-container b.uploading{top:0;z-index:99;height:100%;background-color:#FFF;display:block;line-height:142px}
.webuploader-container a.preview-close{top:0;right:0;line-height:20px;padding:0 5px;font-size:16px;position:absolute;z-index:2;display:inline-block;cursor:pointer}
.selectRadio{margin-top:8px}
.errorMessage{color:red}
.images_size b{color:blue;}
</style>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'edit' ? '添加' : '编辑'; ?> 广告</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true,  'htmlOptions'=>array('class'=>'form-wraper','enctype'=>"multipart/form-data"))); ?>
		<ul>
			<li>
				<span><em>*</em> 广告名称：</span>
				<?php
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<span><em></em> 广告链接：</span>
				<?php
					$form->link = $form->link ? $form->link : (isset($info['link'])?$info['link']:'');
					echo $active->textField($form , 'link' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'link');
				?>
			</li>
			<li class="radios" style="height:28px;">
				<span><em>*</em>广告分类：</span>
				<input class="selectRadioType" value="1" style="margin-top:8px" <?php if(isset($info['class_one_id']) && $info['class_one_id']):?>checked="checked"<?php endif;?> name="PromotionForm[select_type]" type="radio"><span style="width:60px">商品分类</span>
				<input class="selectRadioType" value="0" style="margin-left:30px;margin-top:8px" <?php if(!isset($info['class_one_id']) || empty($info['class_one_id'])):?>checked="checked"<?php endif;?> name="PromotionForm[select_type]" type="radio"><span style="width:60px">其他位置</span>
			</li>
			<li>
				<span><em>*</em> 选择分类：</span>
				<?php
					$form->class_one_id = $form->class_one_id ? $form->class_one_id : (isset($info['class_one_id'])?$info['class_one_id']:'');
					echo $active->dropDownList($form , 'class_one_id' , $goodsClassId, array('style' => isset($info['class_one_id']) && $info['class_one_id'] ? 'width:10%':'width:10%;background-color:gray' , 'class'=>'textbox','disabled' => isset($info['class_one_id']) && $info['class_one_id'] ? false : true));
					echo $active->error($form , 'class_one_id');
				?>
				<span><em>*</em> 选择位置：</span>
				<?php
					$form->code_key_one = $form->code_key_one ? $form->code_key_one : (isset($info['code_key'])?$info['code_key']:'');
					$form->code_key_two = $form->code_key_two ? $form->code_key_two : (isset($info['code_key'])?$info['code_key']:'');
					echo $active->dropDownList($form , 'code_key_one' , isset($typeDataArr['classOne']) ? $typeDataArr['classOne'] : array(''=>'请选择') , array('id'=>'typeOne'));
					echo $active->dropDownList($form , 'code_key_two' , isset($typeDataArr['otherOne']) ? $typeDataArr['otherOne'] : array(''=>'请选择'), array('id'=>'typeTwo'));
				?>
			</li>
			<li class="imgx">
				<input type="hidden" id="sizeWidth" value="<?php echo isset($info['width']) ? $info['width'] : 2000;?>">
				<input type="hidden" id="sizeHeight" value="<?php echo isset($info['height']) ? $info['height'] : 365;?>">
				<span>广告图片：</span>
				<div id="image_url" class="_web_uploader" name="PromotionForm[image_url]"></div>
				<?php echo $active->error($form , 'image_url');?>
			</li>
			<li>
				
				<span style="color:red;width:130px">* 上传图片格式：</span>
				<div class="images_size"><b><?php echo isset($info['width']) ? $info['width'] : 2000;?></b> * <b><?php echo isset($info['height']) ? $info['height'] : 365;?></b> 像素</div>
			</li>
			</li>
			<li class='radios'>
				<span>是否显示：</span>
				<?php
					$form->image_url = $form->image_url ? $form->image_url : (isset($info['image_url'])?$info['image_url']:'');
				
					$form->is_show = $form->is_show ? $form->is_show : (isset($info['is_show'])?$info['is_show']:'1');
					echo $active->radioButton($form , 'is_show' , array('class'=>'selectRadio','value'=>1,'checked'=>'checked')).' <span style="width:10px">是</span>';
					echo $active->radioButton($form , 'is_show' , array('class'=>'selectRadio','value'=>0,'style'=>'margin-left:30px')).' <span style="width:10px">否</span>';
				?>
				<div class="hint"></div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ? '添加广告':'提交修改' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
var radio = <?php echo isset($info['is_show'])?$info['is_show']:0;?>;
var
comPicJSON = <?php echo json_encode(array('image_url' => $form->image_url)); ?>;
var imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';

function __web_uploader(imgJson)
{
	var config = {
			pick: {id: 'div._web_uploader[name]',label: '<i>+</i><p>点击上传图片</p>'},
			swf: 'Uploader.swf',
			chunked: false,
			chunkSize: 512 * 1024,
			formData:{'width':0 , 'height':0},
			accept: {title: 'Images',extensions: 'gif,jpg,jpeg,bmp,png',mimeTypes: 'image/*'},
			server: '<?php echo Yii::app()->params['imgUploadSrc']; ?>',
			preview : '<?php echo Yii::app()->params['imgPreviewSrc']; ?>',
			disableGlobalDnd: true,
			chunkSize:true,
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

		uploader.option('formData' , {'width':$('#sizeWidth').val() , 'height':$('#sizeHeight').val()});
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
		var i , _code = _name = '' , obj;
		for (key in imgJson)
		{
			if (imgJson[key])
			{
				obj = $('#' + key);
				_name = obj.attr('name')||'';
				obj.prepend('<input type="hidden" value="'+imgJson[key]+'" name="'+_name+'">' +
					'<div class="preview-set"><span></span></div>' +
					'<img src="'+imgDomain+imgJson[key]+'"><a class="preview-close">x</a>');
			}
		}
	}
}

$(document).ready(function(){
	__web_uploader(comPicJSON);
	
	$('.imgx')
	//图片关闭
	.on('click' , 'a.preview-close' , function(){
		var container = $(this).parent();
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();
	});
	
	$('.radios input[type=hidden]').val(radio);
	//是否显示
	$('.selectRadio').click(function(){
		$('.radios input[type=hidden]').val($(this).val());
	});
	
	$('.selectRadioType').click(function(){
		var selectName = $(this).val();
		if(selectName == 0){
			$('#typeOne').hide();$('#typeTwo').show();
			$('#PromotionForm_class_one_id').attr('disabled',true).css('background-color','gray');
		}else{
			$('#typeTwo').hide();$('#typeOne').show();
			$('#PromotionForm_class_one_id').attr('disabled',false).css('background-color','white');
		}
		$('.radios input[type=hidden]').val($(this).val());
	});
	//点击返回
	$('.goback').click(function(){
		history.go(-1);
	});
	//查询图片分类所限制的宽和高
	$('#typeOne,#typeTwo').change(function(){
		var codeKey = $(this).val(),sizeArr = {};
		$.ajax({
			url:"/supervise/promotion.getImageSize",
			type:"POST",
			async: false,
			data:{id:codeKey},
			success: function (data) {
				if(data){
					sizeArr = data.split('-');
					$('#sizeWidth').val(sizeArr[0]);
					$('#sizeHeight').val(sizeArr[1]);
					$('.images_size').find('b:eq(0)').html(sizeArr[0]);
					$('.images_size').find('b:eq(1)').html(sizeArr[1]);
				}else{
					return false;
				}
			}
		});	
	});
	<?php if ($form->class_one_id): ?>
	$('#typeOne').show();$('#typeTwo').hide();
	<?php else: ?>
	$('#typeOne').hide();$('#typeTwo').show();
	<?php endif; ?>
});
</script>
