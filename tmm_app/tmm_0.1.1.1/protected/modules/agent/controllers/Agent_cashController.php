<?php
/**
 * 代理商提现相关控制器
 * @author Moore Mo
 * Class Agent_cashController
 */
class Agent_cashController extends AgentController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model = 'Cash';

    /**
     * 提现申请记录列表
     */
    public function actionAdmin()
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/normalize.css');
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/business.css');

        $model=new Cash('search');
        $model->unsetAttributes();  // 删除默认属性
        if(isset($_GET['Cash']))
            $model->attributes=$_GET['Cash'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * 提现 （代理商）
     */
    public function actionCreate()
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/profit.css');

        $this->_class_model = 'Agent';
        $agent_model = $this->loadModel(Yii::app()->agent->id,array(
            'with'=>array(
                'Agent_Bank'
            ),
            'condition'=>'`t`.`status`=1',
        ));
        $model_cash = new Cash;
        $this->_Ajax_Verify($model_cash,'agent-form');

        if (isset($_POST['Cash'])) {
            //开启事物
            $transaction = $agent_model->dbConnection->beginTransaction();
            try
            {
                $model = new Cash;
                //$model->scenario = 'agent_create';
                //$model->unsetAttributes();
                // 1. 验证是否已绑定银行帐号
                $agent_info = $this->validate_bank($model_cash);
                if ($agent_info == false) {
                    throw new Exception("代理商提现-错误-未绑定银行帐号");
                }
                // 2. 验证帐是否可以申请
                $cash_result = $this->validate_cash($model_cash);
                if ($cash_result == false) {
                    throw new Exception("代理商提现-错误-还有没处理完的提现申请");
                }

                // 3. 验证帐单金额
                $bills_result = $this->validate_bills($model_cash);

                if ($bills_result == false) {
                    throw new Exception("代理商提现-错误-提取金额不足");
                }
                // 验证成功成功后
                $model->cash_type = Cash::cash_type_agent;
                $model->cash_id = Yii::app()->agent->id;
                $model->cash_status = Cash::cash_status_cashing;
                $model->audit_status = Cash::audit_status_first;
                $model->money = $bills_result['total_money'];
                $model->price = $bills_result['total_money'];
                $model->cash_name = $agent_info->bank_name;
                $model->bank_code = $agent_info->bank_code;
                $model->bank_info = $agent_info->Agent_Bank->name . $agent_info->bank_branch;

                if (!$model->hasErrors() && $model->validate()) {
                    if (! $model->save(false)) {
                        throw new Exception("代理商提现-错误-添加提现记录失败");
                    }else {
                        if (!$this->update_cash_account($model))
                            throw new Exception("代理商提现-错误-冻结提现金额失败");

                    }
                }else
                    throw new Exception("代理商提现-错误-验证申请记录失败");

                $return = $this->log('代理商提现', ManageLog::agent, ManageLog::create);
                $transaction->commit();
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                $this->error_log($e->getMessage(), ErrorLog::agent, ErrorLog::create, ErrorLog::rollback, __METHOD__);
            }

            if (isset($return)) {
                $this->redirect(array('/agent/agent_cash/success'));
            }
        }

        $this->render('create',array(
            'model'=>$agent_model,
            'cash_model'=>$model_cash,
            'agent_account'=> Account::get_account(Yii::app()->agent->id, Account::agent)
        ));
    }

    /**
     * 结算成功提示页
     */
    public function actionSuccess()
    {
        $this->addCss(Yii::app()->request->baseUrl.'/css/agent/css/profit.css');
        $this->render('success',array(
        ));
    }

    /**
     * 提现时验证是否绑定银行
     * @param $model
     * @return bool|unknown
     * @throws CHttpException
     */
    private function validate_bank($model)
    {
        $this->_class_model = 'Agent';
        $agent = $this->loadModel(Yii::app()->agent->id, 'status=1');
        if ($agent->bank_id && $agent->bank_name && $agent->bank_branch && $agent->bank_code)
        {
            $agent_info = $agent;
        }
        else
        {
            //throw new Exception("代理商提现-错误-未绑定银行帐号");
            $model->addError('bank_code','未绑定银行帐号');
            return false;
        }
        return $agent_info;
    }

    /**
     * 提现时，更新帐单记录
     * @param $model
     * @param $user_model
     * @param $cash_id
     * @param $bills_count
     * @return bool
     */
    private function update_cash_bills($model, $cash_id, $bills_count)
    {
        $this->_class_model = 'Bills';
        $update_criteria_bills = new CDbCriteria;
        $update_criteria_bills->addCondition('`status`=1 AND `agent_id`=:agent_id AND `cash_id`=0 AND cash_status=:cash_status');
        $update_criteria_bills->params[':agent_id'] = Yii::app()->agent->id;
        $update_criteria_bills->params[':cash_status'] = Bills::cash_status_not_apply; // 未申请

        $count = Bills::model()->updateAll(array(   //更新代理商项目 取消订单
            'cash_id' => $cash_id, // 申请记录id
            'cash_status' => Bills::cash_status_auditing, // 账单提现状态 待审核
        ),$update_criteria_bills);

        if ($count != $bills_count)
        {
            $model->addError('bank_code','提现失败');
            return false;
            //throw new Exception("代理商提现-错误-更新帐单记录失败");
        }
        return true;
    }

    /**
     * 提现时，冻结金额
     * @param $model
     * @return bool
     * @throws CDbException
     */
    private function update_cash_account($model)
    {
        $cash_account_model = Account::get_account(Yii::app()->agent->id, Account::agent);

        $result = Account::cash_money(Yii::app()->agent->id, Account::agent, $cash_account_model->money);

        if (! $result)
        {
            //throw new Exception("代理商提现-错误-冻结提现金额失败");
            $model->addError('bank_code','提现失败');
            return false;
        }
        return true;
    }

    /**
     * 提现时验证钱包
     * @param $model
     * @param $total_money
     * @return bool
     */
    private function validate_account($model, $total_money)
    {
        // 获取钱包信息
        $account_model = Account::get_account(Yii::app()->agent->id, Account::agent);

        if ($account_model->money <=0)
        {
            $model->addError('bank_code', '提现金额不足');
            return false;
        }

        if ($account_model->money < Yii::app()->params['order_deposit_price_agent'])
        {
            $model->addError('bank_code', '提现金额不能小于'.Yii::app()->params['order_deposit_price_agent'].'元');
            return false;
        }

        if (bccomp($account_model->money, $total_money) !== 0)
        {
            //throw new Exception("代理商提现-错误-金额数目出错");
            $model->addError('bank_code', '金额数目出错');
            return false;
        }
        return true;
    }

    /**
     * 提现时验证帐单，金额数目是否匹配
     * @param $model
     * @return array|bool
     */
    private function validate_bills($model){
        // 获取钱包信息
        $account_model = Account::get_account(Yii::app()->agent->id, Account::agent);

        //可提现金额 不能 小于0
        if ($account_model->money <=0)
        {
            $model->addError('bank_code', '提现金额不足');
            return false;
        }
        //可提现金额 不能 小于 设置最少提现额度
        if ($account_model->money < Yii::app()->params['order_deposit_price_agent'])
        {
            $model->addError('bank_code', '提现金额不能小于'.Yii::app()->params['order_deposit_price_agent'].'元');
            return false;
        }
        //可提现金额 + 冻结金额 = 资金总额
        if( ! (($account_model->money + $account_model->no_money) == $account_model->total) )
        {
            $model->addError('bank_code', '');
            return false;
        }

        return array(
            'total_money' => $account_model->money,
        );
    }
    /**
     * 提现时验证帐单，金额数目是否匹配====moore
     * @param $model
     * @return array|bool
     */
    private function validate_bills_bak($model)
    {
        $this->_class_model = 'Bills';
        $criteria_bills = new CDbCriteria;
        $criteria_bills->addCondition('`status`=1 AND `agent_id`=:agent_id AND `cash_id`=0 AND cash_status=:cash_status');
        $criteria_bills->params[':agent_id'] = Yii::app()->agent->id;
        $criteria_bills->params[':cash_status'] = Bills::cash_status_not_apply; // 未申请
        $bills_models = Bills::model()->findAll($criteria_bills);

        if (empty($bills_models)) {
            //throw new Exception("代理商提现-错误-提取金额不足");
            $model->addError('bank_code', '提现金额不足');
            return false;
        }
        // 可提取的金额
        $total_money = 0.00;
        foreach ($bills_models as $bills_model) {
            $total_money += $bills_model->items_money_agent;
        }

        if ($total_money <= 0) {
            //throw new Exception("代理商提现-错误-提取金额不足");
            $model->addError('bank_code', '提现金额不足');
            return false;
        }
        if ($total_money < Yii::app()->params['order_deposit_price_agent']) {
            //throw new Exception("代理商提现-错误-提现金额不能小于100元");
            $model->addError('bank_code', '提现金额不能小于'.Yii::app()->params['order_deposit_price_agent'].'元');
            return false;
        }

        if (! $this->validate_account($model, $total_money))
        {
            return false;
        }
        else
        {
            return array(
                'total_money' => $total_money,
                'bills_count' => count($bills_models),
            );
        }
    }

    /**
     * 提现时验证是否还有提现申请
     * @param $model
     * @return bool
     */
    private function validate_cash($model)
    {
        $this->_class_model = 'Cash';
        $criteria_cash = new CDbCriteria;
        $criteria_cash->addCondition('`status`=1 AND `cash_type`=:cash_type AND `cash_id`=:cash_id AND (`audit_status`=:audit_status_first OR `audit_status`=:audit_status_double OR `audit_status`=:audit_status_submit)');
        $criteria_cash->params[':cash_type'] = Cash::cash_type_agent;
        $criteria_cash->params[':cash_id'] = Yii::app()->agent->id;
        $criteria_cash->params[':audit_status_first'] = Cash::audit_status_first;
        $criteria_cash->params[':audit_status_double'] = Cash::audit_status_double;
        $criteria_cash->params[':audit_status_submit'] = Cash::audit_status_submit;
        $cash_model = Cash::model()->find($criteria_cash);
        if (! empty($cash_model))
        {
            // throw new Exception("代理商提现-错误-还有没处理完的提现申请");
            $model->addError('bank_code','还有没处理完的提现申请');
            return false;
        }
        return true;
    }
}