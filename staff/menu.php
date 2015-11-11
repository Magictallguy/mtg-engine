<?php
if(!defined('MTG_ENABLE'))
	exit;
?><ul class="pure-menu-list"><?php
$links = [
	'divider0' => 'Index',
	'index.php' => 'Game',
	'staff' => 'Staff Index',
	'staff?action=settings' => 'Game Settings',
	'divider1' => 'Staff Ranks',
	'staff/?pull=ranks' => 'Rank Management',
	'staff/?pull=ranks&amp;action=currentstaff' => 'Staff Management',
	'staff/?pull=ranks&amp;action=set' => 'Set Player Staff Rank',
	'divider2' => 'Misc',
	'?action=logout' => 'Logout'
];
foreach($links as $url => $disp) {
	if(preg_match('/^divider(.*?)$/i', $url))
		echo '<li class="pure-menu-item menu-item-divided"><a href="staff/#" class="pure-menu-link pure-menu-heading">'.$disp.'</a></li>';
	else
		printf('<li class="pure-menu-item"><a href="%s" class="pure-menu-link%s">%s</a></li>'."\n", $url, $_SERVER['PHP_SELF'] == '/'.$url.'.php' ? ' pure-menu-selected' : null, $disp);
}
?></ul>