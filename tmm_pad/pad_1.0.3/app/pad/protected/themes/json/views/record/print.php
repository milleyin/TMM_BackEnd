<?php
$print = array(
    'name' => CHtml::encode($model->prize_name),
    'code' => $model->code,
    'info' => explode("\r\n", CHtml::encode($model->prize_info)),
    'time' => date('Y-m-d H:i:s', $model->add_time),
    'success' => Yii::app()->createAbsoluteUrl('/record/success', array('id' => $model->id)),
);
echo json_encode($print);