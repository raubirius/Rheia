<?php

$javaScript2 = EOL.'(function() {'.EOLT.
	'var cx = \'002314613412010875433:ilmrkn_gzpk\';'.EOLT.
	'var gcse = document.createElement(\'script\');'.EOLT.
	'gcse.type = \'text/javascript\';'.EOLT.'gcse.async = true;'.EOLT.
	// 'gcse.src = (document.location.protocol == \'https:\' ?'.EOL.TAB2.
	// '\'https:\' : \'http:\') + \'//www.google.com/cse/cse.js?'.
	// 	'cx=\' + cx;'.EOLT.
	'gcse.src = \'https://cse.google.com/cse.js?cx=\' + cx;'.EOLT.
	'var s = document.getElementsByTagName(\'script\')[0];'.EOLT.
	's.parentNode.insertBefore(gcse, s);'.EOL.'})();'.EOL;

echo '<gcse:searchresults-only></gcse:searchresults-only>'.EOL;

?>