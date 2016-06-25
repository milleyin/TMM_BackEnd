<div class="project">
<?php
    foreach ($model->Dot_Pro as $key=>$data){
?>
    <div class="top_title">项目<?php echo Pro::num_han($key+1); ?></div>
    <div class="line"></div>
    <div class="project_content">
        <div class="left spot_content">
            <div class="row-fluid">
                <div class="span8 spot_info">
                    <div class="row-fluid controls controls-row name">
                        <span class="span8"><?php echo CHtml::link($data->Pro_Items->name,array('/agent/agent_'.$data->Pro_ItemsClassliy->admin.'/view','id'=>$data->Pro_Items->id)); ?></span>
                        <?php 
                        	if($data->Pro_Items->status != Items::status_online)
                        	{
                        ?>
                        <span style="color:red;">（已<?php echo Items::$_status[$data->Pro_Items->status];?>）</span>
                    	<?php 
                        	}
                    	?>
                    </div>
                    <div class="row-fluid address">
                        <span>地址：</span>
                        <span>
                        <?php
                            echo CHtml::encode(
                                $data->Pro_Items->Items_area_id_p_Area_id->name.
                                $data->Pro_Items->Items_area_id_m_Area_id->name.
                                $data->Pro_Items->Items_area_id_c_Area_id->name.
                                $data->Pro_Items->address
                            );
                        ?>
                        </span>
                    </div>
                    <div class="row-fluid belong_business">
                        <span>所属商家：</span>
                        <span><?php echo CHtml::encode($data->Pro_Items->Items_StoreContent->name); ?></span>
                    </div>
                </div>
                <div class="pull-right spot_img">
                    <?php
                    $img='';
                    if(isset($data->Pro_Items->Items_ItemsImg[0]))
                        $img=$data->Pro_Items->Items_ItemsImg[0]->img;
                   	echo CHtml::link(Yii::app()->controller->show_img($img,'','',array('style'=>'width:130px;height:60px;')),array('/agent/agent_'.$data->Pro_ItemsClassliy->admin.'/view','id'=>$data->Pro_Items->id));
                    ?>
                </div>
            </div>  <!-- .row-fluid -->
            <div class="good_info">
                <div class="box_div">
                    <div style="visibility:hidden"><span>商品信息</span></div>
                    <div class="title"><span>商品信息</span></div>
                    <div class="box box_one">
                        <?php echo Fare::show_fare_ul($data->Pro_Items->Items_Fare,$data->Pro_ItemsClassliy->append=='Hotel'?true:false);?>
                    </div> <!--  .box -->
                </div>   <!-- .box_div -->
            </div>    <!-- .good_info -->
        </div>  <!-- .left -->

        <div class="right">
            <div class="name">项目阐述：</div>
            <div class="content">
                <?php echo CHtml::encode($data->info);?>
            </div>
        </div>
    </div> <!-- .project_content -->
<?php
    }
?>
</div>    <!--.project-->
		