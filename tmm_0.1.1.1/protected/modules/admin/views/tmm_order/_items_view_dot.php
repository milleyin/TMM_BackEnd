
<div class="group_container">
    <div class="group_header">
        <span class="title">行程安排</span>
<?php
$data_array=array();
$info_array=array();
	foreach($model->Order_OrderShops as $order_shops)
	 {
?>
            <div class="group_wrap">
                <span class="warp_day"><?php  echo CHtml::encode($order_shops->shops_name); ?></span>
                <h2>
                    <?php  echo CHtml::link(CHtml::encode($order_shops->shops_name),array('/admin/tmm_dot/view','id'=>$order_shops->shops_id));?>
                </h2>
                <?php

                 foreach ($order_shops->OrderShops_OrderItems as $k=>$value) {

                ?>
                    <div class="warp_left">
                        <div class="item">
                            <span class="item_id"><?php echo CHtml::encode($k + 1); ?></span>

                             <div class="item_content">
                                <h3>
                                    <?php  echo CHtml::link(CHtml::encode($value->items_name),array('/admin/tmm_'.$value->OrderItems_ItemsClassliy->admin.'/view','id'=>$value->items_id));?>
                                    <span>
                                    <?php echo CHtml::encode($value->items_c_name);
                                    ?>
                                </span>&nbsp;&nbsp;消费码：
                                    <span style="width: 60px;" title="<?php echo $value->employ_time==0?'':date('Y-m-d H:i:s',$value->employ_time);?>">
                                        <?php echo CHtml::encode(OrderItems::$_is_barcode[$value->is_barcode]); ?>
                                    </span>
                                    <span style="width: 60px;">
                                        <?php echo CHtml::encode(Items::$_free_status[$value->items_free_status]); ?>
                                    </span>
                                </h3>

                                <div class="store">
                                    <label><strong>所属供应商：</strong></label>
                                    <span title="<?php echo $value->OrderItems_StoreUser->phone;?>">
                                    	<?php echo CHtml::encode($value->OrderItems_StoreUser->Store_Content->name).'('.$value->OrderItems_StoreUser->phone.')';?>
                                    </span>
                                </div>
                                <div class="address">
                                    <label>地址：</label>
                                    <span><?php echo CHtml::encode($value->items_address);
                                        ?></span>
                                </div>
                                 <div class="address">
                                     <label>接单状态：</label>
                                    <span><strong><?php  echo CHtml::encode(OrderItems::$_is_shops[$value->is_shops]); ?></strong></span>
                                 </div>
                            </div>

                          <div class="item_shops">
                                <span class="item_shops_title">已选商品</span>
                                <?php echo Fare::show_order_organizer_fare($value,$value->items_c_id == 2 ? true : false);
                                ?>

                          </div>
                         <div class="clearfix"></div>
                     </div>
                  </div>
                     <div class="warp_right">
                         <h4>项目简介：</h4>

                         <p><?php echo CHtml::encode($value->shops_info); ?>
                         </p>
                     </div>
                     <div class="clearfix"></div>
               <?php
                }
                ?>

            </div>
            <?php
        }
?>
</div>
</div>