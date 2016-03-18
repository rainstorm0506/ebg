<style type="text/css">
	.form-wraper li > span{width:150px}
	#CompanyForm_user_type{width:auto}
	#CompanyForm_user_type label{margin:0 20px 0 0}
	#CompanyForm_source{width:auto}
	#CompanyForm_source label{margin:0 20px 0 0}
	
	.com-img .webuploader-container{float:left;position:relative;z-index:0;text-align:center;border:1px solid #ccc;height:120px;width:120px;margin-right:20px}
	.com-img .webuploader-container input{left:0;position:absolute;top:0;z-index:0;background-color:#FFF;opacity:0;height:100%;width:100%}
	.com-img .webuploader-container i{color:#e1e1e1;display:block;font-size:60px;margin-top:10px;height:60px;line-height:60px;font-style:normal}
	.com-img .webuploader-container p{color:#999;top:-10px}
	.com-img .webuploader-container img{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;margin:0}
	.com-img .webuploader-container b{position:absolute;top:55px;left:0;z-index:2;width:100%;font-weight:400; text-align:center}
	.com-img .webuploader-container b.uploading{top:0;z-index:99;height:100%;background-color:#FFF;display:block;line-height:120px}
	
	.com-img a.preview-close{color:red;top:0;right:0;line-height:20px;padding:0 5px;font-size:16px;position:absolute;z-index:2;display:inline-block;cursor:pointer}
	.com-img .preview-set span{z-index:998;background-color:#000;opacity:0.6;filter:alpha(opacity=60);}
	.com-img .preview-set a{z-index:999;color:#FFF}
	.com-img .preview-set a.this{color:#0F0}
</style><br/>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('company/list', array()); ?>">返回</a></span>
</div><br/>
<?php 
	Yii::app()->clientScript->registerCoreScript('webUploader');
?>
<fieldset class="public-wraper">
<legend></legend>
<h1 class="title"><?php echo $action != 'modify' ? '添加' : '编辑'; ?> 企业会员</h1>
<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
	<ul>
		<li>
			<span><em>*</em> 手机：</span>
			<?php
				if ($action == 'modify')
				{
					$form->phone = $user['phone'];
					echo $user['phone'];
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
			<span style="width:auto;color:red">* 以字母开头，长度在6~18之间，只能包含字符、数字和下划线 <?php echo $action == 'modify'?'(注：不修改密码则不填)':''; ?></span>
		</li>
		<?php if ($action == 'modify'): ?>
		<li>
			<span><em></em>会员推荐码：</span>
			<?php echo $user['user_code']; ?>
		</li>
		<li>
			<span><em></em>推荐人推荐码：</span>
			<?php echo empty($user['re_code']) ? '无' : $user['re_code']; ?>
		</li>
		<?php else: ?>
		<li>
			<span><em></em>推荐人推荐码：</span>
			<?php
				$form->re_code = empty($user['re_code'])?'':$user['re_code'];
				echo $active->textField($form , 're_code' , array('placeholder'=>'推荐您的人的推荐码', 'class'=>'textbox')).'</li>';
			?>
		</li>
		<?php endif; ?>
		<li>
			<span> 昵称：</span>
			<?php
				$form->nickname = $form->nickname ? $form->nickname : (isset($user['nickname'])?$user['nickname']:'');
				echo $active->textField($form , 'nickname' , array('class'=>'textbox'));
			?>
		</li>
		<?php $form->face = empty($form->face) ?(isset($user['face'])?$user['face']:'') : $form->face; ?>
		<li class="avatar">
			<span> 头像：</span>
			<aside class="com-img"><div id="imgFace" name="CompanyForm[face]"></div></aside>
		</li>
		<li>
			<span><em>*</em> 公司名称：</span>
			<?php
				$form->com_name = $form->com_name ? $form->com_name : (isset($info['com_name'])?$info['com_name']:'');
				echo $active->textField($form , 'com_name' , array('style' => 'width:30%' , 'class'=>'textbox'));
				echo "<em>".$active->error($form , 'com_name')."</em>";
			?>
		</li>
		<li>
			<span><em>*</em> 公司类型：</span>
			<?php
				$form->com_property = $form->com_property ? $form->com_property : (isset($info['com_property'])?$info['com_property']:'');
				echo $active->dropDownList($form,'com_property',$companyType,array('style' => 'width:10%' , 'class'=>'textbox','id'=>'com_property'));
				echo "<em>".$active->error($form , 'com_property')."</em>";
			?>
		</li>
		<li>
			<span><em>*</em> 公司地址：</span>
			<?php
				$form->dict_one_id = isset($form->dict_one_id) ? $form->dict_one_id : (isset($info['dict_one_id'])?$info['dict_one_id']:'');
				$form->dict_two_id = isset($form->dict_two_id) ? $form->dict_two_id : (isset($info['dict_two_id'])?$info['dict_two_id']:'');
				$form->dict_three_id = isset($form->dict_three_id) ? $form->dict_three_id : (isset($info['dict_three_id'])?$info['dict_three_id']:'');
				
				echo $active->dropDownList($form , 'dict_one_id' ,CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()), array('id'=>'dictOne','style' => 'width:10%', 'class'=>'textbox ajax-dict'));
				echo $active->dropDownList($form , 'dict_two_id' ,array(''=>' - 请选择 - '), array('id'=>'dictTwo','style' => 'width:10%', 'class'=>'textbox ajax-dict'));
				echo $active->dropDownList($form , 'dict_three_id' ,array(''=>' - 请选择 - '), array('id'=>'dictThree','style' => 'width:10%', 'class'=>'textbox'));
				echo "<em>".$active->error($form , 'dict_one_id')."</em>";
				echo "<em>".$active->error($form , 'dict_two_id')."</em>";
				echo "<em>".$active->error($form , 'dict_three_id')."</em>";
			?>
		</li>
		<li>
			<span><em>*</em> 详细地址：</span>
			<?php
				$form->com_address = isset($form->com_address) ? $form->com_address : (isset($info['com_address'])?$info['com_address']:'');
				echo $active->textField($form , 'com_address' , array('style' => 'width:30%' , 'class'=>'textbox'));
				echo "<em>".$active->error($form , 'com_address')."</em>";
			?>
		</li>
		<li>
			<span> 公司人数：</span>
			<?php
				$form->com_num = empty($form->com_num) ? (isset($info['com_num'])?$info['com_num']:'') : $form->com_num;
				echo $active->dropDownList($form,'com_num',$companyNumber,array('class'=>'textbox'));
				echo "<em>".$active->error($form , 'com_num')."</em>";
			?>
		</li>
		<?php $form->com_org = empty($form->com_org) ? (isset($info['com_org'])?$info['com_org']:'') : $form->com_org; ?>
		<li class="org">
			<span><em>*</em> 机构代码证：</span>
			<aside class="com-img">
				<div id="imgOrg" name="CompanyForm[com_org]"></div>
			</aside>
			<?php echo "<em>".$active->error($form , 'com_org')."</em>";?>
		</li>
		<?php $form->com_tax = empty($form->com_tax) ? (isset($info['com_tax'])?$info['com_tax']:'') : $form->com_tax; ?>
		<li class="tax">
			<span><em>*</em> 税务登记证：</span>
			<aside class="com-img">
				<div id="imgTax" name="CompanyForm[com_tax]"></div>
			</aside>
			<?php echo "<em>".$active->error($form , 'com_tax')."</em>";?>
		</li>
		<?php $form->com_license = empty($form->com_license) ? (isset($info['com_license'])?$info['com_license']:'') : $form->com_license; ?>
		<li class="licen">
			<span><em>*</em> 营业执照：</span>
			<aside class="com-img">
				<div id="imgLicen" name="CompanyForm[com_license]"></div>
			</aside>
			<?php echo "<em>".$active->error($form , 'com_license')."</em>";?>
		</li>
		<li>
			<span><em>*</em> 营业执照到期日期：</span>
			<?php
				$form->com_license_timeout = empty($form->com_license_timeout) ? (!empty($info['com_license_timeout'])?date('Y-m-d',$info['com_license_timeout']):'') : $form->com_license_timeout;
				$active->widget ( 'Laydate', array (
						'form'		=> $form,
						'id' 		=> 'com_license_timeout',
						'name'		=> 'com_license_timeout',
						'class'		=> "tbox38 tbox38-1",
						'style' 	=> 'width:200px',
						'dateFormat'=>'YYYY-MM-DD',
						'isTime'	=> false
				));
				if(!isset($info['com_license_timeout'])){
					echo "<em>".$active->error($form , 'com_license_timeout')."</em>";
				}
			?>
		</li>
		<li>
			<span>&nbsp;</span>
			<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
</fieldset>

<script type="text/javascript">
	var imgDomain	= '<?php echo Yii::app()->params['imgDomain']; ?>',
		com = {
			imgFace : '<?php echo $form->face; ?>',
			imgOrg : '<?php echo $form->com_org; ?>',
			imgTax : '<?php echo $form->com_tax; ?>',
			imgLicen : '<?php echo $form->com_license; ?>',
		},
		dicts = {
			'dictOne' : '<?php echo $form->dict_one_id; ?>' ,
			'dictTwo' : '<?php echo $form->dict_two_id; ?>' ,
			'dictThree' : '<?php echo $form->dict_three_id; ?>'
		};
	function selectReset(obj){obj.html('<option selected="selected" value=""> - 请选择 - </option>')}
	function selectvaluation(obj , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
		obj.html('<option value=""> - 请选择 - </option>' + code);
	}
	
	var __web_uploader = function(imgJson){
		var config = {
			pick: {id: '.com-img>div[name]',label: '<i>+</i><p>点击上传图片</p>'},
			// formData: {'width':500 , 'height':600},
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
		}).on('fileQueued', function(file){//当文件被加入队列以后触发 , 预览并上传
			var div = $('#rt_' + file.source.ruid);
			div.nextAll().remove();
			div.after('<b class="uploading">正在上传中...</b>');
	
			uploader.upload();
		}).on('uploadSuccess' , function(file , json)	{//当文件上传成功时触发
			var container = $('#rt_' + file.source.ruid).parent() , code = '' , name = container.attr('name');
			container.children(':hidden').remove();
			container.children('b').remove();
			container.children('img').remove();
	
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
	
		if (!$.isEmptyObject(imgJson)){
			var _key=null , _code = _name = '' , _obj=null;
			for (_key in imgJson){
				if (imgJson[_key]){
					_obj = $('#' + _key);
					_name = _obj.attr('name')||'';
					_obj.prepend('<input type="hidden" value="'+imgJson[_key]+'" name="'+_name+'">' +
						'<img src="'+imgDomain+imgJson[_key]+'"><a class="preview-close">x</a>');
				}
			}
		}
	};
	
	$(document).ready(function(){
		__web_uploader(com);
		
		//图片关闭
		$('.avatar,.tax,.org,.licen').on('click' , 'a.preview-close' , function() {
			var container = $(this).parent();
			container.children(':hidden').remove();
			container.children('b').remove();
			container.children('img').remove();
			container.children('div[class="preview-set"]').remove();
			container.children('a[class="preview-close"]').remove();
		});
		
		genArea($("#dictOne"));
		
		$("#append-form").on('change' , 'select.ajax-dict' , function(){
			genArea(this);
		});
	});
	function genArea(evt){
		var e = evt , id = $(e).attr('id') , val = $(e).val() , dict = {'dictOne':0 , 'dictTwo':0 , 'dictThree':0};
		selectReset($('#dictThree'));
		switch (id) {
			case 'dictOne' :
				selectReset($('#dictTwo'));
				dict.dictOne = val;
			break;
			case 'dictTwo' :
				dict.dictOne = $('#dictOne').val();
				dict.dictTwo = val;
			break;
		}

		$.getJSON('<?php echo $this->createUrl('company/dictChild'); ?>' , dict , function(json) {
			json = jsonFilterError(json);
			switch (id) {
				case 'dictOne' :
					if (val) {
						selectvaluation($('#dictTwo') , json , dicts.dictTwo);
						dicts.dictTwo > 0 && $('#dictTwo').change();
					}
				break;
				case 'dictTwo' :
					if (val){
						selectvaluation($('#dictThree') , json , dicts.dictThree);
						dicts.dictThree > 0 && $('#dictThree').change();
					}
				break;
			}
		});
	}
	
</script>