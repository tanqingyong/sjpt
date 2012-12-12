# 修改users表，增标识加用户在线状态字段
alter table users add column online tinyint(1) default 0 comment '0-离线  1-在线';

# 菜单被操作日志记录
create table menu_log(
	id int(11) not null auto_increment comment '日志记录ID',
	user_id int(11) not null comment '用户id',
	ip	varchar(50) comment '用户ip',
	menu_id int(11) not null comment '菜单id',
	action_time int(11) comment '访问时间',
	primary key(id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单被操作日志记录';





