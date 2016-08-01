<?php
namespace app\controllers;

use FrontController;

/**
 * 广告
 * Class AdController
 * @package app\controllers
 *
 * @author Moore Mo
 */
class AdController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Ad';
     
    /**
     * 图片广告
     */
    public function actionImage()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Select_Ad' => array(
                'with'=>array(
                    'Ad_Upload'
                ),
            ),
        );
        $criteria->addColumnCondition(array(
            '`Select_Ad`.`status`'=>\Ad::_STATUS_NORMAL,
            '`t`.`pad_id`'=> \Yii::app()->user->padId,
            '`t`.`store_id`'=> \Yii::app()->user->id,
             '`t`.`ad_type`'=>\Ad::AD_TYPE_IMAGE,
        ));
        $this->_modelName = 'Select';
        $this->render('image', array(
            'models' =>$this->loadModelAll($criteria)
        ));
    }

    /**
     * 视频广告
     */
    public function actionVideo()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Select_Ad' => array(
                'with'=>array(
                    'Ad_Upload'
                ),
            ),
        );
        $criteria->addColumnCondition(array(
            '`Select_Ad`.`status`'=>\Ad::_STATUS_NORMAL,
            '`t`.`pad_id`'=> \Yii::app()->user->padId,
            '`t`.`store_id`'=> \Yii::app()->user->id,
            '`t`.`ad_type`'=>\Ad::AD_TYPE_VIDEO,
        ));
        $this->_modelName = 'Select';
        $this->render('video', array(
            'model' =>$this->loadModel($criteria)
        ));
    }
}