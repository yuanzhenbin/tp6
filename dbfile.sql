CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) DEFAULT '' COMMENT '姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1-男 2-女',
  `phone` varchar(20) DEFAULT '' COMMENT '电话',
  `department_id` int(11) DEFAULT '0' COMMENT '部门id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-正常 2-删除',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_deparment_id` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('1', 'admin', '0', '13456789002', '0', '1', '1658052000', '0');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('2', '张三', '1', '', '0', '1', '1658052231', '0');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('3', '李四', '1', '', '0', '1', '1658052731', '0');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('4', '王五', '2', '', '0', '1', '1658052731', '0');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('5', 'del1', '1', '', '0', '0', '1658042731', '1658052731');
INSERT INTO `tp6`.`user` (`id`, `name`, `sex`, `phone`, `department_id`, `status`, `create_time`, `update_time`) VALUES ('6', 'del2', '2', '', '0', '0', '0', '0');
