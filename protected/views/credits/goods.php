<?php
Views::css(array('shopping'));
Yii::app()->getClientScript()->registerCoreScript('layer');
Yii::app()->getClientScript()->registerCss('cart.clos' , '
.shipping-address li.current a.this{background-color:#d00f2b;color:#FFF;display:inline-block;padding:0 7px}
.shipping-address li.current{border:2px solid #d00f2b}
.select-models q{color:#666;margin:0 0 0 12px;line-height:26px;display:none}
');
?>
<main class="shop-wrap">
    <ul class="shop-process">
        <li class="current first"><b></b><em>1</em><i></i><p>我的购物车</p></li>
        <li class="current"><b></b><em>2</em><i></i><p>确认订单信息</p></li>
        <li><b></b><em>3</em><i></i><p>成功提交订单</p></li>
    </ul>
    <?php $active = $this->beginWidget('CActiveForm' , array('id'=>'createOrders')); ?>
    <section class="order-wrap">
        <h3><strong>确认收货地址</strong></h3>
        <div class="shipping-address">
            <?php 
            	echo $active->hiddenField($form,'userAddressID',array('id'=>'userAddressID')); 
			?>
            <ul id="address"><li id="addAddress" class="last"><q>+</q><p>增加新地址</p></li></ul>
            <nav><a>显示全部收货地址</a></nav>
        </div>
        <h3>配送方式</h3>
        <nav class="shop-nav-1 select-models">
            <a val="1" key="give" class="current">市内配送<i></i></a>
            <a val="2" key="oneself">上门自提<i></i></a>
            <q>自提地址：成都市一环路南二段15号东华电脑城北楼104</q>
            <?php echo $active->hiddenField($form,'deliveryWay',array('id'=>'deliveryWay' , 'value'=>1)); ?>
        </nav>
        <div class="shop-tab-wrap">
            <table class="shop-tab">
                <colgroup>
                    <col style="width:80px">
                    <col style="width:auto">
                    <col style="width:15%">
                    <col style="width:12%">
                    <col style="width:12%">
                    <col style="width:12%">
                </colgroup>
                <thead>
                <tr>
                    <th colspan="2"><span>商品</span></th>
                    <th></th>
                    <th></th>
                    <th>积分</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="tit">
                    <td class="tl" colspan="6">积分商城</td>
                </tr>
                <tr>
                    <?php $_last_mer_id = 0; $_html_remark = ''; $_html_weight = array();?>
                    <td><a href="#"><img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $goods['cover'];?>" width="80" height="80"></a></td>
                    <td class="link"><a href="#"><?php echo $goods['title'];?><br/><?php echo $attr;?></a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="price"><?php echo $goods['points'];?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                $form->id = empty($form->id) ? $goods['id'] : (int)$form->id;
                echo $active->hiddenField($form , 'id');
                ?>
                <?php
                $form->points = empty($form->points) ? $goods['points'] : (int)$form->points;
                echo $active->hiddenField($form , 'points');
                ?>
                </tbody>
            </table>
        </div>
        <!-- 配送方式 -->
        <ul class="pre-address settlement-info settlement-info-1"></ul>
    </section>
    <nav class="shop-nav">
        <?php
            if($goods['points']<=$user['fraction']){
                echo CHtml::link('确认兑换','' , array('class'=>'create-order btn-4'));
            }else{
                echo '<a style="font-size: 20px;height: 50px;line-height: 50px;float: right;width: 200px;border: 0 none;color: #fff;display: block;text-align: center;background-color: #d22238;">积分不足</a>';
            }
        ?>
    </nav>
    <?php $this->endWidget(); ?>
</main>

<script>
    jQuery.extend(
        {
            formatFloat : function(src, pos)
            {
                var num = parseFloat(src).toFixed(pos);
                num = num.toString().replace(/\$|\,/g,'');
                if(isNaN(num))
                    num = "0";
                sign = (num == (num = Math.abs(num)));
                num = Math.floor(num*100+0.50000000001);
                cents = num%100;
                num = Math.floor(num/100).toString();
                if(cents<10)
                    cents = "0" + cents;
                for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                    num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
                return (((sign)?'':'-') + num + '.' + cents);
            }
        });

    var addObj = null;
    function getUserAddress()
    {
        $.ajax({
            'url'		: '<?php echo $this->createUrl('asyn/userAddress'); ?>',
            'dataType'	: 'html',
            'success'	: function(html)
            {
                $('#addAddress').siblings('li').remove();
                html = $.trim(html);
                if (html)
                {
                    $('#addAddress').before(html);
					$('#address>li:eq(0)').click();
                    var _current = $('#address>li.current') , _p = _current.children('p') , _code = '';
                    $('#userAddressID').val(_current.attr('val'));
                    _code = '<li>配送至：'+ _p.eq(1).text() + _p.eq(2).text() +'</li>' +
                    '<li>收货人：'+ _p.eq(0).text() +'</li>';
                    $('.pre-address').html(_code);

                    
                }else{
                    $('#addAddress').click();
                }
            },
            'error'		: function()
            {
                layer.msg('地址请求失败!');
            }
        });
    }

    $(document).ready(function()
    {
        $('.confirm-txt-list').on('change' , 'select' , function(){editPrice()});

        $('.shipping-address')
            //添加
            .on('click' , 'li#addAddress' , function()
            {
                addObj && addObj.remove();

                addObj = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('asyn/address'); ?>"></iframe>');
                $('body').append(addObj);
            })
            //修改
            .on('click' , 'a.js-mod' , function()
            {
                addObj && addObj.remove();
                addObj = $('<iframe class="pop-iframe" src="'+$(this).attr('href')+'"></iframe>');
                $('body').append(addObj);
                return false;
            })
            //设为默认地址
            .on('click' , 'a.set-default' , function()
            {
                var e = this;
                $.ajax({
                    'url'		: $(e).attr('href'),
                    'dataType'	: 'json',
                    'success'	: function(json)
                    {
                        getUserAddress();
                    },
                    'error'		: function(){layer.msg('请求失败!')}
                });
                return false;
            })
            //选择
            .on('click' , 'li[class!="last"]' , function()
            {
                $(this).addClass('current').siblings('li.current').removeClass('current');
                $('#userAddressID').val($(this).attr('val'));

                var _p = $(this).children('p') , _code = '';
                _code = '<li>配送至：'+ _p.eq(1).text() + _p.eq(2).text() +'</li>' +
                '<li>收货人：'+ _p.eq(0).text() +'</li>';
                $('.pre-address').html(_code);

//                $.ajax({
//                    'url'		: '<?php //echo $this->createUrl('cart/orderFreight'); ?>//',
//                    'data'		: {'dict':$(this).attr('dict') , 'weight':'<?php //echo join('|' , $_html_weight); ?>//'},
//                    'dataType'	: 'json',
//                    'success'	: function(json)
//                    {
//                        if (json.code === 0)
//                        {
//                            layer.msg(json.message);
//                        }else{
//                            layer.msg(json.message);
//                        }
//                    },
//                    'error'		: function(){layer.msg('请求失败!')}
//                });
            })
            //显示&隐藏全部收货地址
            .on('click' , 'nav>a' , function()
            {
                if ($(this).text() == '显示全部收货地址')
                {
                    $(this).text('隐藏收货地址');
                    $('#address>li').show();
                }else{
                    $(this).text('显示全部收货地址');

                    var _cur = $('#address>li.current');
                    if (_cur.index() > 2)
                        $('#address').prepend(_cur);
                    $('#address>li[class!="last"]:gt(2)').hide();
                }
            });
        $('.select-models').on('click' , 'a' , function()
        {
            var _val = $(this).attr('val') , _key = $(this).attr('key');
            $(this).addClass('current').siblings('.current').removeClass('current');
            $(this).siblings(':hidden').val(_val);

            if (_key)
            {
                if (_key == 'oneself')
                {
                    $(this).siblings('q').show();
                }else{
                    $(this).siblings('q').hide();
                }
                editPrice();
            }
        });

        $('.create-order').click(function()
        {
            if (parseInt($('#userAddressID').val()||0 , 10) < 1)
            {
                layer.msg('请选择收货地址');
                return false;
            }
            if (parseInt($('#deliveryWay').val()||0 , 10) < 1)
            {
                layer.msg('请选择配送方式');
                return false;
            }
            $('#createOrders').submit();
            return false;
        });

        getUserAddress();
    });
</script>