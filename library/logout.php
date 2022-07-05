<?php
echo '<h1 class="logout">'.($title = $designTexts['logout-title']).'</h1>'.EOL.'<p class="logout">';
echo LoginManagement::logOut() ? $designTexts['logout-successful'] :
	$designTexts['logout-no-need'];
echo '</p>';
?>