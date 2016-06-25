<?php
$select=ItemsWifi::checked($data->id,$select_id);
?>
<div class="tag_img <?php echo $select?'selected':'optional';?>" style="cursor:pointer;" <?php echo $select?'title="已选择"':'title="点击添加"'?>>
    <?php
    if($select){
        ?>
        <?php echo $this->show_img($data->icon,'','',array('style'=>'height:40px;width:40px;opacity:0.5;','title'=>$data->name));?>
    <?php }else{?>
        <?php echo $this->show_img($data->icon,'','',array('style'=>'height:40px;width:40px;opacity:1;','title'=>$data->name));?>
    <?php }?>
    <span class="wifi_id" style="display:none"><?php echo CHtml::encode($data->id);?></span>
</div>