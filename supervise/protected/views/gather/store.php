<?php
    Views::css('goods.create');
    Views::js(array('jquery-dragPlug'));
    $this->renderPartial('navigation');
?>
<style type="text/css">
    .form-wraper li > span{width:150px}
    .attrs ul{border:1px solid #CCC;padding:20px 0 20px 0;margin:0 0 20px 0;width:700px}
    .attrs b{font-weight:400;margin:0 0 0 20px;color:#333}
    .form-wraper .attrs input{width:462px}
    .attrs input.error{color:#F00}
    .attrs .errorMessage{padding:0 0 0 100px}
    .attrs .heads{border-bottom:1px dotted #CCC;padding:0 0 10px 0}
    .attrs .heads input{width:462px}
    .attrs a{display:inline-block;border:1px solid #999;border-radius:5px;padding:0 12px;background-color:#EEE;cursor:pointer}
    .attrs a:hover{background-color:#999}
    .attrs a.dels{border:0 none;background-color:#FFF;color:#00F}
    .attrs-delete{margin:0 0 0 254px}
    .gcShow span{font-size:14px}
    .gcShow{padding:0 0 0 20px}
    .gcShow i{margin:0 25px;font-style:normal;color:#F00;font-weight:900;font-size:20px}
    #class_two_id{margin:0 20px}
    .form-wraper li em.x{margin:0;color:#000}
    input.default-val{color:#666}
    input.default-val.this{color:#000}
    .dicts{line-height:26px}
    .dicts input{margin:0 6px 0 20px;vertical-align:baseline}
    form select{width:100px}
    #store{color: red;}
</style>
<fieldset class="public-wraper">
	<h1 class="title"><?php echo isset($info['id'])?'编辑':'添加';?> 店铺编号</h1>
    <?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
        <ul>
            <?php
                $form->id=$form->id ? $form->id : isset($info['id']) ? $info['id'] : '';
                echo $active->hiddenField($form , 'id');
                echo $active->error($form , 'id');
            ?>
            <li>
                <span><em>*</em> 所属电脑城：</span>

                <?php
                echo ' <p>';
                $form->gather = $form->gather ? $form->gather : (isset($info['parent_id'])?$info['parent_id']:0);
                echo $active->dropDownList($form , 'gather' , CMap::mergeArray(array(''=>' - 请选择 - '),$computer) , array('id'=>'gather','class'=>'sbox36'));
                #echo $active->error($form , 'gather',array('id'=>'fnone'));
                ?>
            </li>
            <li>
                <span><em>*</em> 楼层：</span>

                <?php
                echo ' <p>';
                $form->storey = $form->storey ? $form->storey : (isset($info['storey'])?$info['storey']:0);
                echo $active->dropDownList($form , 'storey' ,  CMap::mergeArray(array(''=>' - 请选择 - '),isset($info['storey'])?$storey:array()) , array('id'=>'storey','class'=>'sbox36'));
                #echo $active->error($form , 'storey',array('id'=>'fnone'));
                ?>
            </li>
			<li>
                <span><em>*</em> 店铺编号：</span>

				<?php
                        echo ' <p>';
                        $form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
                        echo $active->textField($form , 'title' , array('class'=>'tbox38'));
                        echo $active->error($form , 'title',array('id'=>'store'));
				?>
			</li>
            <li>
                <span><em>*</em> 排序：</span>

                <?php
                echo ' <p>';
                $form->rank = $form->rank ? $form->rank : (isset($info['rank'])?$info['rank']:0);
                echo $active->textField($form , 'rank' , array('class'=>'tbox38'));
                echo $active->error($form , 'rank',array('id'=>'store'));
                echo '<span style="width: 280px; line-height: 36px;">注：按照数字大小排序，数字越大越靠后！</span>';
                ?>
            </li>
            <li>
				<h6>&nbsp;</h6>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
    var gather = {
        'gather'	: 0 ,
        'storey'    : 0
    } , gatherOld = {
        'gather'	: <?php echo $form->gather; ?> ,
        'storey'	: <?php echo $form->storey; ?>
    };
    function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
    function selectvaluation(id , json ,child_id)
    {
        var code = i = '';
        for (i in json)
            code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
        $('#'+id).html('<option value=""> - 请选择 - </option>' + code);
    }
    $(document).ready(function(){
        //楼层
        $('select#gather').change(function(){
            var e = this , id = $(e).attr('id') , val = $(e).val();
            gather={'gather':val}
            $.getJSON('<?php echo $this->createUrl('gather/getStorey'); ?>' , gather , function(json){
                json = jsonFilterError(json);
                if (val)
                {
                    selectvaluation('storey' , json , gatherOld.storey );
                    $('#storey').change();
                }
            });
        })
        $('input:reset').click(function(){window.location.reload();});
    })
</script>