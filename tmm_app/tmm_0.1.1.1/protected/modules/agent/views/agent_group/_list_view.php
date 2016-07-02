<div class="project">
    <div class="top_title">
        行程安排
    </div>
    <div class="line"></div>
<?php
$data_array=array();
$info_array=array();
$item_arr = Pro::circuit_info($model->Group_Pro,'Pro_Group_Dot');
$data_array = isset($item_arr['data_arr']) && $item_arr['data_arr'] ? $item_arr['data_arr'] : array();
$info_array = isset($item_arr['info_arr']) && $item_arr['info_arr'] ? $item_arr['info_arr'] : array();

foreach ($data_array as $key=>$data_dot_sort)
{
?>
    <div class="project_content">
        <div class="left spot_content">
            <div class="row-fluid line_all">
                <div class="row-fluid spot">
                    <div class="row-fluid span1 date_all">
                        <a href="javascript:;"><?php echo CHtml::encode(Pro::item_swithc($key)); ?></a>
                    </div>
                    <!-- .date_all -->
                    <div class="row-fluid span10 inner_right line_right_box">
            <?php
            foreach ($data_dot_sort as $data_dot)
            {
                foreach ($data_dot as $dot_name => $data_items)
                {
                    ?>
                    <div class="spot_name">
                        <?php echo CHtml::link(CHtml::encode($info_array['dot_data'][$dot_name]->name),array('/agent/agent_dot/view','id'=>$info_array['dot_data'][$dot_name]->id)); ?>
                    <?php 
                    	if($info_array['dot_data'][$dot_name]->status!=Shops::status_online)
						{
                    ?>
                    <span style="colore:red;">（已<?php echo Shops::$_status[$info_array['dot_data'][$dot_name]->status];?>）</span>
                    <?php 
						}
                    ?>
                    </div>
                    <?php
                    foreach ($data_items as $sort => $items)
                    {
                        ?>
                        <div class="row-fluid noe">
                            <div class="text_top num">
                                <a href="javascript:;"><?php echo CHtml::encode($items->half_sort + 1); ?></a>
                            </div>
                            <div class="content">
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
                                        <span>所属商家：</span>
                                        <span>
                                            <?php echo CHtml::encode($items->Pro_Items->Items_StoreContent->name); ?>
                                        </span>
                                    </div>
                                    <div class="row-fluid address">
                                        <span>地址：</span>
                                        <span>
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
                                <div class="good_info">
                                    <div class="box_div">
                                        <div style="visibility:hidden"><span>商品信息</span></div>
                                        <div class="title"><span>商品信息</span></div>
                                        <div class="box box_one">
                                            <?php echo Fare::show_fare_ul($items->Pro_ProFare, $items->Pro_Items->Items_ItemsClassliy->append == "Hotel" ? true : false, true); ?>
                                        </div>
                                        <!--  .box -->
                                    </div>
                                    <!-- .box_div -->
                                </div>
                                <!-- .good_info -->
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
                    <!-- .inner_right -->
                </div>
                <!-- .spot -->
            </div>
            <!-- .row-fluid -->
        </div>
        <!-- .left -->
        <div class="right">
            <div class="name">日程亮点</div>
            <div class="content">        	
                <?php 
	                if(isset($info_array[$key]))
	                	echo $info_array[$key]['info'];
	                else
	                	echo CHtml::encode(Pro::get_day_info($key, $model->id));
                ?>
                
            </div>
        </div>
    </div>
    <!-- .project_content -->
<?php } ?>
</div>
<!--.project-->