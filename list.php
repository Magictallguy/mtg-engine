<?php
require_once(__DIR__ . '/includes/globals.php');
require_once(__DIR__ . '/includes/class/class_mtg_paginate.php');
$pages = new Paginator();
$_GET['action'] = isset($_GET['action']) && ctype_alpha($_GET['action']) ? strtolower(trim($_GET['action'])) : null;
switch($_GET['action']) {
	case 'players':
		$db->query("SELECT COUNT(id) FROM users");
		$db->execute();
		$pages->items_total = $db->fetch_single();
		$pages->mid_range = 3;
		$pages->paginate();
		$db->query("SELECT id, last_seen FROM users ORDER BY id ASC ".$pages->limit);
		$db->execute();
		$rows = $db->fetch_row();
		?><p class='paginate'><?php echo $pages->display_pages(); ?></p>
		<table class='pure-table pure-table-striped' width='75%'>
			<tr>
				<th width='25%'>Player</th>
				<th width='25%'>Last Seen</th>
				<th width='50%'>Actions</th>
			</tr><?php
			foreach($rows as $row) {
				?><tr>
					<td><?php echo $mtg->username($row['id']); ?></td>
					<td><?php echo date('H:i:s d/m/Y', strtotime($row['last_seen'])); ?></td>
					<td><a href='messages.php?action=write&amp;ID=<?php echo $row['id']; ?>'>Message</a></td>
				</tr><?php
			}
		?></table>
		<p class='paginate'><?php echo $pages->display_pages(); ?></p><?php
		break;
}