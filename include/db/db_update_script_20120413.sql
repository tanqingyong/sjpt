# �޸�users������ʶ���û�����״̬�ֶ�
alter table users add column online tinyint(1) default 0 comment '0-����  1-����';

# �˵���������־��¼
create table menu_log(
	id int(11) not null auto_increment comment '��־��¼ID',
	user_id int(11) not null comment '�û�id',
	ip	varchar(50) comment '�û�ip',
	menu_id int(11) not null comment '�˵�id',
	action_time int(11) comment '����ʱ��',
	primary key(id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='�˵���������־��¼';





