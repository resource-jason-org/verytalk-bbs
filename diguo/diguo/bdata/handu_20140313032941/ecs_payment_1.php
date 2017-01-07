<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_payment`;");
E_C("CREATE TABLE `ecs_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_payment` values('1',0x616c69706179,0xe694afe4bb98e5ae9d,'0',0xe694afe4bb98e5ae9de7bd91e7ab99287777772e616c697061792e636f6d2920e698afe59bbde58685e58588e8bf9be79a84e7bd91e4b88ae694afe4bb98e5b9b3e58fb0e380823c62722f3ee694afe4bb98e5ae9de694b6e6acbee68ea5e58fa3efbc9ae59ca8e7babfe58db3e58fafe5bc80e9809aefbc8c3c666f6e7420636f6c6f723d22726564223e3c623ee99bb6e9a284e4bb98efbc8ce5858de5b9b4e8b4b93c2f623e3c2f666f6e743eefbc8ce58d95e7ac94e998b6e6a2afe8b4b9e78e87efbc8ce697a0e6b581e9878fe99990e588b6e38082,'0',0x613a343a7b693a303b613a333a7b733a343a226e616d65223b733a31343a22616c697061795f6163636f756e74223b733a343a2274797065223b733a343a2274657874223b733a353a2276616c7565223b733a303a22223b7d693a313b613a333a7b733a343a226e616d65223b733a31303a22616c697061795f6b6579223b733a343a2274797065223b733a343a2274657874223b733a353a2276616c7565223b733a303a22223b7d693a323b613a333a7b733a343a226e616d65223b733a31343a22616c697061795f706172746e6572223b733a343a2274797065223b733a343a2274657874223b733a353a2276616c7565223b733a303a22223b7d693a333b613a333a7b733a343a226e616d65223b733a31373a22616c697061795f7061795f6d6574686f64223b733a343a2274797065223b733a363a2273656c656374223b733a353a2276616c7565223b733a313a2230223b7d7d,'1','0','1');");
E_D("replace into `ecs_payment` values('2',0x636f64,0xe8b4a7e588b0e4bb98e6acbe,'0',0xe5bc80e9809ae59f8ee5b882efbc9ac397c397c3970d0ae8b4a7e588b0e4bb98e6acbee58cbae59f9fefbc9ac397c397c397,'0',0x613a303a7b7d,'1','1','0');");

require("../../inc/footer.php");
?>