<?php
$this->addCss(Yii::app()->baseUrl.'/css/admin/main/right/group_items.css');
?>
<div class="group_container">
    <div class="group_header">
        <span class="title">行程安排</span>
    </div>
    <?php
//
//    $item_arr = Pro::circuit_info($model->Thrand_Pro,'Pro_Thrand_Dot');
//    $data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
//    $info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();
    $info_array = array();
    $data_array = array();
    foreach ($model->Order_OrderItems as $value)
    {
        $data_array[$value->shops_day_sort][$value->shops_half_sort][$value->shops_dot_id][$value->shops_sort]=$value;
        $info_array['dot_data'][$value->shops_dot_id]=$value->shops_dot_id;
        if($value->shops_half_sort==0 && $value->shops_sort==0)
            $info_array[$value->shops_day_sort]=array('info'=>$value->shops_info,'data'=>$value);
    }

    foreach ($data_array as $key=>$data_dot_sort)
    {
        ?>
        <div class="group_wrap">
            <span class="warp_day"><?php echo CHtml::encode(Pro::item_swithc($key)); ?></span>
            <?php
            foreach ($data_dot_sort as $data_dot)
            {
                $num = 0;
                foreach ($data_dot as $dot_name => $data_items)
                {
                    ?>
                    <div class="warp_left">
                        <h2>
                            <?php echo CHtml::link(CHtml::encode($data_items[$num]->shops_name),array('/admin/tmm_dot/view','id'=>$dot_name));

                            //  if($data->Pro_Items->status != Items::status_online)
                            //     echo "<span style='color: red'>（已". Items::$_status[$data->Pro_Items->status].")</span>";
                            ?>
                        </h2>
                        <?php

                        foreach ($data_items as $sort => $items) {
                           // if($items->Pro_Thrand_Dot->Dot_Shops->status != Shops::status_online)
                            //    echo "<span style='color: red'>（当前点已". Shops::$_status[$items->Pro_Thrand_Dot->Dot_Shops->status].")</span>";
                            ?>

                            <div class="item">
                                <span class="item_id"><?php echo CHtml::encode($sort+1);?></span>

                                <div class="item_content">
                                    <h3>
                                        <?php
                                        echo CHtml::link(CHtml::encode($items->items_name),array('/admin/tmm_'.$items->OrderItems_ItemsClassliy->admin.'/view','id'=>$items->items_id));
                                         //if($items->Pro_Items->status != Items::status_online)
                                            //  echo "<span style='color: red'>（已". Items::$_status[$items->Pro_Items->status].")</span>";
                                          ?>
                                          <span title="<?php echo CHtml::encode($items->items_c_name);?>"><?php echo CHtml::encode($items->items_c_name);?></span>
                                         &nbsp;&nbsp;消费码：

                                        <span style="width: 60px;" title="<?php 
												echo isset($items->OrderItems_OrderItems->employ_time) && 
												$items->OrderItems_OrderItems->employ_time!=0?date('Y-m-d H:i:s',$items->OrderItems_OrderItems->employ_time):'';
												?>">
											<?php
												echo isset($items->OrderItems_OrderItems->is_barcode) ?CHtml::encode(OrderItems::$_is_barcode[$items->OrderItems_OrderItems->is_barcode]):'无效'; 
											?>
                                        </span>
                                     <span style="width: 60px;">
                                        <?php echo CHtml::encode(Items::$_free_status[$items->items_free_status]); ?>
                                    </span>
                                      </h3>

                                      <div class="store">
		                                   <label><strong>所属供应商：</strong></label>
		                                    <span title="<?php echo $items->OrderItems_StoreUser->phone;?>">
	                                    	<?php echo CHtml::encode($items->OrderItems_StoreUser->Store_Content->name).'('. $items->OrderItems_StoreUser->phone.')';?>
		                                    </span>
                                       </div>

                                      <div class="address">
                                          <label>地址：</label>
                                         <span><?php echo CHtml::encode($items->items_address);?></span>
                                      </div>
                                    <div class="address">
                                        <label>接单状态：</label>
                                        <span><strong><?php  echo CHtml::encode(OrderItems::$_is_shops[$value->is_shops]); ?></strong></span>
                                    </div>
                                  </div>

                                  <div class="item_shops">
                                      <span class="item_shops_title">已选商品</span>
                                      <?php echo Fare::show_order_organizer_fare($items,$items->OrderItems_ItemsClassliy->append=="Hotel"?true:false);  //echo Fare::show_fare($items,$items->OrderItems_ItemsClassliy->append=="Hotel"?true:false,true); ?>
                                  </div>
                                  <div class="clearfix"></div>
                              </div>
                              <?php
                          }

                        ?>
                    </div>
                    <?php
                    $num ++;
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