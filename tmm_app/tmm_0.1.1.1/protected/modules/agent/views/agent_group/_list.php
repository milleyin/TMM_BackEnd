<!-- 结伴中 -->
<table class="table item_box">
    <tbody>
    <tr>
        <th class="s_head">
            <div class="total_title">
                <?php echo CHtml::link(CHtml::encode($data->Group_Shops->name), array("/agent/agent_group/view","id"=>$data->id));?>
            </div>

            <?php if ($data->group == Group::group_peering) { ?>
                <div class="pull-right mark mask1"><span>结伴中</span></div>
            <?php } elseif($data->group == Group::group_cancel) { ?>
                <div class="pull-right mark mask2"><span>已取消</span></div>
            <?php } elseif($data->group == Group::group_user_confirm) { ?>
                <div class="pull-right mark mask3"><span>用户确认中</span></div>
            <?php } elseif($data->group == Group::group_store_confirm) { ?>
                <div class="pull-right mark mark4"><span>商家确认中</span></div>
            <?php } elseif($data->group == Group::group_already_peer) { ?>
                <div class="pull-right mark mark5"><span>已结伴</span></div>
            <?php } elseif($data->group == Group::group_no_peer) { ?>
                <div class="pull-right mark mark6"><span>未结伴</span></div>
            <?php } ?>
            <div class="row-fluid date">
                <div class="span3">
                    <div class="pull-left little_tag"><span>浏</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Group_Shops->brow); ?></span>
                </div>
                <div class="span3">
                    <div class="pull-left little_tag"><span>分</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Group_Shops->share); ?></span>
                </div>
                <div class="span3">
                    <div class="pull-left little_tag"><span>赞</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Group_Shops->praise); ?></span>
                </div>
                <span class="datetime"><?php echo CHtml::encode( date('Y年m月d日', $data->Group_Shops->pub_time)); ?></span>
            </div>
            <!-- .date -->
        </th>
    </tr>
    <?php

    $data_array=array();
    $info_array=array();
    $item_arr = Pro::circuit_info($data->Group_Pro,'Pro_Group_Dot');
    $data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
    $info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

    foreach ($data_array as $key=>$data_dot_sort)
    {

    ?>
    <tr>
        <td>
            <div class="row-fluid spot">
                <div class="row-fluid span1 date_all">
                    <a href="javascript:;"><?php echo CHtml::encode(Pro::item_swithc($key)); ?></a>
                </div>
                <!-- .date_all -->
                <div class="row-fluid span10 right line_right_box">
                <?php
                foreach ($data_dot_sort as $data_dot)
                {

                    foreach ($data_dot as $dot_name => $data_items)
                    {
                        ?>
                        <div class="spot_name">
                            <?php echo CHtml::link(CHtml::encode($info_array['dot_data'][$dot_name]->name),array('/agent/agent_dot/view','id'=>$info_array['dot_data'][$dot_name]->id)); ?>
                        </div>
                    <?php 
                    	if($info_array['dot_data'][$dot_name]->status!=Shops::status_online)
						{
                    ?>
                    <span style="colore:red;">（已<?php echo Shops::$_status[$info_array['dot_data'][$dot_name]->status];?>）</span>
                    <?php 
						}
                    ?>
                        <?php
                        foreach ($data_items as $sort => $items)
                        {
                            ?>
                            <div class="row-fluid noe">
                                <div class="span2 num">
                                    <a href="javascript:;"><?php echo CHtml::encode($items->half_sort + 1); ?></a>
                                </div>
                                <div class="span10 content">
                                    <div class="spot_info">
                                        <div class="row-fluid controls controls-row name">
                                        <span class="span5">
                                             <?php
                                             echo CHtml::link(CHtml::encode(mb_substr($items->Pro_Items->name,0,4,'utf-8').' ...'),array('/agent/agent_'.$items->Pro_Items->Items_ItemsClassliy->admin.'/view','id'=>$items->Pro_Items->id));
                                             ?>
                                        </span>
						                    <?php 
						                    	if($items->Pro_Items->status!=Items::status_online)
												{
						                    ?>
						                    <span style="color:red;">（已<?php echo Items::$_status[$items->Pro_Items->status];?>）</span>
						                    <?php 
												}
						                    ?>
                                            <div class="span1 pull-right little_tag">
                                            <span
                                                title="<?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->info); ?>">
                                                <?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name); ?>
                                            </span>
                                            </div>
                                        </div>
                                        <div class="row-fluid belong_business">
                                            <span class="span4">所属商家：</span>
                                        <span class="span8">
                                            <?php echo CHtml::encode($items->Pro_Items->Items_StoreContent->name); ?>
                                        </span>
                                        </div>
                                        <div class="row-fluid address">
                                            <span class="span3">地址：</span>
                                        <span class="span9">
                                            <?php echo CHtml::encode(
                                                $items->Pro_Items->Items_area_id_p_Area_id->name .
                                                $items->Pro_Items->Items_area_id_m_Area_id->name .
                                                $items->Pro_Items->Items_area_id_c_Area_id->name .
                                                $items->Pro_Items->address);
                                            ?>
                                        </span>
                                        </div>
                                    </div>
                                    <!-- .spot_info -->
                                </div>
                                <!-- .content -->
                            </div>
                            <!-- .noe-->
             <?php
                        }
                    }
                }
                ?>
                    </div>
                <!-- .right -->
            </div>
            <!-- .spot -->
        </td>
    </tr>
<?php } ?>
    </tbody>
</table>