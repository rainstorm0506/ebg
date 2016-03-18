<?php
    Yii::app()->clientScript->registerCoreScript('layer');
?>
<style type="text/css">
	select{border:1px solid #CCC;text-align:center;margin-right:4px; height: 30px;}
	.set-yes{color: #999;}
	.set-not{color: #ff0000;}
</style>
<main>
    <section class="merchant-content merchant-content-a">
        <!-- 搜索框 -->
        <form class="mer-search mer-search-a" method="get" action="<?php echo $this->createUrl('list'); ?>">
	        <select name="class_one"><option value=""> - 一级分类 - </option></select>
	        <select name="class_two"><option value=""> - 二级分类 - </option></select>
	        <select name="class_three"><option value=""> - 三级分类 - </option></select>
        <?php
            echo CHtml::dropDownList('shelf',(empty($_GET['shelf'])?0:(int)$_GET['shelf']),CMap::mergeArray(array(''=>' - 上下架状态 - '),$shelfStatus),array('class'=>'she'));
            echo CHtml::dropDownList('verify',(empty($_GET['verify'])?0:(int)$_GET['verify']),CMap::mergeArray(array(''=>' - 审核状态 - '),$verifyStatus),array('class'=>'sta'));
        ?>
            <button type="submit" class="btn-1 btn-1-7"  style="border: none;">查询</button>
            <input type="text" name="keyword" class="tbox28 tbox28-3 mr10px" placeholder="支持搜索商品名称、货号" style="width:160px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">

        </form>
        <!-- table -->
        <form method="post" action="<?php echo $this->createUrl('batch'); ?>">
        <table class="goods-tab">
            <colgroup>
                <col width="30">
                <col width="5%">
                <col width="auto">
                <col width="15%">
                <col width="10%">
                <col width="10%">
	            <col width="9%">
                <col width="7%">
                <col width="10%">
                <col width="18%">
            </colgroup>
            <thead>
            <tr>
                <td><input class="select-all" type="checkbox"></td>
                <td class="goods-tab-tit" colspan="7">
                    <span>全部</span>
                    <i class="ico-yes"></i><button type="submit" name="shelf" value="1001" "class="tj"  style="border: none;">上架</button>
                    <i class="ico-no"></i><button type="submit" name="shelf" value="1002" class="tj"  style="border: none;">下架</button>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th class="tl">编号</th>
                <th>商品名</th>
                <th>商品分类</th>
                <th>品牌</th>
                <th>货号</th>
	            <th>关键字设定</th>
                <th>上架状态</th>
                <th>审核状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $val): ?>
            <tr>
                <td>
                    <?php if($val['status_id']==1013):?>
                    <input type="checkbox" style="margin-right:4px;" name="id[]" value="<?php echo $val['id']; ?>"/>
                    <?php endif;?>
                </td>
                <td class="tl"><?php echo $val['id']; ?></td>
                <td><?php echo $val['title']; ?></td>
                <td>
                <?php
	                echo join('-' , array(
		                GlobalUsedClass::getClassName($val['class_one_id']),
		                GlobalUsedClass::getClassName($val['class_two_id']),
		                GlobalUsedClass::getClassName($val['class_three_id'])
	                ));
                ?>
                </td>
                <td><?php echo GlobalBrand::getBrandName($val['brand_id']); ?></td>
                <td><?php echo $val['goods_num']; ?></td>
	            <td>
	            <?php
		            $sk = $val['seo_title'] ? '<span class="seo set-yes">(已设置)</span>' : '<span class="seo set-not">(未设置)</span>';
		            echo CHtml::link($sk , $this->createUrl('seo' , array('id'=>$val['id'])));
	            ?>
	            </td>
                <td><i class="<?php echo $val['shelf_id']==1001?'ico-yes':($val['shelf_id']==1002?'ico-no':''); ?>"></i></td>
                <td class="gc"><?php echo $val['status_id']==1011?'待审核':($val['status_id']==1013?'审核通过':'审核失败') ; ?></td>
                <td class="control">
                <?php
                    echo CHtml::link('<i class="btn-mod"></i><span>复制 </span>' , $this->createUrl('copy' , array('id'=>$val['id']))).'<i>|</i>';
                    echo CHtml::link('<i class="btn-mod"></i><span> 查看 </span>' , $this->createUrl('intro' , array('id'=>$val['id']))).'<i>|</i>';
                    echo CHtml::link('<i class="btn-mod"></i><span> 编辑 </span>' , $this->createUrl('modify' , array('id'=>$val['id'])), array('class' => 'link-modify')).'<i>|</i>';
                    echo CHtml::link('<i class="btn-del"></i><span> 删除</span>' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
                ?>
                </td>
            </tr>
            <?php endforeach; if (!$list): ?>
                <tr><td colspan="10" class="else">没 有 更 多 二 手 商 品</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </form>
        <!-- pager -->
        <?php $this->widget('WebListPager', array('pages' => $page)); ?>
    </section>
</main>
<script>
    var classJSON = <?php echo json_encode($class); ?> , classInit = {
	    'class_one'		: <?php echo empty($_GET['class_one'])?0:(int)$_GET['class_one']; ?>,
	    'class_two'		: <?php echo empty($_GET['class_two'])?0:(int)$_GET['class_two']; ?>,
	    'class_three'	: <?php echo empty($_GET['class_three'])?0:(int)$_GET['class_three']; ?>,
    };
    function selectReset(evt , val){evt.html('<option selected="selected" value="">'+val+'</option>')}
    function selectvaluation(evt , json , child_id , val)
    {
	    var code = i = '';
	    if (!$.isEmptyObject(json))
	    {
		    for (i in json)
			    code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
	    }
	    evt.html('<option value="">'+val+'</option>' + code);
    }
    $(document).ready(function()
    {
        //上下架
        $('button[name="shelf"]').click(function(){
            
        	var s = this;
        	var len = $("input:checkbox:checked").length; 
        	if(len==0){
				layer.msg('请选择要操作的商品!');
		        return false;
        	}
        })
        //删除
    	$('a.link-delete').click(function()
		{
			var e = this;
			layer.confirm
			(
				'你确认删除商品吗？',
				function()
				{
					window.location.href = $(e).attr('href');
					return true;
				}
			);
			return false;
		});
		//编辑
        $('a.link-modify').click(function()
        		{
        			var e = this;
        			layer.confirm
        			(
        				'编辑后会改变审核状态和上下架状态，你确认编辑商品吗？',
        				function()
        				{
        					window.location.href = $(e).attr('href');
        					return true;
        				}
        			);
        			return false;
        		});
	    $('.select-all').click(function(){
		    if($(this).prop("checked"))
		    {
			    $("input[type='checkbox']").prop("checked",true);

			    $(".select-all").html("");
		    }
		    else
		    {
			    $("input[type='checkbox']").prop("checked",false);
			    $(".select-all").html("");
		    }
	    })
	    $('.mer-search ')
		    .on('change' , 'select[name="class_one"]' , function()
		    {

			    var thisID = parseInt($(this).val() || 0 , 10) , nextSelect = $('.mer-search select[name="class_two"]');
			    selectReset(nextSelect , ' - 二级分类 - ');
			    selectReset($('.mer-search  select[name="class_three"]') , ' - 三级分类 - ');
			    if (thisID && !$.isEmptyObject(classJSON[thisID].child))
			    {
				    selectvaluation(nextSelect , classJSON[thisID].child , classInit.class_two , ' - 二级分类 - ');
				    if (classInit.class_two > 0)
					    nextSelect.change();
			    }
		    })
		    .on('change' , 'select[name="class_two"]' , function()
		    {
			    var
				    oneID = parseInt($('.mer-search  select[name="class_one"]').val() || 0 , 10) ,
				    thisID = parseInt($(this).val() || 0 , 10) ,
				    nextSelect = $('.mer-search  select[name="class_three"]');

			    selectReset(nextSelect , ' - 三级分类 - ');
			    if (oneID && thisID && !$.isEmptyObject(classJSON[oneID].child[thisID].child))
				    selectvaluation(nextSelect , classJSON[oneID].child[thisID].child , classInit.class_three , ' - 三级分类 - ');
		    })
		    .on('click' , '.search-button' , function()
		    {
			    $(this).parent('form').submit();
		    });

	    //给 class_one 赋值
	    selectvaluation($('.mer-search  select[name="class_one"]') , classJSON , classInit.class_one , ' - 一级分类 - ');
	    $('.mer-search select[name="class_one"]').change();

        //提交
        $('.section').on('click', '.btn-1', function () {
            $(this).parent('form').submit();
        });
    })
</script>