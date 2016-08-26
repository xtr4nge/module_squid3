<?php
$mod_name="squid3";
$mod_version="1.4";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$url_rewrite_program="pasarela_xss.js";
$mod_panel="show";
$mod_isup="ps auxww | grep squid3 | grep -v -e 'grep squid3'";
$mod_alias="Squid3";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_squid3 = "/usr/sbin/squid3";
$bin_killall = "/usr/bin/killall";
$bin_iptables = "/sbin/iptables";
$bin_sed = "/bin/sed";
$bin_cp = "/bin/cp";
$bin_grep = "/bin/grep";
$bin_rm = "/bin/rm";
$bin_touch = "/bin/touch";
$bin_chmod = "/bin/chmod";
$bin_echo = "/bin_echo";
?>
