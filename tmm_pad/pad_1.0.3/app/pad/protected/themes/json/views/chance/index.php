<?php
if ($model) {
    $name = CHtml::encode($model->Chance_User->name);
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
    $chance = array(
        'name'=>$str1 . '**' . $str2,
        'number'=>$model->number,
        'type' => array(
            'name' => Config::$_type[$model->type],
            'value' => $model->type,
        ),
    );
} else {
    $chance = array(
        'name'=>'游客',
        'number'=>0,
    );
}
echo json_encode($chance);