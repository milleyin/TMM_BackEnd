<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:27:03 */
class RecordController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Record';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Record('search');
        $model->Record_User = new \User('search');
        $model->Record_Pad = new \Pad('search');
        $model->Record_Store = new \Store('search');    
        //清除默认值
        $model->unsetAttributes();
        $model->Record_User->unsetAttributes();
        $model->Record_Pad->unsetAttributes();
        $model->Record_Store->unsetAttributes();
        if (isset($_GET['Record']))
            $model->attributes = $_GET['Record'];
        if (isset($_GET['User']))
            $model->Record_User->attributes = $_GET['User'];
        if (isset($_GET['Pad']))
            $model->Record_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Store']))
            $model->Record_Store->attributes = $_GET['Store'];

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
            'Record_User',
            'Record_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
            'Record_Pad',
            'Record_Upload',
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 兑换
     * @param $id
     */
    public function actionExchange($id)
    {
        $this->loadModelByPk(
            $id,
            '`exchange_status`=:exchange_status AND `status`=:status AND `receive_type`=:receive_type',
            array(':exchange_status'=>\Record::RECORD_EXCHANGE_STATUS_NO, ':status'=>\Record::_STATUS_NORMAL , ':receive_type' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE)
        )->updateByPk(
            $id,
            array('exchange_status'=>\Record::RECORD_EXCHANGE_STATUS_YES, 'exchange_time'=>time())
        );
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
