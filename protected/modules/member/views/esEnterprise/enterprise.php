<?php
Views::css(array('register'));
Views::js(array('jquery.validate' , 'jquery.sendVerification'));
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
#dictTwoId{margin:0 10px}
#companyAddress{margin:10px 0 0 110px;width:460px}
/*
#com_license{background-image:url(<?php echo Views::imgShow('images/banner/license-1.png'); ?>);width:104px;height:142px;margin:0 auto}
#com_tax,#com_org{background-image:url(<?php echo Views::imgShow('images/banner/license-2.png'); ?>);width:142px;height:101px;margin:0 auto}
#com_org{background-image:url(<?php echo Views::imgShow('images/banner/license-3.png'); ?>)}
*/
</style>
<main class="register-wraper">
	<aside><img src="<?php echo Views::imgShow('images/banner/register-ad.png'); ?>"></aside>
	<section>
		<h3 class="tit-1">升级到企业用户</h3>
		<fieldset class="form-list">
			<legend>升级到企业用户</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('id'=>'comanyRegister')); ?>
				<ul>
					<li>
						<h6>公司名称：</h6>
						<?php echo $active->textField($form,'companyName',array('id'=>'companyName','class'=>'tbox38 tbox38-1','placeholder'=>'公司名称')); ?>
					</li>
					<li>
						<h6>公司地址：</h6>
						<?php
							$form->dictOneId = isset($form->dictOneId) ? (int)$form->dictOneId : 0;
							$form->dictTwoId = isset($form->dictTwoId) ? (int)$form->dictTwoId : 0;
							$form->dictThreeId = isset($form->dictThreeId) ? (int)$form->dictThreeId : 0;

							echo $active->dropDownList($form , 'dictOneId' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'dictOneId','class'=>'sbox40 ajax-dict'));
							echo $active->dropDownList($form , 'dictTwoId' , array(''=>' - 请选择 - ') , array('id'=>'dictTwoId','class'=>'sbox40 ajax-dict'));
							echo $active->dropDownList($form , 'dictThreeId' , array(''=>' - 请选择 - ') , array('id'=>'dictThreeId' , 'class'=>'sbox40'));

							echo $active->textField($form,'companyAddress',array('id'=>'companyAddress','class'=>'tbox38 tbox38-1','placeholder'=>'公司详细地址'));
						?>
					</li>
					<li>
						<h6>公司人数：</h6>
						<?php echo $active->dropDownList($form,'companyNumber',$companyNumber,array('class'=>'sbox40','id'=>'companyNumber')); ?>
					</li>
					<li>
						<h6>公司类型：</h6>
						<?php echo $active->dropDownList($form,'companyType',$companyType,array('class'=>'sbox40','id'=>'companyType')); ?>
					</li>
					<li>
						<h6>营业执照：</h6>
						<div id="com_license" class="_web_uploader" name="EsEnterpriseForm[com_license]"></div>
						<article class="license-txt-list">
							<p>1、请上传公司营业执照复印件照片并盖公司公章；</p>
							<p>2、证件信息清晰可见，且不能被遮挡；</p>
							<p>3、仅支持JPG格式，图片大小不超过5M；</p>
							<a href="#">详细</a>
							<div class="js-box"></div>
						</article>
					</li>
					<li>
						<h6>执照到期时间：</h6>
						<?php
							$active->widget(
								'Laydate' ,
								array(
									'form'			=> $form ,
									'name'			=> 'expireTime' ,
									'id'			=> 'expireTime' ,
									'class'			=> 'tbox38 tbox38-1',
									'placeholder'	=> '营业执照到期时间',
									'isTime'		=> false,
									'style'			=> 'width:228px'
								)
							);
						?>
					</li>
					<li>
						<h6>税务登记证：</h6>
						<div id="com_tax" class="_web_uploader" name="EsEnterpriseForm[com_tax]"></div>
						<article class="license-txt-list">
							<p>1、请上传公司税务登记证照片并盖公司公章；</p>
							<p>2、证件信息清晰可见，且不能被遮挡；</p>
							<p>3、仅支持JPG格式，图片大小不超过5M；</p>
							<a href="#">详细</a>
							<div class="js-box"></div>
						</article>
					</li>
					<li>
						<h6>组织机构代码：</h6>
						<div id="com_org" class="_web_uploader" name="EsEnterpriseForm[com_org]"></div>
						<article class="license-txt-list">
							<p>1、请上传公司组织机构代码照片并盖公司公章；</p>
							<p>2、证件信息清晰可见，且不能被遮挡；</p>
							<p>3、仅支持JPG格式，图片大小不超过5M；</p>
							<a href="#">详细</a>
							<div class="js-box"></div>
						</article>
					</li>
					<li><?php echo CHtml::submitButton('等待审核企业账号' , array('class'=>'btn-1 btn-1-1')); ?></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
</main>

<script>
var
	dictOld = {
		'dictOneId' : <?php echo $form->dictOneId; ?> ,
		'dictTwoId' : <?php echo $form->dictTwoId; ?> ,
		'dictThreeId' : <?php echo $form->dictThreeId; ?>
	},
	comPicJSON = <?php echo json_encode(array(
		'com_license'	=> $form->com_license,
		'com_tax'		=> $form->com_tax,
		'com_org'		=> $form->com_org,
	)); ?>,
	imgDomain = '<?php echo Yii::app()->params['imgDomain']; ?>';

function jsonFilterError(json)
{
	if (json.code == 0)
		return json.data;
	else
		alert(json.message);
}
function selectReset(obj){obj.html('<option selected="selected" value=""> - 请选择 - </option>')}
function selectvaluation(obj , json , child_id)
{
	var code = i = '';
	for (i in json)
		code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
	obj.html('<option value=""> - 请选择 - </option>' + code);
}

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

$(document).ready(function(){
	$('#comanyRegister').validate({
		rule : {
			companyName : {
				required : '公司名称不能为空',
				promt : '请输入公司的名称'
			},
			companyNumber : {
				required : '请选择公司人数',
				msg : '请选择公司人数'
			},
			companyType : {
				required : '请选择公司类型',
				msg : '请选择公司类型'
			},
			expireTime : {
				required: '请输入营业执照到期时间',
				timeFormat : '时间格式形如：2015-10-1',
				promt : '时间格式形如：2015-10-1'
			}
		},
		submit:function(){
					var flag = confirm("温馨提示：申请提交后该登录账号将会进入审核阶段，审核期间不能进行登录操作，审核通过后方能进行登录操作！确认提交该申请？");
					return flag;
				}
	});

	__web_uploader(comPicJSON);

	$('#comanyRegister')
	.on('mouseover' , '._web_uploader' , function()
	{
		
	})
	.on('mouseout' , '._web_uploader' , function()
	{
		
	})
	//图片关闭
	.on('click' , 'a.preview-close' , function(){
		var container = $(this).parent();
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();
	})
	//区域选择
	.on('change' , 'select.ajax-dict' , function()
	{
		var e = this , id = $(e).attr('id') , val = $(e).val() , dict = {'dictOneId':0 , 'dictTwoId':0 , 'dictThreeId':0};
		selectReset($('#dictThreeId'));
		switch (id)
		{
			case 'dictOneId' :
				selectReset($('#dictTwoId'));
				dict.dictOneId = val;
			break;
			case 'dictTwoId' :
				dict.dictOneId = $('#dictOneId').val();
				dict.dictTwoId = val;
			break;
		}

		$.getJSON('<?php echo $this->createFrontUrl('asyn/dictChild'); ?>' , dict , function(json)
		{
			json = jsonFilterError(json);
			switch (id)
			{
				case 'dictOneId' :
					if (val)
					{
						selectvaluation($('#dictTwoId') , json , dictOld.dictTwoId);
						dictOld.dictTwoId > 0 && $('#dictTwoId').change();
					}
				break;
				case 'dictTwoId' :
					if (val)
					{
						selectvaluation($('#dictThreeId') , json , dictOld.dictThreeId);
						dictOld.dictThreeId > 0 && $('#dictThreeId').change();
					}
				break;
			}
		});
	});

	if (dictOld.dictOneId > 0)
		$('#dictOneId').change();
	
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