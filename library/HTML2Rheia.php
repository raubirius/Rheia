<?php

include_once 'whitespace-constants.php';

set_error_handler(
	create_function(
		'$severity, $message, $file, $line',
		'throw new ErrorException($message, $severity, $severity, $file, $line);'
	)
);

class HTML2RHeia
{
	public static $errors = array();
	public static $debug = array();


	public static function processFile($filename)
	{
		try
		{
			$contents = preg_replace('/<([^\r\n>]+)[\r\n]+/', '<$1 ',
				file_get_contents($filename));

			// To debug:
			// HTML2RHeia::$debug[] = array('pre', null,
			// 	htmlspecialchars($contents));
		}
		catch (Exception $e)
		{
			HTML2RHeia::$errors[] = array('Conversion error', $e->getMessage());
			return null;
		}

		// // Dismissed attempt:
		// if (preg_match('~<body([^>]+)>(.*?)</body~is', $contents, $match))
		// {
		// 	$document = new DOMDocument('1.0', 'UTF-8');
		// 	$document->loadHTML('<?xml encoding="UTF-8">'.$match[2]);
		// 
		// 	// $xml = simplexml_load_string($match[2]);
		// 	// var_dump($xml);
		// 
		// 	// To debug:
		// 	HTML2RHeia::$debug[] = array('pre', null, htmlspecialchars($match[2]).
		// 		print_r($document, true));
		// }
		// else HTML2RHeia::$errors[] = array('Conversion error',
		// 	'The body hasn’t been found…');

		try
		{
			$document = new DOMDocument();
			$document->loadHTML($contents);

			// To debug:
			// HTML2RHeia::$debug[] = array('pre',
			// 	null, print_r($document, true));
		}
		catch (Exception $e)
		{
			HTML2RHeia::$errors[] = array('Conversion error', $e->getMessage());
			return null;
		}

		if ($document->hasChildNodes()) foreach ($document->childNodes as $node)
		{
			// To debug:
			// HTML2RHeia::$debug[] = array('pre',
			// 	'Node:', print_r($node, true));

			if ($node->hasChildNodes()) foreach ($node->childNodes as $subnode)
			{
				// To debug:
				// HTML2RHeia::$debug[] = array('pre',
				// 	'Subnode:', print_r($subnode, true));

				if (($subnode instanceof DOMElement) &&
					('body' == strtolower($subnode->tagName)))
					return HTML2RHeia::processBody($subnode);
			}
		}
		else
		{
			HTML2RHeia::$errors[] = array(
				'Conversion error', 'The document is empty.');
			return null;
		}
	}


	public static function processBody($body)
	{
		$bodyContents = '';

		if ($body->hasChildNodes()) foreach ($body->childNodes as $bodyNode)
		{
			if ($bodyNode instanceof DOMElement)
			{
				$tagName = strtolower($bodyNode->tagName);

				if ('div' == $tagName)
					$bodyContents .= HTML2RHeia::processBody($bodyNode);
				elseif ('p' == $tagName || 'h1' == $tagName || 'h2' == $tagName ||
					'h3' == $tagName || 'h4' == $tagName || 'hr' == $tagName)
					$bodyContents .= HTML2RHeia::processPara($bodyNode, $tagName);

				// To debug:
				else HTML2RHeia::$debug[] = array('pre',
					'Body subnode:', print_r($bodyNode, true));
			}
			// To debug:
			// else HTML2RHeia::$debug[] = array('pre',
			// 	'Body object:', print_r($bodyNode, true));
		}

		return $bodyContents;
	}


	public static function processPara($para, $type)
	{
		$paraContents = '';
		$paraPrefix = '';
		$paraPostfix = EOL2;
		if ('h1' == $type) $paraPostfix = EOL.'==='.EOL2;
		elseif ('h2' == $type) $paraPostfix = EOL.'---'.EOL2;
		elseif ('h3' == $type) $paraPostfix = EOL.'___'.EOL2;
		elseif ('h4' == $type) $paraPostfix = EOL.'...'.EOL2;
		elseif ('hr' == $type) return EOL2.'---'.EOL2;

		if ($para->hasAttributes()) foreach ($para->attributes as $attribute)
		{
			$attrName = strtolower($attribute->name);

			if ('class' == $attrName || 'align' == $attrName)
			{
				// To debug:
				// HTML2RHeia::$debug[] = array('p',
				// 	'Ignoring attribute:', $attrName);
			}
			elseif ('style' == $attrName)
			{
				foreach (explode(';', strtolower($attribute->value)) as $style)
				{
					if (preg_match('/[\t ]*([-a-z]+)[\t ]*:[\t ]*(.*)[\t ]*/',
						$style, $styleProp))
					{
						if ('text-align' == $styleProp[1])
						{
							if ('left' == $styleProp[2])
								$paraPrefix = '<|';
							elseif ('right' == $styleProp[2])
								$paraPrefix = '>|';
							elseif ('center' == $styleProp[2])
								$paraPrefix = '||';
							elseif ('justify' == $styleProp[2])
								$paraPrefix = '_|';
							else HTML2RHeia::$errors[] = array(
								'Unknown text-align', $styleProp[2]);
						}
						// To debug:
						else HTML2RHeia::$debug[] = array('p',
							'Unprocessed style property:',
							$styleProp[1].': '.$styleProp[2].';');
					}
					else HTML2RHeia::$errors[] = array(
						'Unknown style property', $style);
				}
			}
			// To debug:
			else
			{
				if ($attribute instanceof DOMAttr)
					HTML2RHeia::$debug[] = array('p',
						'Unprocessed para attribute:',
						$attribute->name.'="'.$attribute->value.'"');
				else
					HTML2RHeia::$debug[] = array('pre',
						'Unprocessed para attribute:',
						print_r($attribute, true));
			}
		}

		if ($para->hasChildNodes()) foreach ($para->childNodes as $paraNode)
		{
			if ($paraNode instanceof DOMText)
			{
				$paraContents .= HTML2Rheia::filterText($paraNode->textContent);

				// To debug:
				// HTML2RHeia::$debug[] = array('pre',
				// 	'Para text:', print_r($paraNode, true));
			}
			elseif ($paraNode instanceof DOMElement)
			{
				$tagName = strtolower($paraNode->tagName);

				if ('b' == $tagName || 'i' == $tagName || 'u' == $tagName ||
					's' == $tagName || 'strong' == $tagName || 'em' == $tagName ||
					'dfn' == $tagName || 'span' == $tagName)
					$paraContents .= HTML2RHeia::processText($paraNode, $tagName);
				else
					// To debug:
					HTML2RHeia::$debug[] = array('pre',
						'Unprocessed para subnode:', print_r($paraNode, true));
			}
			// To debug:
			else
				HTML2RHeia::$debug[] = array('pre',
					'Para object:', print_r($paraNode, true));
		}

		return $paraPrefix.$paraContents.$paraPostfix;
	}


	public static function processText($text, $type)
	{
		$textContents = '';
		$textPrefix = '';
		$textPostfix = '';

		if ('b' == $type) $textPrefix = $textPostfix = '*';
		elseif ('i' == $type) $textPrefix = $textPostfix = '/';
		elseif ('u' == $type) $textPrefix = $textPostfix = '_';
		elseif ('s' == $type) $textPrefix = $textPostfix = '-';
		elseif ('strong' == $type) $textPrefix = $textPostfix = '~';
		elseif ('em' == $type) $textPrefix = $textPostfix = '|';
		elseif ('dfn' == $type) $textPrefix = $textPostfix = '≈';
		// elseif ('span' == $type) $textPrefix = $textPostfix = '‖';
		// else $textPrefix = $textPostfix = '?';

		if ($text->hasAttributes()) foreach ($text->attributes as $attribute)
		{
			$attrName = strtolower($attribute->name);

			if ('class' == $attrName)// || 'align' == $attrName)
			{
				// To debug:
				// HTML2RHeia::$debug[] = array('p',
				// 	'Ignoring attribute:', $attrName);
			}
			elseif ('style' == $attrName)
			{
				foreach (explode(';', strtolower($attribute->value)) as $style)
				{
					if (preg_match('/[\t ]*([-a-z]+)[\t ]*:[\t ]*(.*)[\t ]*/',
						$style, $styleProp))
					{
						if ('font-size' == $styleProp[1] ||
							'line-height' == $styleProp[1])
						{
							// To debug:
							// HTML2RHeia::$debug[] = array('p',
							// 	'Ignoring style property:',
							// 	$styleProp[1].': '.$styleProp[2].';');
						}
						elseif ('display' == $styleProp[1])
						{
							// To debug:
							// try
							// {
							// 	HTML2RHeia::$debug[] = array('p',
							// 		'Hiding the content:', $text->textContent);
							// }
							// catch (Exception $e)
							// {
							// 	HTML2RHeia::$debug[] = array('pre',
							// 		'Hiding the content:', print_r($text, true));
							// }

							// Don’t continue processing the hidden objects…
							if ('none' == $styleProp[2]) return '';
						}
						// To debug:
						else HTML2RHeia::$debug[] = array('p',
							'Unprocessed style property:',
							$styleProp[1].': '.$styleProp[2].';');
					}
					else HTML2RHeia::$errors[] = array(
						'Unknown style property', $style);
				}
			}
			// To debug:
			else
			{
				if ($attribute instanceof DOMAttr)
					HTML2RHeia::$debug[] = array('p',
						'Unprocessed text attribute:',
						$attribute->name.'="'.$attribute->value.'"');
				else
					HTML2RHeia::$debug[] = array('pre',
						'Unprocessed text attribute:',
						print_r($attribute, true));
			}
		}

		if ($text->hasChildNodes()) foreach ($text->childNodes as $textNode)
		{
			if ($textNode instanceof DOMText)
			{
				$textContents .= HTML2Rheia::filterText($textNode->textContent);

				// To debug:
				// HTML2RHeia::$debug[] = array('pre',
				// 	'Text text:', print_r($textNode, true));
			}
			elseif ($textNode instanceof DOMElement)
			{
				$tagName = strtolower($textNode->tagName);

				if ('b' == $tagName || 'i' == $tagName || 'u' == $tagName ||
					's' == $tagName || 'strong' == $tagName || 'em' == $tagName ||
					'dfn' == $tagName || 'span' == $tagName)
					$textContents .= HTML2RHeia::processText($textNode, $tagName);
				else
					// To debug:
					HTML2RHeia::$debug[] = array('pre',
						'Unprocessed text subnode:', print_r($textNode, true));
			}
			// To debug:
			else
				HTML2RHeia::$debug[] = array('pre',
					'Text object:', print_r($textNode, true));
		}

		return $textPrefix.$textContents.$textPostfix;
	}


	public static function filterText($text)
	{
		return preg_replace('/[\r\n]+/', EOL, $text);
	}


	public static function clearErrorsAndDebug()
	{
		HTML2RHeia::$errors = array();
		HTML2RHeia::$debug = array();
	}


	public static function dumpErrors($showNoError = false)
	{
		if (0 == count(HTML2RHeia::$errors))
		{ if ($showNoError) echo '<p>No errors.</p>'; }
		else
		{
			echo '<h1 class="error">Errors:</h1>';
			foreach (HTML2RHeia::$errors as $error)
				echo '<p class="error"><b>'.$error[0].':</b> '.$error[1].'</p>'.EOL;
		}
	}


	public static function dumpDebug($showNoDebug = false)
	{
		if (0 == count(HTML2RHeia::$debug))
		{ if ($showNoDebug) echo '<p>No debug info.</p>'; }
		else
		{
			foreach (HTML2RHeia::$debug as $debug)
			{
				if ('pre' == $debug[0])
					echo '<'.$debug[0].'>'.(null === $debug[1] ? '' :
						'<b>'.$debug[1].'</b><br />').$debug[2].'</'.
						$debug[0].'>'.EOL;
				else
					echo '<'.$debug[0].'>'.(null === $debug[1] ? '' :
						'<b>'.$debug[1].'</b> ').$debug[2].'</'.
						$debug[0].'>'.EOL;
			}
		}
	}
}


// To test:
// echo '<pre>'.HTML2RHeia::processFile('«source».htm').'</pre>';
// HTML2RHeia::dumpErrors(true);
// HTML2RHeia::dumpDebug(true);


// function asciiMandelbrot()
// {
// 	$i = 1.125;
// 
// 	while ($i >= -1.225)
// 	{
// 		for ($j = -2; $j <= 1; $j += 3 / 79.0)
// 		{
// 			$k = $l = 0;
// 
// 			for ($n = 127; $k * $k + $l * $l < 4.0 && --$n > 32;)
// 			{
// 				$m = $k;
// 				$k = $k * $k -$l * $l + $j;
// 				$l = 2 * $m * $l + $i;
// 			}
// 
// 			echo chr($n);
// 		}
// 
// 		echo '<br'.EOL.'/>';
// 		$i -= 9 / 88.0;
// 	}
// }
// 
// echo '<pre>'; asciiMandelbrot(); echo '</pre>';

?>