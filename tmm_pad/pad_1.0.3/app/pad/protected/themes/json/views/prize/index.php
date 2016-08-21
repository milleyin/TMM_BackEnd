<?php
$name = CHtml::encode($chanceModel->Chance_User->name);
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
$prize = array(
    'user_name' => $str1 . '***' . $str2,
    'name' => CHtml::encode($model->name),
    'info' => nl2br(CHtml::encode($model->info)),
    'type' => array(
        'name' => Config::$_type[$recordModel->type],
        'value' => $recordModel->type,
    ),
    'receive_type'=>array(
       'name'=>CHtml::encode(Prize::$_receive_type[$model->receive_type]),
       'value'=>$model->receive_type,
    ),
    'path'=>$model->Prize_Upload ? $model->Prize_Upload->getUrlPath() : '',
    'url' =>$model->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXPRESS ? Yii::app()->createAbsoluteUrl('/user/express/view', array('id'=>$recordModel->id)) : Yii::app()->createAbsoluteUrl('/user/record/url', array('id'=>$recordModel->id)),
    'site'=>$site,
    'number'=>$chanceModel->number,
    'status'=>array(
        'name'=>$model->status == Prize::_STATUS_DELETED ? '谢谢参与' : '其他奖品',
        'value'=>$model->status == Prize::_STATUS_DELETED ? -1 : 1,
    ),
    'select' => array(
        'name' => Record::$_exchange_status[$recordModel->exchange_status],
        'value' => $recordModel->exchange_status,
        'url' => Yii::app()->createAbsoluteUrl('/record/select'),
    ),
    'print_status' => array(
        'name' => Record::$_print_status[$recordModel->print_status],
        'value' => $recordModel->print_status,
        'url' => Yii::app()->createAbsoluteUrl('/record/print', array('id' => $recordModel->id)),
    ),
    'config' => array(
        'info'=>nl2br(CHtml::encode($chanceModel->Chance_Config->info)),
        'type' => array(
            'name' => Config::$_type[$chanceModel->type],
            'value' => $chanceModel->type,
        ),
    ),
);
$prizes = array();
foreach ($models as $modelPrize) {
    if ($modelPrize->status == Prize::_STATUS_DELETED) {
        $path = $this->getAssets() . '/images/thanks.png';
    } else {
        $path = $modelPrize->Prize_Upload->getUrlPath();
    }
    $prizes[] = array(
        'position' => $modelPrize->position,
        'path'=>$path,
    );
}
$prize['prizes'] = $prizes;
echo json_encode($prize);