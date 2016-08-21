<?php
$record['list'] = array();
foreach ($models as $model) {    
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
    $record['list'][] = array(
        'name' => $str1 . '**' . $str2,
        'prize_name'=>$model->prize_name,
    );
}
echo json_encode($record);
