<?php

$counterBase = (isSet($counterLevel) ? $counterLevel : '').
	'../counter/'.counterBase;

if (file_exists($counterBase.'.php')) include $counterBase.'.php';
if (!isSet($accessLog)) $accessLog = array();

if (class_exists('RheiaMainClass'))
	$accessDay = date('Y-m-d', RheiaMainClass::$currentTime);
else
	$accessDay = date('Y-m-d');
if (!isSet($accessLog[$accessDay])) $accessLog[$accessDay] = array();

$serverURI = $_SERVER['REQUEST_URI'];
$clientAddress = $_SERVER['REMOTE_ADDR'];

if (isSet($accessLog[$accessDay][$serverURI]))
	++$accessLog[$accessDay][$serverURI];
else
	$accessLog[$accessDay][$serverURI] = array();

if (isSet($accessLog[$accessDay][$serverURI][$clientAddress]))
	++$accessLog[$accessDay][$serverURI][$clientAddress];
else
	$accessLog[$accessDay][$serverURI][$clientAddress] = 0;

do
{
	$accessRestart = false;

	foreach ($accessLog as $accessKey => $accessData)
	{
		if ($accessKey !== $accessDay)
		{
			$counter = '<'.'?php'.EOL2;
			$counter .= '$accessDay = '.var_export($accessData, true);
			$counter .= ';'.EOL2.'?'.'>';
			file_put_contents($counterBase.'-'.$accessKey.
				'.php', $counter, LOCK_EX);
			unset($accessLog[$accessKey]);
			$accessRestart = true;
			break;
		}
	}

}
while ($accessRestart);

$counter = '<'.'?php'.EOL2;
$counter .= '$accessLog = '.var_export($accessLog, true);
$counter .= ';'.EOL2.'?'.'>';
file_put_contents($counterBase.'.php', $counter, LOCK_EX);

?>