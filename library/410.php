<?php header('HTTP/1.1 410 Gone'); ?><h1 class="error"><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-410-head'])) echo $GLOBALS['designTexts']['error-410-head']; else echo 'Odstránený dokument'; ?></h1>
<p class="error"><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-410-text'])) echo $GLOBALS['designTexts']['error-410-text']; else echo 'Dokument, ktorý požadujete, bol odstránený.'; ?></p>
<p><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-410-desc'])) echo $GLOBALS['designTexts']['error-410-desc']; else echo 'Váš odkaz je pravdepodobne zastaraný. Článok alebo profil osoby, na ktorú sa odkaz vzťahuje, bol odstránený. Článok mohol byť neaktuálny alebo osoba už nemusí byť zamestnancom fakulty, prípadne sa zmenilo jej meno/priezvisko. Skúste získať aktuálny odkaz alebo vyberte možnosť z hlavnej, prípadne vedľajšej ponuky.'; ?></p>
<!--

Gone – The document requested is not on the server.
(Note: we do not guarantee that the file comes never back…)

Official explanation (according to HTTP standard):

Indicates that the resource requested is no longer available and will not be available again. The client should not request the resource again in the future. Clients such as search engines should remove the resource from their indices.

Most use cases do not require clients and search engines to purge the resource, and a “404 Not Found” may be used instead.

-->