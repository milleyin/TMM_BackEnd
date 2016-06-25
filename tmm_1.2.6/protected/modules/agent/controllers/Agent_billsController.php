<?php
/**
 * 代理商帐单相关控制器
 * @author Moore Mo
 * Class Agent_BillsController
 */
class Agent_BillsController extends AgentController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model = 'Bills';
    /**
     * （提现）帐单明细列表
     * @param $id
     */
    public function actionAdmin($id)
    {
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/business.css');
        $model = new Bills('search');
        $model->unsetAttributes();  // 删除默认属性
        if (isset($_GET['Bills']))
            $model->attributes = $_GET['Bills'];

        $this->render('admin', array(
            'model' => $model,
            'id' => $id,
        ));
    }
}