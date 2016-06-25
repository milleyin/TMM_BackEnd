<div class="group_container">
    <div class="group_header">
        <span class="title">行程安排</span>
<?php
$data_array=array();
$info_array=array();

foreach ($model->OrderOrganizer_OrderItems as $value)
{
    $data_array[$value->shops_day_sort][]=$value;
    if($value->shops_half_sort==0 && $value->shops_sort==0) {
        $info_array[$value->shops_day_sort]['shops_info'] = $value->shops_info;
        $info_array[$value->shops_day_sort]['shops_name'] = $value->shops_name;
        $info_array[$value->shops_day_sort]['shops_dot_id'] = $value->shops_dot_id;
    }
}

        foreach($data_array as $k=>$vs) {

            ?>
            <div class="group_wrap">
                <span class="warp_day"><?php echo CHtml::encode(Pro::item_swithc($k)); ?></span>
                        <h2>
                            <?php  echo CHtml::link(CHtml::encode($info_array[$k]['shops_name']),array('/admin/tmm_dot/view','id'=>$info_array[$k]['shops_dot_id']));?>
                        </h2>
                        <?php
                        foreach ($vs as $v) {
                        ?>
                            <div class="warp_left">
                        <div class="item">
                            <span class="item_id"><?php echo CHtml::encode($v->shops_sort + 1); ?></span>

                            <div class="item_content">
                                <h3>
                                    <?php  echo CHtml::link(CHtml::encode($v->items_name),array('/admin/tmm_'.$v->OrderItems_ItemsClassliy->admin.'/view','id'=>$v->items_id));?>
                                    <span>
                                    <?php echo CHtml::encode($v->items_c_name);
                                    ?>
                                </span>
                                </h3>

                                <div class="store">
                                    <label>所属供应商：</label>
                                    <span><?php echo CHtml::encode($v->OrderItems_StoreUser->Store_Content->name);
                                        ?></span>
                                </div>

                                <div class="address">
                                    <label>地址：</label>
                                    <span><?php echo CHtml::encode($v->items_address);
                                        ?></span>
                                </div>
                            </div>

                            <div class="item_shops">
                                <span class="item_shops_title">已选商品</span>
                                <?php echo Fare::show_order_organizer_fare($v,$v->items_c_id == 2 ? true : false);
                                ?>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <?
                }
                ?>
                <div class="warp_right">
                    <h4>日程亮点：</h4>

                    <p><?php echo CHtml::encode($info_array[$k]['shops_info']); ?>
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php

        }
?>
</div>