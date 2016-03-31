<style type="text/css">
	.form-wraper li > span{width:150px}
	#UserForm_user_type{width:auto}
	#UserForm_user_type label{margin:0 20px 0 0}
	#UserForm_source{width:auto}
	#UserForm_source label{margin:0 20px 0 0}
	.user-img .webuploader-container{float:left;position:relative;z-index:0;text-align:center;border:1px solid #ccc;height:120px;width:120px;margin-right:20px}
	.user-img .webuploader-container input{left:0;position:absolute;top:0;z-index:0;background-color:#FFF;opacity:0;height:100%;width:100%}
	.user-img .webuploader-container i{color:#e1e1e1;display:block;font-size:60px;margin-top:10px;height:60px;line-height:60px;font-style:normal}
	.user-img .webuploader-container p{color:#999;top:-10px}
	.user-img .webuploader-container img{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;margin:0}
	.user-img .webuploader-container b{position:absolute;top:55px;left:0;z-index:2;width:100%;font-weight:400; text-align:center}
	.user-img .webuploader-container b.uploading{top:0;z-index:99;height:100%;background-color:#FFF;display:block;line-height:120px}
	
	.user-img a.preview-close{color:red;top:0;right:0;line-height:20px;padding:0 5px;font-size:16px;position:absolute;z-index:2;display:inline-block;cursor:pointer}
	.user-img .preview-set *{position:absolute;bottom:0;left:0;height:30px;line-height:30px;display:block;width:100%}
	.user-img .preview-set span{z-index:998;background-color:#000;opacity:0.6;filter:alpha(opacity=60);}
	.user-img .preview-set a{z-index:999;color:#FFF}
	.user-img .preview-set a.this{color:#0F0}
</style><br/>
<?php
	//Views::js(array('jquery-dragPlug','goods.create'));
	Yii::app()->clientScript->registerCoreScript('webUploader');
?>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('user/list', array()); ?>">返回</a></span>
</div><br/><br/>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo $action != 'modify' ? '添加' : '编辑'; ?> 会员</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 手机：</span>
				<?php
					if ($action == 'modify')
					{
						$form->phone = $info['phone'];
						echo $info['phone'];
					}else{
						$form->phone = $form->phone ? $form->phone : '';
						echo $active->textField($form , 'phone' , array('class'=>'textbox','maxlength'=>'11'));
						echo "<em>".$active->error($form , 'phone')."</em>";
					}
				?>
			</li>
			<li>
				<span><?php echo $action != 'modify' ? '<em>*</em>' : ''; ?> 密码：</span>
				<?php
					echo $active->passwordField($form , 'password' , array('class'=>'textbox','maxlength'=>'11'));
					echo "<em>".$active->error($form , 'password')."</em>";
				?>
				<span style="width:auto;color:red">* 以字母开头，长度在6~18之间，只能包含字符、数字和下划线 <?php echo $action == 'modify'?'(注：不修改密码则不填)':'';?></span>
			</li>
			<?php if ($action == 'modify'): ?>
			<li>
				<span><em></em>会员推荐码：</span>
				<?php echo $info['user_code']; ?>
			</li>
			<li>
				<span><em></em>推荐人推荐码：</span>
				<?php echo empty($info['re_code']) ? '无' : $info['re_code']; ?>
			</li>
			<?php else: ?>
			<li>
				<span><em></em>推荐人推荐码：</span>
				<?php
					$form->re_code = empty($info['re_code'])?'':$info['re_code'];
					echo $active->textField($form , 're_code' , array('placeholder'=>'推荐您的人的推荐码', 'class'=>'textbox')).'</li>';
				?>
			</li>
			<?php endif; ?>
			<li>
				<span>昵称：</span>
				<?php
					$form->nickname = $form->nickname ? $form->nickname : (isset($info['nickname'])?$info['nickname']:'');
					echo $active->textField($form , 'nickname' , array('class'=>'textbox'));
				?>
			</li>
			<?php $form->face = empty($form->face) ? (isset($info['face'])?$info['face']:'') : $form->face; ?>
			<li class="userPic">
				<span>头像：</span>
				<aside class="user-img"><div id="face" name="UserForm[face]"></div></aside>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script type="text/javascript">
	var imgDomain	= '<?php echo Yii::app()->params['imgDomain']; ?>';
	var imgFace		= {
		face : <?php echo json_encode($form->face); ?>
	}

	var __web_uploader = function(imgJson, imgSelect){
		var config = {
			pick: {id: '.user-img>div[name]',label: '<i>+</i><p>点击上传图片</p>'},
			//formData: {'width':500 , 'height':600},
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
		var isSupportBase64 = (function(){
			var data = new Image() , support = true;
			data.onload = data.onerror = function()	{
				if (this.width != 1 || this.height != 1)
					support = false;
			}
			data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
			return support;
		})();
	
		//-------------------------------事件绑定说明----------------------------------
		//	http://fex.baidu.com/webuploader/doc/index.html#WebUploader_Uploader_events
		var uploader = WebUploader.create(config);
		
		//当验证不通过时触发
		uploader.on('error', function(error){
			var code = '上传错误';
			switch (error){
				case 'Q_TYPE_DENIED'		: code = '文件类型不匹配'; break;
				case 'Q_EXCEED_NUM_LIMIT'	: 
				case 'Q_EXCEED_SIZE_LIMIT'	: code = '只能上传'+config.fileNumLimit+'个文件'; break;
				case 'F_EXCEED_SIZE'		: code = '只能上传5M以内的图片'; break;
				case 'F_DUPLICATE'			: code = '此文件已上传'; break;
			}
			alert(code);
		}).on('fileQueued', function(file){ //当文件被加入队列以后触发 , 预览并上传
			var div = $('#rt_' + file.source.ruid);
			div.nextAll().remove();
			div.after('<b class="uploading">正在上传中...</b>');
	
			uploader.upload();
		}).on('uploadSuccess', function(file , json){  //当文件上传成功时触发
			var container = $('#rt_' + file.source.ruid).parent() , code = '' , name = container.attr('name');
			container.children(':hidden').remove();
			container.children('b').remove();
			container.children('img').remove();
			container.children('a[class="preview-close"]').remove();
	
			if (json.error != 0){
				uploader.removeFile(file);
				alert(json.message);
				return false;
			}
			code = '<input type="hidden" name="'+name+'" value="'+json.src+'">';
			uploader.makeThumb(file, function(error , ret){
				if (error){
					code += '<b>预览错误</b>';
					container.prepend(code);
				}else{
					if (isSupportBase64){
						code += '<img src="'+ret+'"><a class="preview-close">x</a>';
						container.prepend(code);
						container.children('a.preview-close').click(function(){
							uploader.removeFile(file);
						});
					}else{
						$.ajax(config.preview , {method: 'POST', data: ret, dataType:'json'}).done(function(response){
							if (response.result){
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
			var _key=null , _code = _name = '' , _obj=null;
			for (_key in imgJson)
			{
				if (imgJson[_key])
				{
					_obj = $('#' + _key);
					_name = _obj.attr('name')||'';
					_obj.prepend('<input type="hidden" value="'+imgJson[_key]+'" name="'+_name+'">' +
						'<img src="'+imgDomain+imgJson[_key]+'"><a class="preview-close">x</a>');
				}
			}
		}
	};
	$(document).ready(function(){
		__web_uploader(imgFace);
		//图片关闭
		$('.userPic').on('click' , 'a.preview-close' , function() {
			var container = $(this).parent();
			container.children(':hidden').remove();
			container.children('b').remove();
			container.children('img').remove();
			container.children('div[class="preview-set"]').remove();
			container.children('a[class="preview-close"]').remove();
		});
	
	});
</script>