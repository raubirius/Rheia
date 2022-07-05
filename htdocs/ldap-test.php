<?php

header('Content-Type: text/html; charset=utf-8');

echo '<h3>LDAP test</h3>';
echo '<p>rand #'.rand().'</p>';

echo '<p>Connecting…';
// $ldap_server = ldap_connect('ldaps://10.33.16.60:636/');
$ldap_server = ldap_connect('ldaps://10.33.16.60/', '636');
ldap_set_option($ldap_server, LDAP_OPT_PROTOCOL_VERSION, 3);
echo ' connect result is: '.$ldap_server.'</p>';

if ($ldap_server)
{
	echo '<p>Binding…</p>';
	$ldap_bind = ldap_bind($ldap_server,
		'cn=PDFwebProxy,ou=users,o=services',
		// NULL,
		'P@#$yzZY');
	echo '<p>Bind result is: ';
	var_dump($ldap_bind);
	echo '</p>';

	echo '<p>Closing connection…</p>';
	ldap_close($ldap_server);
}
else
{
	echo '<h4>Unable to connect to LDAP server</h4>';
}
?>