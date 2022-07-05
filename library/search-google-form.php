<?php

$style .= 'form.google-search input.query'.EOL.'{'.EOLT.'width: 480px;'.
	EOLT.'background-color: #ddd;'.EOLT.'border: 1px solid #ccc;'.EOLT.
	'font-size: 10px;'.EOL.'}'.EOL2.'form.google-search input.submit'.EOL.
	'{'.EOLT.'background: none;'.EOLT.'padding: 0px;'.EOLT.
	'margin-left: 16px;'.EOLT.'border: none;'.
	// EOLT.'color: #462;'.
	EOL.'}'.
	EOL2.'form.google-search input.submit:hover,'.EOL.
	'form.google-search input.submit:focus,'.EOL.
	'form.google-search input.submit:active'.EOL.
	'{'.EOLT.'color: #999;'.EOL.'}'.EOL;

echo '<hr />'.EOL;
echo '<p>'.$searchTexts['search-not-satisfied-try-google'].':</p>'.EOL;
if (isSet($siteSectionPath)) echo '<p><small><em>(<b>'.
	RheiaMainClass::getText('common-note').':</b> '.$searchTexts
	['search-note-global-search'].')</em></small></p>';

echo '<form class="google-search">'.EOL;
echo TAB.'<input type="text" name="q" value="'.
	htmlspecialchars(stripslashes($_POST['q'])).
	'" class="query">'.EOL;
echo TAB.'<input type="submit" value="'.$searchTexts
	['search-use-google'].'" class="submit">'.EOL;
echo '</form>';

?>