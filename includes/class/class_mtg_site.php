<?php
namespace MTG;
/*DON'T BE A DICK PUBLIC LICENSE

Everyone is permitted to copy and distribute verbatim or modified copies of this license document, and changing it is allowed as long as the name is changed.

    DON'T BE A DICK PUBLIC LICENSE TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

    Do whatever you like with the original work, just don't be a dick.

    Being a dick includes - but is not limited to - the following instances:

    1a. Outright copyright infringement - Don't just copy this and change the name.
    1b. Selling the unmodified original with no work done what-so-ever, that's REALLY being a dick.
    1c. Modifying the original work to contain hidden harmful content. That would make you a PROPER dick.

    If you become rich through modifications, related works/services, or supporting the original work, share the love. Only a dick would make loads off this work and not buy the original works creator(s) a pint.

    Code is provided with no warranty. Using somebody else's code and bitching when it goes wrong makes you a DONKEY dick. Fix the problem yourself. A non-dick would submit the fix back.
*/
if(!defined('MTG_ENABLE'))
	exit;
class mtg_site {
	static $inst = null;
	static function getInstance() {
		if(self::$inst == null)
			self::$inst = new mtg_site();
		return self::$inst;
	}
	public function checkEnabled($area) {
		global $db;
		$db->query('SELECT `status` FROM `settings_mods` WHERE `area` = ?');
		$db->execute([$area]);
		if(!$db->num_rows())
			return true;
		return !$db->fetch_single() ? false : true;
	}
}
$site = mtg_site::getInstance();