
var secShown = null;
var secs = 0;
var secAlias = new Array();
var secSelect = new Array();
// var secAutoScroll = true;
var defaultBookmark = 'sec1';


window.onpopstate = function(event)
{
	if (null == event.state)
	{
		displaySec(bookmarkToSec());
	}
	else
	{
		displaySec(event.state);
	}
}


function displaySec(secName)
{
	// console.log(secName);
	if (secName == secShown) return;

	var index = -1;
	var superSec = '';

	if (null == secShown)
	{
		hideItem('nosec');
	}
	else if (secShown)
	{
		index = secShown.indexOf('-');
		if (-1 != index)
		{
			superSec = secShown.substr(0, index);
			hideItem(superSec);
			setClass(superSec + '-item', '');
		}

		hideItem(secShown);
		setClass(secShown + '-item', '');
	}


	if (null == secName)
	{
		showItem('nosec');
		secShown = secName;
	}
	else
	{
		// Search default subsec:
		index = secName.indexOf('-');
		if (-1 == index)
		{
			index = secName + '-';
			for (var i in secSelect)
			{
				if (secSelect[i] && 0 == i.indexOf(index))
				{
					secName = i;
					break;
				}
			}
		}


		try
		{
			index = secName.indexOf('-');
			if (-1 != index)
			{
				superSec = secName.substr(0, index);
				if (getElement(superSec + 'nosub'))
					hideItem(superSec + 'nosub');
				showItem(superSec);
				setClass(superSec + '-item', 'selected');
			}
			else if (getElement(secName + 'nosub'))
				showItem(secName + 'nosub');


			showItem(secName);
			secShown = secName;
			setClass(secShown + '-item', 'selected');
	
			// Scroll to element:
	
			/*
			if (secAutoScroll)
			{
				var pageElement = getElement('page-content');
				var pageRect = getRect(pageElement);
				var secRect = getRect(getElement(secShown + '-item'));
	
				var delta = pageRect.top + pageRect.height -
					secRect.top - secRect.height;
	
				if (delta < pageElement.scrollTop)
					pageElement.scrollTop = secRect.top - pageRect.top -
					(pageRect.height / 3);
			}
			*/
		}
		catch (err)
		{
			// secNotExistsAlert();
		}
	}

	// console.log('Page – top: ' + pageRect.top + '; height: ' + pageRect.height +
	// 	'; scroll: ' + pageElement.scrollTop + '\n' +
	// 	'Sec – top: ' + secRect.top + '; height: ' + secRect.height + '\n' +
	// 	'delta: ' + delta);
}


/*
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
*/


function navigateSec(secName)
{
	// secAutoScroll = false;

	displaySec(secName);
	var str = secName
	for (var i in secAlias)
	{
		if (-1 != str.indexOf(i))
			str = str.replace(i, secAlias[i]);
	}
	history.pushState(secName,
		document.title, '#' + str.replace('sec', 'sekcia-'));

	// secAutoScroll = true;
}

function bookmarkToSec()
{
	var str = document.location.toString();
	var index = str.indexOf('#');

	if (-1 == index) return defaultBookmark;

	str = str.substr(1 + index);

	for (var x = 0; x < 2; ++x)
	{
		var breakMe = true;

		for (var i in secAlias)
		{
			if (-1 != str.indexOf(secAlias[i]))
				str = str.replace(secAlias[i], i);
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

	return str.replace('sekcia-', 'sec');
}


function initSecs(secsCount, subsecsCounts)
{
	secs = secsCount;
	for (var i = 1; i <= secs; ++i)
	{
		hideItem('sec' + i);
		if (getElement('sec' + i + 'nosub'))
			hideItem('sec' + i + 'nosub');
		setClass('sec' + i + '-item', '');
		for (var j = 1; j <= subsecsCounts[i - 1]; ++j)
		{
			hideItem('sec' + i + '-' + j);
			setClass('sec' + i + '-' + j + '-item', '');
		}
	}

	// Search default sec:
	for (var i in secSelect)
	{
		if (secSelect[i] && -1 == i.indexOf('-'))
		{
			defaultBookmark = i;
			break;
		}
	}

	// window.scrollTo(0, 0);
	// console.log('window.scrollTo(0, 0);');
	displaySec(bookmarkToSec());
	// console.log(bookmarkToSec());
}
