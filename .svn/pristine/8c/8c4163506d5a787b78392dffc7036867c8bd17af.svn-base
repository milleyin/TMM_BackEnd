<?php
$config = array(
	'info'=>CHtml::encode($model->info),     
    'path'=>$model->Config_Upload->getUrlPath(),
);
$config['info'] = str_replace(array("\r\n","\n","\r"), '<br/>', $config['info']);
echo json_encode($config);