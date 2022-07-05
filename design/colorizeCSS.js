var CSSColors = ['aliceblue', 'antiquewhite', 'aqua', 'aquamarine',
	'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue',
	'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse',
	'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson',
	'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray',
	'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen',
	'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen',
	'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet',
	'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick',
	'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite',
	'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew',
	'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender',
	'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue',
	'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray',
	'lightgreen', 'lightpink', 'lightsalmon', 'lightseagreen',
	'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow',
	'lime', 'limegreen', 'linen', 'magenta', 'maroon',
	'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple',
	'mediumseagreen', 'mediumslateblue', 'mediumspringgreen',
	'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream',
	'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive',
	'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod',
	'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip',
	'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple',
	'rebeccapurple', 'red', 'rosybrown', 'royalblue', 'saddlebrown',
	'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver',
	'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen',
	'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise',
	'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen'];

var CSSClauses = ['rgb(', 'rgba(', 'hsl(', 'hsla('];

var CSSColorizer = function()
{
	this.preTags = document.getElementsByTagName('pre');
	this.oldHTML = '';
	this.newHTML = '';
	this.mode = CSSColorizer.SELECTOR_0;
	this.token = '';
	this.lowerCaseToken = '';
	this.ch = '';
	this.nch = '';
	this.makeComment = false;
};

CSSColorizer.SELECTOR_0 = 0;
CSSColorizer.SELECTOR_1 = 1;
CSSColorizer.SELECTOR_2 = 2;
CSSColorizer.ATTRIBUTE = 3;
CSSColorizer.VALUES = 4;
CSSColorizer.URL = 5;

CSSColorizer.WORD = /^[-_a-zA-Z][-_a-zA-Z0-9]+$/;
CSSColorizer.NUMBER = /^#?[-+a-fA-F0-9.]+[%ceimprv]?[cehmntwx]?[aim]?[nx]?$/;

CSSColorizer.prototype =
{
	// This function name is symbolic. It should express that “the process of
	// parsing begins here”. It initializes coloring of new block.
	parseBegin: function(theHTML)
	{
		console.log(theHTML);

		this.oldHTML = theHTML + '\r';
		this.newHTML = '';
		this.mode = CSSColorizer.SELECTOR_0;
		this.token = '';
		this.lowerCaseToken = '';
		this.ch = '';
		this.nch = '';
		this.makeComment = false;
	}
	,
	// This function name is symbolic. It should express that “the process of
	// parsing ends here”. It stores parsed colorized block.
	parseEnd: function()
	{
		return '<span class="CSSBlock">' + this.newHTML + '</span>';
	}
	,
	// Set new value of token (default new value is the value of current
	// character). Optionally change the mode. Optionally add current
	// character to HTML (in this case the default token new value is empty
	// string).
	setToken: function(tokenValue, newMode, chToHTML)
	{
		if (typeof chToHTML == 'boolean' && chToHTML)
		{
			this.newHTML += this.ch;
			if (typeof tokenValue == 'string')
			{
				this.token = tokenValue;
				this.lowerCaseToken = tokenValue.toLowerCase();
			}
			else
			{
				this.token = '';
				this.lowerCaseToken = '';
			}
		}
		else if (typeof chToHTML == 'string')
		{
			this.newHTML += '<span class="' + chToHTML +
				'">' + this.ch + '</span>';
			if (typeof tokenValue == 'string')
			{
				this.token = tokenValue;
				this.lowerCaseToken = tokenValue.toLowerCase();
			}
			else
			{
				this.token = '';
				this.lowerCaseToken = '';
			}
		}
		else if (typeof tokenValue == 'string')
		{
			this.token = tokenValue;
			this.lowerCaseToken = tokenValue.toLowerCase();
		}
		else
		{
			this.token = this.ch;
			this.lowerCaseToken = this.ch.toLowerCase();
		}

		if (typeof newMode == 'number')
		{
			this.mode = newMode;
		}
		else if (typeof newMode == 'boolean')
		{
			this.makeComment = newMode;
		}
	}
	,
	// Use token – add it to HTML and reset it. Optionally change the mode.
	// Optionally add also the current character to HTML.
	useToken: function(inClass, newMode, chToHTML)
	{
		if ('' != this.token)
		{
			if (typeof inClass == 'string')
			{
				this.newHTML += '<span class="' + inClass +
					'">' + this.token + '</span>';
			}
			else
			{
				this.newHTML += this.token;
			}

			this.token = '';
			this.lowerCaseToken = '';
		}

		if (typeof chToHTML == 'boolean' && chToHTML)
		{
			this.newHTML += this.ch;
		}
		else if (typeof chToHTML == 'string')
		{
			this.newHTML += '<span class="' + chToHTML +
				'">' + this.ch + '</span>';
		}

		if (typeof newMode == 'number')
		{
			this.mode = newMode;
		}
		else if (typeof newMode == 'boolean')
		{
			this.makeComment = newMode;
		}
	}
	,
	// Adds current character to token. Optionally enclose it within a class.
	advanceToken: function(inClass)
	{
		if (typeof inClass == 'string')
		{
			this.token += '<span class="' + inClass +
				'">' + this.ch + '</span>';
		}
		else
		{
			this.token += this.ch;
		}

		this.lowerCaseToken += this.ch.toLowerCase();
	}
	,
	// Checks if current character is part of selector syntax or space.
	isSelectorSeparator: function()
	{
		return ' ' == this.ch || ' ' == this.ch || ',' == this.ch ||
			'>' == this.ch || '+' == this.ch || '~' == this.ch;
	}
	,
	// Makes some conversion to whitespace (in some modes).
	convertWhitespace: function(useToken)
	{
		if (' ' == this.ch || ' ' == this.ch || '	' == this.ch ||
			'\r' == this.ch || '\n' == this.ch)
		{
			if (typeof useToken == 'string')
			{
				this.useToken(useToken);
			}

			if ('\n' == this.ch) this.newHTML += '<br />';
			else if (' ' == this.ch || ' ' == this.ch) this.newHTML += ' ';
			else if ('	' == this.ch) this.newHTML += '    ';
			return true;
		}
		return false;
	}
	,
	// Parses and processes a single value within parseValues method.
	processValue: function()
	{
		if ('' == this.token) return;

		for (var k in CSSColors)
		{
			if (CSSColors[k] === this.lowerCaseToken)
			{
				this.useToken('color');
				return;
			}
		}

		if (this.token.match(CSSColorizer.WORD))
		{
			this.useToken('word');
			return;
		}

		if (this.token.match(CSSColorizer.NUMBER))
		{
			this.useToken('number');
			return;
		}

		this.useToken('otherValue');
	}
	,
	// Parses all types of CSS values within main loop.
	parseValues: function()
	{
		if (' ' == this.ch || ' ' == this.ch || '	' == this.ch ||
			'\r' == this.ch || '\n' == this.ch)
		{
			this.processValue();

			if ('\n' == this.ch) this.newHTML += '<br />';
			else if (' ' == this.ch || ' ' == this.ch) this.newHTML += ' ';
			else if ('	' == this.ch) this.newHTML += '    ';
		}
		else if (';' == this.ch)
		{
			this.processValue();
			this.useToken('unknownValue',
				CSSColorizer.ATTRIBUTE, true);
		}
		else if ('}' == this.ch)
		{
			this.processValue();
			this.useToken('unknownValue',
				CSSColorizer.SELECTOR_0, true);
		}
		else if (',' == this.ch)
		{
			this.processValue();
			this.advanceToken();
			this.useToken();
		}
		else
		{
			if (')' == this.ch)
			{
				this.processValue();
				this.advanceToken();
				this.useToken('clause');
			}
			else
			{
				this.advanceToken();

				for (var k in CSSClauses)
				{
					if (CSSClauses[k] === this.lowerCaseToken)
					{
						this.useToken('clause');
						return;
					}
				}

				if ('url(' === this.lowerCaseToken)
				{
					this.useToken('urlClause', CSSColorizer.URL);
					return;
				}

				if ('(' == this.ch)
				{
					this.useToken('otherClause');
					return;
				}
			}
		}
	}
	,
	// 
	parseHTML: function(theHTML)
	{
		this.parseBegin(theHTML);

		for (var j = 0; j < this.oldHTML.length; ++j)
		{
			this.ch = this.oldHTML.charAt(j);
			this.nch = '';

			if ((j + 1) < this.oldHTML.length)
				this.nch = this.oldHTML.charAt(j + 1);

			if (this.makeComment)
			{
				this.advanceToken();
				if ('*' == this.ch && '/' == this.nch)
				{
					this.ch = this.nch;
					this.advanceToken();
					this.useToken('comment', false);
					++j;
				}
			}
			else if ('/' == this.ch && '*' == this.nch)
			{
				switch (this.mode)
				{
				case CSSColorizer.SELECTOR_0:
					this.useToken('selector0'); break;
				case CSSColorizer.SELECTOR_1:
					this.useToken('selector1'); break;
				case CSSColorizer.SELECTOR_2:
					this.useToken('selector2'); break;
				case CSSColorizer.ATTRIBUTE:
					this.useToken('attribute'); break;
				case CSSColorizer.VALUES:
					this.processValue(); break;
				case CSSColorizer.URL:
					this.useToken('url'); break;
				default: this.useToken();
				}
				this.setToken('/*', true);
				++j;
			}
			else switch (this.mode)
			{
			case CSSColorizer.SELECTOR_0:
				if (this.convertWhitespace('selector0'))
				{
				}
				else if ('{' == this.ch)
				{
					this.useToken('selector0',
						CSSColorizer.ATTRIBUTE, true);
				}
				else if ('.' == this.ch || '#' == this.ch)
				{
					this.useToken('selector0');
					this.setToken(null, CSSColorizer.SELECTOR_1);
				}
				else if (':' == this.ch)
				{
					this.useToken('selector0');
					this.setToken(null, CSSColorizer.SELECTOR_2);
				}
				else
				{
					this.advanceToken();
				}
				break;

			case CSSColorizer.SELECTOR_1:
				if (this.isSelectorSeparator())
				{
					this.useToken('selector1',
						CSSColorizer.SELECTOR_0, true);
				}
				else if (this.convertWhitespace('selector1'))
				{
				}
				else if ('{' == this.ch)
				{
					this.useToken('selector1',
						CSSColorizer.ATTRIBUTE, true);
				}
				else if (':' == this.ch)
				{
					this.useToken('selector1',
						CSSColorizer.SELECTOR_2);
					this.advanceToken();
				}
				else
				{
					this.advanceToken();
				}
				break;

			case CSSColorizer.SELECTOR_2:
				if (this.isSelectorSeparator())
				{
					this.useToken('selector2',
						CSSColorizer.SELECTOR_0, true);
				}
				else if (this.convertWhitespace('selector2'))
				{
				}
				else if ('{' == this.ch)
				{
					this.useToken('selector2',
						CSSColorizer.ATTRIBUTE, true);
				}
				else
				{
					this.advanceToken();
				}
				break;

			case CSSColorizer.ATTRIBUTE:
				if (this.convertWhitespace('attribute'))
				{
				}
				else if ('}' == this.ch)
				{
					this.useToken('attribute',
						CSSColorizer.SELECTOR_0, true);
				}
				else if (':' == this.ch)
				{
					this.useToken('attribute', CSSColorizer.VALUES,
						true);
				}
				else
				{
					this.advanceToken();
				}
				break;

			case CSSColorizer.VALUES:
				this.parseValues();
				break;

			case CSSColorizer.URL:
				if (')' == this.ch)
				{
					this.useToken('url',
						CSSColorizer.VALUES, 'urlClause');
				}
				else
				{
					this.advanceToken();
				}
				break;

			default:
				this.useToken(null, CSSColorizer.SELECTOR_0);
				--j;
			}
		}

		return this.parseEnd();
	}
	,
	// The main iteration function.
	iterate: function()
	{
		for (var i = 0; i < this.preTags.length; ++i)
			if (this.preTags[i].className == 'CSSCode')
				this.preTags[i].innerHTML =
					this.parseHTML(this.preTags[i].innerHTML);
	}
};

function colorizeCSS(theHTML)
{
	var colorizer = new CSSColorizer();
	if (typeof theHTML == 'undefined')
		colorizer.iterate();
	else
		return colorizer.parseHTML(theHTML);
}