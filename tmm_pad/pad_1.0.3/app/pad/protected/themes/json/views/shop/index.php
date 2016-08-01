<?php
$list['shop'] = array();
foreach ($models as $model)
{
    $list['shop'][] = array(
        'name'=>CHtml::encode($model->name),
        'path'=>$model->Shop_Upload->getUrlPath(),
//         'sort' => $model->sort,
//         'up_time' => $model->up_time,
    );
}
echo json_encode($list);