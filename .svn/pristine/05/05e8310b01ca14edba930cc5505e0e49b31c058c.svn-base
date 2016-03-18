<?php
Views::css(array('register'));
Views::js(array('jquery.validate'));
Yii::app()->clientScript->registerCoreScript('webUploader');

if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
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
.promt , .promt.error{min-width:220px}
</style>
<main class="register-wraper">
	<aside><img src="<?php echo Views::imgShow('images/banner/register-ad.png'); ?>"></aside>
	<section>
		<h3 class="tit-1">我要开店</h3>
		<fieldset class="form-list form-list-1">
			<legend>我要开店</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('id'=>'companyNextForm')); ?>
				<ul>
					<li><?php echo $active->textField($form,'mer_name',array('id'=>'mer_name','class'=>'tbox38 tbox38-1','placeholder'=>'姓名')); ?></li>
					<li><?php echo $active->textField($form,'mer_card',array('id'=>'mer_card','class'=>'tbox38 tbox38-1','placeholder'=>'身份证号码')); ?></li>
					<li>
						<h6>手持身份证正面照：</h6>
						<div id="mer_card_front" class="_web_uploader" name="MerSignNextForm[mer_card_front]"></div>
						<article class="license-txt-list">
							<p>1、请上传手持身份证正面照；</p>
							<p>2、证件信息清晰可见，且不能被遮挡；</p>
							<p>3、仅支持JPG格式，图片大小不超过5M；</p>
							<a href="#">详细</a>
							<div class="js-box"></div>
						</article>
					</li>
					<li>
						<h6>手持身份证背面照：</h6>
						<div id="mer_card_back" class="_web_uploader" name="MerSignNextForm[mer_card_back]"></div>
						<article class="license-txt-list">
							<p>1、请上传手持身份证背面照；</p>
							<p>2、证件信息清晰可见，且不能被遮挡；</p>
							<p>3、仅支持JPG格式，图片大小不超过5M；</p>
							<a href="#">详细</a>
							<div class="js-box"></div>
						</article>
					</li>
					<li><?php echo CHtml::submitButton('等待审核' , array('class'=>'btn-1 btn-1-1')); ?></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
</main>
<script>
var comPicJSON = <?php echo json_encode(array(
		'mer_card_front'	=> $form->mer_card_front,
		'mer_card_back'		=> $form->mer_card_back,
	)); ?>,
	imgDomain = '<?php echo Yii::app()->params['imgDomain']; ?>';

$(document).ready(function()
{
	$('#companyNextForm').validate({
		rule : {
			mer_name : {
				required : '姓名不能为空',
				promt : '请输您的姓名'
			},
			mer_card : {
				required : '身份证号码不能为空',
				idcard : '身份证号码格式不正确',
				promt : '请输入您的身份证号码'
			}
		}
	});
	
	$('#companyNextForm')
	//图片关闭
	.on('click' , 'a.preview-close' , function(){
		var container = $(this).parent();
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();
	})
	
	function __web_uploader(imgJson)
	{
		var config = {
			pick: {id: 'div._web_uploader[name]',label: '<i>+</i><p>点击上传图片</p>'},
			swf: 'Uploader.swf',
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
			var i , _code = name = '' , obj;
			for (key in imgJson)
			{
				if (imgJson[key])
				{
					obj = $('#' + key);
					name = obj.attr('name')||'';
					obj.prepend('<input type="hidden" value="'+imgJson[key]+'" name="'+name+'">' +
						'<div class="preview-set"><span></span></div>' +
						'<img src="'+imgDomain+imgJson[key]+'"><a class="preview-close">x</a>');
				}
			}
		}
	}

	__web_uploader(comPicJSON);

	<?php if ($formError): ?>
	(function(){
		var formError = <?php echo json_encode($formError); ?> , code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError)
		{
			for (b in formError[a])
			{
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		layer.alert(code);
	})();
	<?php endif; ?>
});
</script>