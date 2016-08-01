<?php
namespace app\controllers;

use FrontController;

/**
 * 
 * @author Changhai Zhan
 *
 */
class UploadsController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Upload';
    
    public function init()
    {
        if (\Yii::app()->user->isGuest) {
            echo '
                <html>
                <head><title>403 Forbidden</title></head>
                <body bgcolor="white">
                <center><h1>403 Forbidden</h1></center>
                <hr><center>nginx/1.4.4</center>
                </body>
                </html>
                ';
            \Yii::app()->end();
        }
    }

    public function actionAd()
    {
        $this->renderPartial('image');
    }
    
    public function actionConfig()
    {
        $this->renderPartial('image');
    }
    
    public function actionPrize()
    {
        $this->renderPartial('image');
    }
    
    public function actionRecord()
    {
        $this->renderPartial('image');
    }
    
    public function actionShop()
    {
        $this->renderPartial('image');
    }
    
    public function actionStore()
    {
        $this->renderPartial('image');
    }
}