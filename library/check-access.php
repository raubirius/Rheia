<?php

if (!LoginManagement::loggedIn())
{
	$loginResult = LoginManagement::handleStandardLogin(false);

	if (true !== $loginResult)
	{
		include '401.php';
		exit;
	}
}

if (!LoginManagement::checkAccess($params[0]))
{
	include 'no-access.php';
	exit;
}

?>