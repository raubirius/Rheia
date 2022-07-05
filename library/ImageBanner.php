<?php

// Note: this is a module used by the RheiaMainClass.
// For more information see: modules-readme.txt

class ImageBanner
{
	function myReplAll($data)
	{
		return
			preg_replace('~(https?:)&#47;~', '$1//',
			preg_replace('~</?i>~', '/',
			preg_replace('~</?del>~', '-',
				$data)));
	}

	function run($args)
	{
		$href  = null;
		$src   = null;
		$alt   = 'image';
		$tgt   = '_blank';
		$pstyle = null;
		$pclass = null;
		$astyle = null;
		$aclass = null;
		$style = null;
		$class = null;
		$title = null;

		$starts = null;
		$expires = null;

		foreach ($args as $arg)
		{
			if (preg_match('/^(link|url|href|anchor|a):(.*)/', $arg, $matches))
				$href = $this->myReplAll($matches[2]);
			elseif (preg_match('/^plain(Image|Img|Src):(.*)/', $arg, $matches))
				$src = $this->myReplAll($matches[2]);
			elseif (preg_match('/^(image|img|src):(.*)/', $arg, $matches))
				$src = '/image?'.$this->myReplAll($matches[2]);
			elseif (preg_match('/^(alt|desc|description):(.*)/', $arg,
				$matches)) $alt = $this->myReplAll($matches[2]);
			elseif (preg_match('/^target:(.*)/', $arg, $matches))
				$tgt = $matches[1];
			elseif (preg_match('/^astyle:(.*)/', $arg, $matches))
			{
				if (empty($astyle))
					$astyle = $matches[1];
				else
					$astyle = $matches[1];
			}
			elseif (preg_match('/^aclass:(.*)/', $arg, $matches))
			{
				if (empty($aclass))
					$aclass = $matches[1];
				else
					$aclass .= ' '.$matches[1];
			}
			elseif (preg_match('/^pstyle:(.*)/', $arg, $matches))
			{
				if (empty($pstyle))
					$pstyle = $matches[1];
				else
					$pstyle .= ' '.$matches[1];
			}
			elseif (preg_match('/^pclass:(.*)/', $arg, $matches))
			{
				if (empty($pclass))
					$pclass = $matches[1];
				else
					$pclass .= ' '.$matches[1];
			}
			elseif (preg_match('/^style:(.*)/', $arg, $matches))
			{
				if (empty($style))
					$style = $matches[1];
				else
					$style .= ' '.$matches[1];
			}
			elseif (preg_match('/^class:(.*)/', $arg, $matches))
			{
				if (empty($class))
					$class = $matches[1];
				else
					$class .= ' '.$matches[1];
			}
			elseif (preg_match('/^center$/', $arg, $matches))
			{
				if (empty($pstyle)) $pstyle = ''; else $pstyle .= ' ';
				$pstyle .= 'text-align: center;';
			}
			elseif (preg_match('/^floatRight$/', $arg, $matches))
			{
				if (empty($pstyle)) $pstyle = ''; else $pstyle .= ' ';
				$pstyle .= 'float: right;';
			}
			elseif (preg_match('/^right$/', $arg, $matches))
			{
				if (empty($pstyle)) $pstyle = ''; else $pstyle .= ' ';
				$pstyle .= 'text-align: right;';
			}
			elseif (preg_match('/^floatLeft$/', $arg, $matches))
			{
				if (empty($pstyle)) $pstyle = ''; else $pstyle .= ' ';
				$pstyle .= 'float: left;';
			}
			elseif (preg_match('/^left$/', $arg, $matches))
			{
				if (empty($pstyle)) $pstyle = ''; else $pstyle .= ' ';
				$pstyle .= 'text-align: left;';
			}
			elseif (preg_match('/^title:(.*)/', $arg, $matches))
				$title = $matches[1];
			else if (preg_match('/^starts:(.*)/', $arg, $matches))
				$starts = RheiaMainClass::mktimestamp($matches[1]);
			else if (preg_match('/^expires:(.*)/', $arg, $matches))
				$expires = RheiaMainClass::mktimestamp($matches[1]);
		}

		if (empty($href) || empty($src))
			return '<!-- Not enough banner data. -->';

		if (!empty($starts) && $starts > RheiaMainClass::$currentTime)
			return '<!-- Preparing for banner ('.$src.' –> '.$href.'). -->';

		if (!empty($expires) && $expires < RheiaMainClass::$currentTime)
			return '<!-- Banner expired ('.$src.' –> '.$href.'). -->';


		$return = EOL.'<p';
		if (!empty($pstyle)) $return .= ' style="'.$pstyle.'"';
		if (!empty($pclass)) $return .= ' class="'.$pclass.'"';
		$return .= '><a href="'.$href.'"';
		if (!empty($tgt)) $return .= ' target="'.$tgt.'"';
		if (!empty($astyle)) $return .= ' style="'.$astyle.'"';
		if (!empty($aclass)) $return .= ' class="'.$aclass.'"';
		$return .= '><img src="'.$src.'" alt="'.$alt.'"';
		if (!empty($style)) $return .= ' style="'.$style.'"';
		if (!empty($class)) $return .= ' class="'.$class.'"';

		return $return.' /></a></p>'.EOL;
		// return '<pre>'.htmlspecialchars($return).'</pre>';
	}
}

?>