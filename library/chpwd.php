<?php

echo '<h1 class="chpwd">'.($title = $designTexts['chpwd-title']).'</h1>'.EOL;

$chpwdResult = LoginManagement::handleStandardChpwd();
if (true === $chpwdResult)
	echo '<p class="chpwd-info">'.$designTexts['chpwd-successful'].'</p>';
elseif (false === $chpwdResult)
{
	echo '<p class="chpwd-info error">'.$designTexts['chpwd-failed'].'</p>'.
		EOL.'<p class="chpwd-info">'.$designTexts['chpwd-explain'].' '.
		$designTexts['chpwd-try-again'].'</p>'.EOL;
	LoginManagement::deployChpwdDialog();
}

?>