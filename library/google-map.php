<?php /*
<div class="google-map"><p><iframe id="google-map" allowfullscreen></iframe><br />< ? php
$googleOpenBiggerLink =
	'https://www.google.sk/maps/place/Pedagogick%C3%A1+fakulta+Trnavskej+univerzity/@48.3620813,17.5968097,807m/data=!3m1!1e3!4m2!3m1!1s0x476b5f8fe34d84c7:0x244da4f229e70dff!6m1!1e1?hl=sk';
$googleIFrameLink =
	'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3514.2056395108225!2d17.5968097!3d48.3620813!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x476b5f8fe34d84c7%3A0x244da4f229e70dff!2sPedagogick%C3%A1+fakulta+Trnavskej+univerzity!5e1!3m2!1ssk!2ssk!4v1438000463546';
echo makeExternalLink($designTexts['design-google-map-zoom'],
// 'https://maps.google.sk/maps?ie=UTF8&cid=1103527080066923765&q=Pedagogick%C3%A1+fakulta+Trnavskej+univerzity+v+Trnave&gl=SK&hl=en&t=h&iwloc=A&ll=48.362021,17.596775&spn=0.006295,0.006295&source=embed');
$googleOpenBiggerLink);
$style .= EOL.'div.google-map'.EOL.'{'.EOLT.'position: absolute;'.EOLT.
	'right: 175px;'.EOLT.'top: 260px;'.EOLT.'z-index: 100;'.EOL.'}'.EOL2.
	'div.google-map iframe'.EOL.'{'.EOLT.'width: 405px;'.EOLT.
	'height: 330px;'.EOL.'}'.EOL2.'div.google-map p'.EOL.'{'.EOLT.
	'font-size: 13px;'.EOLT.'text-align: center;'.EOL.'}'.EOL2.'h1'.EOL.
	'{'.EOLT.'margin-top: 3px;'.EOL.'}'.EOL;
$style .= EOL.'@media print'.EOL.'{'.EOLT.'div.google-map'.EOLT.'{'.
	EOL.TAB2.'right: 20px;'.EOL.TAB2.'top: 70px;'.EOLT.'}'.EOL.'}'.EOL2;
// $javaScript2 .= TAB.'document.getElementById(\'google-map\').src =
// \'https://maps.google.sk/maps?ie=UTF8&cid=1103527080066923765&q=Pedagogick%C3%A1+fakulta+Trnavskej+univerzity+v+Trnave&gl=SK&hl=en&t=h&iwloc=A&ll=48.362021,17.596775&spn=0.006295,0.006295&output=embed\';'.EOL;
$javaScript2 .= TAB.'document.getElementById(\'google-map\').src = \''.
	$googleIFrameLink.'\';'.EOL;
 ? ></p></div>
*/ ?>