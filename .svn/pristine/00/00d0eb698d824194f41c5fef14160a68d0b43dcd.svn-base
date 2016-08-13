<?php
$name = CHtml::encode($model->Record_User->name);
$len = mb_strlen($name, 'utf-8');
if ($len > 4) {
    $str1 = mb_substr($name, 0, 2, 'utf-8');
    $str2 = mb_substr($name, $len-2, 2, 'utf-8');
} elseif ($len > 2) {
    $str1 = mb_substr($name, 0, 1, 'utf-8');
    $str2 = mb_substr($name, $len-1, 1, 'utf-8');
} else {
    $str1 = mb_substr($name, 0, 1, 'utf-8');
    $str2 = '';
}
$confirm = array(
    'user_name' => $str1 . '***' . $str2,
    'name' => CHtml::encode($model->prize_name),
    'info' => nl2br(CHtml::encode($model->prize_info)),
    'type' => array(
        'name' => Config::$_type[$model->type],
        'value' => $model->type,
    ),
    'receive_type'=>array(
        'name'=>CHtml::encode(Prize::$_receive_type[$model->receive_type]),
        'value'=>$model->receive_type,
    ),
    'path'=>$model->Record_Upload ? $model->Record_Upload->getUrlPath() : '',
    'url' =>$model->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXPRESS ? Yii::app()->createAbsoluteUrl('/user/express/view', array('id'=>$model->id)) : Yii::app()->createAbsoluteUrl('/user/record/url', array('id'=>$model->id)),
    'print_status' => array(
        'name' => Record::$_print_status[$model->print_status],
        'value' => $model->print_status,
        'url' => Yii::app()->createAbsoluteUrl('/record/print', array('id' => $model->id)),
    ),
);
echo json_encode($confirm);