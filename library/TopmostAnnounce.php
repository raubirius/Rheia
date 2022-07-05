<?php

// Note: this is a module used by the RheiaMainClass.
// For more information see: modules-readme.txt

class TopmostAnnounce
{
	private static $deployStyle = true;
	private static $defaultStyle = true;
	private static $defaultHead = true;

	function run($args)
	{
		global $style, $javaScript1;
		$announceBody = ''; $starts = null; $expires = null;
		$announceLinkText = 'Pokračovať na stránku / Continue to Website';
		$announceTime = 'topmostAnnounceTime';
		// $announceRestart = 21600; // 6 h
		$announceRestart = 28800; // 8 h

		foreach ($args as $arg)
		{
			if ('defaultStyle' == $arg)
				TopmostAnnounce::$defaultStyle = true;
			else if ('noDefaultStyle' == $arg)
				TopmostAnnounce::$defaultStyle = false;
			else if ('defaultHead' == $arg)
				TopmostAnnounce::$defaultHead = true;
			else if ('noDefaultHead' == $arg)
				TopmostAnnounce::$defaultHead = false;
			else if (preg_match('/^head:(.*)/', $arg, $matches))
			{
				TopmostAnnounce::$defaultHead = false;
				$announceBody .= '<h1>'.$matches[1].'</h1>';
			}
			else if (preg_match('/^text:(.*)/', $arg, $matches))
			{
				$announceBody .= '<p>'.$matches[1].'</p>';
			}
			else if (preg_match('/^link[Tt]ext:(.*)/', $arg, $matches))
			{
				$announceLinkText = $matches[1];
			}
			else if (preg_match('/^starts:(.*)/', $arg, $matches))
			{
				$starts = RheiaMainClass::mktimestamp($matches[1]);
				if ($starts > RheiaMainClass::$currentTime) return '';
			}
			else if (preg_match('/^expires:(.*)/', $arg, $matches))
			{
				$expires = RheiaMainClass::mktimestamp($matches[1]);
				if ($expires < RheiaMainClass::$currentTime) return '';
			}
			else if (preg_match('/^baseSeparator:(.*)/', $arg, $matches))
				TopmostAnnounce::$baseSeparator = $matches[1];
			else if (preg_match('/^contents:(.*)/', $arg, $matches))
				TopmostAnnounce::$contents = $matches[1];
			else if (preg_match('/^timerID:(.*)/', $arg, $matches))
				$announceTime = $matches[1];
			else if (preg_match('/^restart:(.*)/', $arg, $matches))
				$announceRestart = $matches[1];
		}

		if (TopmostAnnounce::$deployStyle && TopmostAnnounce::$defaultStyle)
		{
			TopmostAnnounce::$deployStyle = false;
			$style .= EOL.'div.topmost'.EOL.'{'.EOLT.'background-color: '.
			'rgba(255, 255, 255, 0.80);'.//EOLT.'/*padding-top: 300px;*/'.
			EOL.'}'.EOL2.'div.topmost div.important'.EOL.'{'.EOLT.'border: '.
			'2px solid black;'.EOLT.'border-radius: 8px;'.EOLT.'box-shadow: '.
			'2px 2px 12px #888;'.EOLT.'display: inline-block;'.EOLT.'padding: '.
			'0px 50px 40px;'.EOLT.'background-color: white;'.EOLT.'top: '.
			'250px;'.EOL2.TAB.'color: #A00;'.EOLT.'letter-spacing: 3px;'.EOLT.
			// '/*text-shadow:'.EOL.TAB2.'-1px 0 #FFF, 0 1px #FFF, 1px 0 '.
			// '#FFF, 0 -1px #FFF,'.EOL.TAB2.'-3px 0 #A00, 0 3px #A00, 3px '.
			// '0 #A00, 0 -3px #A00,'.EOL.TAB2.'-2px -2px #A00, -2px 2px '.
			// '#A00, 2px 2px #A00, 2px -2px #A00;*/'.EOL2.TAB.
			'text-shadow:'.EOL.TAB2.'-3px '.
			'0 #fff, 0 3px #fff, 3px 0 #fff, 0 -3px #fff,'.EOL.TAB2.'-2px '.
			'-2px #fff, -2px 2px #fff, 2px 2px #fff, 2px -2px #fff,'.EOL.TAB2.
			'2px 2px 12px #000;'.EOL2.'}'.EOL2.
			// 'div.topmost div.important table'.EOL.'{'.EOLT.'/* width:100%;'.
			// EOLT.'min-height: 700px; */'.EOL.'}'.EOL2.
			'div.topmost h1'.EOL.'{'.EOLT.'text-align: center;'.EOLT.
			'font-size: 32px;'.EOLT.'margin-bottom: 40px;'.EOLT.
			'font-weight: bold;'.EOL.'}'.EOL2.'div.topmost p'.EOL.
			'{'.EOLT.'text-align: center;'.EOLT.'font-size: 24px;'.EOLT.
			'font-weight: bold;'.EOL.'}'.EOL2.'div.topmost p.close'.EOL.
			'{'.EOLT.'font-size: 19px;'.EOLT.'margin-top: 40px;'.EOL.'}'.EOL2.
			// '/*div.topmost p.close a:before'.EOL.'{'.EOLT.'content: "[ ";'.
			// EOLT.'color: black;'.EOLT.'font-size: 24px;'.EOL.'}'.EOL2.
			// 'div.topmost p.close a:after'.EOL.'{'.EOLT.'content: " ]";'.EOLT.
			// 'color: black;'.EOLT.'font-size: 24px;'.EOL.'}*/'.EOL2.
			'div.topmost a:hover'.EOL.'{'.EOLT.'text-decoration: none;'.
			EOL.'}'.EOL2;
		}

		$visible = LoginManagement::getSessionProperty($announceTime);
		if (empty($visible) || ($announceRestart <= 0)) $visible = true; else
		{
			$visible = time() - $visible;
			$visible = $visible >= $announceRestart;
		}

		if (!empty($announceBody) && $visible)
		{
			if (TopmostAnnounce::$defaultHead)
				$announceBody = '<h1>Upozornenie</h1>'.$announceBody;

			$javaScript1 .=
				'var topmost = getElement(\'topmost\');'.EOL2.
				'topmost.innerHTML = \'<div class="important"><table><tr><td>'.
					addcslashes($announceBody, '\\'.EOL).'<p class="close">'.
					'<a href="javascript:void(0)" onclick="hideItem(\\\''.
					'topmost\\\');saveSessionTime(\\\''.$announceTime.
					'\\\');">'.$announceLinkText.'</a></p>'.
					'</td></tr></table></div>\';'.EOL2.
				'showItem(\'topmost\');'.EOL2;
		}

		return '';
	}
}

?>