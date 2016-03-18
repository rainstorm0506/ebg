<?php
	Views::css(array('merchant','merchant.goods'));
	Views::js(array('jquery-dragPlug','json2'));
    Views::css('main');
    Views::css('extension');
	Yii::app()->clientScript->registerCoreScript('webUploader');
	Yii::app()->getClientScript()->registerCoreScript('select2');
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style type="text/css">
	.dicts{line-height:26px}
	.dicts input{margin:0 6px 0 20px;vertical-align:baseline}
	input.double-price{width:40px;text-align:center;float:none}
	h1.title a{font-size:16px;color:#00F;margin:0 0 0 20px}
	form select{width:210px}
	form li b{font-weight:300;margin:0 0 0 20px;color:#F00}
	.sbox36{width:150px; margin-right: 14px;}
	.ajax-dict{width:150px; border: 1px solid #ddd;
		color: #333;
		height: 36px;
		font-family: Microsoft YaHei;
		font-size: 14px;
		padding-left: 5px;
	}
</style>
<main>
    <section class="pop-wrap pop-mer">
        <header><h3>修改 二手商品</h3>
            <?php
            echo CHtml::link('' , $this->createUrl('list' ),array('id'=>'close'));
            ?>
        </header>
    <fieldset class="form-list-34 form-list-34-1 crbox18-group" style="padding-top: 20px;">
        <?php $active = $this->beginWidget('CActiveForm', array('id'=>'formWrap','enableAjaxValidation'=>true)); ?>
            <ul>
            <?php
                $form->id = empty($form->id) ? $used['id'] : (int)$form->id;
                echo $active->hiddenField($form , 'id');
            ?>
                <li>
                    <h6><i>*</i>商品名称：</h6>
                    <?php
                        CHtml::$errorContainerTag = 'span';
                        $form->title = isset($form->title) ? $form->title :$used['title'];
                        echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'tbox34'));
                        echo $active->error($form , 'title');
                    ?>
                </li>
	            <li>
		            <h6><i></i>亮点：</h6>
		            <?php
		            $form->lightspot = isset($form->lightspot) ? $form->lightspot : $used['lightspot'];
		            echo $active->textField($form , 'lightspot' , array('style' => 'width:40%' , 'class'=>'tbox34'));
		            echo $active->error($form , 'lightspot');
		            ?>
	            </li>
                <li><h6>商品货号：</h6><?php echo $used['goods_num']; ?></li>
                <li class="usedgoods-tags">
                    <h6>商品标签：</h6>
                    <?php
                        $form->tag_id = isset($form->tag_id) ? $form->tag_id : $used['tag_id'];
                        echo $active->radioButtonList($form , 'tag_id' , CMap::mergeArray(array(0=>'无') , $tag) , array('separator'=>''));
                    ?>
                </li>
                <li>
                    <h6><i>*</i>所在地：</h6>
                    <ul>
                        <li style="float: left; margin-right: 20px;">
                        <?php
                            CHtml::$errorContainerTag = 'span';
                            $form->dict_one_id = isset($form->dict_one_id) ? (int)$form->dict_one_id : (isset($freight['dict_one_id'])?(int)$freight['dict_one_id']:$used['dict_one_id']);
                            echo $active->dropDownList($form , 'dict_one_id' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'one_id','class'=>'ajax-dict'));
                            echo $active->error($form , 'dict_one_id');
                        ?>
                        </li>
                        <li style="float: left; margin-right: 20px;">
                        <?php
                            $form->dict_two_id = isset($form->dict_two_id) ? (int)$form->dict_two_id : (isset($freight['dict_two_id'])?(int)$freight['dict_two_id']:$used['dict_two_id']);
                            echo $active->dropDownList($form , 'dict_two_id' , array(''=>' - 请选择 - ') , array('id'=>'two_id','class'=>'ajax-dict'));
                        ?>
                        </li>
                        <li style="float: left; margin-right: 20px;">
                        <?php
                            $form->dict_three_id = isset($form->dict_three_id) ? (int)$form->dict_three_id : (isset($freight['dict_three_id'])?(int)$freight['dict_three_id']:$used['dict_three_id']);
                            echo $active->dropDownList($form , 'dict_three_id' , array(''=>' - 请选择 - ') , array('id'=>'three_id','class'=>'ajax-dict'));
                        ?>
                        </li>
                    </ul>
                </li>
                <li class="class-select">
                    <h6><i>*</i>商品分类：</h6>
                <?php
	                $form->class_one_id = isset($form->class_one_id) ? (int)$form->class_one_id : $used['class_one_id'];
	                $form->class_two_id = isset($form->class_two_id) ? (int)$form->class_two_id : $used['class_two_id'];
	                $form->class_three_id = isset($form->class_three_id) ? (int)$form->class_three_id : $used['class_three_id'];
	                if ($class)
	                {
		                echo $active->dropDownList($form , 'class_one_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
		                echo $active->dropDownList($form , 'class_two_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36 mlr10px'));
		                echo $active->dropDownList($form , 'class_three_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
	                }else{
		                echo CHtml::link('当前没有商品分类 , 点击创建商品分类' , $this->createUrl('goodsClass/create'));
	                }
                ?>
                </li>
				<li>
					<h6><i>*</i>品牌：</h6>
					<?php
						if ($brand)
						{
							$form->brand_id = isset($form->brand_id) ? (int)$form->brand_id : $used['brand_id'];
							echo $active->dropDownList($form , 'brand_id' , CMap::mergeArray(array(''=>' - 请选择 - ') , $brand) , array('class'=>'sbox36 js-example-basic-multiple'));
							#echo $active->error($form , 'brand_id');
						}else{
							echo CHtml::link('当前没有品牌数据 , 点击创建品牌' , $this->createUrl('goodsBrand/create'));
						}
					?>
				</li>
            </ul>
            <ul>
                <li>
                    <h6><i>*</i>新旧成色：</h6>
                <?php
	                $form->use_time = isset($form->use_time)&&$form->use_time!='' ? (int)$form->use_time :$used['use_time'];
	                echo $active->dropDownList($form , 'use_time' , CMap::mergeArray(array(''=>' - 请选择 - ') , GlobalUsedGoods::$UseTime) , array('class'=>'sbox36'));
                ?>
                <?php
                    echo $active->error($form , 'use_time');
                ?>
                </li>
                <li>
                    <h6><i>*</i>原买价：</h6>
                    <?php
                        $form->buy_price = isset($form->buy_price)&&$form->buy_price!=''?(double)$form->buy_price:(double)$used['buy_price'];
                        echo $active->textField($form , 'buy_price' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0')).'<em style="float:none;">元</em>';
                        #echo $active->error($form , 'buy_price');
                    ?>
                </li>
                <li>
                    <h6><i>*</i>现售价：</h6>
                    <?php
                        $form->sale_price = isset($form->sale_price)&&$form->sale_price!='' ? (double)$form->sale_price :(double)$used['sale_price'];
                        echo $active->textField($form , 'sale_price' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0')).'<em style="float:none;">元</em>';
                        echo '<span class="show-price"></span>';
                        #echo $active->error($form , 'sale_price');
                    ?>
                </li>
            </ul>
            <ul id="goodsDefaultSet">

                <li class="infinite-stock">
                    <h6><i>*</i>库存：</h6>
                    <?php
                        $form->stock = isset($form->stock)&&$form->stock!='' ? (int)$form->stock : (int)$used['stock'];
                        echo $active->textField($form , 'stock' , array('class'=>'tbox34 int-price' , '_val'=>$form->stock));
                        #echo $active->error($form , 'stock');
                    ?>
                </li>
                <li>
                    <h6><i>*</i>重量：</h6>
                    <?php
                        $form->weight = isset($form->weight)&&$form->weight!='' ? (double)$form->weight : (double)$used['weight'];
                        echo $active->textField($form , 'weight' , array('class'=>'tbox34 double-price')).'kg';
                        #echo $active->error($form , 'weight');
                    ?>
                </li>
            </ul>
            <ul>
                <?php
                    $form->cover = isset($form->cover) ? $form->cover : $used['cover'];
                    echo $active->hiddenField($form , 'cover' , array('id'=>'goods_cover'));
                ?>
                <li>
                    <h6><i>*</i>商品图片：</h6>
                    <aside class="goods-img" id="imgs">
                        <div name="UsedGoodsForm[img][]"></div>
                        <div name="UsedGoodsForm[img][]"></div>
                        <div name="UsedGoodsForm[img][]"></div>
                        <div name="UsedGoodsForm[img][]"></div>
                        <div name="UsedGoodsForm[img][]"></div>
                    </aside>
                </li>
            </ul>
            <ul>
                <li>
                    <h6><i>*</i>商品详情：</h6>
                    <aside style="line-height:18px;display:block;width:80%;height:auto">
                    <?php
                        $form->content = isset($form->content) ? $form->content : $used['content'];
                        $active->widget('UEditor' , array('form'=>$form , 'name'=>'content' , 'id'=>'content'));
                        #echo $active->error($form , 'content' , array('inputID'=>'content'));
                    ?>
                    </aside>
                </li>
                <li>
                    <h6>&nbsp;</h6>
                    <?php echo CHtml::submitButton('保存' , array('class'=>'btn-1 btn-1-3')); ?>
                </li>
            </ul>
        <?php $this->endWidget(); ?>
    </fieldset>
    </section>
    <div class="mask" id="maskbox"></div>
</main>
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
		brandUrl		= '<?php echo $this->createUrl('usedGoods/getBrand');?>',
        formError		= <?php echo json_encode($formError); ?>,
        imgsSet			= '<?php echo $form->imgsSet; ?>',
        imgData			= <?php echo json_encode($form->img); ?>,
        imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';

    function getLayer()
    {
        return window.top.layer || window.layer || false;
    }
    function jsonFilterError(json)
    {
        if (json.code == 0)
            return json.data;
        else
            alert(json.message);
    }
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
		server: '<?php echo Yii::app()->params['imgUploadSrc'];?>',
		preview : '<?php echo Yii::app()->params['imgPreviewSrc'];?>',
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
    $(document).ready(function() {
	    $('#formWrap')
		    //商品分类 第一个 select
		    .on('change', '.class-select>select:eq(0)', function () {
			    var nextSelect = $(this).next('select'), thisID = parseInt($(this).val() || 0, 10);
			    classselectReset(nextSelect);
			    classselectReset($(this).next('select').next('select'));

			    if (thisID && !$.isEmptyObject(classJson[thisID].child)) {
				    classselectvaluation(nextSelect, classJson[thisID].child, goodsClass.class_two_id);
				    if (goodsClass.class_two_id > 0)
					    nextSelect.change();
			    }

			    goodsClass.class_one_id = thisID;
			    goodsClass.class_two_id = 0;
			    goodsClass.class_three_id = 0;
		    })
		    //商品分类 第二个 select
		    .on('change', '.class-select>select:eq(1)', function () {
			    var
				    nextSelect = $(this).next('select'),
				    thisID = parseInt($(this).val() || 0, 10),
				    oneID = parseInt($(this).prev('select').val() || 0, 10);
			    classselectReset(nextSelect);

			    if (oneID && thisID && !$.isEmptyObject(classJson[oneID].child[thisID].child)) {
				    classselectvaluation(nextSelect, classJson[oneID].child[thisID].child, goodsClass.class_three_id);
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

	    //填充第一个select的数据
	    if (!$.isEmptyObject(classJson))
		    classselectvaluation($('.class-select>select:eq(0)'), classJson, goodsClass.class_one_id);

	    //如果有默认值 , 填充默认值
	    if (goodsClass.class_one_id > 0)
		    $('.class-select>select:eq(0)').change();
    })
</script>