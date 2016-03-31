<?php
Views::css ( array (
		'web_uploader',
		'default',
		'merchant' 
) );
Views::js ( array (
		'jquery.validate',
		'jquery.sendVerification' 
) );
Yii::app ()->clientScript->registerCoreScript ( 'webUploader' );
Yii::app ()->getClientScript ()->registerCoreScript ( 'layer' );
?>
<style type="text/css">
.errorMessage {
	color: red
}
.tab-goods td {
	height:85px;
}
td.uploader {
	width: 100px;
}
.form-list.form-list-1.crbox18-group>span {
	cursor: pointer;
	font-size: 15px;
	height: 40px;
	line-height: 40px;
	padding: 5px;
	margin: 5px;
}
.current {
	color: red;
}
a.preview-close {
    color: red;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    line-height: 20px;
    padding: 0 5px;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 2;
}
</style>
<nav class="current-stie current-stie-wrap">
	<span><?php echo CHtml::link('首页 ' , $this->createUrl('home/index'));?></span><i>&gt;</i>
	<span><?php echo CHtml::link('企业采购 ' , $this->createUrl('index'));?></span><i>&gt;</i>
	<span>我的采购单</span>
</nav>
<main class="proc-publish">
<fieldset class="form-list form-list-1 crbox18-group">
	<span><a href="<?php echo $this->createUrl('purchase/public');?>" <?php if(Yii::app()->request->requestUri == $this->createUrl('purchase/public')){?> class="current"<?php }?>>发布采购单</a></span>
	<span><a href="<?php echo $this->createUrl('purchase/batch');?>" <?php if(Yii::app()->request->requestUri == $this->createUrl('purchase/batch')){?> class="current"<?php }?>>批量发布采购单</a></span>
	<!-- 单个采购单 -->
		<?php $active = $this->beginWidget('CActiveForm',array('id'=>'single','enableAjaxValidation'=>true,'enableClientValidation'=>true,'action'=>$this->createUrl('purchase/batch'))); ?>
				<ul>
		<li><h6>联系人：</h6>
					<?php
					$form->link_man = $form->link_man ? $form->link_man :'';
					echo $active->textField ( $form, 'link_man', array (
							'id' => 'link_name',
							'class' => 'tbox38 tbox38-1',
							'placeholder' => "请输入联系人姓名",
							'autocomplete'=> 'off',
					) );
					echo $active->error ( $form, 'link_man' );
					?>
					</li>
		<li><h6>联系电话：</h6>
					<?php
					$form->phone = $form->phone ? $form->phone : '';
					echo $active->textField ( $form, 'phone', array (
							'id' => 'phone',
							'class' => 'tbox38 tbox38-1',
							'placeholder' => "请输入手机号码",
							'autocomplete'=> 'off',
					) );
					echo $active->error ( $form, 'phone' );
					?>
					</li>
		<li><h6>商品名称：</h6>
					<?php
					echo $active->textField ($form, 'title', array (
							'id' => 'title',
							'class' => 'tbox34 tbox34-2',
							'autocomplete'=> 'off',
					) );
					echo $active->error ( $form, 'title' );
					?>

					<p class="p-txt">
				还能输入<i>50</i>字
			</p> <span class="promt error msg" style="display: none">长度不能超过50</span>
		</li>

		<li><h6>产品：</h6>
			<aside>
				<table class="tab-goods">
					<colgroup>
						<col style="width: 20%">
						<col style="width: 20%">
						<col style="width: 13%">
						<col style="width: 13%">
						<col style="width: 25%">
						<col style="width: auto">
					</colgroup>
					<thead>
						<tr>
							<th>商品名称</th>
							<th>规格型号</th>
							<th>数量</th>
							<th>单位</th>
							<th>产品描述</th>
							<th>图/文档</th>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($form->goods_name)):?>
					<?php //print_r($form->img);?>
						<?php foreach ($form->goods_name as $key=> $val):?>
						
						<tr>
							<td><input style="width:175px;" class="goods_name tbox28 tbox-1 " type="text" name="PurchaseGoodsTmpForm[goods_name][]" autocomplete='off' value="<?php echo $val;?>" /><?php echo $active->error($form, 'goods_name'.$key);?></td>
							<td><input class="tbox28 tbox-1" type="text" name="PurchaseGoodsTmpForm[params][]" autocomplete='off' value="<?php echo $form->params[$key];?>"/></td>
							<td><input class="tbox28 tbox-2" type="text" name="PurchaseGoodsTmpForm[num][]" autocomplete='off' value="<?php echo $form->params[$key];?>" /></td>
							<td><input class="tbox28 tbox-2" type="text" placeholder="件、吨" name="PurchaseGoodsTmpForm[unit][]" autocomplete='off' value="<?php echo $form->params[$key];?>" /></td>
							<td><input class="tbox28 tbox-3" type="text"
								placeholder="规格等说明，报价更精准" name="PurchaseGoodsTmpForm[descript][]" value="<?php echo $form->params[$key];?>" autocomplete='off'/></td>
							<td class="uploader"></td>
							<input type="hidden" class="img" name="PurchaseGoodsTmpForm[img][]"/>
						</tr>
						<?php endforeach;?>
					<?php else:?>
						<tr>
							<td><input style="width:175px;" class="goods_name tbox28 tbox-1" type="text" name="PurchaseGoodsTmpForm[goods_name][]" autocomplete='off' /><?php echo $active->error($form, 'goods_name');?></td>
							<td><input class="tbox28 tbox-1" type="text" name="PurchaseGoodsTmpForm[params][]" autocomplete='off'/></td>
							<td><input class="tbox28 tbox-2" type="text" name="PurchaseGoodsTmpForm[num][]" autocomplete='off'/></td>
							<td><input class="tbox28 tbox-2" type="text" placeholder="件、吨" name="PurchaseGoodsTmpForm[unit][]" autocomplete='off'/></td>
							<td><input class="tbox28 tbox-3" type="text"
								placeholder="规格等说明，报价更精准" name="PurchaseGoodsTmpForm[descript][]" autocomplete='off'/></td>
							<td class="uploader"><aside class="mer-img"><div id="imgAvat" name=""></div></aside></td>
							<input type="hidden" class="img" name="PurchaseGoodsTmpForm[img][]"/>
						</tr>
					<?php endif;?>
					</tbody>
				</table>
				<nav class="add-del-nav">
					<a href="javascript:;" id="addproduct">添加产品</a> <i>|</i> <a
						href="javascript:;" id="delproduct">删除</a>
				</nav>
			</aside></li>
		<li><h6>报价截止时间：</h6>
					<?php
					$form->price_endtime = $form->price_endtime ? $form->price_endtime : '';
					$active->widget ( 'Laydate', array (
							'form' => $form,
							'isTime' => false,
							'id' => 'expireTime',
							'name' => 'price_endtime',
							'class' => "tbox34 tbox34-6 mr10px",
							'choose' => 'function (dates){
									var currentDate = new Date().getTime();
									var selectDate = Date.parse(dates);
									var dateNum = selectDate-currentDate;
									var dayNums = Math.ceil(dateNum/1000/3600/24);
									if(dayNums<0){
										$("#expireTime").val(" ");
										$(".surplus_date").html("共计   天").next().show().next().hide();
										$("#expireTime").css("border-color","#d00f2b");
									}else{
										$(".surplus_date").html("共计 "+dayNums+" 天").next().hide().next().show();
										$("#expireTime").css("border-color","#ddd");
									}
								}',
					) );
					echo $active->error ( $form, 'price_endtime' );
					?>
					<span class="ml20px surplus_date">共计 天</span></li>
		<li>
			<h6>招投标：</h6>
			<aside class="dh">
				<label for="4">
							<?php
							echo $active->radioButton ( $form, 'is_tender_offer', array (
									'class' => 'selectRadio',
									'value' => 1 
							) ) . '<i>是</i>';
							?>
							</label> <label for="5">
							<?php
							echo $active->radioButton ( $form, 'is_tender_offer', array (
									'class' => 'selectRadio',
									'value' => 0 
							) ) . '<i>否</i>';
							?>
							</label>
			</aside>
		</li>
		<li>
			<h6>面谈：</h6>
			<aside class="dh">
				<label for="4">
							<?php
							$form->is_interview = $form->is_interview ? $form->is_interview : (isset ( $info ['is_interview'] ) ? $info ['is_interview'] : '1');
							echo $active->radioButton ( $form, 'is_interview', array (
									'class' => 'selectRadio',
									'value' => 1 
							) ) . '<i>是</i>';
							?>
							</label> <label for="5">
							<?php
							echo $active->radioButton ( $form, 'is_interview', array (
									'class' => 'selectRadio',
									'value' => 0 
							) ) . '<i>否</i>';
							?>
						</label>
			</aside>
		</li>
		<li><h6>&nbsp;</h6> <input class="btn-1 btn-1-14" type="button" id="commit" style="cursor: pointer;"
			value="确认发布"></li>
	</ul>
	<?php $this->endWidget(); ?>
	</fieldset>
</main>

<script type="text/javascript">
$(function($){
	var error_html = '<div id="PurchaseGoodsTmpForm_goods_name_em_" class="errorMessage" style="display:none"></div><span class="promt error msg">请输入详细的商品名称</span>';
	$('#single').validate({
		rule : {
			link_name : {
				required : '联系人不能为空',
				   promt : '请输入联系人'
			},
			phone : {
				required : '请输入11位正确的手机号码',
				  mobile : '手机号码不合法',
				   promt : '请输入手机号，验证后，您可以用该手机号登录'
			},
			title : {
				required : '请输入正确的商品名称',

			},
			expireTime : {
				required : '请选择正确的报价截止时间',
			},
		}

	});
	$('.form-list>span').click(function(){
		$('.form-list>span').removeClass('current');
		var id = $(this).attr('value');
		$('.form-list form').hide();
		$("#"+id).show();
		$(this).addClass('current');
	})
	$('#addproduct').click(function(){
		var tr = $('.tab-goods > tbody').find('tr');
		/*if(tr.length >= 5){
			layer.msg('添加的产品批次不能超过五个!')
			return false;
		}*/
		var html = '';
		html += '<tr><td><input style="width:175px;" class="goods_name tbox28 tbox-1" type="text" name="PurchaseGoodsTmpForm[goods_name][]" autocomplete="off"/></td>';
		html += '	<td><input class="tbox28 tbox-1" type="text" name="PurchaseGoodsTmpForm[params][]" autocomplete="off"/></td>';
		html += '	<td><input class="tbox28 tbox-2" type="text" name="PurchaseGoodsTmpForm[num][]" autocomplete="off"/></td>';
		html += '	<td><input class="tbox28 tbox-2" type="text" placeholder="件、吨" name="PurchaseGoodsTmpForm[unit][]" autocomplete="off"/></td>';
		html += '	<td><input class="tbox28 tbox-3" type="text" name="PurchaseGoodsTmpForm[descript][]" placeholder="规格等说明，报价更精准" autocomplete="off"/></td>';
		html += '<td class="uploader"><aside class="mer-img"><div id="imgAvat" name=""></div></aside></td><input type="hidden" class="img" name="PurchaseGoodsTmpForm[img][]"/></tr>';	
		$('.tab-goods > tbody').append(html);
		var div = $('.tab-goods > tbody').find('.mer-img>div#imgAvat');
		__web_uploader(true ,div[div.length-1]);
		$('.goods_name').on('input propertychange', function() {
					var value = $(this).val();
					if(value.trim() == ''){
						$(this).nextAll().remove();
						$(this).addClass('error-input');
						$(this).after(error_html);
						$(this).val(' ');
					}else{
						$(this).removeClass('error-input');
						$(this).nextAll().remove();
					}
		});
	});
	$('#delproduct').click(function(){
		var tr = $('.tab-goods > tbody').find('tr');
		if(tr.length <= 1){
			layer.msg('添加的产品批次不能少于一个!')
			return false;
		}
		tr[tr.length-1].remove();

	});
	$('#title').bind('input propertychange', function() {
		var length = 50;
		var now   = $(this).val().length;
		if(now <= 50){
			$('.p-txt > i').html(length-now);
		}else{
			$(this).val($(this).val().substring(0,50));
		}
	});
	$('.goods_name').on('input propertychange', function() {
		var value = $(this).val();
		if(value.trim() == ''){
			$(this).nextAll().remove();
			$(this).addClass('error-input');
			$(this).after(error_html);
			$(this).val(' ');
		}else{
			$(this).removeClass('error-input');
			$(this).nextAll().remove();
		}
	});
	$('.goods_name')
	.on('blur' , function()
	{
		var value = $(this).val();
		if(value.trim() == ''){
			$(this).nextAll().remove();
			$(this).addClass('error-input');
			$(this).after(error_html);
			$(this).val(' ');
		}else{
			$(this).removeClass('error-input');
			$(this).nextAll().remove();
		}
	})
	.on('focus' , function()
	{
		$(this).removeClass('error-input');
		$(this).nextAll().remove();
	});
	$('#commit').click(function(){
		var obj = $('.goods_name');
		$('.goods_name').each(function(){
			if($(this).val().trim() == ''){
				$(this).nextAll().remove();
				$(this).addClass('error-input');
				$(this).after(error_html);
				$(this).val(' ');
			}
		});
		for(var i=0;i < obj.length;i++){
			if(obj[i].value==' '){
				return;
			}
		} 
		$("#single").submit();
	})

});
	var __web_uploader = function(setMinPic,imgSelect)
	{
		var config = {
			//pick: {id:imgSelect, label:'<p>上传</p>'},
			pick: {id:imgSelect,label: '<p>上传</p>'},
			//formData: {'width':500 , 'height':600},
			swf	: '<?php echo Yii::app()->params['imgDomain']; ?>/DUpload/Uploader.swf',
			chunked: true,
			chunkSize: 512 * 1024,
			accept: {
				title: 'files',
				extensions: 'gif,jpg,jpeg,bmp,png,doc,docx,xls,xlsx',
				mimeTypes: '*'
				},
			server  : '<?php echo Yii::app()->params['fileUploadSrc']; ?>',
			preview : '<?php echo Yii::app()->params['imgPreviewSrc']; ?>',
			disableGlobalDnd: true,
			fileSizeLimit: 1024 * 1024 * 1024,			//验证文件总大小是否超出限制
			fileSingleSizeLimit: 50 * 1024 * 1024,	   //验证单个文件大小是否超出限制
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
	// 检测是否已经安装flash，检测flash的版本
    flashVersion = ( function() {
        var version;

        try {
            version = navigator.plugins[ 'Shockwave Flash' ];
            version = version.description;
        } catch ( ex ) {
            try {
                version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                        .GetVariable('$version');
            } catch ( ex2 ) {
                version = '0.0';
            }
        }
        version = version.match( /\d+/g );
        return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
    } )(),

    supportTransition = (function(){
        var s = document.createElement('p').style,
            r = 'transition' in s ||
                    'WebkitTransition' in s ||
                    'MozTransition' in s ||
                    'msTransition' in s ||
                    'OTransition' in s;
        s = null;
        return r;
    })(),

    // WebUploader实例
    uploader;

	if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) 
	{
	    // flash 安装了但是版本过低。
	    if (flashVersion) {
	        (function(container) {
	            window['expressinstallcallback'] = function( state ) {
	                switch(state) {
	                    case 'Download.Cancelled':
	                        alert('您取消了更新！')
	                        break;
	
	                    case 'Download.Failed':
	                        alert('安装失败')
	                        break;
	
	                    default:
	                        alert('安装已成功，请刷新！');
	                        break;
	                }
	                delete window['expressinstallcallback'];
	            };
	
	            var swf = './expressInstall.swf';
	            // insert flash object
	            var html = '<object type="application/' +
	                    'x-shockwave-flash" data="' +  swf + '" ';
	
	            if (WebUploader.browser.ie) {
	                html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
	            }
	
	            html += 'width="100%" height="100%" style="outline:0">'  +
	                '<param name="movie" value="' + swf + '" />' +
	                '<param name="wmode" value="transparent" />' +
	                '<param name="allowscriptaccess" value="always" />' +
	            '</object>';
	
	            container.html(html);
	
	        })($wrap);
	
	    // 压根就没有安转。
	    } else {
	    	$('.tab-goods').parents('li').html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
	    }
	    return;
	} else if (!WebUploader.Uploader.support()) {
	    alert( 'Web Uploader 不支持您的浏览器！');
	    return;
	}

	//-------------------------------事件绑定说明----------------------------------
	//	http://fex.baidu.com/webuploader/doc/index.html#WebUploader_Uploader_events
	var uploader = WebUploader.create(config);
	uploader
	//当验证不通过时触发
	.on('error', function(error)
	{
		console.log(error);
		var code = '上传错误';
		switch (error)
		{
			case 'Q_TYPE_DENIED'		: code = '文件类型不匹配'; break;
			case 'Q_EXCEED_NUM_LIMIT'	:
			case 'Q_EXCEED_SIZE_LIMIT'	: code = '只能上传'+config.fileNumLimit+'个文件'; break;
			case 'F_EXCEED_SIZE'		: code = '只能上传50M以内的图片'; break;
			case 'F_DUPLICATE'			: code = '此文件已上传'; break;uploader.reset();
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
		var container = $('#rt_' + file.source.ruid).parent() , code = '' , title='',name = container.attr('name');
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('p').remove();
		if (json.code != 0)
		{
			uploader.removeFile(file);
			alert(json.message);
			return false;
		}
		container.nextAll('.img').val(json.src);
		var html = '<div class="webuploader-pick"><p>上传</p></div>';
		uploader.makeThumb(file, function(error , ret)
		{
			 if ( error ) {
				 title = '<p class="title">' + file.name + '</p><a class="preview-close">x</a>';
				 title += '<b>不能预览</b>';
				 container.prepend(title);
				 container.children('div.webuploader-pick').remove();
				 container.children('a.preview-close').click(function(){
						container.children(':hidden').remove();
						container.children('b').remove();
						container.children('img').remove();
						container.children('p').remove();
						container.children('a[class="preview-close"]').remove();
						container.nextAll('.img').val(' ');
						uploader.removeFile(file);
						container.prepend(html);
					});
                 return;
             }
            
				if (isSupportBase64)
				{
					title += '<img style="width:85px;" src="'+ret+'"><a class="preview-close">x</a>';
					container.prepend(title);
					container.children('div.webuploader-pick').remove();
					container.children('a.preview-close').click(function(){
						container.children(':hidden').remove();
						container.children('b').remove();
						container.children('img').remove();
						container.children('p').remove();
						container.children('a[class="preview-close"]').remove();
						container.nextAll('.img').val(' ');
						uploader.removeFile(file);
						container.prepend(html);
					});
				}else{
					$.ajax(config.preview , {method: 'POST', data: ret, dataType:'json'}).done(function(response)
					{
						if (response.result){
							title += '<img style="width:85px;" src="'+response.result+'"><a class="preview-close">x</a>';
							container.prepend(title);
							container.children('div.webuploader-pick').remove();
							container.children('a.preview-close').click(function(){
								container.children(':hidden').remove();
								container.children('b').remove();
								container.children('img').remove();
								container.children('p').remove();
								container.children('a[class="preview-close"]').remove();
								container.nextAll('.img').val(' ');
								uploader.removeFile(file);
								container.prepend(html);
							});
						}else{
							title += '<b>预览出错</b>';
							container.prepend(title);
						}
					});
				}
		});
	});
};

$(document).ready(function(){
	__web_uploader(true ,'.mer-img>div[name]');
});
</script>