
var PHPKeywords1 = ['__halt_compiler', 'abstract', 'array', 'as', 'break',
	'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue',
	'declare', 'default', 'do', 'else', 'elseif', 'enddeclare', 'endfor',
	'endforeach', 'endif', 'endswitch', 'endwhile', 'exit', 'extends',
	'final', 'finally', 'fn', 'for', 'foreach', 'function', 'global',
	'goto', 'if', 'implements', 'instanceof', 'insteadof', 'interface',
	'list', 'match', 'namespace', 'new', 'private', 'protected', 'public',
	'return', 'static', 'switch', 'throw', 'trait', 'try', 'use', 'var',
	'while', 'yield', 'from'];

var PHPKeywords2 = ['die', 'echo', 'empty', 'eval', 'isset', 'print', 'unset',
	// 'count', 'sizeof'
	];

var PHPKeywords3 = ['false', 'null', 'parent', 'self', 'true'];

var PHPPrimitives = ['int', 'float', 'bool', 'string', 'true', 'false',
	'null', 'void', 'iterable', 'object',
	// reserved:
	'resource', 'mixed', 'numeric'];

var PHPSymbols = ['and', 'or', 'xor'];

var PHPDirectives = ['include', 'include_once', 'require', 'require_once'];

var PHPConstants = ['__class__', '__dir__', '__file__', '__function__',
	'__line__', '__method__', '__namespace__', '__trait__',
	'default_include_path', 'e_all', 'e_compile_error', 'e_compile_warning',
	'e_core_error', 'e_core_warning', 'e_deprecated', 'e_error', 'e_notice',
	'e_parse', 'e_recoverable_error', 'e_strict', 'e_user_deprecated',
	'e_user_error', 'e_user_notice', 'e_user_warning', 'e_warning',
	'php_binary', 'php_bindir', 'php_config_file_path',
	'php_config_file_scan_dir', 'php_datadir', 'php_debug', 'php_eol',
	'php_extension_dir', 'php_extra_version', 'php_fd_setsize',
	'php_float_dig', 'php_float_epsilon', 'php_float_max', 'php_float_min',
	'php_int_max', 'php_int_min', 'php_int_size', 'php_libdir',
	'php_localstatedir', 'php_major_version', 'php_mandir', 'php_maxpathlen',
	'php_minor_version', 'php_os', 'php_os_family', 'php_prefix',
	'php_release_version', 'php_sapi', 'php_shlib_suffix', 'php_sysconfdir',
	'php_version', 'php_version_id', 'php_windows_event_ctrl_break',
	'php_windows_event_ctrl_c', 'php_zts'];


function cutAndColorizePHP(theHTML)
{
	let theHTMLbefore, thePHP = '', theHTMLafter = '';

	let indexOf1 = theHTML.indexOf('&lt;?');
	if (-1 != indexOf1)
	{
		let indexOf2 = theHTML.indexOf('?&gt;', indexOf1);
		if (-1 != indexOf2)
		{
			// console.error('OK');

			theHTMLbefore = theHTML.substring(0, indexOf1);
			thePHP = theHTML.substring(indexOf1 + 5, indexOf2);
			theHTMLafter = theHTML.substring(indexOf2 + 5);

			// console.log('\ncolorizeHTML1:\n' + theHTMLbefore);
			// console.log('\ncolorizePHP1:\n' + thePHP);
			// console.log('\ncutAndColorizePHP:\n' + theHTMLafter);

			return colorizeHTML(theHTMLbefore) + '<span class="PHPBlock">' +
				'<span class="phptag">&lt;?</span></span>' +
				colorizePHP(thePHP) + '<span class="PHPBlock">' +
				'<span class="phptag">?&gt;</span></span>' +
				cutAndColorizePHP(theHTMLafter);
		}
		else
		{
			console.error('ERROR');

			theHTMLbefore = theHTML.substring(0, indexOf1);
			thePHP = theHTML.substring(indexOf2 + 5);

			// console.log('\ncolorizeHTML2:\n' + theHTMLbefore);
			// console.log('\ncolorizePHP2:\n' + thePHP);

			return
				colorizeHTML(theHTMLbefore) + '<span class="PHPBlock">' +
				'<span class="error">&lt;?</span></span>' +
				colorizePHP(thePHP);
		}
	}
	else
	{
		// console.log('\ncolorizeHTML3:\n' + theHTML);
		return colorizeHTML(theHTML);
	}
}

function colorizePHP(theHTML)
{
	if (typeof theHTML == 'undefined')
	{
		var preTags = document.getElementsByTagName('pre');
		for (var i = 0; i < preTags.length; ++i)
		{
			if (preTags[i].className != 'PHPCode') continue;

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

			preTags[i].innerHTML = cutAndColorizePHP(preTags[i].innerHTML);
		}
	}
	else
	{
		var oldHTML = theHTML + '\r';
		var newHTML = '';
		var mode = 0; // default
		var token = '';
		var isVariable = false;
		var j0 = 0;

		if (oldHTML.startsWith('php'))
		{
			j0 = 3;
			newHTML += '<span class="phptag">php</span>';
		}

		for (var j = j0; j < oldHTML.length; ++j)
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
					mode = 2; // string1
					token = ch;
				}
				else if ('\'' == ch)
				{
					mode = 3; // string2
					token = ch;
				}
				else if ('&' == ch)
				{
					mode = 4; // HTML entity as symbol
					token = ch;
				}
				else if (('#' == ch) || ('/' == ch && '/' == nch))
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
				else if ('$' == ch)
				{
					isVariable = true;
					newHTML += '<span class="variable">$</span>';
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

			case 2: case 3: // string1 and string2
				if ((2 == mode && '"' == ch) ||
					(3 == mode && '\'' == ch))
				{
					newHTML += '<span class="' + (2 == mode ? 'string1' :
						'string2') + '">' + token + ch + '</span>';
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
				else if ('"' == ch || '\'' == ch || '_' == ch || '$' == ch)
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
					var tokenLC = token.toLowerCase();
					var className = '';

					mode = 0; // temporal meaning change to “neutral”
					for (var x in PHPKeywords1)
						if (tokenLC == PHPKeywords1[x])
						{
							mode = 1; // tmp: “keyword1 found”
							className = 'keyword1';
							break;
						}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPKeywords2)
							if (tokenLC == PHPKeywords2[x])
							{
								mode = 2; // tmp: “keyword2 found”
								className = 'keyword2';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPKeywords3)
							if (tokenLC == PHPKeywords3[x])
							{
								mode = 3; // tmp: “keyword3 found”
								className = 'keyword3';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPSymbols)
							if (tokenLC == PHPSymbols[x])
							{
								mode = 4; // tmp: “symbol found”
								className = 'symbol';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPPrimitives)
							if (tokenLC == PHPPrimitives[x])
							{
								mode = 5; // tmp: “primitive found”
								className = 'primitive';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPDirectives)
							if (tokenLC == PHPDirectives[x])
							{
								mode = 6; // tmp: “directive found”
								className = 'directive';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in PHPConstants)
							if (tokenLC == PHPConstants[x])
							{
								mode = 7; // tmp: “constant found”
								className = 'constant';
								break;
							}

					if (0 != mode) // some keyword found
						newHTML += '<span class="' + className +
							'">' + token + '</span>';
					else if (isVariable)
						newHTML += '<span class="variable">' +
							token + '</span>';
					else if ('(' == ch)
						newHTML += '<span class="function">' +
							token + '</span>';
					else
						newHTML += token;

					isVariable = false;
					mode = 0; --j; // back to default
						// (temporal changes dismissed)
				}
				break;

			default:
				mode = 0; --j; // default
				newHTML += token;
			}
		}

		return '<span class="PHPBlock">' + newHTML + '</span>';
	}
}