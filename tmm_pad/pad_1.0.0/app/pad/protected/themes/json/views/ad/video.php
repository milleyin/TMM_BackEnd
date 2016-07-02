<?php
$video = array(
    'name'=>CHtml::encode($model->Select_Ad->name),
    'type'=>array(
        'name'=>CHtml::encode(Ad::$_type[$model->Select_Ad->type]),
        'value'=>$model->Select_Ad->type,
    ),
    'path'=>$model->Select_Ad->Ad_Upload->getUrlPath(),
);
echo json_encode($video);