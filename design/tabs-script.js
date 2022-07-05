
var tabShown = null;
var tabs = 0;
var tabAlias = new Array();
var tabSelect = new Array();
// var tabAutoScroll = true;
var defaultBookmark = 'tab1';


allPopStateHandlers.push(function (event)
{
	if (null == event.state)
	{
		displayTab(bookmarkToTab());
	}
	else
	{
		displayTab(event.state);
	}
});


function displayTab(tabName, tabAutoScroll = false)
{
	// alert(tabName);
	if (tabName == tabShown) return;

	var index = -1;
	var superTab = '';

	if (null == tabShown)
	{
		hideItem('notab');
	}
	else if (tabShown)
	{
		index = tabShown.indexOf('-');
		if (-1 != index)
		{
			superTab = tabShown.substr(0, index);
			hideItem(superTab);
			setClass(superTab + '-item', '');
		}

		hideItem(tabShown);
		setClass(tabShown + '-item', '');
	}


	if (null == tabName)
	{
		showItem('notab');
		tabShown = tabName;
	}
	else
	{
		// Search default subtab:
		index = tabName.indexOf('-');
		if (-1 == index)
		{
			index = tabName + '-';
			let i; for (i in tabSelect)
			{
				if (tabSelect[i] && 0 == i.indexOf(index))
				{
					tabName = i;
					break;
				}
			}
		}


		try
		{
			index = tabName.indexOf('-');
			if (-1 != index)
			{
				superTab = tabName.substr(0, index);
				if (getElement(superTab + 'nosub'))
					hideItem(superTab + 'nosub');
				showItem(superTab);
				setClass(superTab + '-item', 'selected');
			}
			else if (getElement(tabName + 'nosub'))
				showItem(tabName + 'nosub');


			showItem(tabName);
			tabShown = tabName;
			setClass(tabShown + '-item', 'selected');

			// Scroll to element:

			if (tabAutoScroll)
			{
				// 1. This does not work, unfortunatelly :
				/*var pageElement = document.body; // getElement('page-content');
				var pageRect = getRect(pageElement);
				var tabRect = getRect(getElement(tabShown + '-item'));

				var delta = pageRect.top + pageRect.height -
					tabRect.top - tabRect.height;

				// if (delta < pageElement.scrollTop)
					// pageElement.scrollTop = …

				pageElement.scrollTo(pageElement.scrollLeft,
					tabRect.top - pageRect.top - (pageRect.height / 3));*/

				// 2. This does work neither because it changes the
				// browser’s history!
				// location.hash = '#' + tabAlias[tabName];

				try
				{
					// 3. Source: https://stackoverflow.com/questions/3163615/
					// how-to-scroll-html-page-to-given-anchor
					var element = document.querySelector(
						'#' + tabName + '-item');
					var topPos = element.getBoundingClientRect().top +
						window.pageYOffset;
	
					window.scrollTo({
						top: topPos, // scroll so that the element is
							// at the top of the view
						behavior: 'smooth' // smooth scroll
					});
				}
				catch (ex)
				{
					console.log(ex);
				}
			}
		}
		catch (err)
		{
			tabNotExistsAlert();
		}
	}

	// alert('Page – top: ' + pageRect.top + '; height: ' + pageRect.height +
	// 	'; scroll: ' + pageElement.scrollTop + '\n' +
	// 	'Tab – top: ' + tabRect.top + '; height: ' + tabRect.height + '\n' +
	// 	'delta: ' + delta);
}


function getRect(element)
{
	var oRect =
	{
		top: 0,
		left: 0,
		width: element.offsetWidth,
		height: element.offsetHeight
	};

	oRect.top = element.offsetTop;
	oRect.left = element.offsetLeft;
	var objParent = element.offsetParent;

	while (objParent)
	{
		oRect.top += objParent.offsetTop;
		oRect.left += objParent.offsetLeft;
		objParent = objParent.offsetParent;
	}

	return oRect;
}



function navigateTab(tabName, tabScroll = false)
{
	// tabAutoScroll = tabScroll;
	displayTab(tabName, tabScroll);

	if (null == tabName)
		history.pushState(tabName, document.title, '#');
	else
	{
		var str = tabName;
		let i; for (i in tabAlias)
		{
			if (-1 != str.indexOf(i))
				str = str.replace(i, tabAlias[i]);
		}
		history.pushState(tabName, document.title,
			'#' + str.replace('tab', 'karta-'));
	}

	// tabAutoScroll = true;
}

function bookmarkToTab()
{
	var str = document.location.toString();
	var index = str.indexOf('#');

	if (-1 == index) return defaultBookmark;

	str = str.substr(1 + index);

	if ('' == str) return defaultBookmark;

	index = str.indexOf(';');

	if (-1 != index)
		str = str.substr(0, index);

	if ('' == str) return defaultBookmark;

	let x; for (x = 0; x < 2; ++x)
	{
		var breakMe = true;

		/*{
			let i; for (i in tabAlias)
			{
				if (-1 != str.indexOf(tabAlias[i]))
					str = str.replace(tabAlias[i], i);
			}
		}*/

		{
			var alias = null, len = -1;

			let i; for (i in tabAlias)
			{
				if (-1 != str.indexOf(tabAlias[i]))
				{
					var n = tabAlias[i].length;
					if (n > len)
					{
						alias = i;
						len = n;
					}
				}
			}

			if (-1 != len) str = str.replace(tabAlias[alias], alias);
		}

		if (null == getElement(str))
		{
			if (null == xmlRequest) xmlRequest = new XMLHttpRequest();
			newStr = removeDiacritics(str);

			if (str != newStr)
			{
				str = newStr;
				breakMe = false;
			}
		}

		if (breakMe) break;
	}

	return str.replace('karta-', 'tab');
}


function initTabs(tabsCount, subtabsCounts)
{
	tabs = tabsCount;
	let i;

	for (i = 1; i <= tabs; ++i)
	{
		hideItem('tab' + i);
		if (getElement('tab' + i + 'nosub'))
			hideItem('tab' + i + 'nosub');
		setClass('tab' + i + '-item', '');
		let j; for (j = 1; j <= subtabsCounts[i - 1]; ++j)
		{
			hideItem('tab' + i + '-' + j);
			setClass('tab' + i + '-' + j + '-item', '');
		}
	}

	// Search default tab:
	for (i in tabSelect)
	{
		if (tabSelect[i] && -1 == i.indexOf('-'))
		{
			defaultBookmark = i;
			break;
		}
	}

	window.scrollTo(0, 0);
	// alert("window.scrollTo(0, 0);");
	displayTab(bookmarkToTab());
	// alert(bookmarkToTab());
}
