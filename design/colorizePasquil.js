
var pasquilKeywords1 = ['begin', 'end',
'program', 'unit', 'class',

'const', 'constructor', 'field', 'function', 'generic', 'label', 'method', 'on', 'procedure', 'property', 'type', 'var', 'uses', 'default',

'implementation', 'interface', 'implements',

'case', 'do', 'downto', 'else', 'except', 'finally', 'for', 'if', 'of', 'otherwise', 'repeat', 'then', 'to', 'try', 'until', 'while', 'with', 'break', 'continue', 'exit', 'raise',

'absolute', 'abstract', 'alias', 'deprecated', 'destructor', 'experimental', 'forward', 'override', 'private', 'protected', 'public', 'static', 'varargs'
];

var pasquilKeywords2 = [
'and', 'as', 'div', 'exclude', 'in', 'include', 'is', 'mod', 'not', 'or', 'pow', 'shl', 'shr', 'xor', 'new'
];

var pasquilKeywords3 = ['false', 'nil', 'parent', 'result', 'self', 'true'];

var pasquilTypes = [
'byte', 'word', 'integer', 'long', 'real', 'double', 'boolean', 'char', 'shortint', 'smallint', 'longint', 'int64', 'single',

'string', 'array', 'file', 'list', 'object', 'of', 'record', 'set'
];

function colorizePasquil(theHTML)
{
	if (typeof theHTML == 'undefined')
	{
		var preTags = document.getElementsByTagName('pre');
		for (var i = 0; i < preTags.length; ++i)
		{
			if (preTags[i].className != 'pasquilCode') continue;

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

			preTags[i].innerHTML = colorizePasquil(preTags[i].innerHTML);
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
				if ('∙' == ch) // debug char
				{
					newHTML += nch.charCodeAt(0);
				}
				else if (' ' == ch || ' ' == ch || '	' == ch ||
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
				else if ('(' == ch && '*' == nch)
				{
					mode = 6; // comment1
					token = ch;

					if ((j + 2) < oldHTML.length)
					{
						nch = oldHTML.charAt(j + 2);
						if ('$' == nch) mode = 7; // comment2
						// This may change:
						else if ('#' == nch) mode = 8; // comment3
					}
				}
				else if ('{' == ch)
				{
					mode = 9; // comment1
					token = ch;

					if ('$' == nch) mode = 10;
					// This may change:
					else if ('#' == nch) mode = 11;
				}
				else if ((/*' ' < ch && */'A' > ch) ||
					('Z' < ch && 'a' > ch) ||
					('z' < ch && '~' > ch))
				{
					mode = 12; // symbols
					token = ch;
				}
				else if ('#' == ch)
				{
					mode = 13; // #character
					token = ch;
				}
				else
				{
					mode = 14; // any other token (including keywords)
					token = ch;
				}
				break;

			case 1: // number
				if ('0' <= ch && '9' >= ch)
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
				if (';' == ch) mode = 12; // symbols
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
				else if ('*' == ch && ')' == nch)
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

			case 9: case 10: case 11: // comments
				if ('	' == ch) token += '    ';
				else if ('}' == ch)
				{
					newHTML += '<span class="comment';
					if (10 == mode) newHTML += '2';
					else if (11 == mode) newHTML += '3';
					else newHTML += '1';
					newHTML += '">' + token + ch + '</span>';

					mode = 0; // ++j; // default
				}
				else token += ch;
				break;

			case 12: // symbols
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

			case 13: // #character
				if ('0' <= ch && '9' >= ch)
				{
					token += ch;
				}
				else
				{
					mode = 0; --j;
					newHTML += '<span class="character">' + token + '</span>';
				}
				break;

			case 14: // any other token (including keywords)
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
					var tokenLC = token.toLowerCase();

					mode = 0; // temporal meaning change to “neutral”
					for (var x in pasquilKeywords1)
						if (tokenLC == pasquilKeywords1[x])
						{
							mode = 1; // tmp: “keyword1 found”
							className = 'keyword1';
							break;
						}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in pasquilKeywords2)
							if (tokenLC == pasquilKeywords2[x])
							{
								mode = 2; // tmp: “keyword2 found”
								className = 'keyword2';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in pasquilKeywords3)
							if (tokenLC == pasquilKeywords3[x])
							{
								mode = 3; // tmp: “keyword3 found”
								className = 'keyword3';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in pasquilTypes)
							if (tokenLC == pasquilTypes[x])
							{
								mode = 4; // tmp: “type found”
								className = 'type';
								break;
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

		return '<span class="pasBlock">' + newHTML + '</span>';
	}
}