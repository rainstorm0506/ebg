<?php
Views::css(array('web_uploader','default' ));
Views::js(array('jquery.validate','jquery.sendVerification'));
Yii::app()->clientScript->registerCoreScript('webUploader');
?>
<style type="text/css">
.errorMessage{color:red}
</style>
<nav class="current-stie current-stie-wrap">
	<span><?php echo CHtml::link('首页 ' , $this->createUrl('home/index'));?></span><i>&gt;</i>
	<span><?php echo CHtml::link('企业采购 ' , $this->createUrl('index'));?></span><i>&gt;</i>
	<span>我的采购单</span>
</nav>
<main class="proc-publish">
	<fieldset class="form-list form-list-1 crbox18-group">
		<legend>发布采购单</legend>
		<?php $active = $this->beginWidget('CActiveForm', array('id'=>'companyRegister')); ?>
			<ul>
				<li>
				<?php
					$form->link_man = $form->link_man ? $form->link_man : (isset ( $info ['link_man'] ) ? $info ['link_man'] : '');
					echo $active->textField ( $form, 'link_man', array (
						'id' => 'name',
						'class' => 'tbox38 tbox38-1',
						'placeholder' => "联系人"
					));
					echo $active->error ( $form, 'link_man' );
				?>
				</li>
				<li>
				<?php
					$form->phone = $form->phone ? $form->phone : (isset ( $info ['phone'] ) ? $info ['phone'] : '');
					echo $active->textField ( $form, 'phone', array (
						'id' => 'tel',
						'class' => 'tbox38 tbox38-1',
						'placeholder' => "验证手机"
					) );
					echo $active->error ( $form, 'phone' );
				?>
				</li>
				<li class="code-verify">
					<?php
						echo $active->textField($form , 'vxcode' , array('ags'=>'member','class'=>'tbox38 tbox38-2','placeholder'=>'请输入图形验证码','onclick'=>'$(this).attr("class","tbox38 tbox38-2")','onfocus'=>'$(this).attr("class","tbox38 tbox38-2")','maxlength'=>6,'style'=>'height:40px'));
						echo '<img ags="member" class="svcode">';
						echo $active->error( $form, 'vxcode' );
					?>
				</li>
				<li>
				<?php
					$form->codeNum = $form->codeNum ? $form->codeNum : '';
					echo $active->textField ( $form, 'codeNum', array (
						'id' => 'code',
						'disabled' => false,
						'class' => 'tbox38 tbox38-2',
						'placeholder' => "短信验证码"
					) );
				?>
				<a class="btn-2" id="sendBtn" href="javascript:;">获取短信验证码</a>
				<?php echo $active->error ( $form, 'codeNum' ); ?>
				</li>
				<li>
					<div id="uploaderHide"></div>
					<div id="uploader">
						<div class="queueList">
							<div id="dndArea" class="placeholder">
								<div id="filePicker"></div>
								<p></p>
							</div>
						</div>
						<div class="statusBar" style="display:none;">
							<div class="progress">
								<span class="text">0%</span>
								<span class="percentage"></span>
							</div>
							<div class="info"></div><div id="_wp_errors"></div>
							<div class="btns">
								<div id="filePicker2"></div>
								<div class="uploadBtn">开始上传</div>
							</div>
						</div>
					</div>
				</li>
				<li>
					<h6>报价截止时间：</h6>
					<?php 
						$active->widget ( 'Laydate', array (
								'form' => $form,
								'isTime'=>false,
								'id' => 'expireTime',
								'name' => 'price_endtime',
								'class' => "tbox38 tbox38-2",
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
								'value' => isset($searchPost['price_endtime']) ? $searchPost['price_endtime'] : ''
						) );
					?>
					<span class="ml20px surplus_date">共计  天</span>
					<?php echo $active->error ( $form, 'price_endtime' );?>
				</li>
				<li class="dh">
					<h6>招投标：</h6>
					<label>
						<?php
							$form->is_tender_offer = $form->is_tender_offer ? $form->is_tender_offer : (isset ( $info ['is_tender_offer'] ) ? $info ['is_tender_offer'] : '1');
							echo $active->radioButton ( $form, 'is_tender_offer', array (
								'class' => 'selectRadio',
								'value' => 1,
								'style' => 'margin-top:7px' 
							) ) . '<i>是</i>';
						?>
					</label>
					<label>
						<?php
							echo $active->radioButton ( $form, 'is_tender_offer', array (
								'class' => 'selectRadio',
								'value' => 0,
								'style' => 'margin-left:30px;margin-top:7px' 
							) ) . '<i>否</i>';
						?>
					</label>
				</li>
				<li class="dh">
					<h6>面谈：</h6>
					<label>
						<?php
							$form->is_interview = $form->is_interview ? $form->is_interview : (isset ( $info ['is_interview'] ) ? $info ['is_interview'] : '1');
							echo $active->radioButton ( $form, 'is_interview', array (
								'class' => 'selectRadio',
								'value' => 1,
								'style' => 'margin-top:7px' 
							) ) . '<i>是</i>';
						?>
					</label>
					<label>
						<?php
							echo $active->radioButton ( $form, 'is_interview', array (
								'class' => 'selectRadio',
								'value' => 0,
								'style' => 'margin-left:30px;margin-top:7px' 
							) ) . '<i>否</i>';
						?>
					</label>
				</li>
				<li>
					<?php echo CHtml::submitButton('确认发布' , array('class'=>'btn-1 btn-1-1', 'id'=>"submitRegisterBtn", 'style'=>'cursor:pointer')); ?>
				</li>
			</ul>
					
		<?php $this->endWidget(); ?>
	</fieldset>
</main>

<script>
var _send_permission = {'find':true} , _code_permission = {'find':null};
$(document).ready(function() {
	//图形验证码
	$('.svcode').click(function(){
		$(this).attr('src' , '<?php echo $this->createUrl('asyn/getVcdoe'); ?>?type=member&_x='+Math.random());
	}).click();
	
	//验证码
	$('.code-verify>:text[ags]')
	.change(function(){
		var ex = this , _v = $(ex).val();
		$(ex).nextAll('span,q').remove();
		if ($.trim(_v) == '')
		{
			$(ex).after('<q class="promt error msg no-sms">请输入验证码</q>');
		}else{
			$.getJSON('<?php echo $this->createUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':'member'} , function(json){
				if (json.code !== 0)
				{
					_code_permission.find = false;
					$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
				}else{
					_code_permission.find = true;
					$(ex).next().after('<q class="success"></q>');
				}
			});
		}
	});
	//验证短信 - 个人
	$('#sendBtn').sendVerification({tel:'#tel' ,'stype':'find',  'callback':function(self){
		var phone = $('#tel').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'member'} , function(json){
			
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});
});
$(function($){
	$('#companyRegister').validate({
		rule : {
			name : {
				required : '联系人不能为空',
				   promt : '请输入联系人'
			},
			tel : {
				required : '请输入11位正确的手机号码',
				  mobile : '手机号码不合法',
				   promt : '请输入手机号，验证后，您可以用该手机号登录'
			},
			expireTime : {
				required : '请选择正确的报价截止时间',
			  //timeFormat : '时间格式形如：2015-10-1、2015/10/1',
				//   promt : '时间格式形如：2015-10-1、2015/10/1'
			},

		}
	});

});
var
	postJSON		= {},
	imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>',
	wup_config		= {
		pick				: {id:'#filePicker',label:'点击选择图片或文件'},
		paste				: '#uploader',
		swf					: '<?php echo Yii::app()->params['imgDomain']; ?>/DUpload/Uploader.swf',
		chunked				: true,
		chunkSize			: 512 * 1024,
		server				: '<?php echo Yii::app()->params['fileUploadSrc']; ?>',
		preview				: '<?php echo Yii::app()->params['imgPreviewSrc']; ?>',
		disableGlobalDnd	: true,
		fileNumLimit		: 8,
		fileSizeLimit		: 200 * 1024 * 1024,	// 200 M
		fileSingleSizeLimit	: 50 * 1024 * 1024,		// 50 M
		accept: {
			title: 'files',
			extensions: 'gif,jpg,jpeg,bmp,png,doc,docx,xls,xlsx',
			mimeTypes: '*'
		}
	};

(function( $ ){
	// 当domReady的时候开始初始化
	$(function() {
		
        var $wrap = $('#uploader'),

            // 图片容器
            $queue = $( '<ul class="filelist"></ul>' ).appendTo( $wrap.find( '.queueList' ) ),

            // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find( '.statusBar' ),

            // 文件总体选择信息。
            $info = $statusBar.find( '.info' ),

            // 上传按钮
            $upload = $wrap.find( '.uploadBtn' ),

            // 没选择文件之前的内容。
            $placeHolder = $wrap.find( '.placeholder' ),

            $progress = $statusBar.find( '.progress' ).hide(),

            // 添加的文件数量
            fileCount = 0,

            // 添加的文件总大小
            fileSize = 0,

            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 110 * ratio,
            thumbnailHeight = 110 * ratio,

            // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

            // 所有文件的进度信息，key为file id
            percentages = {},
            // 判断浏览器是否支持图片的base64
            isSupportBase64 = ( function() {
                var data = new Image();
                var support = true;
                data.onload = data.onerror = function() {
                    if( this.width != 1 || this.height != 1 ) {
                        support = false;
                    }
                }
                data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                return support;
            } )(),

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
                $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
            }

            return;
        } else if (!WebUploader.Uploader.support()) {
            alert( 'Web Uploader 不支持您的浏览器！');
            return;
        }
        // 实例化
        uploader = WebUploader.create(wup_config);

        // 拖拽时不接受 js, txt 文件。
        uploader.on( 'dndAccept', function( items ) {
            var denied = false,
                len = items.length,
                i = 0,
                // 修改js类型
                unAllowed = 'text/plain;application/javascript ';

            for ( ; i < len; i++ ) {
                // 如果在列表里面
                if ( ~unAllowed.indexOf( items[ i ].type ) ) {
                    denied = true;
                    break;
                }
            }

            return !denied;
        });

        // 添加“添加文件”的按钮，
        uploader.addButton({
            id: '#filePicker2',
            label: '继续添加'
        });

        uploader.on('ready', function() {
            window.uploader = uploader;
        });

        // 当有文件添加进来时执行，负责view的创建
        function addFile( file ) 
        {
            var $li = $( '<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>'+
                    '<p class="progress"><span></span></p>' +
                    '</li>' ),

                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span>' +
                    '<span class="rotateRight">向右旋转</span>' +
                    '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find( 'p.imgWrap' ),
                $info = $('<p class="error"></p>'),

                showError = function( code ) {
                    switch( code ) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;

                        case 'interrupt':
                            text = '上传暂停';
                            break;

                        default:
                            text = '上传失败，请重试';
                            break;
                    }

                    $info.text( text ).appendTo( $li );
                };

            if ( file.getStatus() === 'invalid' ) {
                showError( file.statusText );
            } else {
                // @todo lazyload
                $wrap.text( '预览中' );
                uploader.makeThumb( file, function( error, src ) {
                    var img;

                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }
                    
                    if( isSupportBase64 ) {
                        img = $('<img src="'+src+'">');
                        $wrap.empty().append( img );
                    } else {
                        $.ajax(wup_config.preview, {
                            method: 'POST',
                            data: src,
                            dataType:'json'
                        }).done(function( response ) {
                            if (response.result) {
                                img = $('<img src="'+response.result+'">');
                                $wrap.empty().append( img );
                            } else {
                                $wrap.text("预览出错");
                            }
                        });
                    }
                }, thumbnailWidth, thumbnailHeight );

                percentages[ file.id ] = [ file.size, 0 ];
                file.rotation = 0;
            }

            file.on('statuschange', function( cur, prev ) {
                if ( prev === 'progress' ) {
                    $prgress.hide().width(0);
                } else if ( prev === 'queued' ) {
                    $li.off( 'mouseenter mouseleave' );
                    $btns.remove();
                }

                // 成功
                if ( cur === 'error' || cur === 'invalid' ) {
                    console.log( file.statusText );
                    showError( file.statusText );
                    percentages[ file.id ][ 1 ] = 1;
                } else if ( cur === 'interrupt' ) {
                    showError( 'interrupt' );
                } else if ( cur === 'queued' ) {
                    percentages[ file.id ][ 1 ] = 0;
                } else if ( cur === 'progress' ) {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if ( cur === 'complete' ) {
                    $li.append( '<span class="success"></span>' );
                }

                $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
            });

            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });

            $btns.on( 'click', 'span', function() {
                var index = $(this).index(),
                    deg;

                switch ( index ) {
                    case 0:
                        uploader.removeFile( file );
                        return;

                    case 1:
                        file.rotation += 90;
                        break;

                    case 2:
                        file.rotation -= 90;
                        break;
                }

                if ( supportTransition ) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                }
            });

            $li.appendTo( $queue );
        }

        // 负责view的销毁
        function removeFile( file ) {
            var $li = $('#'+file.id);

            delete percentages[ file.id ];
            updateTotalProgress();
            $li.off().find('.file-panel').off().end().remove();
        }

        function updateTotalProgress() {
            var loaded = 0,
                total = 0,
                spans = $progress.children(),
                percent;

            $.each( percentages, function( k, v ) {
                total += v[ 0 ];
                loaded += v[ 0 ] * v[ 1 ];
            } );

            percent = total ? loaded / total : 0;


            spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
            spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
            updateStatus();
        }

        function updateStatus() {
            var text = '', stats;

            if ( state === 'ready' ) {
                text = '选中' + fileCount + '张图片，共' +
                        WebUploader.formatSize( fileSize ) + '。';
            } else if ( state === 'confirm' ) {
                stats = uploader.getStats();
                if ( stats.uploadFailNum ) {
                    text = '已成功上传' + stats.successNum+ '张照片至XX相册，'+
                        stats.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>'
                }

            } else {
                stats = uploader.getStats();
                text = '共' + fileCount + '张（' +
                        WebUploader.formatSize( fileSize )  +
                        '），已上传' + stats.successNum + '张';

                if ( stats.uploadFailNum ) {
                    text += '，失败' + stats.uploadFailNum + '张';
                }
            }

            $info.html( text );
        }

        function setState( val ) {
            var file, stats;

            if ( val === state ) {
                return;
            }

            $upload.removeClass( 'state-' + state );
            $upload.addClass( 'state-' + val );
            state = val;

            switch ( state ) {
                case 'pedding':
                    $placeHolder.removeClass( 'element-invisible' );
                    $queue.hide();
                    $statusBar.addClass( 'element-invisible' );
                    uploader.refresh();
                break;

                case 'ready':
                    $placeHolder.addClass( 'element-invisible' );
                    $( '#filePicker2' ).removeClass( 'element-invisible');
                    $queue.show();
                    $statusBar.removeClass('element-invisible');
                    uploader.refresh();
                break;

                case 'uploading':
                    $( '#filePicker2' ).addClass( 'element-invisible' );
                    $progress.show();
                    $upload.text( '暂停上传' );
                break;

                case 'paused':
                    $progress.show();
                    $upload.text( '继续上传' );
                break;

                case 'confirm':
                    $progress.hide();
                    $( '#filePicker2' ).removeClass( 'element-invisible' );
                    $upload.text( '开始上传' );

                    stats = uploader.getStats();
                    if ( stats.successNum && !stats.uploadFailNum ) {
                        setState( 'finish' );
                        return;
                    }
                break;
                case 'finish':
                    stats = uploader.getStats();
                    if ( stats.successNum ) {
                        $upload.text( '上传成功' );
                    } else {
                        // 没有成功的图片，重设
                        state = 'done';
                        location.reload();
                    }
                break;
            }

            updateStatus();
        }

        uploader.onUploadProgress = function( file, percentage ) {
            var $li = $('#'+file.id),
                $percent = $li.find('.progress span');

            $percent.css( 'width', percentage * 100 + '%' );
            percentages[ file.id ][ 1 ] = percentage;
            updateTotalProgress();
        };

        uploader.onFileQueued = function( file ) {
            fileCount++;
            fileSize += file.size;

            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }
            //alert(uploader.preview);
            addFile( file );
            setState( 'ready' );
            updateTotalProgress();
        };

        uploader.onFileDequeued = function( file ) {
            fileCount--;
            fileSize -= file.size;

            if ( !fileCount ) {
                setState( 'pedding' );
            }

            removeFile( file );
            updateTotalProgress();

        };

        uploader.on( 'all', function( type ) {
            var stats;
            switch( type ) {
                case 'uploadFinished':
                    setState( 'confirm' );
                    break;

                case 'startUpload':
                    setState( 'uploading' );
                    break;

                case 'stopUpload':
                    setState( 'paused' );
                    break;
            }
        });

		uploader.on('uploadSuccess' , function(file , json)
		{
			if (json.code == 0)
			{
				$('#uploaderHide').append('<input type="hidden" name="PurchaseForm[img][]" value="'+json.src+'">');
			}else if(json.code > 0){
				uploader.removeFile(file);
				alert(json.message);
				return false;
			}
		});

        uploader.onError = function( code ) {
            $('#_wp_errors').html( 'Eroor: ' + code).delay(3000).hide();
        };

        $upload.on('click', function() {
            if ( $(this).hasClass( 'disabled' ) ) {
                return false;
            }

            if ( state === 'ready' ) {
                uploader.upload();
            } else if ( state === 'paused' ) {
                uploader.upload();
            } else if ( state === 'uploading' ) {
                uploader.stop();
            }
        });

        $info.on( 'click', '.retry', function() {
            uploader.retry();
        } );

        $info.on( 'click', '.ignore', function() {
            $('#_wp_errors').html('todo:').delay(3000).hide();
        } );

        $upload.addClass( 'state-' + state );
        updateTotalProgress();
    });

})( jQuery );
</script>