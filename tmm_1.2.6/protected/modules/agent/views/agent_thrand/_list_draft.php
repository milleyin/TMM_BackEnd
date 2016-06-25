<table class="table item_box">
    <tbody>
    <tr>
        <th class="s_head">
            <div class="total_title">
                <?php echo CHtml::link(CHtml::encode($data->Thrand_Shops->name), array("/agent/agent_thrand/view","id"=>$data->id));?>
            </div>

            <div class="row-fluid date">
                <div class="span3">
                    <div class="pull-left little_tag"><span>浏</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Thrand_Shops->brow); ?></span>
                </div>
                <div class="span3">
                    <div class="pull-left little_tag"><span>分</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Thrand_Shops->share); ?></span>
                </div>
                <div class="span3">
                    <div class="pull-left little_tag"><span>赞</span></div>
                    <span class="orderid"><?php echo CHtml::encode($data->Thrand_Shops->praise); ?></span>
                </div>
                <span class="datetime"><?php echo CHtml::encode( date('Y年m月d日', $data->Thrand_Shops->pub_time)); ?></span>
            </div>  <!-- .date -->
        </th>
    </tr>
    <?php

    $item_arr = Pro::circuit_info($data->Thrand_Pro,'Pro_Thrand_Dot');
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
                    </div>  <!-- .date_all -->
                    <div class="row-fluid span10 right line_right_box">
                        <?php
                                foreach ($data_dot_sort as $data_dot)
                                {
                                    foreach ($data_dot as $dot_id => $data_items)
                                    {
                            ?>
                                        <div class="spot_name">
                                       		 <?php echo CHtml::link(CHtml::encode($info_array['dot_data'][$dot_id]->name),array('/agent/agent_dot/view','id'=>$dot_id)); ?>
                                    <?php 
				                       		if($info_array['dot_data'][$dot_id]->status != Shops::status_online)
				                       		{
				                       	?>
				                        <span style="color: red">（已<?php echo Shops::$_status[$info_array['dot_data'][$dot_id]->status];?>）</span>
				                        <?php 
				                       		}
				                        ?>
                                        </div>
                                        <?php
                                              foreach ($data_items as $sort => $items)
                                              {
                                            ?>
                                                  <div class="row-fluid noe">
                                                <div class="span2 num">
                                                    <a href="javascript:;"><?php echo CHtml::encode($sort+1);?></a>
                                                </div>
                                                <div class="span10 content">
                                                    <div class="spot_info">
                                                        <div class="row-fluid controls controls-row name">
                                                <span class="span5">
                                                    <?php
                                                    echo CHtml::link(CHtml::encode(mb_substr($items->Pro_Items->name,0,4,'utf-8').' ...'),array('/agent/agent_'.$items->Pro_Items->Items_ItemsClassliy->admin.'/view','id'=>$items->Pro_Items->id));
                                                   ?>
                                                </span>
                                                            <div class="span1 pull-right little_tag">
                                                    <span title="<?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->info);?>">
                                                        <?php echo CHtml::encode($items->Pro_Items->Items_ItemsClassliy->name);?>
                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid belong_business">
                                                            <span class="span4">所属商家：</span>
                                                <span class="span8">
                                                    <?php echo CHtml::encode($items->Pro_Items->Items_StoreContent->name);?>
                                                </span>
                                                        </div>
                                                        <div class="row-fluid address">
                                                            <span class="span3">地址：</span>
                                                <span class="span9">
                                                    <?php echo CHtml::encode(
                                                        $items->Pro_Items->Items_area_id_p_Area_id->name.
                                                        $items->Pro_Items->Items_area_id_m_Area_id->name.
                                                        $items->Pro_Items->Items_area_id_c_Area_id->name.
                                                        $items->Pro_Items->address);
                                                    ?>
                                                </span>
                                                        </div>
                                                    </div>  <!-- .spot_info -->
                                                </div>  <!-- .content -->
                                            </div>  <!-- .noe-->
                            <?php        }
                                      }
                                }
                        ?>
                    </div>  <!-- .right -->
                </div>  <!-- .spot -->
            </td>
        </tr>
        <?php

    }
    ?>
    <tr class="last">
        <td>
            <div class="check_failed">
                <div class="row-fluid btn_group">
                    <a href="<?php echo Yii::app()->createUrl("/agent/agent_shops/delete",array("id"=>$data->id)); ?>" class="delete">删除</a>
                    <a href="<?php echo Yii::app()->createUrl("/agent/agent_thrand/update",array("id"=>$data->id)); ?>">编辑</a>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>  <!-- .table -->