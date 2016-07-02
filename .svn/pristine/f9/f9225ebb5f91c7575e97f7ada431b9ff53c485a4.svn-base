<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-03 17:19:21 */
class AreaController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Area';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Area('search');
        //清除默认值
        $model->unsetAttributes();
        if (isset($_GET['Area']))
            $model->attributes = $_GET['Area'];

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
     * 地址联动
     */
    public function actionDrop()
    {
        if (isset($_POST['pid']))
        {
            $pid = $_POST['pid'];
            $criteria = new \CDbCriteria;
            $criteria->addColumnCondition(array(
                'pid'=>$pid,
                'status'=>\Area::_STATUS_NORMAL,
            ));
            $htmlOptions = array();
            if ( !!$models = \Area::model()->findAll($criteria))
                echo \CHtml::listOptions('', array(''=>'--请选择--') + \CHtml::listData($models, 'id', 'name'), $htmlOptions);
        }
    }

    /**
     * 禁用
     * @param integer $id the ID of the model to be deleted
     * @return Success page "admin"
     */
    public function actionDisable($id)
    {
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Area::_STATUS_NORMAL))->updateByPk($id, array('status'=>\Area::_STATUS_DISABLE));
        
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
        $this->loadModelByPk($id, '`status`=:status', array(':status'=>\Area::_STATUS_DISABLE))->updateByPk($id, array('status'=>\Area::_STATUS_NORMAL));
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
