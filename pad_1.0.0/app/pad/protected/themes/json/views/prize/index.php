<?php
$prize = array(
    'name' => CHtml::encode($model->name),
    'info' => CHtml::encode($model->info),
    'receive_type'=>array(
       'name'=>CHtml::encode(Prize::$_receive_type[$model->receive_type]),
       'value'=>$model->receive_type,
    ),
    'path'=>$model->Prize_Upload ? $model->Prize_Upload->getUrlPath() : '',
    'url' =>$model->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXPRESS ? Yii::app()->createAbsoluteUrl('/user/express/view', array('id'=>$recordModel->id)) : Yii::app()->createAbsoluteUrl('/user/record/url', array('id'=>$recordModel->id)),
    'angle'=>$angle,
    'number'=>$chanceModel->number,
    'status'=>array(
        'name'=>$model->status == Prize::_STATUS_DELETED ? '谢谢参与' : '其他奖品',
        'value'=>$model->status == Prize::_STATUS_DELETED ? -1 : 1,
    ),
    'config' => array(
        'info'=>CHtml::encode($chanceModel->Chance_Config->info),
        'path'=>$chanceModel->Chance_Config->Config_Upload->getUrlPath(),
     ),
);
$prize['config']['info'] = str_replace(array("\r\n","\n","\r"), '<br/>', $prize['config']['info']);
echo json_encode($prize);