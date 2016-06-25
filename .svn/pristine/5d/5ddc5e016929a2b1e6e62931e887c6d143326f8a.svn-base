<?php
class FileLogRoute extends CFileLogRoute
{
    /**
     * @param string $value log file name
     */
    public function setLogFile($value)
    {
        $dirname = dirname($value);
        if ( !is_dir(Yii::app()->getRuntimePath() . '/' . $dirname))
            mkdir(Yii::app()->getRuntimePath() . '/' . $dirname, 0777, true);
        parent::setLogFile($value);
    }
}