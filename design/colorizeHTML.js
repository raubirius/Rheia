
/*var HTMLKeywords1 = ['a', 'abbr', 'acronym', 'address', 'applet', 'area', 'article', 'aside', 'audio', 'b', 'base', 'basefont', 'bdi', 'bdo', 'big', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'font', 'footer', 'form', 'frame', 'frameset', 'h1',  to 'h6', 'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'menu', 'menuitem', 'meta', 'meter', 'nav', 'noframes', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'q', 'rp', 'rt', 'ruby', 's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'style', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 'tt', 'u', 'ul', 'var', 'video', 'wbr'];

var HTMLKeywords2 = ['accept', 'accesskey', 'align', 'alink', 'alt', 'any', 'archive', 'async', 'author', 'autocomplete', 'autofocus', 'autoplay', 'background', 'bgcolor', 'bookmark', 'border', 'circle', 'cite', 'class', 'classid', 'codebase', 'codetype', 'cols', 'command', 'contenteditable', 'contextmenu', 'controls', 'coords', 'crossorigin', 'data', 'date', 'datetime', 'declare', 'defer', 'disabled', 'download', 'draggable', 'for', 'form', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget', 'framename', 'height', 'help', 'hidden', 'href', 'hreflang', 'hspace', 'challenge', 'char', 'charoff', 'charset', 'checked', 'icon', 'id', 'ismap', 'item', 'itemprop', 'keytype', 'label', 'license', 'link', 'list', 'longdesc', 'loop', 'manifest', 'max', 'maxlength', 'media', 'metadata', 'min', 'multiple', 'muted', 'name', 'next', 'nofollow', 'nohref', 'none', 'noreferrer', 'open', 'pattern', 'placeholder', 'poly', 'poster', 'prefetch', 'preload', 'prev', 'radiogroup', 'readonly', 'rect', 'rel', 'required', 'rev', 'rows', 'search', 'shape', 'size', 'sizes', 'span', 'spellcheck', 'src', 'standby', 'step', 'style', 'subject', 'tabindex', 'tag', 'target', 'text', 'title', 'type', 'usemap', 'valign', 'value', 'valuetype', 'vlink', 'vspace', 'width', 'wrap'];

var HTMLKeywords3 = ['auto', 'bottom', 'center', 'false', 'hidden', 'left', 'middle', 'right', 'top', 'true', '_blank', '_parent', '_self', '_top', 'ltr', 'rtl'];*/

function colorizeHTML(theHTML)
{
	if (typeof theHTML == 'undefined')
	{
		var preTags = document.getElementsByTagName('pre');
		for (var i = 0; i < preTags.length; ++i)
		{
			if (preTags[i].className != 'HTMLCode') continue;
			preTags[i].innerHTML = colorizeHTML(preTags[i].innerHTML);
		}
	}
	else
	{
		var oldHTML = theHTML + '\r';
		var newHTML = '';
		var mode = 0;
		var token = '';
		var isTagName = true;
		var startTag = false;
		var isValue = false;
		var styleContent = false;
		var scriptContent = false;

		for (var j = 0; j < oldHTML.length; ++j)
		{
			var ch = oldHTML.charAt(j); var nch = '';

			if ((j + 1) < oldHTML.length)
				nch = oldHTML.charAt(j + 1);

			switch (mode)
			{
			case 0: // neutral mode
				if (' ' == ch || '	' == ch || '\r' == ch || '\n' == ch)
				{
					if ('\n' == ch) newHTML += '<br />';
					else if (' ' == ch) newHTML += ' ';
					else if ('	' == ch) newHTML += '    ';
				}
				else if ('<' == ch)
				{
					mode = 1; // real tag
					token = ch;
				}
				else if ('&' == ch)
				{
					mode = 2; // real entity
					token = ch;
				}
				else newHTML += ch;
				break;

			case 1: // real tag (copies it to source code)
				if ('>' == ch)
				{
					newHTML += token + ch;
					mode = 0; // neutral
				}
				else token += ch;
				break;

			case 2: // real entity – searches also for syntax of <
				token += ch;

				if (';' == ch)
				{
					if ('&amp;' == token)
					{
						mode = 3; // entity
					}
					else if ('&lt;' == token)
					{
						if ('!' == nch)
						{
							if ((j + 3) < oldHTML.length &&
								'-' == oldHTML.charAt(j + 2) &&
								'-' == oldHTML.charAt(j + 3))
							{
								mode = 5; // comment
								token += nch + '--';
								j += 3;
							}
							else
							{
								mode = 6; // definition
								token += nch;
								++j;
							}
						}
						else
						{
							startTag = '/' != nch;
							isTagName = true;
							mode = 4; // tag
							newHTML += '<span class="symbol">' +
								token + '</span>';
							token = '';
						}
					}
					else
					{
						mode = 0; // neutral
						newHTML += token;
					}
				}

				break;

			case 3: // entity
				token += ch;
				if (';' == ch)
				{
					mode = 0; // neutral
					newHTML += '<span class="entity">' + token + '</span>';
				}
				break;

			case 4: // tag
				if ('>' == ch)
				{
					mode = 0; // neutral
					newHTML += '<span class="symbol">' +
						token + '&gt;</span>';
				}
				else if ('"' == ch || '\'' == ch)
				{
					mode = '"' == ch ? 7 : 8; // string 1, 2
					newHTML += '<span class="symbol">' + token + '</span>';
					token = ch;
				}
				else if ('&' == ch &&
					(j + 3) < oldHTML.length &&
					'g' == oldHTML.charAt(j + 1) &&
					't' == oldHTML.charAt(j + 2) &&
					';' == oldHTML.charAt(j + 3))
				{
					mode = 0; // neutral
					newHTML += '<span class="symbol">' +
						token + '&gt;</span>';
					j += 3;

					if (styleContent)
					{
						console.log('style');
						newHTML += '</span>';
						var indexOf = oldHTML.indexOf('&lt;/style&gt;', j + 1);
						if (-1 != indexOf)
						{
							newHTML += colorizeCSS(oldHTML.
								substring(j + 1, indexOf));
							j = indexOf - 1;
						}
						else
						{
							newHTML += colorizeCSS(
								oldHTML.substring(j + 1));
							j = oldHTML.length;
						}
						newHTML += '<span class="HTMLBlock">';
						styleContent = scriptContent = false;
					}
					else if (scriptContent)
					{
						console.log('script');
						newHTML += '</span>';
						var indexOf = oldHTML.indexOf('&lt;/script&gt;', j + 1);
						if (-1 != indexOf)
						{
							newHTML += colorizeJava(oldHTML.
								substring(j + 1, indexOf), true);
							j = indexOf - 1;
						}
						else
						{
							newHTML += colorizeJava(
								oldHTML.substring(j + 1), true);
							j = oldHTML.length;
						}
						newHTML += '<span class="HTMLBlock">';
						styleContent = scriptContent = false;
					}
				}
				else if (' ' == ch || '	' == ch || '\r' == ch || '\n' == ch)
				{
					if ('\n' == ch) token += '<br />';
					else if (' ' == ch) token += ' ';
					else if ('	' == ch) token += '    ';
				}
				else if (('a' <= ch && 'z' >= ch) || ('A' <= ch && 'Z' >= ch))
				{
					if ('' != token)
						newHTML += '<span class="symbol">' + token + '</span>';
					token = ch;
					if (isValue)
					{
						mode = 12; // attribute value
						isValue = false;
					}
					else if (isTagName)
					{
						mode = 9; // tag name
						isTagName = false;
					}
					else mode = 10; // attribute name
				}
				else if ('0' <= ch && '9' >= ch)
				{
					if ('' != token)
						newHTML += '<span class="symbol">' + token + '</span>';
					token = ch;
					mode = 11; // number
					isTagName = false;
				}
				else if ('=' == ch)
				{
					isValue = true;
					token += ch;
				}
				else token += ch;
				break;

			case 5: // comment
				token += ch;
				if ('-' == ch && '-' == nch)
				{
					if ((j + 2) < oldHTML.length)
					{
						if ('>' == oldHTML.charAt(j + 2))
						{
							mode = 0; // neutral
							newHTML += '<span class="comment">' +
								token + '-&gt;</span>';
							j += 2;
						}
						else if ('&' == oldHTML.charAt(j + 2) &&
							(j + 5) < oldHTML.length &&
							'g' == oldHTML.charAt(j + 3) &&
							't' == oldHTML.charAt(j + 4) &&
							';' == oldHTML.charAt(j + 5))
						{
							mode = 0; // neutral
							newHTML += '<span class="comment">' +
								token + '-&gt;</span>';
							j += 5;
						}
					}
				}
				break;

			case 6: // definition
				token += ch;
				if ('>' == nch)
				{
					mode = 0; // neutral
					newHTML += '<span class="definition">' +
						token + '&gt;</span>';
					++j;
				}
				else if ('&' == nch &&
					(j + 4) < oldHTML.length &&
					'g' == oldHTML.charAt(j + 2) &&
					't' == oldHTML.charAt(j + 3) &&
					';' == oldHTML.charAt(j + 4))
				{
					mode = 0; // neutral
					newHTML += '<span class="definition">' +
						token + '&gt;</span>';
					j += 4;
				}
				break;

			case 7: // string 1
				token += ch;
				if ('"' == ch)
				{
					mode = 4; // tag
					newHTML += '<span class="string">' + token + '</span>';
					token = '';
					isValue = false;
				}
				break;

			case 8: // string 2
				token += ch;
				if ('\'' == ch)
				{
					mode = 4; // tag
					newHTML += '<span class="string">' + token + '</span>';
					token = '';
					isValue = false;
				}
				break;

			case 9: // tag name
				if (('a' > ch || 'z' < ch) && ('A' > ch || 'Z' < ch))
				{
					mode = 4; // tag
					if ('style' == token && startTag) styleContent = true;
					else if ('script' == token && startTag)
						scriptContent = true;
					newHTML += '<span class="tagName">' + token + '</span>';
					token = ''; --j;
				}
				else token += ch;
				break;

			case 10: // attribute name
				if (('a' > ch || 'z' < ch) && ('A' > ch || 'Z' < ch))
				{
					mode = 4; // tag
					newHTML += '<span class="attributeName">' +
						token + '</span>';
					if (scriptContent && startTag && 'src' == token)
						scriptContent = false;
					token = ''; --j;
				}
				else token += ch;
				break;

			case 11: // number
				if ('0' > ch || '9' < ch)
				{
					mode = 4; // tag
					newHTML += '<span class="number">' + token + '</span>';
					token = ''; --j;
					isValue = false;
				}
				else token += ch;
				break;

			case 12: // attribute value
				if (('a' > ch || 'z' < ch) && ('A' > ch || 'Z' < ch))
				{
					mode = 4; // tag
					newHTML += '<span class="attributeValue">' +
						token + '</span>';
					token = ''; --j;
					isValue = false;
				}
				else token += ch;
				break;

			default:
				mode = 0; // neutral
				--j; newHTML += token;
			}
		}

		return '<span class="HTMLBlock">' + newHTML + '</span>';
	}
}