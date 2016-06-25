<?php
$list['shop'] = array();
foreach ($models as $model)
{
    $list['shop'][] = array(
    	'name'=>CHtml::encode($model->name),
        'path'=>$model->Shop_Upload->getUrlPath(),
    );
}
echo json_encode($list);