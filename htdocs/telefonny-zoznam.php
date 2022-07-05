<?php
include 'pdf.php';
include 'design.php';

if ('uplny' != strtolower($selectedItem)) {

$title = 'Telefónny zoznam – vyhľadávanie';
$style .= 'div.page-content input.search
{
	position: relative;
	top: -1px;
	width: 130px;
	background-color: #eee;
	border: 1px solid #bbb;
	font-size: 12px;
}

div.page-content a.search
{
	font-size: 13px;
}';
?>
<h1>Telefónny zoznam</h1>

<p>Telefónny zoznam je zároveň súčasťou vyhľadávacieho modulu. Môžete použiť vyhľadávacie pole vo vrchnej časti obrazovky alebo nasledujúce pole:</p>

<p> </p>

<p>     Zadajte meno zamestnanca alebo jeho časť:  
<input id="phone_list" type="text" name="q" value=""
	onkeypress="checkSubmit('phone_list', 'sfe')" autocomplete="on"
	class="search" />  
<a href="javascript:search('phone_list', 'sfe');" class="search">Hľadať</a></p>

<?php include 'search-employee-only.php'; ?>

<form name="sfe" method="post" style="display: none;"><input type="text" name="q" value="" required="required" /></form>

<?php } else {

$title = 'Úplný telefónny zoznam';
echo '<h1>'.$title.'</h1>'.EOL;
$langPostfix['en'] = 'full';

include 'phone-list.php';

} ?>

<p> </p>

<p><a href="https://www.truni.sk/telefonny-zoznam" target="_blank" class="external-link"><span>Telefónny zoznam univerzity</span> <img src="design/null.gif" alt="externý odkaz" title="externý odkaz" class="external-icon"></a></p>