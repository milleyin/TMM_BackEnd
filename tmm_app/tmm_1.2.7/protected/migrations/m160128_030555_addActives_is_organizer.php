<?php
/**
 * 命令讲解
 * 全部帮助 yiic 
 * 创建新的迁移：yiic migrate create $name
 * 执行所有迁移：yiic migrate
 * 向上迁移：yiic migrate up 3 （默认执行1 如：Yiic migrate up）
 * 还原迁移：yiic migrate down 3 （默认执行1 如：Yiic migrate down）
 * 指定版本迁移：yiic migrate to 120808_125201
 * 标记指定迁移版本（表示已经执行了）：yiic migrate mark 120808_125201 
 * 查看已应用迁移：yiic migrate history [limit]  
 * 查看帮助：yiic migrate help
 * @author Changhai Zhan
 * actives 添加字段
 */
class m160128_030555_addActives_is_organizer extends CDbMigration
{
	/**
	 * AFTER 之后
	 * (non-PHPdoc)
	 * @see CDbMigration::up()
	 */
	public function up()
	{
		$tableName = Actives::model()->tableName();
		$this->addColumn($tableName,'is_organizer',"tinyint(3) NOT NULL DEFAULT 1 COMMENT '是否组织者1是0不是' AFTER `organizer_id`");
		return true;
	}

	public function down()
	{
		$tableName = Actives::model()->tableName();
		$this->dropColumn($tableName,'is_organizer');
		return true;
	}
	
}