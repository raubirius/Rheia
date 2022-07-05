<?php
//‼ include_once 'design.php';

switch ((int)$_SERVER['QUERY_STRING'])
{
case 401:
	echo '<h1 class="error">'.$designTexts['error-401-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-401-text'].'</p>'.EOL.
		'<p>'.$designTexts['error-401-desc'].'</p>';
	break;

case 403:
	echo '<h1 class="error">'.$designTexts['error-403-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-403-text'].'</p>'.EOL.
		'<p>'.$designTexts['error-403-desc'].'</p>';
	break;

case 404:
	echo '<h1 class="error">'.$designTexts['error-404-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-404-text'].'</p>'.EOL.
		'<p>'.$designTexts['error-404-desc'].'</p>';
	break;

case 410:
	echo '<h1 class="error">'.$designTexts['error-410-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-410-text'].'</p>'.EOL.
		'<p>'.$designTexts['error-410-desc'].'</p>';
	break;

case 500:
	echo '<h1 class="error">'.$designTexts['error-500-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-500-text'].'</p>';
	break;

case 501:
	echo '<h1 class="error">'.$designTexts['error-501-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-501-text'].'</p>';
	break;

default:
	echo '<h1 class="error">'.$designTexts['error-unkwn-head'].'</h1>'.EOL.
		'<p class="error">'.$designTexts['error-unkwn-text'].'</p>'.EOL.
		'<p>'.$designTexts['error-unkwn-desc'].'</p>';
}
?>