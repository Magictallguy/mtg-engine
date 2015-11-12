<?php
if(!defined('MTG_ENABLE'))
	exit("Direct access not permitted");
$_GET['action'] = isset($_GET['action']) && ctype_alpha($_GET['action']) ? strtolower(trim($_GET['action'])) : null;
switch($_GET['action']) {
	case 'settings':
		gameSettings($db, $my, $mtg, $set);
		break;
	default:
		index($db, $my, $mtg, $set, $users);
		break;
}
function index($db, $my, $mtg, $set, $users) {
	?><h3 class="content-subhead">Welcome to the Staff Panel</h3><?php
	if(array_key_exists('submit', $_POST)) {
		$_POST['text'] = isset($_POST['text']) ? trim($_POST['text']) : null;
		$db->query("UPDATE `game_settings` SET `value` = ? WHERE `name` = 'staff_notepad'");
		$db->execute([$_POST['text']]);
		$set['staff_notepad'] = $_POST['text'];
	}
	$installedVersion = $mtg->codeVersion('installed');
	$repoVersion = $mtg->codeVersion('repo');
	$repoVersionNF = strip_tags($repoVersion);
	if(array_key_exists('updateversion', $_GET)) {
		if($installedVersion != $repoVersionNF) {
			$db->query('UPDATE `settings_game` SET `value` = ? WHERE `name` = "engine_version"');
			$db->execute([$repoVersionNF]);
			$installedVersion = $repoVersionNF;
			$mtg->success('Local engine version marker has been updated');
		} else
			$mtg->info('You\'re already running the latest version');
	}
	$db->query("SELECT VERSION()");
	$db->execute();
	$sqlVersion = $db->fetch_single();
	?><p><table width="100%" class="pure-table pure-table-striped">
		<tr>
			<th width="25%">Code Version</th>
			<td width="75%"><?php
				echo $installedVersion;
				if($users->hasAccess('staff_panel_code_version_manage') && $repoVersionNF != $installedVersion)
					echo ' <span class="small">[<a href="staff/?updateversion=true">update marker</a>]</span>';
			?></td>
		</tr>
		<tr>
			<th>Repo Version</th>
			<td><?php echo $repoVersion;?></td>
		<tr>
			<th>PHP Version</th>
			<td><?php echo phpversion();?></td>
		</tr>
		<tr>
			<th>MySQL Version</th>
			<td><?php echo $sqlVersion;?></td>
		</tr>
	</table></p><?php
	if(array_key_exists('text', $_POST))
		echo $mtg->success("Staff Notepad updated");
	?><form method="post" class="pure-form">
		<div class="pure-control-group">
			<textarea name="text" rows="10" cols="100%"><?php echo stripslashes($set['staff_notepad']);?></textarea>
		</div>
		<div class="pure-controls">
			<button type="submit" name="submit" class="pure-button pure-button-primary">Update Staff Notepad</button>
			<button type="reset" class="pure-button pure-button-secondary"><i class="fa fa-recycle"></i> Reset</button>
		</div>
	</form><?php
}
function gameSettings($db, $my, $mtg, $set) {
	?><h2 class="styleh3 p2">Game Settings</h2><?php
	if(array_key_exists('submit', $_POST)) {
		$strs = ['game_name', 'game_description', 'register_promo_code', 'main_currency_symbol'];
		foreach($strs as $what)
			$_POST[$what] = isset($_POST[$what]) && is_string($_POST[$what]) ? trim($_POST[$what]) : null;
		$nums = ['register_start_cash', 'register_promo_cash', 'game_owner_id'];
		foreach($nums as $what)
			$_POST[$what] = isset($_POST[$what]) && ctype_digit(str_replace(',', '', $_POST[$what])) ? str_replace(',', '', $_POST[$what]) : 0;
		$posted = array_merge($strs, $nums);
		foreach($posted as $what) {
			$db->query("UPDATE game_settings SET value = ? WHERE name = ?");
			$db->execute([$_POST[$what], $posted]);
		}
		$mtg->success("You've updated the game's settings");
	}
	?><form action="?action=settings" method="post" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<span class="pure-heading">Basic Settings</span>
			<fielset class="pure-group">
				<label for="name">Name</label>
				<input type="text" name="game_name" value="<?php echo $mtg->format($set['game_name']);?>" />
				<label for="owner">Game Owner's ID</label>
				<input type="text" name="game_owner_id" value="<?php echo $mtg->format($set['game_owner_id']);?>" />
				<label for="description">Description</label>
				<textarea name="game_description" rows="10" cols="40"><?php echo $mtg->format($set['game_description']);?></textarea>
			</fielset>
			<fieldset class="pure-group">
				<span class="pure-heading">Registration</span>
				<label for="cash">Start Cash</label>
				<input type="text" name="register_start_cash" value="<?php echo $mtg->format($set['register_start_cash']);?>" />
				<label for="promo-code">Promotional Code</label>
				<input type="text" name="register_promo_code" value="<?php echo $mtg->format($set['register_promo_code']);?>" />
				<label for="promo-cash">Promo Cash</label>
				<input type="text" name="register_promo_cash" value="<?php echo $mtg->format($set['register_promo_cash']);?>" />
			</fieldset>
			<fieldset class="pure-group">
				<span class="pure-heading">Game Settings</span>
				<label for="curreny-symbol">Currency Symbol</label>
				<input type="text" name="main_currency_symbol" value="<?php echo htmlentities($mtg->format($set['main_currency_symbol']));?>" />
			</fieldset>
		</div>
		<div class="pure-controls">
			<button type="submit" name="submit" class="pure-button pure-button-primary">Update Settings</button>
			<button type="reset" class="pure-button pure-button-secondary"><i class="fa fa-recycle"></i> Reset</button>
		</div>
	</form><?php
}