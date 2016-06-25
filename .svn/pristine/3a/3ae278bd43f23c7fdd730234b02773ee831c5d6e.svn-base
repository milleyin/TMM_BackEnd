
<div class="items_container">
<?php 
	foreach ($model->Dot_Pro as $key=>$data){
?>
    <div class="view">
        <div class="list_left">
            <div class="list_title">
                <span class="list_name"><?php echo CHtml::encode($data->Pro_Items->name); ?></span>
                <span class="list_classliy" title="<?php echo CHtml::encode($data->Pro_ItemsClassliy->info); ?>">
                   <?php echo CHtml::encode($data->Pro_ItemsClassliy->name); ?>
                </span>
            </div>
            <div class="list_address">
                <span>地址:</span>
               <?php 
					echo CHtml::encode(
											$data->Pro_Items->Items_area_id_p_Area_id->name.
											$data->Pro_Items->Items_area_id_m_Area_id->name.
											$data->Pro_Items->Items_area_id_c_Area_id->name.
											$data->Pro_Items->address
					);
				?>
            </div>
            <div class="list_store">
                <label class="label_title">所属供应商:</label>
                <span title="供应商公司名称"><?php echo CHtml::encode($data->Pro_Items->Items_StoreContent->name); ?></span>
                <span title="供应商(主)账号"><?php echo CHtml::encode($data->Pro_Items->Items_StoreContent->Content_Store->phone); ?></span>
                <span title="项目管理账号"><?php echo CHtml::encode($data->Pro_Items->Items_Store_Manager->phone); ?></span>
            </div>


            <div class="list_fare">
                <span>商品信息</span>
               <?php echo Fare::show_fare($data->Pro_Items->Items_Fare,$data->Pro_ItemsClassliy->append=='Hotel'?true:false);?>
            </div>

        </div>
        <div class="list_id" style="display: none">
        	<?php echo $form->hiddenField($data,"[$key]id");?>
        </div>
        
        <div class="list_right">
            <h6><?php echo $form->labelEx($data,"[$key]info");?></h6>
            <?php echo $form->textArea($data,"[$key]info");?>
            <?php echo $form->error($data,"[$key]info");?>
        </div>
        <div style="clear: both;"></div>
        <div class="list_tool">
            <a href="javascript:;" onclick="" class="btn group_top" title="置顶">顶</a>
            <a href="javascript:;" onclick="" class="btn group_up" title="上移">上</a>
            <a href="javascript:;" onclick="" class="btn group_down" title="下移">下</a>
        </div>
    </div>
    <?php 
    	}
    ?>
</div>