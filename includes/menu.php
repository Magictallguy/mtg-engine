<?php
if(!defined('MTG_ENABLE'))
	exit;
?><ul class="pure-menu-list"><?php
$links = array(
	'index' => 'Home',
	'messages' => 'Messages [msg_count]',
	'events' => 'Notifications [ev_count]',
	'hospital' => 'Hospital [hosp_count]',
	'jail' => 'Jail [jail_count]',
	'crimes' => 'Crimes',
	'gym' => 'Gym',
	'markets' => 'Markets'
);
foreach($links as $url => $disp) {
	if(preg_match('/\[msg_count\]/', $disp)) {
		$db->query("SELECT COUNT(`id`) FROM `users_messages` WHERE `read` = 0 AND `receiver` = ?");
		$db->execute([$my['id']]);
		$disp = str_replace('[msg_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[ev_count\]/', $disp)) {
		$db->query("SELECT COUNT(`id`) FROM `users_events` WHERE `read` = 0 AND `user` = ?");
		$db->execute([$my['id']]);
		$disp = str_replace('[ev_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[hosp_count\]/', $disp)) {
		$db->query("SELECT COUNT(`id`) FROM `users` WHERE `hospital` > ?");
		$db->execute([time()]);
		$disp = str_replace('[hosp_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[jail_count\]/', $disp)) {
		$db->query("SELECT COUNT(`id`) FROM `users` WHERE `jail` > ?");
		$db->execute([time()]);
		$disp = str_replace('[jail_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	printf("<li class='pure-menu-item'><a href='%s.php'>%s</a></li>\n", $url, $disp);
}
if($users->hasAccess('staff_panel_access'))
	echo "<li class='pure-menu-item menu-item-divided'><a href='staff'>Staff Panel</a></li>\n";
?><li class="pure-menu-item menu-item-divided"><a href='?action=logout'>Logout</a></li>
</ul>