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
$select = array(
    'value' => $model->id,
    'name' => $str1 . '***' . $str2,
    'prize_name' => CHtml::encode($model->prize_name),
    'prize_info' => nl2br(CHtml::encode($model->prize_info)),
    'path' => $model->Record_Upload->getUrlPath(),
    'quit' => array(
        'nama' => '放弃奖品',
        'url' => Yii::app()->createAbsoluteUrl('/record/quit', array('id' => $model->id)),
    ),
    'confirm' => array(
        'nama' => '领取奖品',
        'url' => Yii::app()->createAbsoluteUrl('/record/confirm', array('id' => $model->id)),
    ),
);
echo json_encode($select);