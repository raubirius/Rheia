<?php
/**
 * Sem sa vykonávanie dostane len pri chybne definovanej položke ponuky.
 * Galérie nemajú mať rodiča, majú byť koreňom a ich „selectedItem“ obsahuje
 * identifikátor galérie. Ak sa však vykonávanie dostane sem, znamená to,
 * že sa omylom dostal koreň galérie do „selectedItem“ (čiže o úroveň nižšie),
 * takže to riešim presmerovaním na html, ktoré by malo byť skutočným koreňom
 * galérií…
 */
include_once 'selectedItem-html.php';
?>