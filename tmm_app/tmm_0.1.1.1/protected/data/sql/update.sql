DROP TABLE IF EXISTS `tmm_software`; 
CREATE TABLE IF NOT EXISTS `tmm_software` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '更新软件的类型 0 user(用户) (store)商家',
  `use` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '更新软件的用途 0 zip 1 apk ',
  `version` varchar(10) NOT NULL DEFAULT '1.0.0.0' COMMENT '更新包版本号',
  `dow_url` varchar(400) NOT NULL COMMENT '下载地址',
  `file_path` varchar(100) NOT NULL DEFAULT '' COMMENT '文件的地址',
  `dow_count` int(11) DEFAULT 0 COMMENT '下载次数',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `up_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '记录状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='software更新表';

m.365tmm.com/index.php?r=admin/tmm_software/query&user=user&zip=zip&ios=ios  //ios
m.365tmm.com/index.php?r=admin/tmm_software/query&user=user&zip=zip			//Android
m.365tmm.com/index.php?r=admin/tmm_software/query&pad=pad&zip=zip 			// pad 大屏
{
	'user':
		{
			'zip':
				{
					'version':'',
					'down_url':''
				},
			'apk':
				{
					'version':'',
					'down_url':''
				}
		},
	'store':
		{
			'zip':
				{
					'version':'',
					'down_url':''
				},
			'apk':
				{
					'version':'',
					'down_url':''
				}
		},
	'pad':
		{
			'zip':
				{
					'version':'',
					'down_url':''
				},
			'apk':
				{
					'version':'',
					'down_url':''
				}
		}
}