<?php header('HTTP/1.1 301 Moved Permanently'); ?><h1 class="error">Presunutý dokument</h1>
<p class="error">Dokument, ktorý požadujete, bol natrvalo presunutý.</p>
<?php
if (isSet($newName))
{
	echo '<p>Nové umiestnenie dokumentu je:</p>';
	echo '<ul><li>https://pdf.truni.sk'.
		(($newName[0] != '/') ? '/' : '').
		'<a href="'.$newName.'">'.$newName.
		'</a></li></ul>';
	/* Do it in the CALLING script! * /header('Location: '.$newName);/**/
}
else echo '<p>Nové umiestnenie nie je známe.</p>';
?>
<!-- The requested resource has been assigned a new permanent URI and any future references to this resource SHOULD use one of the returned URIs. Clients with link editing capabilities ought to automatically re-link references to the Request-URI to one or more of the new references returned by the server, where possible. This response is cacheable unless indicated otherwise. -->