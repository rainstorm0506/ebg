<?php
$this->renderPartial('navigation');?>
<style type="text/css">
    .purviews{margin:10px 10px 20px 10px}
    .purviews dd{height:40px;line-height:40px;overflow:hidden; border-bottom:1px dotted #CCC;}
    .purviews dt .errorMessage{margin:10px 0 0 0}
    .purviews label{display:inline-block;width:120px;margin:0}
    .show table.Modify .purviews label input{margin:5px}
    .purviews a{margin:0 10px}
    table.list th{background-color:#FBFBD7; padding:1em;}
    table.list td{padding:0 0.5em; border-bottom:1px dashed #CCC; height:30px;line-height:30px}
    .btn-re{color: #ffffff; border: solid #4c4c4c 1px; border-radius: 10px; background: #ff0000; position: relative;left: 50px;}
</style>
<div class="show">
    <table class="list">
        <tr><th colspan="2">二手分类详情</th></tr>
        <tr>
            <th align="right" width="20%" >分类名称</th>
            <td align="center" style="font-size: 18px;"><?php echo $class['title'];?></td>
        </tr>
        <tr>
            <th align="right" width="20%" >价格区间</th>
            <td align="center" style="font-size: 18px;">

                    <?php foreach($price as $val):?>
                        <p style="color:#666;"><?php echo $val['price_start'];?>元-<?php echo $val['price_end'];?>元</p>
                    <?php endforeach;?>

            </td>
        </tr>
        <tr>
            <th align="right" width="20%" >属性</th>
            <td>
                <table class="list">
                    <?php foreach($attr as $v):?>
                    <tr>
                        <td align="center" width="15%" style="border-right: solid 1px #ccc; font-size: 22px; height: auto;"><?php echo $v['title'];?></td>
                        <td>
                            <ul>
                                <?php foreach($v['value'] as $v1):?>
                                    <li style="border-bottom: dashed #ccc 1px; text-align: left;"><?php echo $v1['title'];?></li>
                                <?php endforeach;?>
                            </ul>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </td>
        </tr>

    </table>
    <div class="sh">
    <?php
        echo CHtml::link('<i></i><span class="btn-1">返回列表</span>' , $this->createUrl('list' ));
    ?>
    </div>
</div>
