DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(16) NOT NULL COMMENT '用户名称',
  `userpass` varchar(32) NOT NULL COMMENT '用户密码',
  PRIMARY KEY (`userid`)
) DEFAULT CHARSET=utf8 COMMENT='用户表';

DROP TABLE IF EXISTS `user_bind_info`;
CREATE TABLE `user_bind_info` (
  `userid` int(11) NOT NULL,
  `openid` char(32) NOT NULL,
  `access_token` char(32) NOT NULL,
  `usernick` char(32) NOT NULL,
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `openid` (`openid`)
) DEFAULT CHARSET=utf8 COMMENT='用户绑定记录表';