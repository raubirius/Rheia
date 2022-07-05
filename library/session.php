<?php
if (isSet($_REQUEST) && is_array($_REQUEST))
{
	if (isSet($_REQUEST['set']) && $_REQUEST['val'] &&
		'userID' !== $_REQUEST['set'])
	{
		if (false === ($sessionError = !session_start()))
		{
			$_SESSION[$_REQUEST['set']] = $_REQUEST['val'];
			die('ok: '.$_REQUEST['set'].' = '.$_SESSION[$_REQUEST['set']]);
		}
	}
	else if (isSet($_REQUEST['savetime']) && 'userID' !== $_REQUEST['savetime'])
	{
		if (false === ($sessionError = !session_start()))
		{
			$_SESSION[$_REQUEST['savetime']] = time();
			die('ok: '.$_REQUEST['savetime'].' = time('.
				$_SESSION[$_REQUEST['savetime']].')');
		}
	}
}
echo 'error';
?>