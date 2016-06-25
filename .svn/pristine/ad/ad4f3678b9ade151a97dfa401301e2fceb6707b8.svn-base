<?php 
/**
 * 登录错误定时任务
 * @author Changhai Zhan
 *	创建时间：2015-10-26 13:41:03
 *	protected php yiic.php login
 * */
class LoginCommand  extends ConsoleCommand
{
	/**
	 * 清除错误次数
	 */
	public function actionIndex()
	{
		$this->afterAction('user',array(),$this->actionUser());
		
		$this->afterAction('agent',array(),$this->actionAgent());
		
		$this->afterAction('store',array(),$this->actionStore());		
		return self::correct;
	}
	
	/**
	 * 用户
	 * @throws Exception
	 * @return string
	 */
	public function actionUser()
	{
		$this->logText[]='START';
		
		$criteria =new CDbCriteria;
		$criteria->addCondition('login_error !=0');
		$criteria->addColumnCondition(array(
				'status'=>1,	//角色状态
		));
		$user=User::model()->find($criteria);
		if($user)
		{
			//开启事物
			$transaction = $user->dbConnection->beginTransaction();
			try{
				$count=User::model()->count($criteria);
				if($count)
				{
					$return = User::model()->updateAll(array('login_error'=>0),$criteria);
					if($count != $return)
						throw new Exception("定时任务 用户登录错误次数清零 错误");
					$this->logText[]='定时任务 用户登录错误次数清零 正确 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[]=$e->getMessage();
				$this->logText[]='END';
				return self::return_error;
			}
		}
		$this->logText[]='END';
		return self::correct;
	}
	
	/**
	 * 商家
	 * @throws Exception
	 * @return string
	 */
	public function actionStore()
	{
		$this->logText[]='START';
		$data=array(
				'User'=>Yii::app()->params['user_login_error'],
				'Agent'=>Yii::app()->params['agent_login_error'],
				'StoreUser'=>Yii::app()->params['store_login_error'],
		);
		
		$criteria =new CDbCriteria;
		$criteria->addCondition('login_error !=0');
		$criteria->addColumnCondition(array(
				'status'=>1,	//角色状态
		));
		$store=StoreUser::model()->find($criteria);
		if($store)
		{
			//开启事物
			$transaction = $store->dbConnection->beginTransaction();
			try{
				$count=StoreUser::model()->count($criteria);
				if($count)
				{
					$return = StoreUser::model()->updateAll(array('login_error'=>0),$criteria);
					if($count != $return)
						throw new Exception("定时任务 商家登录错误次数清零 错误");
					$this->logText[]='定时任务 商家登录错误次数清零 正确 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[]=$e->getMessage();
				$this->logText[]='END';
				return self::return_error;
			}
		}
		$this->logText[]='END';
		return self::correct;
	}
	
	/**
	 * 代理商
	 * @throws Exception
	 * @return string
	 */
	public function actionAgent()
	{
		$this->logText[]='START';
		$criteria =new CDbCriteria;
		$criteria->addCondition('login_error !=0');
		$criteria->addColumnCondition(array(
				'status'=>1,	//角色状态
		));
		$agent=Agent::model()->find($criteria);
		if($agent)
		{
			//开启事物
			$transaction = $agent->dbConnection->beginTransaction();
			try{
				$count=Agent::model()->count($criteria);
				if($count)
				{
					$return = Agent::model()->updateAll(array('login_error'=>0),$criteria);
					if($count != $return)
						throw new Exception("定时任务 代理商登录错误次数清零 错误");
					$this->logText[]='定时任务 代理商登录错误次数清零 正确 NO. '.$return;
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$transaction->rollBack();
				$this->logText[]=$e->getMessage();
				$this->logText[]='END';
				return self::return_error;
			}
		}
		$this->logText[]='END';
		return self::correct;
	}
}