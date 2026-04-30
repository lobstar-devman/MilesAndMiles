<?php
require_once('plugins/login-servers.php');

/** Set supported servers
    * @param array array($domain) or array($domain => $description) or array($category => array())
    * @param string
    */
return new AdminerLoginServers(
    [
		'dev'  =>['server' => 'postgres', 'driver' => 'pgsql'],
		'test' =>['server' => 'testdb', 'driver' => 'pgsql']
	]
);