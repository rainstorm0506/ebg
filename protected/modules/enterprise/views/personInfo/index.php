<?php 
Views::js(array('jquery.validate' , 'jquery.sendVerification','jquery-placeholderPlug','jquery-popClose'));
Yii::app()->clientScript->registerCoreScript('webUploader');
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
.images_size b{color:blue;}
.errorMessage{color:red}
#PersonForm_vxcode{width:107px}
#formBox ul li{margin-bottom: 13px;}
</style>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">企业信息
				<nav>
				<?php
					echo CHtml::link('设置新密码' , $this->createUrl('personInfo/showVerity'));
					echo CHtml::link('基本资料' , $this->createUrl('index'),array('class'=>'current'));
				?>
				</nav>
			</header>
			<fieldset class="form-list form-list-36 crbox18-group per-info-form">
				<legend>设置基本资料</legend>
				<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true,'action'=>$this->createUrl('edit'), 'htmlOptions'=>array('class'=>'form-wraper', 'onsubmit'=>'return checkSubmit();'))); ?>
					<ul>
						<input type="hidden" name="id" value="<?php echo $personData['id'];?>">
						<li class="p-txt-1">亲爱的 <?php echo $personData['nickname'];?>，填写真实的资料有助于朋友找到你哦！</li>
						<li class="imgx">
							<h6>当前头像：</h6>
							<div id="image_url" class="_web_uploader" name="User[image_url]"></div>
						</li>
						<li><h6> <i> * </i> 手机号：</h6><span><?php echo $personData['phone'];?></span><span class="ml20px"><a class="h-c-1" href="javascript:;" onclick="$('.pop-wrap-2,.mask').show();">修改</a></span></li>
						<li><h6> <i> * </i> 用户名：</h6>
							<input class="tbox34 tbox34-4" type="text" id="nickname" name="User[nickname]" value=" <?php echo $personData['nickname'];?>">
							<div class="errorMessage" style="display:none"> * 用户名不可为空白.</div>
						</li>
						<li><h6>公司地址：</h6>
							<select class="sbox36" id="provices" name="User_detail[dict_one_id]">
								<option selected>请选择省</option>
								<?php foreach ($cityData as $key => $val):?>
								<option <?php if($personData['dict_one_id'] == $val['id'])echo "selected='selected'";?> value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
								<?php endforeach;?>
							</select>
							<i class="mlr5px">省</i>
							<select class="sbox36" id="citys" name="User_detail[dict_two_id]">
								<?php if($personData['dict_two_id']):?>
								<option selected value="<?php echo $personData['dict_two_id'];?>"><?php echo $personData['dict_two_name'];?></option>
								<?php else:?>
								<option selected>请选择市</option>
								<?php endif;?>
							</select>
							<i class="mlr5px">市</i>
							<select class="sbox36" id="dicts" name="User_detail[dict_three_id]">
								<?php if($personData['dict_three_id']):?>
								<option selected value="<?php echo $personData['dict_three_id'];?>"><?php echo $personData['dict_three_name'];?></option>
								<?php else:?>
								<option selected>请选择区、县</option>
								<?php endif;?>
							</select>
							<i class="mlr5px">县</i>
						</li>
						<li><h6>详细地址：</h6><textarea name="User_detail[address]" ><?php if(isset($personData['com_address']))echo $personData['com_address'];?></textarea></li>
						<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="保存"></li>
					</ul>
				<?php $this->endWidget(); ?>
			</fieldset>
		</section>
	</main>

	<!-- 修改手机号弹出框 -->
	<section class="pop-wrap pop-wrap-2" style="height:300px;display: none" >
		<header><h3>更换手机号</h3><a id="close" href="javascript:;" onclick="$('.pop-wrap-2,.mask').hide();"></a></header>
		<fieldset class="form-list form-change-tel" id="formBox">
			<legend>更换手机号</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('personInfo/editPhone',array('s'=>'member')))); ?>
				<div class="promt error msg promt-1" id="promt">请输入真实姓名</div>
				<ul>
					<li><?php echo $active->textField($form,'phone',array('id'=>'tel','class'=>'tbox38 tbox38-1','placeholder'=>'请输入新手机号','autocomplete'=>'off')); ?></li>
					<li class="code-verify">
					<?php
						echo $active->textField($form , 'vxcode' , array('ags'=>'venterprise','class'=>'tbox38 tbox38-2','placeholder'=>'请输入图形验证码','maxlength'=>6,'style'=>'height:40px'));
						echo '<img ags="venterprise" class="svcode">';
					?>
					</li>
					<li>
						<?php
							$_disabled = true;
							echo $active->textField($form,'codeNum',array('id'=>'codeNum','class'=>'tbox38 tbox38-2','disabled'=>$_disabled,'placeholder'=>'短信验证码','autocomplete'=>'off'));
						?>
						<a class="btn-2" id="sendBtn" href="javascript:;">获取短信验证码</a><input type="hidden" name="type" value="venterprise" />
					</li>
					<li><?php echo CHtml::submitButton('保存' , array('class'=>'btn-1 btn-1-3','id'=>'enterpriseSubmit')); ?><input id="reset" class="btn-1 btn-1-4" type="reset" value="取消" onclick="$('.pop-wrap-2,.mask').hide();"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<div class="mask" style="display: none"></div>
	<script>
	var _send_permissions = {'find':true} , _code_permissions = {'find':null};
	$(function($){
		// ================================== 焦点特效
		$('#tel').placeholderPlug();
		$('#codeNum').placeholderPlug();
		// ================================== 个人注册表单验证
		$.validate.reg('repeat',function(val){
			return val === $('#password').val();
		});
	
		var $form = $('#formBox');
		$form.validate({
			rule : {
				tel : {
					required : '请输入11位正确的手机号码',
					  mobile : '手机号码不合法'
				},
				codeNum : {
					required : '验证码不能为空',
				}
			},
			site : 'one',
			 way : 'one',
			focus : true,
			submit : function(){
				var tel = $('#tel').val(),flag = false;
				var codeNum = $('#codeNum').val();
				$.ajax({
					url:"<?php echo $this->createUrl('personInfo/checkPhone'); ?>",
					type:"POST",
					async: false,
					data:{tel:tel,codeNum:codeNum,type:'venterprise'},
					success: function (data) {
						if(data == 1){
							flag = true;
						}else{
							$('#promt').html(data).show();
							flag = false;
						}
					}
				});
				return flag;
			},
		});
		// ================================== 重置表单
		function reset(){
			$form.find('.promt').hide();
			$form.find('*').removeClass('error-input')
		}
		$('#reset').click(function(){
			reset();
		});
	});
	$(document).ready(function() { 
		//图形验证码
		$('.svcode').click(function(){
			$(this).attr('src' , '<?php echo $this->createFrontUrl('asyn/getVcdoe'); ?>?type=venterprise&_x='+Math.random());
		}).click();

		//验证码
		$('.code-verify>:text[ags]')
		.change(function(){
			var ex = this , _v = $(ex).val();
			$(ex).nextAll('span,q').remove();
			if ($.trim(_v) == '')
			{
				$(ex).next().after('<q class="promt error msg no-sms">请输入验证码</q>');
			}else{
				$.getJSON('<?php echo $this->createFrontUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':'venterprise'} , function(json){
					if (json.code !== 0)
					{
						_code_permissions.find = false;
						$('#promt').stop(true,false).fadeIn().text(json.message);
					}else{
						_code_permissions.find = true;
						$('#promt').fadeOut()
						$(ex).next().after('<q class="success"></q>');
					}
				});
			}
		});
		// 选择生日年份后触发
		$("#birthdayYear").change(function() {
			changeSelectBrithdayDay();
		});
		
		// 选择生日月份后触发
		$("#birthdayMonth").change(function() {
			changeSelectBrithdayDay();
		});
		//选择地区--省
		$('#provices,#citys').change(function(index,item){
			var dict_id = $(this).val(),selectStr = '';
			if(dict_id)
			{
				var types = $(this).attr('id') == 'provices'?1:0;
				$.ajax({
					url:"<?php echo $this->createUrl('personInfo/getDictList'); ?>",
					type:"POST",
					async: false,
					data:{dictid:dict_id,type:types},
					success: function (data) {
						if(data){
							if(types == 1){
								selectStr = "<option selected>请选择市</option>";
								$('#citys').html(selectStr+data);
							}else{
								selectStr = "<option selected>请选择区、县</option>";
								$('#dicts').html(selectStr+data);
							}
							return true;
						}else{
							alert("错误！");
							return false;
						}
					}
				});	
			}
		});
		//验证短信 - 个人
		$('#sendBtn').sendVerification({tel:'#tel' , 'stype':'find', site:'one','callback':function(self){
			var phone = $('#tel').val()||'';
			$(self).nextAll('span,q').remove();
			if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
			{
				$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
				return false;
			}
			//send sms
			$.getJSON('<?php echo $this->createFrontUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'venterprise'} , function(json){
// 				if (json.code !== 0)
// 				{
// 					$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
// 				}else{
// 					$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
// 				}
			});
		}});
		//上传头像
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

	});


	//保存信息判断提示
	function checkSubmit(){
		var nickname = $('#nickname').val().trim();
		if(nickname == ''){
			$('#nickname').next().show();
			$('#nickname').focus();
			return false;
		}
		alert('提交成功！');
		return true;
	}

	// 根据所选择的年份、月份计算月最大天数,并重新填充生日下拉框中的日期项
	function changeSelectBrithdayDay() {
	
		var maxNum;
		var month = $("#birthdayMonth").val();
		var year = $("#birthdayYear").val();
		//选择生日初始化
		var originalBirthdayDay=1;
				
		if (year == 0) { // 如果年份没有选择，则按照闰年计算日期(借用2004年为闰年)
			year = 2004;
		}
		if (month == 0) {
			maxNum = 31;
		} else if (month == 2) {
			if (year.toString().substring(2) == "后") { // 判断年份是否为模糊年份
														// 如果是模糊年份则天数设为29
				maxNum = 29;
			} else {
				if (year % 400 == 0 || (year % 4 == 0 && year % 100 != 0)) { // 判断闰年
					maxNum = 29;
				} else {
					maxNum = 28;
				}
			}
		} else if (month == 4 || month == 6 || month == 9 || month == 11) {
			maxNum = 30;
		} else {
			maxNum = 31;
		}
	
		// 清空日期的下拉框 进行重新添加选项
		$("#birthdayDay").empty();
		if (month == 0) {
			$("<option value='0' selected='selected'>请选择：</option>")
					.appendTo("#birthdayDay");
		} else {
			for (var startDay = 1; startDay <= maxNum; startDay++) {
				$("<option value='" + startDay + "'>" + startDay + "</option>")
						.appendTo("#birthdayDay");
			}
			if (maxNum >= originalBirthdayDay) {
				setTimeout(function() {
					$("#birthdayDay").val(originalBirthdayDay);
				}, 1);
			} else {
				setTimeout(function() {
					$("#birthdayDay").val(1);
				}, 1);
				originalBirthdayDay = 1;
			}
		}
	}

	var
	comPicJSON = <?php echo json_encode(array('image_url' => $personData['face'])); ?>;
	var imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';
	
	function __web_uploader(imgJson)
	{
		var config = {
				pick: {id: 'div._web_uploader[name]',label: '<i>+</i><p>点击上传图片</p>'},
				swf: 'Uploader.swf',
				chunked: false,
				chunkSize: 512 * 1024,
				formData:{'width':0 , 'height':0},
				accept: {title: 'Images',extensions: 'gif,jpg,jpeg,bmp,png,docx',mimeTypes: 'image/*'},
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
</script>

