<?php
include 'pdf.php';
include 'design.php';
include 'utils.php';

if ($string = handleSomeRedirects((int)$_SERVER['QUERY_STRING']))
	echo $string; else echo '<h1 class="error">Informácia</h1>
<p class="error">Počas spracovania požiadavky sa vyskytla neznáma chyba.</p>';
?>