<form class="form-horizontal" method="post" action="<?php echo $action ?>">
    <input type="hidden" name="id" value="<?php echo isset($data->id) ? $data->id : '' ?>"/>
    <div class="control-group">
        <label class="control-label" for="title">产品名称:</label>
        <div class="controls">
            <input type="text" id="title" name="title"  placeholder="title" value="<?php echo isset($data->title) ? $data->title : '' ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="image">图片:</label>
        <div class="controls">
            <input type="file" id="image" placeholder="Image" name="image">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="point">积分:</label>
        <div class="controls">
            <input type="text" id="point" placeholder="point" name="point" value="<?php echo isset($data->point) ? $data->point : '' ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="stock">库存:</label>
        <div class="controls">
            <input type="text" id="stock" placeholder="stock" name="stock" value="<?php echo isset($data->stock) ? $data->stock : '' ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="sort_order">排序:</label>
        <div class="controls">
            <input type="text" id="sort_order" placeholder="sort_order" name="sort_order" value="<?php echo isset($data->sort_order) ? $data->sort_order : '' ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="stock">上下架:</label>
        <div class="controls">
            <?php
            $option = array('0' => '下架', '1' => '上架');
            echo CHtml::dropDownList('is_active', isset($data->is_active) ? $data->is_active : 0, $option);
            ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">保存</button>
        </div>
    </div>
</form>