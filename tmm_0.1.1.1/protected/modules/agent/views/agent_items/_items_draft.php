<div class="item_box">
    <div class="bg_group">
        <?php
        if(isset($data->Items_ItemsImg['0']))
        	echo CHtml::link($this->show_img($data->Items_ItemsImg['0']->img),array('/agent/agent_'.$data->Items_ItemsClassliy->admin."/view","id"=>$data->id));       	 
        else
            echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/items/item_bg.png');
        ?>
        <div class="bg_title">
            <?php echo CHtml::link(CHtml::encode($data->name), array("/agent/agent_".$data->Items_ItemsClassliy->admin."/view","id"=>$data->id));?>
        </div>
    </div>  <!-- .img -->
    <div class="business_info">
        <div class="row-fluid controls controls-row">
            <span class="span10">
                商家：
                <?php echo CHtml::encode($data->Items_StoreContent->name) ;?>
            </span>
            <div class="span1 pull-right little_tag">
                <span>
                    <?php echo CHtml::encode($data->Items_ItemsClassliy->name) ;?>
                </span>
            </div>
        </div>
        <div>
            地址：
            <?php echo CHtml::encode($data->Items_area_id_p_Area_id->name.$data->Items_area_id_m_Area_id->name.$data->Items_area_id_c_Area_id->name.$data->address) ;?>
        </div>
    </div>
    <div class="good_info">
        <div class="box_div">
            <div style="visibility:hidden"><span>商品信息</span></div>
            <div class="title"><span>商品信息</span></div>
            <div class="box box_one">
                <?php if ($data->Items_ItemsClassliy->append === 'Hotel') {?>
                    <ul>
                        <?php foreach($data->Items_Fare as $val) { ?>
                            <li>
                                <span title="<?php echo CHtml::encode($val->name); ?>"><?php echo CHtml::encode($val->name); ?></span>
                                <span title="<?php echo CHtml::encode($val->info); ?>平方"><?php echo CHtml::encode($val->info); ?>平方</span>
                                <span title="<?php echo CHtml::encode($val->number); ?>人间"><?php echo CHtml::encode($val->number); ?>人间</span>
                                <span class="last" title="<?php echo CHtml::encode($val->price); ?>元"><?php echo CHtml::encode($val->price); ?>元</span>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <?php foreach($data->Items_Fare as $val) { ?>
                            <li>
                                <span title="<?php echo CHtml::encode($val->name); ?>"><?php echo CHtml::encode($val->name); ?></span>
                                <span title="<?php echo CHtml::encode($val->info); ?>"><?php echo CHtml::encode($val->info); ?></span>
                                <span class="last" title="<?php echo CHtml::encode($val->price); ?>元"><?php echo CHtml::encode($val->price); ?>元</span>
                            </li>
                        <?php } ?>
                    </ul>
                <?php }?>
            </div> <!--  .box -->
        </div>   <!-- .box_div -->
    </div>    <!-- .good_info -->
    <div class="intro">
            <span>

            </span>
    </div>  <!-- .intro -->
    <div class="row-fluid date">
        <div class="span1 pull-left little_tag"><span>单</span></div>
        <span class="span1 orderid"><?php echo $data->down; ?></span>
        <div class="span3"></div>
        <span class="datetime">
            <?php echo CHtml::encode(date('Y年m月d日', $data->pub_time)); ?>
        </span>
    </div>  <!-- .date -->


    <div class="check_failed">
        <div class="button_group ">
            <a href="<?php echo Yii::app()->createUrl("/agent/agent_".$data->Items_ItemsClassliy->admin."/update",array("id"=>$data->id)); ?>"><span>编辑</span></a>
            <div class="division"></div>
          <a href="<?php echo Yii::app()->createUrl("/agent/agent_items/delete",array("id"=>$data->id)); ?>" class="delete"><span>删除</span></a>
        </div>  <!-- .button_group -->
    </div> <!-- .check_failed -->

</div>  <!-- .item_box -->
