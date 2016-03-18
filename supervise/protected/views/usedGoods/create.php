<?php
	Views::css('goods.create');
	Views::js(array('jquery-dragPlug'));
	Yii::app()->clientScript->registerCoreScript('webUploader');
	Yii::app()->getClientScript()->registerCoreScript('select2');
	$this->renderPartial('navigation');
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style type="text/css">
	.dicts{line-height:26px}
	.dicts input{margin:0 6px 0 20px;vertical-align:baseline}
	input.double-price{width:40px;text-align:center;float:none}
	h1.title a{font-size:16px;color:#00F;margin:0 0 0 20px}
	form select{width:204px}
	form li b{font-weight:300;margin:0 0 0 20px;color:#F00}
	.sbox36{width:150px; padding-left:5px; margin-right: 14px;}
    .ajax-dict{width:120px; border: 1px solid #ddd;
        color: #333;
        height: 36px;
        font-family: Microsoft YaHei;
        font-size: 14px;
        padding-left: 5px;
        width: 150px;
	}
</style>
<h2 class="tit-2">添加 二手商品</h2>
<fieldset class="form-list-34 form-list-34-1 crbox18-group">
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'formWrap','enableAjaxValidation'=>true,)); ?>
		<ul>
			<li>
				<h6><i>*</i>商品名称：</h6>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->title = isset($form->title) ? $form->title : '';
					echo $active->textField($form , 'title' , array('style' => 'width:38%' , 'class'=>'tbox34'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<h6><i></i>亮点：</h6>
			<?php
				$form->lightspot = isset($form->lightspot) ? $form->lightspot : '';
				echo $active->textField($form , 'lightspot' , array('style' => 'width:38%' , 'class'=>'tbox34'));
				echo $active->error($form , 'lightspot');
			?>
			</li>
			<li>
				<h6>商品货号：</h6>
				<?php
					$form->goods_num = isset($form->goods_num) ? $form->goods_num : '';
					echo $active->textField($form , 'goods_num' , array('class'=>'tbox34' , 'placeholder'=>'如果您不输入商品货号，系统将自动生成一个唯一的货号'));
					echo $active->error($form , 'goods_num');
				?>
				<span style="padding:0 0 0 10px">注：商品生成后，商品货号不能修改。</span>
			</li>
			<li class="goods-tags">
				<h6>商品标签：</h6>
				<?php
					$form->tag_id = isset($form->tag_id) ? $form->tag_id : 0;
					echo $active->radioButtonList($form , 'tag_id' , CMap::mergeArray(array(0=>'无') , $tag) , array('separator'=>''));
				?>
			</li>
			<li>
				<h6><i>*</i>所属商家：</h6>
				<?php
					$form->merchant = isset($form->merchant) ? $form->merchant : '';
					echo $active->textField($form , 'merchant' , array('class'=>'tbox34' , 'id'=>'merchant' , 'placeholder'=>'输入商家姓名，编号，店铺名称，自动检索'));

					$form->merchant_id = empty($form->merchant_id) ? 0 : (int)$form->merchant_id;
					echo $active->hiddenField($form , 'merchant_id' , array('id'=>'merchant_id'));
				?>
				<span style="padding:0 0 0 10px">注：商品生成后，所属商家不能修改。</span>
				<table class="user-merchant"></table>
			</li>
			<li>
				<h6><i>*</i>所在地：</h6>
				<ul>
					<li style="float: left; margin-right: 20px;">
					<?php
						CHtml::$errorContainerTag = 'span';
						$form->dict_one_id = isset($form->dict_one_id) ? (int)$form->dict_one_id : (isset($freight['dict_one_id'])?(int)$freight['dict_one_id']:0);
						echo $active->dropDownList($form , 'dict_one_id' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'one_id','class'=>'ajax-dict'));
                        echo $active->error($form , 'dict_one_id');
                    ?>
                    </li>
                    <li style="float: left; margin-right: 20px;">
                    <?php
                        $form->dict_two_id = isset($form->dict_two_id) ? (int)$form->dict_two_id : (isset($freight['dict_two_id'])?(int)$freight['dict_two_id']:0);
                        echo $active->dropDownList($form , 'dict_two_id' , array(''=>' - 请选择 - ') , array('id'=>'two_id','class'=>'ajax-dict'));
                    ?>
					</li>
					<li style="float: left; margin-right: 20px;">
					<?php
						$form->dict_three_id = isset($form->dict_three_id) ? (int)$form->dict_three_id : (isset($freight['dict_three_id'])?(int)$freight['dict_three_id']:0);
                        echo $active->dropDownList($form , 'dict_three_id' , array(''=>' - 请选择 - ') , array('id'=>'three_id','class'=>'ajax-dict'));
                    ?>
                    </li>
                </ul>
            </li>
			<li class="class-select">
				<h6><i>*</i>商品分类：</h6>
			<?php
				$form->class_one_id = isset($form->class_one_id) ? (int)$form->class_one_id : 0;
				$form->class_two_id = isset($form->class_two_id) ? (int)$form->class_two_id : 0;
				$form->class_three_id = isset($form->class_three_id) ? (int)$form->class_three_id : 0;
				if ($class)
				{
					echo $active->dropDownList($form , 'class_one_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
					echo $active->dropDownList($form , 'class_two_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36 mlr10px'));
					echo $active->dropDownList($form , 'class_three_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
					#echo $active->error($form , 'class_error');
				}else{
					echo CHtml::link('当前没有商品分类 , 点击创建商品分类' , $this->createUrl('usedClass/create'));
				}
			?>
			</li>
			<li>
				<h6><i>*</i>品牌：</h6>
				<?php
				if ($brand)
				{
					$form->brand_id = isset($form->brand_id) ? (int)$form->brand_id : 0;
					echo $active->dropDownList($form , 'brand_id' , CMap::mergeArray(array(''=>' - 请选择 - ') , $brand) , array('class'=>'sbox36 js-example-basic-multiple'));
				}else{
					echo CHtml::link('当前没有品牌数据 , 点击创建品牌' , $this->createUrl('goodsBrand/create'));
				}
				?>
			</li>
		</ul>
		<ul id="goodsDefaultSet">
            <li>
                <h6><i>*</i>新旧成色：</h6>
			<?php
				$form->use_time = isset($form->use_time)&&$form->use_time!='' ? (int)$form->use_time : '';
				echo $active->dropDownList($form , 'use_time' , CMap::mergeArray(array(''=>' - 请选择 - ') , GlobalUsedGoods::$UseTime) , array('class'=>'sbox36'));
			?>
			<?php
				echo $active->error($form , 'use_time');
			?>	
			</li>
			<li>
				<h6><i></i>原买价：</h6>
			<?php
				$form->buy_price = isset($form->buy_price)&&$form->buy_price!='' ? (double)$form->buy_price : 0;
				echo $active->textField($form , 'buy_price' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0')).'<em style="float:none;">元</em>';
			?>
			<span style="float:none;">
				<?php echo $active->error($form , 'buy_price');?>
			</span>
            </li>
			<li>
				<h6><i>*</i>现售价：</h6>
				<?php
					$form->sale_price = isset($form->sale_price)&&$form->sale_price!='' ? (double)$form->sale_price : 0;
					echo $active->textField($form , 'sale_price' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0')).'<em style="float:none;">元</em>';
					echo '<span class="show-price"></span>';
					
				?>
				<span style="float:none;">
					<?php echo $active->error($form , 'sale_price');?>
				</span>
			</li>
			<li class="infinite-stock">
				<h6><i>*</i>库存：</h6>
				<?php
					$form->stock = isset($form->stock)&&$form->stock!='' ? (int)$form->stock : '';
					echo $active->textField($form , 'stock' , array('class'=>'tbox34 int-price' , '_val'=>$form->stock));
					echo '<em style="float:none;">件</em>';
				?>
			</li>
			<li>
				<h6><i>*</i>重量：</h6>
				<?php
					$form->weight = isset($form->weight)&&$form->weight!='' ? (double)$form->weight : '';
					echo $active->textField($form , 'weight' , array('class'=>'tbox34 double-price')).'kg';
					#echo $active->error($form , 'weight');
				?>
			</li>
		</ul>
		<ul>
			<?php
				$form->cover = isset($form->cover) ? $form->cover : '';
				echo $active->hiddenField($form , 'cover' , array('id'=>'goods_cover'));
			?>
			<li>
				<h6><i>*</i>商品图片：</h6>
				<aside class="goods-img">
					<div name="UsedGoodsForm[img][]"></div>
					<div name="UsedGoodsForm[img][]"></div>
					<div name="UsedGoodsForm[img][]"></div>
					<div name="UsedGoodsForm[img][]"></div>
					<div name="UsedGoodsForm[img][]"></div>
				</aside>
			</li>
		</ul>
		<ul id="goodsImgGruoup"></ul>
		<ul>
			<li>
				<h6><i></i>排序：</h6>
				<?php
					$form->rank = isset($form->rank)&&$form->rank!='' ? (int)$form->rank : 0;
					echo $active->textField($form , 'rank' , array('class'=>'tbox34 int-price' , '_val'=>$form->rank));
				?>
				<span style="padding:0 0 0 10px">注：按照降序排序，数字越大前端显示越靠前。</span>
			</li>
			<li>
				<h6><i>*</i>详情：</h6>
				<aside style="line-height:18px;display:block;width:80%;height:auto">
				<?php
					$form->content = isset($form->content) ? $form->content : '';
					$active->widget('UEditor' , array('form'=>$form , 'name'=>'content' , 'id'=>'content'));
				?>
				</aside>
			</li>
			<li>
				<h6>&nbsp;</h6>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-4')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<?php //echo json_encode($class);exit;?>
<script>
	var
		goodsClass		= {
			'class_one_id'		: <?php echo $form->class_one_id; ?> ,
			'class_two_id'		: <?php echo $form->class_two_id; ?>,
			'class_three_id'	: <?php echo $form->class_three_id; ?>
		} ,
		classJson		= <?php echo json_encode($class); ?> ,
		inputFormName	= 'UsedGoodsForm' ,
		isPost			= <?php echo (int)$this->isPost(); ?> ,
		isPost_1		= <?php echo (int)$this->isPost(); ?> ,
		merchantID		= '<?php echo $form->merchant_id; ?>' ,
		merchantUrl		= '<?php echo $this->createUrl('merchant/searchKeyword'); ?>',
		brandUrl		= '<?php echo $this->createUrl('usedGoods/getBrand');?>',
		formError		= <?php echo json_encode($formError); ?>,
		imgsSet			= '<?php echo $form->imgsSet; ?>',
		imgData			= <?php echo json_encode($form->img); ?>,
		imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';
var formName='UsedGoodsForm';

    /*地区选择*/
    var dict = {
        'one_id'	: 0 ,
        'two_id'    : 0 ,
        'three_id'  : 0
    } , dictOld = {
        'one_id'	: <?php echo $form->dict_one_id; ?> ,
        'two_id'	: <?php echo $form->dict_two_id; ?> ,
        'three_id'	: <?php echo $form->dict_three_id; ?>
    };
	//地区下拉函数
    function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
    function selectvaluation(id , json , child_id)
    {
        var code = i = '';
        for (i in json)
            code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
        $('#'+id).html('<option value=""> - 请选择 - </option>' + code);
    }
	//分类下拉函数
	function classselectReset(evt){evt.html('<option selected="selected" value=""> - 请选择 - </option>')}
	function classselectvaluation(evt , json , child_id)
	{
		var code = i = '';
		for (i in json)
			code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
		evt.html('<option value=""> - 请选择 - </option>' + code);
	}

    $(document).ready(function(){
		$(".js-example-basic-multiple").select2();

		//区域选择
        $('select.ajax-dict').change(function(){
            var e = this , id = $(e).attr('id') , val = $(e).val();
            selectReset('four_id');
            switch (id)
            {
                case 'one_id' :
                    selectReset('two_id');
                    selectReset('three_id');
                    dict = {'one_id':val , 'two_id':0 , 'three_id':0};
                    break;
                case 'two_id' :
                    selectReset('three_id');
                    dict = {'one_id':dict.one_id , 'two_id':val , 'three_id':0};
                    break;
                case 'three_id' :
                    dict = {'one_id':dict.one_id , 'two_id':dict.two_id , 'three_id':val};
                    break;
            }

            $.getJSON('<?php echo $this->createUrl('dict/getUnidList'); ?>' , dict , function(json){
                json = jsonFilterError(json);
                $('#four_id').parent('li').show();

                switch (id)
                {
                    case 'one_id' :
                        if (val)
                        {
                            selectvaluation('two_id' , json , dictOld.two_id);
                            dictOld.two_id > 0 && $('#two_id').change();
                        }
                        break;
                    case 'two_id' :
                        if (val)
                        {
                            selectvaluation('three_id' , json , dictOld.three_id);
                            dictOld.three_id > 0 && $('#three_id').change();
                        }
                        break;
                }
            });
        });

        if (dictOld.one_id > 0)
            $('#one_id').change();

        $('input:reset').click(function(){window.location.reload();});
    });


    $(document).ready(function() {
        //设为主图
        $('.goods-img')
        .on('click', '.preview-set>a[class!="this"]', function ()
        {
            $(this).closest('.goods-img').find('.preview-set>a.this').removeClass('this').text('设为主图');
            $(this).addClass('this').text('主图');
            $('#goods_cover').val($(this).parent().siblings('input:hidden').val());
        });

        //图片关闭
        $('.goods-img')
        .on('click', 'a.preview-close', function () {
            var container = $(this).parent();
            container.children(':hidden').remove();
            container.children('b').remove();
            container.children('img').remove();
            container.children('div[class="preview-set"]').remove();
            container.children('a[class="preview-close"]').remove();
        })
    });

/*上传图片*/

var __web_uploader = function(setMinPic , imgJson , imgSelect)
{
	var config = {
		pick: {id: '.goods-img>div[class!="webuploader-container"]',label: '<i>+</i><p>点击上传图片</p>'},
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
					code += setMinPic ? '<div class="preview-set"><span></span><a>设为主图</a></div>' : '';
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
							code += setMinPic ? '<div class="preview-set"><span></span><a>设为主图</a></div>' : '';
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
		var i = 0 , _code ='' , _name = $(imgSelect).attr('name') , _cover = $('#goods_cover').val();
		for (; i < 5 ; i++)
		{
			if (imgJson[i])
			{
				_code = (_cover == imgJson[i]) ? '<a class="this">主图</a>' : '<a>设为主图</a>';
				$(imgSelect).eq(i).prepend('<input type="hidden" value="'+imgJson[i]+'" name="'+_name+'">' +
					'<div class="preview-set"><span></span>'+_code+'</div>' +
					'<img src="'+imgDomain+imgJson[i]+'"><a class="preview-close">x</a>');
			}
		}
	}

};

$(document).ready(function(){
	__web_uploader(true , imgData , '.goods-img>div[name="UsedGoodsForm[img][]"]');

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
});
/**
*选择商家
*/
$(document).ready(function(){
//搜索商家
	$('#formWrap').on('change' , '#merchant' , function()
	{
		$('.user-merchant').empty().hide();
		$('#merchant_id').val(0);

		var keyword = $.trim($(this).val()) , code = '';
		if (keyword == '')
			return false;

		$.getJSON(merchantUrl , {'keyword':keyword} , function(json)
		{
			json = jsonFilterError(json);
			if (!$.isEmptyObject(json))
			{
				code =	'<tr><th style="width:50px" class="user-seek">选择</th><th style="width:150px">商家编号</th>' +
						'<th style="width:150px">姓名</th><th>店铺名称</th></tr>';

				for (var i in json)
				{
					if (i > 9)
					{
						code += '<tr><td colspan="4" class="txt">最多搜索10条信息 , 如果你搜索的商家不在其中 , 请输入更详细的关键词 !</td></tr>';
					}else{
						code += '<tr '+(i%2==0?'':'class="this"')+'><td><input type="radio" name="merID" value="'+json[i].uid+'"></td><td>' +
								json[i].mer_no+'</td><td>'+json[i].mer_name+'</td><td class="_l">'+json[i].store_name+'</td></tr>';
					}
				}
				$('.user-merchant').html(code).show();
				merchantID && $('.user-merchant :radio[value="'+merchantID+'"]').click();
			}else{
				$('.user-merchant').html('<tr><td class="_l">搜索不到商家信息!</td></tr>').show();
			}
		});
	})
	//选择商家
	$('#formWrap').on('click' , '.user-merchant :radio' , function()
	{
		$(this).closest('tr').siblings('tr:gt(0)').hide();
		$('.user-seek').text('重选').addClass('this');
		$('#merchant_id').val($(this).val());
	})
	//重新选择
	$('#formWrap').on('click' , '.user-merchant th.user-seek' , function()
	{
		if ($(this).hasClass('this'))
		{
			$('#merchant_id').val(0);
			$(this).removeClass('this').text('选择');
			$(this).closest('tr').nextAll('tr').show().find('input:checked').attr('checked' , false);
		}
	})
})
$(document).ready(function()
{
    $('#formWrap')
	    //商品分类 第一个 select
	    .on('change' , '.class-select>select:eq(0)' , function()
	    {
		    var nextSelect = $(this).next('select') , thisID = parseInt($(this).val() || 0 , 10);
		    classselectReset(nextSelect);
		    classselectReset($(this).next('select').next('select'));

		    if (thisID && !$.isEmptyObject(classJson[thisID].child))
		    {
			    classselectvaluation(nextSelect , classJson[thisID].child , goodsClass.class_two_id);
			    if (goodsClass.class_two_id > 0)
				    nextSelect.change();
		    }

		    goodsClass.class_one_id = thisID;
		    goodsClass.class_two_id = 0;
		    goodsClass.class_three_id = 0;
	    })
	    //商品分类 第二个 select
	    .on('change' , '.class-select>select:eq(1)' , function()
	    {
		    var
			    nextSelect = $(this).next('select') ,
			    thisID = parseInt($(this).val() || 0 , 10) ,
			    oneID = parseInt($(this).prev('select').val() || 0 , 10);
		    classselectReset(nextSelect);

		    if (oneID && thisID && !$.isEmptyObject(classJson[oneID].child[thisID].child))
		    {
			    classselectvaluation(nextSelect , classJson[oneID].child[thisID].child , goodsClass.class_three_id);
			    if (goodsClass.class_three_id > 0)
				    nextSelect.change();
		    }

		    goodsClass.class_one_id = oneID;
		    goodsClass.class_two_id = thisID;
		    goodsClass.class_three_id = 0;
	    })
		//商品分类 第三个 select
		.on('change' , '.class-select>select:eq(2)' , function()
		{
			var opt = {
				'one_id'	: parseInt($('.class-select>select:eq(0)').val() || 0 , 10),
				'two_id'	: parseInt($('.class-select>select:eq(1)').val() || 0 , 10),
				'three_id'	: parseInt($('.class-select>select:eq(2)').val() || 0 , 10)
			};
			//商品品牌
			$.getJSON(brandUrl , opt , function(json)
			{
				json = jsonFilterError(json);
				var code='';
				if(!$.isEmptyObject(json))
				{
					code+='<option>- 请选择 -</option>'
					for(i in json)
					{
						code+='<option value="'+i+'">'+json[i]+'</option>';
					}
					$('#UsedGoodsForm_brand_id').html(code);
				}
			});
		})
		//提交验证
        .on('submit', function ()
        {
            //return true;
            var _error = {};
            if ($.trim($('#' + formName + '_title').val()) == '')
                _error[1] = '请填写商品名称';

            if ($.trim($('#merchant_id').val()) <= 0)
                _error[2] = '请选择所属商家';

            if ($.trim($('#' + formName + '_brand_id').val()) <= 0)
                _error[3] = '请选择品牌';
	        
            if ($('#goodsDefaultSet').is(':visible')) {
                if (parseFloat($.trim($('#' + formName + '_sale_price').val()) || 0) <= 0)
                    _error[6] = '请填写正确的售价';

            if (parseInt($.trim($('#' + formName + '_stock').val()) || 0, 10) <= 0)
                _error[7] = '请填写正确的库存';

            if (parseFloat($.trim($('#' + formName + '_weight').val()) || 0) <= 0)
                _error[8] = '请填写正确的重量';
        }

            //商品主图片
            var _cover = false;
            $('#goods_cover').next('li').find(':hidden').each(function () {
                _cover = ($.trim($(this).val()) != '') ? true : _cover;
            });
            if (!_cover)
                _error[15] = '请至少上传一张商品图片!';
            if ($.trim($('#goods_cover').val()) == '')
                _error[16] = '请设置商品主图!';
            if ($.trim($('#content').val()) == '')
                _error[17] = '请填写商品详情!';

            if ($.isEmptyObject(_error)) {
                return true;
            } else {
                var _c = '', _w = '', i, x = 0;
                for (i in _error) {
                    _c += _w + (++x) + ' . ' + _error[i];
                    _w = '<br />';
                }
                getLayer().alert(_c);
                return false;
            }
        });

    //填充第一个select的数据
    if (!$.isEmptyObject(classJson))
	    classselectvaluation($('.class-select>select:eq(0)'), classJson, goodsClass.class_one_id);

    if (merchantID)
		$('#merchant').change();
})
</script>