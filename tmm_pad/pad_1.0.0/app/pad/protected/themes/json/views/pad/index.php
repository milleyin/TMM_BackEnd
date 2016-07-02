<?php
$pad = array(
    'url'=>Yii::app()->createAbsoluteUrl('/user/index/index', array('id'=>$model->id)),
);
echo json_encode($pad);