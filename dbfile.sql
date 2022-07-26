CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) DEFAULT '' COMMENT '姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1-男 2-女',
  `phone` varchar(20) DEFAULT '' COMMENT '电话',
  `department_id` int(11) DEFAULT '0' COMMENT '部门id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-正常 2-删除',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `email` varchar(50) DEFAULT '' COMMENT '邮箱',
  `account` varchar(100) DEFAULT '' COMMENT '账号',
  `password` varchar(100) DEFAULT '' COMMENT '密码',
  `salt` varchar(100) DEFAULT '' COMMENT '盐',
  PRIMARY KEY (`id`),
  KEY `idx_deparment_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('1', 'admin', '0', '13456789002', '0', '2', '1658052000', '0', 'admin@admin.com', 'admin', 'c57dafe5590b6e79a4cc3f5f1464d4c7', '77885');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('2', '张三', '1', '', '0', '1', '1658052231', '0', '', 'zhangsan', 'abe81947ee31e398b72b5dccdd3431a1', '56112');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('3', '李四', '1', '', '0', '1', '1658052731', '0', '', 'lisi', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('4', '王五', '2', '', '0', '1', '1658052731', '0', '', 'wangwu', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('5', 'del1', '1', '', '0', '2', '1658042731', '1658572000', '', 'del1', '1b9ff04ef832682d4712ece27487f84e', '22402');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('6', 'del2', '2', '', '0', '1', '0', '0', '', 'del2', '3ccd65979197e060c41a39a512800305', '79471');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('7', 'ccc7', '1', '1111', '0', '1', '1658140708', '0', '', 'ccc7', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('8', 'ccc8', '1', '1111', '0', '1', '1658140726', '0', '', 'ccc8', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('9', 'ccc9', '1', '1111', '0', '1', '1658140746', '0', '', 'ccc9', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('10', 'ccc10', '1', '1111', '0', '1', '1658140751', '0', '', 'ccc10', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('11', 'ccc11', '1', '1111', '0', '1', '1658140847', '0', '', 'ccc11', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('12', 'ccc12', '1', '1111', '0', '1', '1658140895', '0', '', 'ccc12', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('13', 'c2', '2', '13452', '0', '1', '1658141391', '1658141391', '', 'c2', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('14', 'c3', '1', '1222', '0', '2', '1658141478', '0', '', 'c3', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('15', 'c4', '0', '1223', '0', '0', '1658141637', '1658141637', '', 'c4', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('16', 'aa5', '0', '1223', '0', '0', '1658141673', '1658141673', '', 'aa5', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('17', '万维网', '1', '111', '0', '2', '1658218987', '1658218987', '', 'www', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('18', '邮箱1', '1', '111', '0', '1', '1658229506', '1658229506', '1111111', 'email1', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('19', '邮箱2', '1', '1234', '0', '2', '1658229657', '1658229657', '123@123', 'email2', '', '');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`, `email`, `account`, `password`, `salt`) VALUES ('20', '啊啊啊', '1', '1345267899', '0', '1', '1658488428', '1658488428', '', 'aaa', '8c10d9c992f1064fcc476017e7de7a3c', '37147');



CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '日志类型 0-系统报错 1-消息队列 2-日志记录',
  `content` varchar(1000) DEFAULT '' COMMENT '电话',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `title` varchar(100) DEFAULT '' COMMENT '对type的文字补充',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='日志表';


CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) DEFAULT '0' COMMENT '发送人',
  `uname` varchar(20) DEFAULT '' COMMENT '发送人姓名',
  `message` varchar(200) DEFAULT '' COMMENT '消息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-未发送 2-发送成功 3-发送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `send_time` int(11) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='信息表';


CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) DEFAULT '' COMMENT '商品名',
  `number` varchar(50) DEFAULT '' COMMENT '商品编号',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `sales` int(11) DEFAULT '0' COMMENT '销量',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0-未设置 1-上架 2-下架 3-删除',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

INSERT INTO `tp6`.`goods` (`id`, `name`, `number`, `price`, `stock`, `sales`, `status`, `create_time`, `update_time`) VALUES ('1', '测试商品1', '001', '1.00', '0', '0', '1', '0', '0');
INSERT INTO `tp6`.`goods` (`id`, `name`, `number`, `price`, `stock`, `sales`, `status`, `create_time`, `update_time`) VALUES ('2', '测试商品2', '002', '0.00', '0', '0', '1', '0', '0');
INSERT INTO `tp6`.`goods` (`id`, `name`, `number`, `price`, `stock`, `sales`, `status`, `create_time`, `update_time`) VALUES ('3', '测试3', 'TEST202207255161', '100.01', '1', '1', '3', '1658738835', '1658739080');
INSERT INTO `tp6`.`goods` (`id`, `name`, `number`, `price`, `stock`, `sales`, `status`, `create_time`, `update_time`) VALUES ('4', '测试444', 'TEST202207252582', '233.00', '12', '2', '1', '1658739105', '0');

