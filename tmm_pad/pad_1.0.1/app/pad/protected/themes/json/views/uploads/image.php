<?php
header('Content-type: image/png');
echo file_get_contents(Yii::app()->basePath . '/assets/images/default.png');