<?php
/*
** 程序版本:和其数据备份系统v1.0
** 调试环境:php+mysql
** 制作日期:2009-07-15
** 程序设计:℡嗄沬°| (网址:http://www.he-qi.com  邮箱:ye3312#163.com  QQ:280708784)
** 如您使用本程序，请保留以上信息。
*/

$lang = array (

'operate_error'				=>"没有选择操作对象",
'operate_success'			=>"完成相应操作",
'operate_fail'				=>"操作失败，请检查数据完整性",

'bakup_in'					=>"正在导入第{$i}卷备份文件，程序将自动导入余下备份文件...",
'bakup_out'					=>"已全部备份,备份文件保存在data目录下，备份文件为<br>$bakfile",
'bakup_step'				=>"正在备份数据库表 $t_name: 共 $rows 条记录，已经备份至 $c_n  条记录<br><br>已生成 $f_num 个备份文件，程序将自动备份余下部分"
);
?>