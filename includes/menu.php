<?php
if(!defined('MTG_ENABLE'))
	exit;
?><ul class="pure-menu-list"><?php
$links = [
	'divider0' => 'Menu',
	'index.php' => 'Home',
	'messages.php' => 'Messages [msg_count]',
	'events.php' => 'Notifications [ev_count]',
	'divider1' => 'Exploration',
	'hospital.php' => 'Hospital [hosp_count]',
	'jail.php' => 'Jail [jail_count]',
	'tasks.php' => 'Tasks',
	'gym.php' => 'Gym',
	'markets.php' => 'Markets',
	'list.php?action=players' => 'Player List',
	'list.php?action=online' => 'Online List',
	'divider2' => 'Account',
	'settings.php' => 'Settings',
	'?action=logout' => 'Logout'
];
foreach($links as $url => $disp) {
	if(preg_match('/\[msg_count\]/', $disp)) {
		$db->query('SELECT COUNT(`id`) FROM `users_messages` WHERE `read` = 0 AND `receiver` = ?');
		$db->execute([$my['id']]);
		$disp = str_replace('[msg_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[ev_count\]/', $disp)) {
		$db->query('SELECT COUNT(`id`) FROM `users_events` WHERE `read` = 0 AND `user` = ?');
		$db->execute([$my['id']]);
		$disp = str_replace('[ev_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[hosp_count\]/', $disp)) {
		$db->query('SELECT COUNT(`id`) FROM `users` WHERE `hospital` > ?');
		$db->execute([time()]);
		$disp = str_replace('[hosp_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/\[jail_count\]/', $disp)) {
		$db->query('SELECT COUNT(`id`) FROM `users` WHERE `jail` > ?');
		$db->execute([time()]);
		$disp = str_replace('[jail_count]', '['.$mtg->format($db->fetch_single()).']', $disp);
	}
	if(preg_match('/^divider(.*?)$/i', $url))
		echo '<li class="pure-menu-item menu-item-divided"><a href="#" class="pure-menu-link pure-menu-heading">'.$disp.'</a></li>';
	else
		printf('<li class="pure-menu-item"><a href="%s" class="pure-menu-link%s">%s</a></li>'."\n", $url, $_SERVER['PHP_SELF'] == '/'.$url ? ' pure-menu-selected' : null, $disp);
}
if($users->hasAccess('staff_panel_access'))
	echo '<li class="pure-menu-item menu-item-divided"><a href="staff" class="pure-menu-link">Staff Panel</a></li>'."\n";
?></ul>