<?php /* $Id: uninstall.php $ */
if (!defined('ISSABELPBX_IS_AUTH')) { die('No direct script access allowed'); }
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$sql[] = "DROP TABLE IF EXISTS `customcontexts_contexts`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_contexts_list`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_includes`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_includes_list`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_module`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_timegroups`";
$sql[] = "DROP TABLE IF EXISTS `customcontexts_timegroups_detail`";
foreach ($sql as $q){
	$db->query($q);
}

modgettext::push_textdomain('customcontexts');

if( php_sapi_name() != 'cli' ) {
    echo '<font color="red"><strong>';
    echo __('You have uninstalled the Class of Service Module!');
    echo "<BR>";
    echo __('Remember to place all of your devices into local contexts or they will not have dialplan access!');
    echo '</strong></font><BR>';
} else {
    echo __("You have uninstalled the Class of Service Module!");
    echo "\n";
    echo __("Remember to place all of your devices into local contexts or they will not have dialplan access!");
    echo "\n";
}
?>


