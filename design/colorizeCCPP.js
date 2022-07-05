
var ccppKeywords1 = [
	// C
	'auto', 'break', 'case', 'const', 'continue', 'default', 'do', 'else', 'for', 'goto', 'if', 'inline', 'register', 'return', 'sizeof', 'static', 'switch', 'volatile', 'while',
	// C++
	'alignas', 'alignof', 'atomic_cancel', 'atomic_commit', 'atomic_noexcept', 'catch', 'co_await', 'co_return', 'co_yield', 'concept', 'constexpr', 'delete', 'explicit', 'friend', 'new', 'private', 'protected', 'public', 'requires', 'synchronized', 'throw', 'try', 'virtual'];

var ccppKeywords2 = [
	// C
	'_Alignas', '_Alignof', '_Atomic', '_Decimal32', '_Decimal64', '_Decimal128', '_Generic', '_Noreturn', '_Static_assert', '_Thread_local',
	// C++
	'__abstract', '__alignof', '__asm', '__assume', '__based', '__box', '__cdecl', '__declspec', '__delegate', '__event', '__except', '__fastcall', '__finally', '__forceinline', '__gc', '__hook', '__identifier', '__if_exists', '__if_not_exists', '__inline', '__int8', '__int16', '__int32', '__int64', '__interface', '__leave', '__m64', '__m128', '__m128d', '__m128i', '__multiple_inheritance', '__nogc', '__noop', '__pin', '__property', '__ptr32', '__ptr64e', '__raise', '__restrict', '__sealed', '__single_inheritancee', '__sptre', '__stdcall', '__super', '__thiscall', '__try_cast', '__unaligned', '__unhook', '__uptr', '__uuidof', '__value', '__vectorcall', '__virtual_inheritance', '__w64', '__wchar_t', 'abstract', 'align', 'allocate', 'allocator', 'appdomain', 'array', 'as_friend', 'char8_t', 'code_seg', 'consteval', 'constinit', 'declaration', 'delegate', 'deprecated', 'directive', 'dllexport', 'dllimport', 'each', 'event', 'finally', 'gcnew', 'generic', 'in', 'initonly', 'interface', 'interior_ptr', 'jitintrinsic', 'literal', 'naked', 'no_sanitize_address', 'noalias', 'noinline', 'noreturn', 'nothrow', 'novtable', 'process', 'property', 'ref', 'safebuffers', 'safecast', 'sealed', 'selectany', 'spectre', 'thread', 'typen8Jll8', 'uuid', 'value'];

var ccppKeywords3 = [
	// Other (C)
	'extern',
	// C++
	'FALSE', 'NULL', 'TRUE', 'false', 'nullptr', 'this', 'true',
	// Other (C++)
	'asm', 'export', 'import', 'module', 'namespace', 'noexcept', 'thread_local', 'using'];

var ccppPrimitive = [
	// C
	'_Bool', '_Complex', '_Imaginary', 'char', 'double', 'enum', 'float', 'int', 'long', 'restrict', 'short', 'signed', 'struct', 'typedef', 'union', 'unsigned', 'void',
	// C++
	'and', 'and_eq', 'bitand', 'bitor', 'bool', 'char16_t', 'char32_t', 'class', 'compl', 'const_cast', 'decltype', 'dynamic_cast', 'mutable', 'not', 'not_eq', 'operator', 'or', 'or_eq', 'reinterpret_cast', 'static_assert', 'static_cast', 'template', 'typeid', 'typename', 'wchar_t', 'xor', 'xor_eq'];

function colorizeCCPP(theHTML)
{
	if (typeof theHTML == 'undefined')
	{
		var preTags = document.getElementsByTagName('pre');
		for (var i = 0; i < preTags.length; ++i)
		{
			if (preTags[i].className != 'ccppCode') continue;

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

			preTags[i].innerHTML = colorizeCCPP(preTags[i].innerHTML);
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
				else if ('#' == ch)
				{
					mode = 11; // directive start
					token = ch;
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

					mode = 0; // temporal meaning change to “neutral”
					for (var x in ccppKeywords1)
						if (token == ccppKeywords1[x])
						{
							mode = 1; // tmp: “keyword1 found”
							className = 'keyword1';
							break;
						}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in ccppKeywords2)
							if (token == ccppKeywords2[x])
							{
								mode = 2; // tmp: “keyword2 found”
								className = 'keyword2';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in ccppKeywords3)
							if (token == ccppKeywords3[x])
							{
								mode = 3; // tmp: “keyword3 found”
								className = 'keyword3';
								break;
							}

					if (0 == mode) // temporal meaning change to “neutral”
						for (var x in ccppPrimitive)
							if (token == ccppPrimitive[x])
							{
								mode = 4; // tmp: “primitive found”
								className = 'primitive';
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

			case 11: // directive start
				if (' ' != ch && '\t' != ch && '\r' != ch && '\n' != ch)
				{
					token += ch;
				}
				else
				{
					mode = 0; --j; // default
					newHTML += '<span class="directive">' + token + '</span>';

					if ('#include' == token)
					{
						mode = 12; ++j; // directive include
						token = ch;
					}
				}
				break;

			case 12: // directive include
				if (' ' == ch || '\t' == ch || '\r' == ch || '\n' == ch)
				{
					token += ch;
				}
				else if ('&' == ch)
				{
					mode = 13; // HTML entity at directive include start
					newHTML += token;
					token = ch;
				}
				else
				{
					mode = 0; --j; // default
					newHTML += token;
				}
				break;

			case 13: // HTML entity at directive include start
				token += ch;
				if (';' == ch)
				{
					if ('&lt;' == token)
					{
						mode = 14; // directive include with HTML entity
					}
					else
					{
						mode = 0; j -= token.length; // default
					}
				}
				break;

			case 14: // directive include with HTML entity
				token += ch;
				if (';' == ch && token.endsWith('&gt;'))
				{
					newHTML += '<span class="string">' + token + '</span>';
					mode = 0; // default
				}
				break;

			default:
				mode = 0; --j; // default
				newHTML += token;
			}
		}

		return '<span class="ccppBlock">' + newHTML + '</span>';
	}
}