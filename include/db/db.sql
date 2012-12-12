/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2011-10-13 15:14:12                          */
/*==============================================================*/


drop table if exists data_resource;

drop table if exists department;

drop table if exists fuction_city;

drop table if exists menu;

drop table if exists permission;

drop table if exists role;

drop table if exists users;

set names utf8;

/*==============================================================*/
/* Table: data_resource                                         */
/*==============================================================*/
create table data_resource
(
   id                   tinyint(2) not null auto_increment,
   data_resource_name   varchar(20),
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*==============================================================*/
/* Table: department                                            */
/*==============================================================*/
create table department
(
   id                   int not null auto_increment,
   data_resource_id     tinyint(2),
   department_name      varchar(20),
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

alter table department comment '用来描述用户来自哪个部门';

/*==============================================================*/
/* Table: fuction_city                                          */
/*==============================================================*/
create table fuction_city
(
   id                   int not null auto_increment,
   role_id              int,
   function_name        varchar(20) comment '具体的含义以业务而定。对于销售部就是城市名，对于运营部就是一个二级菜单',
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

alter table fuction_city comment '这个表存储权限控制的最小单元，在不同的业务中可以表示不同的含义。对于运营部最小的单元就是一个二级菜单，对于销售部则是一个';

/*==============================================================*/
/* Table: menu                                                  */
/*==============================================================*/
create table menu
(
   id                   int not null auto_increment,
   data_resource_id     tinyint,
   department_id        int,
   is_viewed_only_admin tinyint(1) default 0 comment '1表示只有管理员可见，0表示全部可见',
   menu_grade           tinyint(1) comment '数字1表示一级菜单，2表示二级菜单，二级菜单隶属于一级菜单',
   parent_id            int default 0 comment '如果该值为0，说明其为顶层菜单',
   menu_name            varchar(30),
   url                  varchar(100),
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*==============================================================*/
/* Table: permission                                            */
/*==============================================================*/
create table permission
(
   id                   int not null auto_increment,
   user_id              int,
   data_resource_id     tinyint,
   department_id        int,
   grade                tinyint comment '权限分为四级：1为城市权限，2为区域权限，3为总部权限，4为管理员',
   role_id              int,
   function_id          int,
   menu_id              int,
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*==============================================================*/
/* Table: role                                                  */
/*==============================================================*/
create table role
(
   id                   int not null auto_increment,
   department_id        int,
   role_name            varchar(20) comment '具体含义需根据部门业务而定，对于销售部就是大区名，对于运营部就是功能组合',
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

alter table role comment '一个角色可以执行多个功能，对于不同部门有不同的含义。对于销售部则是大区，一个大区会包含若干个城市；对于运营部则是一个角色';

/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users
(
   id                   int(5) not null auto_increment,
   username            varchar(20),
   password             char(32) comment '使用MD5对原始密码进行加密，加密后的结果为32位',
   created_by_id        int(5) default 0,
   create_time          int(10) default 0,
   update_time          int(10) default 0,
   login_time           int(10) default 0,
   state                tinyint(1) comment '用户状态，1为有效，0为失效',
   primary key (id)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

alter table users comment '数据平台的用户表';

/*==============================================================*/
/*     数据源初始数据     */
/*==============================================================*/
INSERT INTO `sjpt`.`data_resource` (`id`, `data_resource_name`) VALUES ('1', '窝窝团');

/*==============================================================*/
/*     线上运营部信息初始数据    */
/*==============================================================*/
INSERT INTO `sjpt`.`department` (`id`, `data_resource_id`, `department_name`) VALUES ('1', '1', '线上运营部');
INSERT INTO `role` (`id`, `department_id`, `role_name`) VALUES(1, 1, '窝窝团线上运营部全局角色');

/*==============================================================*/
/*     线上营销部信息初始数据    */
/*==============================================================*/
INSERT INTO `sjpt`.`department` (`id`, `data_resource_id`, `department_name`) VALUES ('2', '1', '线上营销部');
INSERT INTO `role` (`id`, `department_id`, `role_name`) VALUES(2, 2, '窝窝团线上营销部全局角色');

/*==============================================================*/
/*     线上推广部信息初始数据    */
/*==============================================================*/
INSERT INTO `sjpt`.`department` (`id`, `data_resource_id`, `department_name`) VALUES ('3', '1', '线上推广部');
INSERT INTO `role` (`id`, `department_id`, `role_name`) VALUES(3, 3, '窝窝团线上推广部全局角色');

/*==============================================================*/
/*     产品部信息初始数据    */
/*==============================================================*/
INSERT INTO `sjpt`.`department` (`id`, `data_resource_id`, `department_name`) VALUES ('4', '1', '产品部');
INSERT INTO `role` (`id`, `department_id`, `role_name`) VALUES(4, 4, '窝窝团产品部全局角色');
/*==============================================================*/
/*     添加菜单表初始数据   */
/*==============================================================*/
INSERT INTO `menu` (`id`, `data_resource_id`, `department_id`, `is_viewed_only_admin`, `menu_grade`, `parent_id`, `menu_name`, `url`) VALUES
(1, 1, 1, 0, 0, 0, 'ALL', NULL),
(2, 1, 1, 0, 1, 1, '日报数据', NULL),
(3, 1, 1, 0, 2, 2, '日报销售数据', '/manage/operation/day_report/day_sale_data.php'),
(4, 1, 1, 0, 2, 2, '日报流量访问数据', '/manage/operation/day_report/day_visit_data.php'),
(5, 1, 1, 0, 1, 1, '整站', NULL),
(6, 1, 1, 0, 2, 5, '网站访问数据', '/manage/operation/whole_site/site_visit_data.php'),
(7, 1, 1, 0, 2, 5, '外站来源域名', '/manage/operation/whole_site/out_visit_domain.php'),
(8, 1, 1, 0, 2, 5, '用户访问深度分布', '/manage/operation/whole_site/user_visit_depth.php'),
(9, 1, 1, 0, 2, 5, '外站来源URL', '/manage/operation/whole_site/out_visit_url.php'),
(11, 1, 1, 0, 2, 5, '跳出URL统计', '/manage/operation/whole_site/outsite_url_data.php'),
(12, 1, 1, 0, 1, 1, '行业', NULL),
(13, 1, 1, 0, 2, 12, '商品详情页来源url', '/manage/operation/category/detailpage_visit_url.php'),
(14, 1, 1, 0, 2, 12, '频道数据', '/manage/operation/category/category_visit_data.php'),
(15, 1, 2, 0, 1, 1, 'EDM日报数据', NULL),
(16, 1, 2, 0, 2, 15, 'EDM日报销售数据', '/manage/edm/day_sales_data/day_sales_data.php'),
(17, 1, 2, 0, 1, 1, 'EDM任务数据', NULL),
(18, 1, 2, 0, 2, 17, '成本导入', '/manage/edm/task_data/import_cost.php'),
(19, 1, 2, 0, 2, 17, '发送任务数据', '/manage/edm/task_data/send_task_data.php'),
(20, 1, 3, 0, 1, 1, '总流量统计', NULL),
(21, 1, 3, 0, 2, 20, '数据统计(日期)', '/manage/dailydata/day_range.php'),
(22, 1, 3, 0, 2, 20, '媒体统计(日期)', '/manage/dailymedia/date_search.php'),
(23, 1, 3, 0, 2, 20, '媒体统计(媒体、城市)', '/manage/dailymedia/media_city_search.php'),
(24, 1, 3, 0, 2, 20, '单媒体(日期)', '/manage/singlemedia/date_search.php'),
(25, 1, 3, 0, 2, 20, '单媒体(广告位、城市)', '/manage/singlemedia/media_city_search.php'),
(26, 1, 2, 0, 1, 1, 'SEO流量统计', NULL),
(27, 1, 2, 0, 2, 26, '百度SEO流量统计', '/manage/seo/jixiao/seo_jixiao_data.php'),
(28, 1, 2, 0, 2, 26, '百度SEO关键字统计', '/manage/seo/jixiao/seo_keyword_data.php'),
(29, 1, 2, 0, 1, 1, 'EDM城市数据', NULL),
(30, 1, 2, 0, 2, 29, 'EDM城市数据', '/manage/edm/edm_city/edm_city.php'),
(31, 1, 1, 0, 1, 1, '广告位统计', NULL),
(32, 1, 1, 0, 2, 31, '广告位点击统计', '/manage/advertising/ad_click.php'),
(33, 1, 1, 0, 2, 31, '广告位来源统计', '/manage/advertising/ad_source.php'),
(34, 1, 4, 0, 1, 1, '网站访问统计', NULL),
(35, 1, 4, 0, 2, 34, '用户访问次数统计', '/manage/sitevisit/site_visit_times.php'),
(36, 1, 4, 0, 2, 34, '用户访问时长统计', '/manage/sitevisit/site_visit_period.php'),
(37, 1, 4, 0, 2, 34, '用户跳出页统计', '/manage/sitevisit/outsite_page.php');

/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2011/10/14 10:44:26                          */
/*==============================================================*/

drop table if exists buss_country_day_order;

drop table if exists country_day_frequser;

drop table if exists country_day_outfromdomain;

drop table if exists country_day_outfromurl;

drop table if exists web_country_day_pvuvip;

drop table if exists webbuss_country_day_other;

/*==============================================================*/
/* Table: buss_country_day_order                                */
/*==============================================================*/
create table buss_country_day_order
(
   id                   int not null auto_increment,
   data_date            date comment '日期',
   order_num            int comment '订单数',
   product              int comment '商品数',
   user                 int comment '用户数',
   sale                 int comment '订单额',
   pay_order            int comment '支付订单数',
   pay_product          int comment '支付商品数',
   pay_user             int comment '支付用户数',
   pay_sale             int comment '支付订单额',
   order_num_zero       int(11), 
   product_zero         int(11), 
   user_zero            int(11),
   pay_order_num_zero   int(11),
   pay_product_zero     int(11),
   pay_user_zero        int(11),
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table buss_country_day_order comment '整站商品交易表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on buss_country_day_order
(
   data_date
);

/*==============================================================*/
/* Table: country_day_frequser                                  */
/*==============================================================*/
create table country_day_frequser
(
   id                   int not null auto_increment,
   data_date            date comment '日期',
   user_click1          int comment '深1用户数',
   user_click2          int comment '深2用户数',
   user_click3          int comment '深3用户数',
   user_click4          int comment '深4用户数',
   user_click5          int comment '深5用户数',
   user_click6          int comment '深6用户数',
   user_click7          int comment '深7用户数',
   user_click8          int comment '深8用户数',
   user_click9          int comment '深9用户数',
   user_click10         int comment '深10用户数',
   user_click_up10      int comment '深10以上用户数',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table country_day_frequser comment '用户访问深度表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on country_day_frequser
(
   data_date
);

/*==============================================================*/
/* Table: country_day_outfromdomain                             */
/*==============================================================*/
create table country_day_outfromdomain
(
   id                   int not null auto_increment,
   data_date            date comment '日期',
   domain_name          varchar(50) comment '来站域名',
   pv                   int comment 'PV',
   uv                   int comment 'UV',
   ip                   int comment 'IP',
   click_onlyone_ratio  decimal(10,10) comment '跳出率',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table country_day_outfromdomain comment '域名来源流量表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on country_day_outfromdomain
(
   data_date
);

/*==============================================================*/
/* Table: country_day_outfromurl                                */
/*==============================================================*/
create table country_day_outfromurl
(
   id                   int not null auto_increment comment 'ID',
   data_date            date comment '日期',
   url                  varchar(100) comment '来站URL',
   pv                   int comment 'PV',
   uv                   int comment 'UV',
   ip                   varchar(50) comment 'IP',
   click_onlyone_ratio  decimal(10,10) comment '跳出率',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table country_day_outfromurl comment 'URL来源流量表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on country_day_outfromurl
(
   data_date
);

/*==============================================================*/
/* Table: web_country_day_pvuvip                                */
/*==============================================================*/
create table web_country_day_pvuvip
(
   id                   int not null auto_increment,
   data_date            date comment '日期',
   total_pv             int comment '整站PV',
   total_uv             int comment '整站UV',
   total_ip             int comment '整站IP',
   index_pv             int comment '首页PV',
   index_uv             int comment '首页UV',
   index_ip             int comment '首页IP',
   goods_pv             int comment '商品页PV',
   goods_uv             int comment '商品页UV',
   goods_ip             int comment '商品页IP',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table web_country_day_pvuvip comment '整站流量表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on web_country_day_pvuvip
(
   data_date
);

/*==============================================================*/
/* Table: webbuss_country_day_other                             */
/*==============================================================*/
create table webbuss_country_day_other
(
   id                   int not null auto_increment,
   data_date            date,
   login_user           int comment '登录用户数',
   register_user        int comment '注册用户数',
   click_product        int comment '点击商品数',
   time_online_peruser  int comment '人均在线时长',
   click_onlyone_ratio  decimal(10,10) comment '整站跳出率',
   click_zero_goodsnum  int comment '0元商品ID数',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8;

alter table webbuss_country_day_other comment '整站访问效率表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on webbuss_country_day_other
(
   data_date
);

/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2011/11/2 18:01:37                           */
/*==============================================================*/

drop table if exists adref_success;

drop table if exists edm;

drop table if exists goods_detail;

drop table if exists industry;

drop table if exists out_statis;

drop table if exists platform_update;

/*==============================================================*/
/* Table: adref_success                                         */
/*==============================================================*/
create table adref_success
(
   id                   int(16) not null auto_increment,
   ad                   varchar(254) not null comment '广告来源',
   date                 date not null comment '日期',
   province             varchar(80) not null comment '省份',
   city                 varchar(80) not null comment '城市',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   order_num            int(16) not null comment '下单数',
   goods_num            int(16) not null comment '下单商品数',
   total_price          decimal(20,2) not null comment '下单金额',
   register_num         int(16) not null comment '新注册用户数',
   suc_order_num        int(16) not null comment '成单数',
   suc_goods_num        int(16) not null comment '成单商品数',
   suc_total_price      decimal(20,2) not null comment '成单金额',
   uniq_ip              int(11) unsigned default 0,
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table adref_success comment '广告明细表,EDM的任务数据包含在其中';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on adref_success
(
   date
);

/*==============================================================*/
/* Index: ad_index                                              */
/*==============================================================*/
create index ad_index on adref_success
(
   ad
);

/*==============================================================*/
/* Table: edm                                                   */
/*==============================================================*/
create table edm
(
   id                   int(16) not null auto_increment,
   edm_id               varchar(254) default NULL comment '平台ID',
   date                 date not null comment '日期',
   ip                   int(16) not null comment 'IP访问数',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   order_num            int(16) not null comment '购买下单数',
   goods_num            int(16) not null comment '商品数',
   total_price          decimal(20,2) not null comment '下单金额',
   register_num         int(16) not null comment '新注册用户数',
   suc_order_num        int(16) not null comment '成单数',
   suc_goods_num        int(16) not null comment '成单商品数',
   suc_total_price      decimal(20,2) not null comment '成单金额',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table edm comment 'edm表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on edm
(
   date
);

/*==============================================================*/
/* Index: edm_index                                             */
/*==============================================================*/
create index edm_index on edm
(
   edm_id
);

/*==============================================================*/
/* Table: goods_detail                                          */
/*==============================================================*/
create table goods_detail
(
   id                   int(16) not null auto_increment,
   date                 date not null comment '日期',
   url                  varchar(80) not null,
   ip                   int(11) unsigned default 0 comment 'IP',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   out_rate             decimal(10,10) not null comment '跳出率',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table goods_detail comment '商品详情表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on goods_detail
(
   date
);

/*==============================================================*/
/* Table: industry                                              */
/*==============================================================*/
create table industry
(
   id                   int(16) not null auto_increment,
   date                 date not null comment '日期',
   ind                  varchar(80) not null comment '频道',
   ip                   int(11) unsigned default 0 comment 'IP',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   goods_ip             int(11) unsigned default 0,
   goods_uv             int(16) not null,
   goods_pv             int(16) not null,
   order_num            int(16) not null comment '下单数',
   total_price          decimal(20,2) not null comment '下单金额',
   user_num             int(16) NOT NULL comment '下单用户数',
   register_num         int(16) not null comment '新注册用户数',
   suc_order_num        int(16) not null comment '成单数',
   suc_total_price      decimal(20,2) not null comment '成单金额',
   suc_user_num         int(16) NOT NULL comment '成单用户数',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table industry comment '行业频道表';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on industry
(
   date
);

/*==============================================================*/
/* Index: ind_index                                             */
/*==============================================================*/
create index ind_index on industry
(
   ind
);

/*==============================================================*/
/* Table: out_statis                                            */
/*==============================================================*/
create table out_statis
(
   id                   int(16) not null auto_increment,
   date                 date not null comment '日期',
   out_url              varchar(80) not null,
   ip                   int(11) unsigned default 0 comment 'IP',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   out_rate             decimal(10,10) not null comment '跳出率',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table out_statis comment '跳出详情页';

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on out_statis
(
   date
);

/*==============================================================*/
/* Table: platform_update                                       */
/*==============================================================*/
create table platform_update
(
   id                   int(16) not null auto_increment,
   platform_id          varchar(254) default NULL comment '平台ID',
   date                 date not null comment '日期',
   ip                   int(16) not null comment 'IP访问数',
   uv                   int(16) not null comment 'UV',
   pv                   int(16) not null comment 'PV',
   order_num            int(16) not null comment '购买下单数',
   goods_num            int(16) not null comment '商品数',
   total_price          decimal(20,2) not null comment '下单金额',
   register_num         int(16) not null comment '新注册用户数',
   suc_order_num        int(16) not null comment '成单数',
   suc_goods_num        int(16) not null comment '成单商品数',
   suc_total_price      decimal(20,2) not null comment '成单金额',
   act_num              int(16) default NULL comment '活动下单数',
   primary key (id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table platform_update comment '平台绩效表';

/*==============================================================*/
/* Index: platform_index                                        */
/*==============================================================*/
create index platform_index on platform_update
(
   platform_id
);

/*==============================================================*/
/* Index: date_index                                            */
/*==============================================================*/
create index date_index on platform_update
(
   date
);


drop table if exists cost;

/*==============================================================*/
/* Table: cost                                                  */
/*==============================================================*/
create table cost
(
   task_id              varchar(254) not null comment '任务ID',
   send_date            date comment '发送日期',
   cost                 decimal(20,2) comment '成本',
   user_id              int comment '操作用户ID',
   update_time          int comment '更新时间',
   primary key (task_id)
)
ENGINE = MYISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table cost comment '成本表';

/*==============================================================*/
/* Index: date_team_index                                       */
/*==============================================================*/
create index date_team_index on cost
(
   task_id,
   send_date
);

/*==============================================================*/
/* Table: adref_success 广告位表                                                  */
/*==============================================================*/
DROP TABLE IF EXISTS `adref_success`;
CREATE TABLE `adref_success` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `ad` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `province` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `uv` int(16) NOT NULL,
  `pv` int(16) NOT NULL,
  `order_num` int(16) NOT NULL,
  `goods_num` int(16) NOT NULL,
  `total_price` decimal(20,2) NOT NULL,
  `register_num` int(16) NOT NULL,
  `suc_order_num` int(16) NOT NULL,
  `suc_goods_num` int(16) NOT NULL,
  `suc_total_price` decimal(20,2) NOT NULL,
  `uniq_ip` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*==============================================================*/
/* Table: adref_success_city 城市广告位表                                                  */
/*==============================================================*/
DROP TABLE IF EXISTS `adref_success_city`;
CREATE TABLE `adref_success_city` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `ad` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `city` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `uv` int(16) NOT NULL,
  `pv` int(16) NOT NULL,
  `order_num` int(16) NOT NULL,
  `goods_num` int(16) NOT NULL,
  `total_price` decimal(20,2) NOT NULL,
  `register_num` int(16) NOT NULL,
  `suc_order_num` int(16) NOT NULL,
  `suc_goods_num` int(16) NOT NULL,
  `suc_total_price` decimal(20,2) NOT NULL,
  `uniq_ip` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*==============================================================*/
/* Table: baiduseo 百度seo表                                           */
/*==============================================================*/
DROP TABLE IF EXISTS `baiduseo`;
CREATE TABLE `baiduseo` (
  `id` int(11) NOT NULL default '0',
  `platform_id` varchar(80) character set utf8 collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `province` varchar(80) character set utf8 collate utf8_unicode_ci NOT NULL,
  `city` varchar(80) character set utf8 collate utf8_unicode_ci NOT NULL,
  `pv` int(11) NOT NULL,
  `uv` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `goods_num` int(11) NOT NULL,
  `total_price` decimal(20,2) NOT NULL,
  `register_num` int(11) NOT NULL,
  `suc_order_num` int(11) NOT NULL,
  `suc_goods_num` int(11) NOT NULL,
  `suc_total_price` decimal(20,2) unsigned NOT NULL,
  `uniq_ip` int(16) unsigned NOT NULL default '0',
  `keyword` varchar(128) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*==============================================================*/
/* Table: baiduseo 媒体统计表                                           */
/*==============================================================*/
DROP TABLE IF EXISTS `platform_update`;
CREATE TABLE `platform_update` (
  `id` int(16) NOT NULL auto_increment,
  `platform_id` varchar(254) collate utf8_unicode_ci default NULL,
  `date` date NOT NULL,
  `ip` int(16) NOT NULL,
  `uv` int(16) NOT NULL,
  `pv` int(16) NOT NULL,
  `order_num` int(16) NOT NULL,
  `goods_num` int(16) NOT NULL,
  `total_price` decimal(20,2) NOT NULL,
  `register_num` int(16) NOT NULL,
  `suc_order_num` int(16) NOT NULL,
  `suc_goods_num` int(16) NOT NULL,
  `suc_total_price` decimal(20,2) NOT NULL,
  `act_num` int(16) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*==============================================================*/
/* Table: baiduseo 城市媒体统计表                                           */
/*==============================================================*/
DROP TABLE IF EXISTS `platform_update_city`;
CREATE TABLE `platform_update_city` (
  `id` int(16) NOT NULL auto_increment,
  `city` varchar(254) collate utf8_unicode_ci default NULL,
  `platform_id` varchar(254) collate utf8_unicode_ci default NULL,
  `date` date NOT NULL,
  `ip` int(16) NOT NULL,
  `uv` int(16) NOT NULL,
  `pv` int(16) NOT NULL,
  `order_num` int(16) NOT NULL,
  `goods_num` int(16) NOT NULL,
  `total_price` decimal(20,2) NOT NULL,
  `register_num` int(16) NOT NULL,
  `suc_order_num` int(16) NOT NULL,
  `suc_goods_num` int(16) NOT NULL,
  `suc_total_price` decimal(20,2) NOT NULL,
  `act_num` int(16) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*==============================================================*/
/* Table: baiduseo 用户跳出页分布表                                            */
/*==============================================================*/
CREATE TABLE `brounce_uv` (
  `date` date NOT NULL,
  `spuv` int(4) unsigned default '0',
  `orderuv` int(4) unsigned default '0',
  `payuv` int(4) unsigned default '0',
  `indexuv` int(4) unsigned default '0',
  `loginuv` int(4) unsigned default '0',
  `reguv` int(4) unsigned default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci


/*==============================================================*/
/* 窝窝数据平台七期：菜单访问日志及 在线用户监控                                           */
/*==============================================================*/
#  修改users表，增标识加用户在线状态字段
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