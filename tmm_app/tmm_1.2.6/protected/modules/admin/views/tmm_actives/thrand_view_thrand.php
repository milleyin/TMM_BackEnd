<div class="popup_box">
	<div class="content">
		<div class="content_div scenic_spot">
        <div class="spot_name">
          <label>点名称：</label>
          <span><?php echo CHtml::encode($model->Dot_Shops->name.$model->Dot_Shops->status);?></span>
          <?php 
          	if($model->Dot_Shops->status != Shops::status_online)
          	{
          ?>
          <span style="color: red;">（已<?php echo Shops::$_status[$model->Dot_Shops->status]; ?>）</span>
          <?php 
          	}
          ?>
          <div id="dot_id" style="display:none"><?php echo CHtml::encode($model->id);?></div>
        </div>
       <?php 
        	foreach ($model->Dot_Pro as $key=>$data)
        	{
        ?>
        <div class="project">
          <div class="top_title">项目<?php echo Pro::num_han($key+1); ?></div>
          <div class="project_line"></div>
          <div class="project_content">
            <div class="left spot_content">
              <div class="row-fluid">
                <div class="span8 spot_info">
                  <div class="row-fluid controls controls-row name">
                    <span class="span8"><?php echo CHtml::encode($data->Pro_Items->name);?></span>
				          <?php 
				          	if($data->Pro_Items->status != Items::status_online)
				          	{
				          ?>
                       			<span style="color: red;">（已<?php echo Items::$_status[$data->Pro_Items->status]; ?>）</span>
				          <?php 
				          	}
				          ?>
                      <div class="item_id" style="display:none"><?php echo CHtml::encode($data->Pro_Items->id);?></div>
                      <div class="item_type" style="display:none"><?php echo CHtml::encode($data->Pro_Items->Items_ItemsClassliy->name);?></div>
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
                   	echo Yii::app()->controller->show_img($img);
                    ?>
                </div>
              </div>
              <!-- .row-fluid -->
              
              <div class="good_info">
                <div class="box_div">
                  <div style="visibility:hidden"><span>商品信息</span></div>
                  <div class="title"><span>商品信息</span></div>
                  <div class="box box_one">
                  <ul>
                    <?php 
                   $all=$data->Pro_ItemsClassliy->append=='Hotel'?true:false;
                    if($all==false)
                    	$attributes=array('name'=>'','info'=>'','price'=>'元');
                    else
                    	$attributes=array('name'=>'','info'=>'平方','number'=>'人间','price'=>'元');
                       
                    	foreach ($data->Pro_Items->Items_Fare as $key_fare=>$Fare)
                    	{
                   ?>
                   <li>
                    <?php 
                   			foreach ($attributes as $attribute=>$unit)
                   			{
                    ?>
              			<span>
              			<?php echo CHtml::encode($Fare->$attribute).' '.$unit;?>       
                    	</span>
                    <?php 
                   			}
             
       echo Chtml::checkBox('Fare['.$model->id.']['.$key.']['.$data->Pro_Items->id.']['.$key_fare.']',false,array('value'=>$Fare->id));
                    ?>
                    </li>
                    <?php 
                    	}
                    	?>
                    </ul>
          
                  </div>
                  <!--  .box -->
                </div>
                <!-- .box_div -->
              </div>
              <!-- .good_info -->
            </div>
            <!-- .left -->
          </div>
          <!-- .project_content -->
        </div>
        <!--.project-->
        <?php 
        	}
        ?>   
        <div class="btn_group last">
          <a href="javascript:;" id="sure">确定</a>
          <a href="javascript:;" id="cancel">取消</a>
        </div>
      </div>
      <!--.content_div-->
    </div>
    </div>