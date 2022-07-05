<?php

/*
http://docs.oracle.com/javase/jndi/tutorial/ldap/security/ldap.html
http://javachamp.blogspot.sk/2008/06/simple-ldap-authentication.html
http://stackoverflow.com/questions/6302870/java-ldap-authentication-using-username-and-password
*/

// $sessionRunnning = session_start();
// require_once('java/Java.inc');

echo '<h1 class="login">'.($title = $designTexts['login-title']).'</h1>'.EOL;
if (LoginManagement::loggedIn())
{
	echo '<p class="login-info error">'.
		$designTexts['login-already-logged-1'].
		'<a href="'.LoginManagement::$logoutScript.'">'.
		$designTexts['login-already-logged-2'].'</a>'.
		$designTexts['login-already-logged-3'].'</p>';
}
else
{
	$loginResult = LoginManagement::handleStandardLogin();
	if (true === $loginResult)
	{
		echo '<p class="login-info">'.$designTexts['login-successful'].'</p>';
		$userData = LoginManagement::reloadUserData($_SESSION['userID']);

		$goHomeText = isSet($userData['loginGoHomeText']) ?
			$userData['loginGoHomeText'][1] :
			$designTexts['login-default-go-home'];

		if (isSet($userData['home–'.$contentLanguage]))
			$goHomeLink = $userData['home–'.$contentLanguage][1];
		elseif (isSet($userData['home']))
			$goHomeLink = $userData['home'][1];
		elseif (!empty(LoginManagement::$defaultHomeScript))
			$goHomeLink = LoginManagement::$defaultHomeScript;
		else
			$goHomeLink = '';

		if (!empty($goHomeText))
			echo '<p class="login-info"><a href="'.
				$goHomeLink.'">'.$goHomeText.'</a></p>';
	}
	elseif (false === $loginResult)
	{
		echo '<p class="login-info error">'.
			$designTexts['login-failed'].'</p>'.EOL.
			'<p class="login-info">'.
			$designTexts['login-try-again'].'</p>';
		LoginManagement::deployLoginDialog();
	}
}

?>