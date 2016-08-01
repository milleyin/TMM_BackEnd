<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:24:30 */
class ConfigController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Config';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Config('search');
        $model->Config_Pad = new \Pad('search');
        $model->Config_Store = new \Store('search');
        $model->Config_Upload = new \Upload('search');
        //清除默认值
        $model->unsetAttributes();
        $model->Config_Pad->unsetAttributes();
        $model->Config_Store->unsetAttributes();
        $model->Config_Upload->unsetAttributes();
        if (isset($_GET['Config']))
            $model->attributes = $_GET['Config'];
        if (isset($_GET['Pad']))
            $model->Config_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Store']))
            $model->Config_Store->attributes = $_GET['Store'];
        if (isset($_GET['Upload']))
            $model->Config_Upload->attributes = $_GET['Upload'];

        $this->render('admin', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 查看
     * @param integer $id
     */
    public function actionView($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Config_Pad',
            'Config_Store',
            'Config_Upload',
        );
        $this->render('view', array(
            'model' => $this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 创建抽奖配置
     * @param $id 展示屏id
     */
    public function actionCreate($id)
    {
        $model = new \Config;

        $this->_modelName = 'Pad';
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Pad_Store'
        );
        $criteria->addColumnCondition(array(
            '`t`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
        ));
        $model->Config_Pad = $this->loadModelByPk($id, $criteria);
        
        $model->pad_id = $model->Config_Pad->id;
        $model->store_id = $model->Config_Pad->Pad_Store->id;
        
        $model->scenario = 'create';
        $this->ajaxVerify($model, 'config-form');

        if (isset($_POST['Config']))
        {
            $model->attributes = $_POST['Config'];
            if ($model->validate())
            {
                //格式化钱
                $model->money = $model->saveMoney($model->money);
                if ($model->save(false)) {
                    $this->redirect(array('view', 'id'=>$model->id));
                }
            }
        }

        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * 更新
     * @param $id 抽奖配置id
     */
    public function actionUpdate($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Config_Pad'=>array(
                'with'=>array('Pad_Store'),
            ),
        );
        $criteria->addColumnCondition(array(
            '`Config_Pad`.`status`'=>\Pad::_STATUS_NORMAL,
            '`Pad_Store`.`status`'=>\Store::_STATUS_NORMAL,
        ));
        $model = $this->loadModelByPk($id, $criteria);
        //钱的转换
        $model->money = $model->viewMoney($model->money);
        
        $model->scenario = 'update';
        $this->ajaxVerify($model, 'config-form');

        if (isset($_POST['Config']))
        {
            $model->attributes = $_POST['Config'];
            if ($model->validate())
            {
                //格式化钱
                $model->money= $model->saveMoney($model->money);
                if ( $model->save(false)) {
                    $this->redirect($this->getLastUrl());
                }
            }
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }

    /**
     * 禁用
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDisable($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Config::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Config::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 激活
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionStart($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Config::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Config::_STATUS_NORMAL));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
