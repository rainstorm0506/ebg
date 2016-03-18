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
    #fnone,#fnone1{ color: red; line-height: 40px;}
    .dicts{line-height:26px}
    .dicts input{margin:0 6px 0 20px;vertical-align:baseline}
    form select{width:100px}
</style>
<fieldset class="public-wraper">
	<h1 class="title"><?php echo isset($info['id'])?'编辑':'添加';?> 电脑城</h1>
    <?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
        <ul>
            <?php
                $form->id=$form->id ? $form->id : isset($info['id']) ? $info['id'] : '';
                echo $active->hiddenField($form , 'id');
                echo $active->error($form , 'id');
            ?>
			<li>
                <span><em>*</em> 名称：</span>

				<?php
                        echo ' <p>';
                        $form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
                        echo $active->textField($form , 'title' , array('class'=>'tbox38'));
                        echo '&nbsp;&nbsp;注：电脑城名称！&nbsp;&nbsp;';
                        echo $active->error($form , 'title',array('id'=>'fnone'));
				?>
			</li>
            <li>
                <span><em>*</em> 所在地：</span>
                <ul>
                    <li style="float: left; margin-right: 20px;">
                        <?php
                        CHtml::$errorContainerTag = 'span';
                        $form->dict_one_id = isset($form->dict_one_id) ? (int)$form->dict_one_id : (isset($info['dict_one_id'])?(int)$info['dict_one_id']:0);
                        echo $active->dropDownList($form , 'dict_one_id' , CMap::mergeArray(array(''=>' - 请选择 - '), GlobalDict::getUnidList()) , array('id'=>'one_id','class'=>'ajax-dict sbox36'));
                        echo $active->error($form , 'dict_one_id');
                        ?>
                    </li>
                    <li style="float: left; margin-right: 20px; position: relative;top: -10px;">
                        <?php
                        $form->dict_two_id = isset($form->dict_two_id) ? (int)$form->dict_two_id : (isset($info['dict_two_id'])?(int)$info['dict_two_id']:0);
                        echo $active->dropDownList($form , 'dict_two_id' , array(''=>' - 请选择 - ') , array('id'=>'two_id','class'=>'ajax-dict sbox36'));
                        ?>
                    </li>
                    <li style="float: left; margin-right: 20px; position: relative;top: -10px;">
                        <?php
                        $form->dict_three_id = isset($form->dict_three_id) ? (int)$form->dict_three_id : (isset($info['dict_three_id'])?(int)$info['dict_three_id']:0);
                        echo $active->dropDownList($form , 'dict_three_id' , array(''=>' - 请选择 - ') , array('id'=>'three_id','class'=>'ajax-dict sbox36'));
                        ?>
                    </li>
                </ul>
            </li>

            <li>
				<h6>&nbsp;</h6>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
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

    function selectReset(id){$('#'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
    function selectvaluation(id , json , child_id)
    {
        var code = i = '';
        for (i in json)
            code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i]+'</option>';
        $('#'+id).html('<option value=""> - 请选择 - </option>' + code);
    }

    $(document).ready(function(){
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
</script>
