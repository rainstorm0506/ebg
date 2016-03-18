<style type="text/css">
	.navigation form select{border:1px solid #CCC;text-align:center;width:120px; margin-right:8px; height: 38px;}
    #ConvertForm_keyword{width: 140px; height: 38px;}
    #ConvertForm_user{width: 140px; height: 38px;}
    .btn-14{height: 38px; line-height: 38px;border: 1px solid #ccc;  border-radius: 3px;  display: inline-block;color: #09f;
        padding:0 12px;float: left;text-decoration: none;font-size: 100%;}
</style>
<div class="navigation">
	<span>
		<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
        <ul>
            <li style="border: none; margin: 0 10px; padding: 0;">
                <span style="width:88px; line-height: 38px;">兑换时间：</span>
                <?php
                    $form->convert_time = $form->convert_time ? $form->convert_time : '';
                    $active->widget ( 'Laydate', array (
                        'form' => $form,
                        'id' => 'convert_time',
                        'name' => 'convert_time',
                        'class' => "tbox38 tbox38-1",
                        'style' => 'width:200px'
                    ) );
                ?>
            </li>
            <li style="border: none; margin: 0 10px; padding: 0;">
                <span style="width:88px; line-height: 38px;">领取时间：</span>
                <?php
                    $form->accept_time = $form->accept_time ? $form->accept_time : '';
                    $active->widget ( 'Laydate', array (
                        'form' => $form,
                        'id' => 'accept_time',
                        'name' => 'accept_time',
                        'class' => "tbox38 tbox38-1",
                        'style' => 'width:200px'
                    ) );
                ?>
            </li>
            <li style="border: none;height: 38px;;margin:0 10px; padding: 0;">
                <span style="width:88px; line-height: 38px;">兑换商品：</span>
                <?php
                    $form->keyword = $form->keyword ? $form->keyword : '';
                    echo $active->textField ($form, 'keyword', array('placeholder'=>'商品名称和货号搜索'), array ('class' => 'tbox38 tbox38-1'));
                ?>
            </li>
            <li style="border: none; margin:0 10px; padding: 0; height: 38px;">
                <span style="width:88px; line-height: 38px;">会员信息：</span>
                <?php
                    $form->user = $form->user ? $form->user : '';
                    echo $active->textField ($form , 'user',  array('placeholder'=>'会员姓名和电话搜索'), array ('class' => 'tbox38 tbox38-1'));
                ?>
            </li>
            <li style="border: none; margin: 0 10px; padding: 0; height: 38px;">
                <?php
                    $form->delivery = isset($form->delivery) ? (int)$form->delivery : '';
                    echo $active->dropDownList($form , 'delivery' , CMap::mergeArray(array(''=>' - 配送方式 - ') , array(1=>'上门自取',2=>'市内配送')) , array('class'=>'sbox36'));
                ?>
            </li>
            <li style="border: none; margin: 0 10px; padding: 0; height: 38px;">
                <?php
                    $form->status = isset($form->status) ? (int)$form->status : '';
                    echo $active->dropDownList($form , 'status' , CMap::mergeArray(array(''=>' - 领取状态 - ') , array(1=>'已领取',2=>'未领取',3=>'未配送')) , array('class'=>'sbox36'));
                ?>
            </li>
            <li style="border: none; margin: 0 10px; padding: 0; height: 38px;">
                <?php echo CHtml::submitButton('查询' , array('class'=>'btn-14')); ?>
            </li>
        </ul>
        <?php $this->endWidget(); ?>
	</span>
	<i class="clear"></i>
</div>