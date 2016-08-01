<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-07-27 17:44:20 */
class OrderfoodController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'OrderFood';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \OrderFood('search');
        $model->OrderFood_Order = new \Order('search');
        $model->OrderFood_Store = new \Store('search');
        $model->OrderFood_Pad = new \Pad('search');
        //清除默认值
        $model->unsetAttributes();
        $model->OrderFood_Order->unsetAttributes();
        $model->OrderFood_Store->unsetAttributes();
        $model->OrderFood_Pad->unsetAttributes();
        if (isset($_GET['OrderFood']))
            $model->attributes = $_GET['OrderFood'];
        if (isset($_GET['Order']))
            $model->OrderFood_Order->attributes = $_GET['Order'];
        if (isset($_GET['Store']))
            $model->OrderFood_Store->attributes = $_GET['Store'];
        if (isset($_GET['Pad']))
            $model->OrderFood_Pad->attributes = $_GET['Pad'];
        
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
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id),
        ));
    }

    /**
     * 创建
     * @return Success page "view"
     */
    public function actionCreate()
    {
        $model = new \OrderFood;

        $model->scenario = 'create';
        $this->ajaxVerify($model, 'order-food-form');

        if (isset($_POST['OrderFood']))
        {
            $model->attributes = $_POST['OrderFood'];
            if ($model->save())
                $this->redirect(array('view', 'id'=>$model->id));
        }

        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * 更新
     * @param integer $id
     * @return Success page "view"
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModelByPk($id);

        $model->scenario = 'update';
        $this->ajaxVerify($model, 'order-food-form');

        if (isset($_POST['OrderFood']))
        {
            $model->attributes = $_POST['OrderFood'];
            if ($model->save())
                $this->redirect(array('view', 'id'=>$model->id));
        }

        $this->render('update', array(
            'model'=>$model,
        ));
    }

    /**
     * 删除
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDelete($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\OrderFood::_STATUS_DISABLE))->updateByPk($id, array('status'=>\OrderFood::_STATUS_DELETED));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 禁用
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDisable($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\OrderFood::_STATUS_NORMAL))->updateByPk($id, array('status'=>\OrderFood::_STATUS_DISABLE));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\OrderFood::_STATUS_DISABLE))->updateByPk($id, array('status'=>\OrderFood::_STATUS_NORMAL));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 还原
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionRestore($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\OrderFood::_STATUS_DELETED))->updateByPk($id, array('status'=>\OrderFood::_STATUS_DISABLE));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 清除记录
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionClear($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\OrderFood::_STATUS_DELETED))->delete();
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
