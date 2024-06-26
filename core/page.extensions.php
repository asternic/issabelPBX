<?php /* $Id$ */
if (!defined('ISSABELPBX_IS_AUTH')) { die('No direct script access allowed'); }
?>

<script src='/admin/modules/core/assets/js/extensions/copy2clipboard.js'></script>
<?php 
$extens = core_users_list();
if($extens===null) $extens=array();
$rnaventries = array();
foreach($extens as $idx=>$data) {
    $midev = core_devices_get($data[0]);
    $rnaventries[]=array($data[0],$data[1],'<span class="tag is-white tagfixed">'.$midev['tech'].'</span> '.$data[0]);
}
drawListMenu($rnaventries, $type, $display, $extdisplay);
?>
<div class='content'>
<?php
// If this is a popOver, we need to set it so the selection of device type does not result
// in the popover closing because config.php thinks it was the process function. Maybe
// the better way to do this would be to log an error or put some proper mechanism in place
// since this is a bit of a kludge
//
if (!empty($_REQUEST['fw_popover']) && empty($_REQUEST['tech_hardware'])) {
?>
	<script>
		$(document).ready(function(){
			$('[name="fw_popover_process"]').val('');
			$('<input>').attr({type: 'hidden', name: 'fw_popover'}).val('1').appendTo('.popover-form');
		});
	</script>
<?php
}
