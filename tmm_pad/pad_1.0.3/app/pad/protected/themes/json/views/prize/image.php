<?php
$prizes = array();
foreach ($models as $model) {
    if ($model->status == Prize::_STATUS_DELETED) {
        $path = $this->getAssets() . '/images/thanks.png';
    } else {
        $path = $model->Prize_Upload->getUrlPath();
    }
    $prizes[] = array(
        'position' => $model->position,
        'path'=>$path,
    );
}
echo json_encode($prizes);