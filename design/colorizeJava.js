
var jscriptKeywords1 = ['await', 'break', 'case', 'catch', 'class', 'const',
	'continue', 'debugger', 'default', 'delete', 'do', 'else', 'enum',
	'extends', 'finally', 'for', 'function', 'if', 'implements', 'in',
	'instanceof', 'interface', 'let', 'new', 'private', 'protected', 'public',
	'return', 'static', 'switch', 'throw', 'try', 'typeof', 'var', 'while',
	'with', 'yield'];

var jscriptKeywords2 = ['export', 'import', 'package', 'hasOwnProperty',
	'isPrototypeOf', 'length', 'name', 'prototype', 'toString', 'valueOf'];

var jscriptKeywords3 = ['eval', 'false', 'null', 'super', 'this', 'true',
	'Infinity', 'isFinite', 'isNaN', 'NaN', 'undefined'];

var jscriptPrimitive = ['arguments', 'void', 'Array', 'Date', 'Math',
	'Number', 'Object', 'String'];

var jscriptError = ['abstract', 'boolean', 'byte', 'char', 'double',
	'final', 'float', 'goto', 'int', 'long', 'native', 'short',
	'synchronized', 'throws', 'transient', 'volatile'];

var jscriptReserved = ['getClass', 'java', 'JavaArray', 'javaClass',
	'JavaObject', 'JavaPackage', 'alert', 'all', 'anchor', 'anchors',
	'area', 'assign', 'blur', 'button', 'checkbox', 'clearInterval',
	'clearTimeout', 'clientInformation', 'close', 'closed', 'confirm',
	'constructor', 'crypto', 'decodeURI', 'decodeURIComponent',
	'defaultStatus', 'document', 'element', 'elements', 'embed', 'embeds',
	'encodeURI', 'encodeURIComponent', 'escape', 'event', 'fileUpload',
	'focus', 'form', 'forms', 'frame', 'innerHeight', 'innerWidth', 'layer',
	'layers', 'link', 'location', 'mimeTypes', 'navigate', 'navigator',
	'frames', 'frameRate', 'hidden', 'history', 'image', 'images',
	'offscreenBuffering', 'open', 'opener', 'option', 'outerHeight',
	'outerWidth', 'packages', 'pageXOffset', 'pageYOffset', 'parent',
	'parseFloat', 'parseInt', 'password', 'pkcs11', 'plugin', 'prompt',
	'propertyIsEnum', 'radio', 'reset', 'screenX', 'screenY', 'scroll',
	'secure', 'select', 'self', 'setInterval', 'setTimeout', 'status',
	'submit', 'taint', 'text', 'textarea', 'top', 'unescape', 'untaint',
	'window'];


var javaKeywords1 = ['abstract', 'assert', 'break', 'case', 'catch',
	'continue', 'default', 'do', 'else', 'final', 'finally', 'for', 'if',
	'instanceof', 'new', 'private', 'protected', 'public', 'return', 'static',
	'switch', 'synchronized', 'throw', 'throws', 'transient', 'try',
	'volatile', 'while'];

var javaKeywords2 = ['class', 'enum', 'extends', 'implements', 'import',
	'interface', 'package'];

var javaKeywords3 = ['super', 'this', 'false', 'null', 'true'];

var javaPrimitive = ['boolean', 'byte', 'char', 'double', 'float', 'int',
	'long', 'short', 'void'];


function colorizeJava(theHTML, javaScript = false)
{
	if (typeof theHTML == 'undefined')
	{
		var preTags = document.getElementsByTagName('pre');
		for (var i = 0; i < preTags.length; ++i)
		{
			if (preTags[i].className != 'javaCode') continue;

			var prevEl = preTags[i].previousElementSibling;
			if (prevEl)
			{
				if ('~' == prevEl.innerHTML)
				{
					// prevEl.innerHTML = '';
					prevEl.remove();
				}
				else
				{
					if ('' == prevEl.className)
						prevEl.className = 'codeTitle';
					else
						prevEl.className += ' codeTitle';
				}
			}

			preTags[i].innerHTML = colorizeJava(preTags[i].innerHTML,
				javaScript);
		}
	}
	else
	{
		var oldHTML = theHTML + '\r';
		var newHTML = '';
		var mode = 0; // default
		var token = '';

		for (var j = 0; j < oldHTML.length; ++j)
		{
			var ch = oldHTML.charAt(j); var nch = '';

			if ((j + 1) < oldHTML.length)
				nch = oldHTML.charAt(j + 1);

			switch (mode)
			{
			case 0: // default
				if (' ' == ch || ' ' == ch || '	' == ch ||
					'\r' == ch || '\n' == ch)
				{
					if ('\n' == ch) newHTML += '<br />';
					else if (' ' == ch || ' ' == ch) newHTML += ' ';
					else if ('	' == ch) newHTML += '    ';
				}
				else if ('0' <= ch && '9' >= ch)
				{
					mode = 1; // number
					token = ch;
				}
				else if ('"' == ch)
				{
					mode = 2; // string
					token = ch;
				}
				else if ('\'' == ch)
				{
					mode = 3; // character
					token = ch;
				}
				else if ('&' == ch)
				{
					mode = 4; // HTML entity as symbol
					token = ch;
				}
				else if ('/' == ch && '/' == nch)
				{
					mode = 5; // comment1
					token = ch;
				}
				else if ('/' == ch && '*' == nch)
				{
					mode = 6; // comment1
					token = ch;

					if ((j + 2) < oldHTML.length)
					{
						nch = oldHTML.charAt(j + 2);
						if ('*' == nch) mode = 7; // comment2
						else if ('#' == nch) mode = 8; // comment3
					}
				}
				else if ((/*' ' < ch && */'A' > ch) ||
					('Z' < ch && 'a' > ch) ||
					('z' < ch && '~' > ch))
				{
					mode = 9; // symbols
					token = ch;
				}
				else
				{
					mode = 10; // any other token (including keywords)
					token = ch;
				}
				break;

			case 1: // number
				if (('0' <= ch && '9' >= ch) || ('a' <= ch && 'f' >= ch) ||
					('A' <= ch && 'F' >= ch) || 'x' == ch || 'X' == ch ||
					'_' == ch || '.' == ch)
				{
					token += ch;
				}
				else
				{
					mode = 0; --j; // default
					newHTML += '<span class="number">' + token + '</span>';
				}
				break;

			case 2: case 3: // string and character
				if ((2 == mode && '"' == ch) ||
					(3 == mode && '\'' == ch))
				{
					newHTML += '<span class="' + (2 == mode ? 'string' :
						'character') + '">' + token + ch + '</span>';
					mode = 0; // default
				}
				else if ('\\' == ch)
				{
					token += ch + nch;
					++j;
				}
				else token += ch;
				break;

			case 4: // HTML entity as symbol
				if (';' == ch) mode = 9; // symbols
				token += ch;

				if ('&nbsp;' == token)
				{
					newHTML += ' ';
					mode = 0;
				}
				break;

			case 5: // comment1
				if ('\r' == ch || '\n' == ch)
				{
					mode = 0; --j; // default
					newHTML += '<span class="comment1">' + token + '</span>';
				}
				else token += ch;
				break;

			case 6: case 7: case 8: // comments
				if ('	' == ch) token += '    ';
				else if ('*' == ch && '/' == nch)
				{
					newHTML += '<span class="comment';
					if (7 == mode) newHTML += '2'; // comment2
					else if (8 == mode) newHTML += '3'; // comment3
					else newHTML += '1'; // comment1
					newHTML += '">' + token + ch + nch + '</span>';

					mode = 0; ++j; // default
				}
				else token += ch;
				break;

			case 9: // symbols
				if ('&' == ch)
				{
					mode = 4;
					token += ch;
				}
				else if ('"' == ch || '\'' == ch || '_' == ch)
				{
					mode = 0; --j; // default
					newHTML += '<span class="symbol">' + token + '</span>';
				}
				else if ((' ' < ch && '0' > ch) ||
					('9' < ch && 'A' > ch) ||
					('Z' < ch && 'a' > ch) ||
					('z' < ch && '~' > ch))
				{
					token += ch;
				}
				else
				{
					mode = 0; --j; // default
					newHTML += '<span class="symbol">' + token + '</span>';
				}
				break;

			case 10: // any other token (including keywords)
				if ('_' == ch || '~' < ch ||
					('0' <= ch && '9' >= ch) ||
					('A' <= ch && 'Z' >= ch) ||
					('a' <= ch && 'z' >= ch))
				{
					token += ch;
				}
				else
				{
					var className = '';

					if (javaScript)
					{
						mode = 0;

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptKeywords1)
								if (token == jscriptKeywords1[x])
								{
									mode = 1; // tmp: “keyword1 found”
									className = 'keyword1';
									break;
								}

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptKeywords2)
								if (token == jscriptKeywords2[x])
								{
									mode = 1; // tmp: “keyword2 found”
									className = 'keyword2';
									break;
								}

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptKeywords3)
								if (token == jscriptKeywords3[x])
								{
									mode = 1; // tmp: “keyword3 found”
									className = 'keyword3';
									break;
								}

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptPrimitive)
								if (token == jscriptPrimitive[x])
								{
									mode = 1; // tmp: “primitive found”
									className = 'primitive';
									break;
								}

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptError)
								if (token == jscriptError[x])
								{
									mode = 1; // tmp: “error found”
									className = 'error';
									break;
								}

						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in jscriptReserved)
								if (token == jscriptReserved[x])
								{
									mode = 1; // tmp: “reserved found”
									className = 'reserved';
									break;
								}
					}
					else
					{
						mode = 'goto' == token ? -1 : 0;
						if (-1 == mode) className = 'error';
	
						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in javaKeywords1)
								if (token == javaKeywords1[x])
								{
									mode = 1; // tmp: “keyword1 found”
									className = 'keyword1';
									break;
								}
	
						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in javaKeywords2)
								if (token == javaKeywords2[x])
								{
									mode = 2; // tmp: “keyword2 found”
									className = 'keyword2';
									break;
								}
	
						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in javaKeywords3)
								if (token == javaKeywords3[x])
								{
									mode = 3; // tmp: “keyword3 found”
									className = 'keyword3';
									break;
								}
	
						if (0 == mode) // temporal meaning change to “neutral”
							for (var x in javaPrimitive)
								if (token == javaPrimitive[x])
								{
									mode = 4; // tmp: “primitive found”
									className = 'primitive';
									break;
								}
					}

					if (0 != mode) // some keyword found
						newHTML += '<span class="' + className +
							'">' + token + '</span>';
					else
						newHTML += token;

					mode = 0; --j; // back to default
						// (temporal changes dismissed)
				}
				break;

			default:
				mode = 0; --j; // default
				newHTML += token;
			}
		}

		if (javaScript)
			return '<span class="javaScriptBlock">' + newHTML + '</span>';
		return '<span class="javaBlock">' + newHTML + '</span>';
	}
}