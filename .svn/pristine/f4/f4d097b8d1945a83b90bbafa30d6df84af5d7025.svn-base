<?php
namespace app\controllers;

use FrontController;

/**
 * 展示屏
 * @author Changhai Zhan
 *
 */
class PadController extends FrontController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Pad';
    
    /**
     * 展示屏的扫描链接
     */
    public function actionIndex()
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Pad_Store',
        );
        $this->render('index', array(
            'model'=>$this->loadModelByPk(\Yii::app()->user->padId, $criteria)
        ));
    }
    
    /**
     * 实时更新状态
     */
    public function actionState()
    {      
        $this->render('state', array(
                'status'=>\Pad::model()->updateByPk(\Yii::app()->user->padId, array('state'=>\Pad::PAD_STATE_NORMAL, 'up_time'=>time())),
        ));
    }
}