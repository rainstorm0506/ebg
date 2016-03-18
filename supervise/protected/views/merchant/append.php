<style type="text/css">
	.form-wraper li > span{width:150px}
	#MerchantForm_user_type{width:auto}
	#MerchantForm_user_type label{margin:0 20px 0 0}
	#MerchantForm_mer_ensure_is_pay{width:auto}
	#MerchantForm_mer_ensure_is_pay label{margin:0 20px 0 0}
	#MerchantForm_is_self{width:auto}
	#MerchantForm_is_self label{margin:0 20px 0 0}
	#MerchantForm_mer_settle_day{width:auto}
	#MerchantForm_mer_settle_day label{margin:0 20px 0 0}
	
	.scope{width:80%; margin:10px 10px 20px 10px}
	.scope dd{height:40px;line-height:40px;overflow:hidden; border-bottom:1px dotted #CCC;}
	.scope dt .errorMessage{margin:10px 0 0 0}
	.scope label{display:inline-block;width:400px;margin:0}
	.scope label input{margin:5px}
	.scope a{margin:0 10px;color:#2B22FC}
	
	.mer-img .webuploader-container{float:left;position:relative;z-index:0;text-align:center;border:1px solid #ccc;height:120px;width:120px;margin-right:20px}
	.mer-img .webuploader-container input{left:0;position:absolute;top:0;z-index:0;background-color:#FFF;opacity:0;height:100%;width:100%}
	.mer-img .webuploader-container i{color:#e1e1e1;display:block;font-size:60px;margin-top:10px;height:60px;line-height:60px;font-style:normal}
	.mer-img .webuploader-container p{color:#999;top:-10px}
	.mer-img .webuploader-container img{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;margin:0}
	.mer-img .webuploader-container b{position:absolute;top:55px;left:0;z-index:2;width:100%;font-weight:400; text-align:center}
	.mer-img .webuploader-container b.uploading{top:0;z-index:99;height:100%;background-color:#FFF;display:block;line-height:120px}
	
	.mer-img a.preview-close{color:red;top:0;right:0;line-height:20px;padding:0 5px;font-size:16px;position:absolute;z-index:2;display:inline-block;cursor:pointer}
	.mer-img .preview-set *{position:absolute;bottom:0;left:0;height:30px;line-height:30px;display:block;width:100%}
	.mer-img .preview-set span{z-index:998;background-color:#000;opacity:0.6;filter:alpha(opacity=60);}
	.mer-img .preview-set a{z-index:999;color:#FFF}
	.mer-img .preview-set a.this{color:#0F0}
</style><br/>
<?php 
	Yii::app()->clientScript->registerCoreScript('webUploader');
?>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('merchant/list', array()); ?>">返回</a></span>
</div><br/>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo $action != 'modify' ? '添加' : '编辑'; ?> 商家会员</h1>
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
			<?php $form->store_avatar = $form->store_avatar ? $form->store_avatar : (isset($info['store_avatar'])?$info['store_avatar']:''); ?>
			<li class="storeAvat">
				<span> 店铺头像：</span>
				<aside class="mer-img"><div id="imgAvat" name="MerchantForm[store_avatar]"></div></aside>
			</li>
			<li>
				<span><em>*</em> 营业范围：</span>
				<?php
					if ($scopes) {
						echo '<dl class="scope">';
						foreach ($scopes as $val) {
							$form->scope[$val['id']] = isset($form->scope[$val['id']]) ? $form->scope[$val['id']] : (isset($sco) && in_array($val['id'] , $sco));
							echo '<dd><label>'.$active->checkBox($form , 'scope['.$val['id'].']' , array('separator' => '','value'=>$val['id'])).$val['title'].'</label>'.$val['describe'].'</dd>';
						}
						echo '<dt><div>注：至少选择一个营业范围。</div>'.$active->error($form , 'scope').'</dt></dl>';
					} else {
						echo '当前没有营业范围, '.CHtml::link('点击营业范围' , $this->createUrl('scope/create'));
					}
				?>
			</li>
			<li>
				<span><em>*</em> 店铺名称：</span>
				<?php
					$form->store_name = $form->store_name ? $form->store_name : (isset($info['store_name'])?$info['store_name']:'');
					echo $active->textField($form , 'store_name' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'store_name')."</em>";
				?>
			</li>
            <li>
                <span><em>*</em> 店铺编号：</span>
                <ul>
                    <?php if($computer):?>
                    <li style="float: left; margin-right: 20px;">
                    <?php
                        $form->computer = isset($form->computer) ? (int)$form->computer : (isset($info['gather_value'][0])?(int)$info['gather_value'][0]:0);
                        echo $active->dropDownList($form , 'computer' , CMap::mergeArray(array(''=>' - 请选择 - '), $computer) , array('id'=>'computer','class'=>'sbox36'));
                        echo $active->error($form , 'computer');
                    ?>
                    </li>
                    <li style="float: left; margin-right: 20px; margin-top: 0px;">
                    <?php
                        $form->storey = isset($form->storey) ? (int)$form->storey : (isset($info['gather_value'][1])?(int)$info['gather_value'][1]:0);
                        echo $active->dropDownList($form , 'storey' ,CMap::mergeArray(array(''=>' - 请选择 - '), isset($storey)?$storey:array()) , array('id'=>'storey','class'=>'sbox36'));
                        echo $active->error($form , 'computer');
                    ?>
                    </li>
                    <li style="float: left; margin-right: 20px; margin-top: 0px;">
                        <?php
                        $form->store_num = isset($form->store_num) ? (int)$form->store_num : (isset($info['gather_value'][2])?(int)$info['gather_value'][2]:0);
                        echo $active->dropDownList($form , 'store_num' ,CMap::mergeArray(array(''=>' - 请选择 - '), isset($mer_store)?$mer_store:array()) , array('id'=>'store_num','class'=>'sbox36'));
                        ?>
                    </li>
                    <?php endif;?>
                    <?php
                        if(empty($computer)){
                            echo CHtml::link('当前没有电脑城 , 点击创建电脑城' , $this->createUrl('gather/create'));
                        }
                    ?>
                </ul>
            </li>
			<li>
				<span><em>*</em> 商家姓名：</span>
				<?php
					$form->mer_name = $form->mer_name ? $form->mer_name : (isset($info['mer_name'])?$info['mer_name']:'');
					echo $active->textField($form , 'mer_name' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'mer_name')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 商家身份证号：</span>
				<?php
					$form->mer_card = isset($form->mer_card) ? $form->mer_card : (isset($info['mer_card'])?$info['mer_card']:'');
					echo $active->textField($form , 'mer_card' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'mer_card')."</em>";
				?>
			</li>
			<?php
				$form->mer_card_front = $form->mer_card_front ? $form->mer_card_front : (isset($info['mer_card_front'])?$info['mer_card_front']:'');
			?>
			<li class="idfront">
				<span><em>*</em> 身份证(正面)：</span>
				<aside class="mer-img">
					<div id="imgFront" name="MerchantForm[mer_card_front]"></div>
				</aside>
				<?php
					echo "<em>".$active->error($form , 'mer_card_front')."</em>";
				?>
			</li>
			<?php
				$form->mer_card_back = $form->mer_card_back ? $form->mer_card_back : (isset($info['mer_card_back'])?$info['mer_card_back']:'');
			?>
			<li class="idback">
				<span><em>*</em> 身份证(背面)：</span>
				<aside class="mer-img">
					<div id="imgBack" name="MerchantForm[mer_card_back]"></div>
				</aside>
				<?php
					echo "<em>".$active->error($form , 'mer_card_back')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 商家结账期：</span>
				<?php
					$form->mer_settle_day = $form->mer_settle_day ? $form->mer_settle_day : (isset($info['mer_settle_day'])?$info['mer_settle_day']:15);
					echo $active->textField($form , 'mer_settle_day' , array('style' => 'width:10%' , 'class'=>'textbox')).'天';
					echo "<em>".$active->error($form , 'mer_settle_day')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 商家保证金：</span>
				<?php
					$form->mer_ensure_money = $form->mer_ensure_money ? $form->mer_ensure_money : (isset($info['mer_ensure_money'])?$info['mer_ensure_money']:'');
					echo $active->textField($form , 'mer_ensure_money' , array('style' => 'width:10%' , 'class'=>'textbox')).'元';
					echo "<em>".$active->error($form , 'mer_ensure_money')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 商家扣点：</span>
				<?php
					$form->mer_take_point = $form->mer_take_point ? $form->mer_take_point : (isset($info['mer_take_point'])?$info['mer_take_point']:'');
					echo $active->textField($form , 'mer_take_point' , array('style' => 'width:10%' , 'class'=>'textbox')).'%';
					echo "<em>".$active->error($form , 'mer_take_point')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 保证金是否支付：</span>
				<?php
					$form->mer_ensure_is_pay = $form->mer_ensure_is_pay ? $form->mer_ensure_is_pay : 1;//(isset($info['mer_ensure_is_pay'])?$info['mer_ensure_is_pay']:1);
					echo $active->radioButtonList($form , 'mer_ensure_is_pay' , array(1=>'是') , array('separator'=>''));
					echo "<em>".$active->error($form , 'mer_ensure_is_pay')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 是否自营：</span>
				<?php
					$form->is_self = $form->is_self ? $form->is_self : (isset($info['is_self'])?$info['is_self']:0);
					echo $active->radioButtonList($form , 'is_self' , array(0=>'非自营', 1=>'自营') , array('separator'=>''));
					echo "<em>".$active->error($form , 'is_self')."</em>";
				?>
			</li>
			<li>
				<span> 通信QQ：</span>
				<?php
					$form->store_join_qq = $form->store_join_qq ? $form->store_join_qq : (isset($info['store_join_qq'])?$info['store_join_qq']:'');
					echo $active->textField($form , 'store_join_qq' , array('style' => 'width:10%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'store_join_qq')."</em>";
				?>
			</li>
			<li>
				<span> 商家评分：</span>
				<?php
				$form->store_grade = $form->store_grade ? $form->store_grade : (isset($info['store_grade'])?$info['store_grade']:'');
				echo $active->textField($form , 'store_grade' , array('style' => 'width:10%' , 'class'=>'textbox int-price'));
				echo "<em>".$active->error($form , 'store_grade')."</em>";
				?>
				<span style="width:auto;color:red">注：评分范围1-5（5代表好，1代表差）</span>
			</li>
            <li>
                <span> 开始经营年份：</span>
                <?php
                $form->bus_start_year = $form->bus_start_year ? $form->bus_start_year : (isset($info['bus_start_year'])?$info['bus_start_year']:0);
                echo $active->textField($form , 'bus_start_year' , array('style' => 'width:10%' , 'class'=>'textbox int-price'));
                echo "<em>".$active->error($form , 'bus_start_year')."</em>";
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
		mer={
			imgAvat		: '<?php echo $form->store_avatar; ?>',
			imgFront	: '<?php echo $form->mer_card_front; ?>',
			imgBack		: '<?php echo $form->mer_card_back; ?>'
		};
	
	var __web_uploader = function(imgJson , imgSelect){
		var config = {
			pick: {id: '.mer-img>div[name]',label: '<i>+</i><p>点击上传图片</p>'},
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
	
		if (!$.isEmptyObject(imgJson)) {
			var _key=null , _code = _name = '' , _obj=null;
			for (_key in imgJson) {
				if (imgJson[_key]) {
					_obj = $('#'+_key);
					_name = _obj.attr('name')||'';
					_obj.prepend('<input type="hidden" value="'+imgJson[_key]+'" name="'+_name+'">' +
						'<img src="'+imgDomain+imgJson[_key]+'"><a class="preview-close">x</a>');
				}
			}
		}
	};
	$(document).ready(function(){
		__web_uploader(mer);
		
		//图片关闭
		$('.avatar,.storeAvat,.idfront,.idback').on('click' , 'a.preview-close' , function() {
			var container = $(this).parent();
			container.children(':hidden').remove();
			container.children('b').remove();
			container.children('img').remove();
			container.children('div[class="preview-set"]').remove();
			container.children('a[class="preview-close"]').remove();
		});
		
	});
    //店铺选择
	var store = {
		'computer'	: 0 ,
		'storey'    : 0 ,
		'store_num'    : 0
	} , storeOld = {
		'computer'	: <?php echo $form->computer; ?> ,
		'store_num'	: <?php echo $form->store_num; ?>
	},
		storeJson		= <?php echo json_encode($store); ?> ;
	function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
	function selectvaluation(id , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+json[i]+'" '+(child_id==json[i] ? 'selected="selected"':'')+'>'+json[i]+'</option>';
		$('#'+id).html('<option value=""> - 请选择 - </option>' + code);
	}
	function selectvalueation(id , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
		$('#'+id).html('<option value=""> - 请选择 - </option>' + code);
	}
	$(document).ready(function() {

		//楼层
		$('#computer').on('change' , function()
		{
			var nextSelect = $('#storey').attr('id') , thisID = parseInt($(this).val() || 0 , 10);
			selectReset(nextSelect);
			selectReset('store');

			if (thisID && !$.isEmptyObject(storeJson[thisID]))
			{
				var storey='';
				for(var key in storeJson[thisID].child)
				{
					storey += key;
				}
				selectvaluation(nextSelect , storey , store.storey);
				if (store.storey > 0)
				nextSelect.change();
			}

			store.gather = thisID;
			store.storey = 0;
			store.store = 0;
		})
		//店铺编号
		$('#storey').on('change' , function()
		{
			var
				nextSelect = $('#store_num').attr('id'),
				thisID = parseInt($(this).val() || 0 , 10) ,
				oneID = parseInt($('#computer').val() || 0 , 10);
			selectReset(nextSelect);
			if (oneID && thisID && !$.isEmptyObject(storeJson[oneID].child[thisID]))
			{
				var asa=storeJson[oneID].child[thisID];
				for(var k in asa)
				{
					if(!asa[k])
					{
						delete(asa[k]);
					}
				}
				selectvalueation(nextSelect , asa , store.store);
				if (store.store > 0)
					nextSelect.change();
			}
			store.gather = oneID;
			store.storey = thisID;
			store.store = 0;
		})
	})
</script>