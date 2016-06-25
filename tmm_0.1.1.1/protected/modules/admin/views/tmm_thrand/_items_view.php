<?php
    $this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');
?>
<div class="group_container">
    <div class="group_header">
        <span class="title">行程安排</span>
    </div>
    <?php

    $item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
    $data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
    $info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();    
		
		foreach ($data_array as $key=>$data_dot_sort) 
		{
    ?>
        <div class="group_wrap">
        <span class="warp_day"><?php echo CHtml::encode(Pro::item_swithc($key)); ?></span>
        <?php
        foreach ($data_dot_sort as $data_dot)
		{     
        	foreach ($data_dot as $dot_name => $data_items)
			{
        ?>
            <div class="warp_left">
            <h2>
                <?php echo CHtml::link(CHtml::encode($info_array['dot_data'][$dot_name]->name),array('/admin/tmm_dot/view','id'=>$info_array['dot_data'][$dot_name]->id));

                  //  if($data->Pro_Items->status != Items::status_online)
                   //     echo "<span style='color: red'>（已". Items::$_status[$data->Pro_Items->status].")</span>";
                ?>
            </h2>
            <?php
                foreach ($data_items as $sort => $items) {
                    if($items->Pro_Thrand_Dot->Dot_Shops->status != Shops::status_online)
                        echo "<span style='color: red'>（当前点已". Shops::$_status[$items->Pro_Thrand_Dot->Dot_Shops->status].")</span>";
            ?>

                    <div class="item">
                    <span class="item_id"><?php echo CHtml::encode($sort+1);?></span>

                    <div class="item_content">
                        <h3>
                            <?php
                            echo CHtml::link(CHtml::encode($items->Pro_Items->name),array('/admin/tmm_'.$items->Pro_Items->Items_ItemsClassliy->admin.'/view','id'=>$items->Pro_Items->id));
                            if($items->Pro_Items->status != Items::status_online)
                                echo "<span style='color: red'>（已". Items::$_status[$items->Pro_Items->status].")</span>";
                            ?>
                            <span title="<?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->info);?>"><?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);?></span>
                        </h3>

                        <div class="store">
                            <label>所属供应商：</label>
                            <span><?php echo CHtml::encode($items->Pro_Items->Items_StoreContent->name);?></span>
                        </div>

                        <div class="address">
                            <label>地址：</label>
                            <span><?php echo CHtml::encode(
                                $items->Pro_Items->Items_area_id_p_Area_id->name.
                                $items->Pro_Items->Items_area_id_m_Area_id->name.
                                $items->Pro_Items->Items_area_id_c_Area_id->name.
                                $items->Pro_Items->address);
                                ?></span>
                        </div>
                    </div>

                    <div class="item_shops">
                        <span class="item_shops_title">已选商品</span>
                        <?php  echo Fare::show_fare($items->Pro_ProFare,$items->Pro_Items->Items_ItemsClassliy->append=="Hotel"?true:false,true); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php
                }
            ?>
            </div>
        <?php
      		 }
		}
        ?>
            <div class="warp_right">
                <h4>日程亮点：</h4>
                <p><?php echo CHtml::encode($info_array[$key]['info']);?>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
<?php
}
 ?>
</div>