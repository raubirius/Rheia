
function errorMessage(message)
{
	// alert('Error: ' + message);
	// console.log('Error: ' + message);
	console.trace('Error: ' + message);
}

function getElement(elementID)
{
	var elementObj = document.getElementById(elementID);
	if (null == elementObj)
	{
		elementObj = eval('document.' + elementID);
		if (null == elementObj)
			errorMessage('Element “' + elementID + '” has not been found.');
	}
	return elementObj;
}

function initInOnLoad()
{
	var links = document.links;
	let i;

	for (i in links)
	{
		var link = links[i];
		if (link.host != link.hostname)
			console.log(link.host + ' does not match ' + link.hostname);
		else if (link.host == 'doi.org')
		{
			link.innerHTML = '<img src="design/crossref-logo-icon-only' +
				'.svgz" alt="logo Crossref" height="16" class="hostlogo" />' +
				link.innerHTML;
		}
	}
}

function initAfterDisplayed()
{
	initLazyImages();
	loadLazyImages();
	addEvent(window, 'scroll', loadLazyImages);
	addEvent(window, 'resize', loadLazyImages);
}


function itemDisplayed(itemToCheck)
{ return '' == getElement(itemToCheck).style.display; }

function showItem(itemToShow)
{ getElement(itemToShow).style.display = ''; }

function showItems()
{ let i; for (i = 0; i < arguments.length; ++i) showItem(arguments[i]); }

function hideItem(itemToHide)
{ getElement(itemToHide).style.display = 'none'; }

function hideItems()
{ let i; for (i = 0; i < arguments.length; ++i) hideItem(arguments[i]); }

function toggleItem(itemID)
{ if (itemDisplayed(itemID)) hideItem(itemID); else showItem(itemID); }

function toggleItems()
{ let i; for (i = 0; i < arguments.length; ++i) toggleItem(arguments[i]); }


function isItemVisible(itemID)
{ return 'hidden' != getElement(itemID).style.visibility; }

function setItemVisible(itemID, visibile)
{ getElement(itemID).style.visibility = visibile ? 'visible' : 'hidden'; }

function setItemsVisible(visible)
{ let i; for (i = 1; i < arguments.length; ++i) setItemVisible(arguments[i], visible); }


function setClass(itemID, className)
{ getElement(itemID).className = className; }

function addClass(itemID, className)
{
	var element = getElement(itemID);
	var arr = element.className.split(' ');

	if (arr.indexOf(className) == -1)
	{
		if ('' == element.className)
			element.className = className;
		else
			element.className += ' ' + className;
	}
}

function removeClass(itemID, className)
{
	var element = getElement(itemID);
	var regex = new RegExp('\\b' + className + '\\b', 'g');
	element.className = element.className.replace(regex, '');
}

function hasClass(itemID, className)
{
	return (' ' + getElement(itemID).className + ' ').
		replace(/[\n\t\r]/g, ' ').indexOf(' ' + className + ' ') > -1;
}

function toggleClass(itemID, className)
{
	if (hasClass(itemID, className))
		removeClass(itemID, className);
	else
		addClass(itemID, className);
}


function isInViewport(element)
{
	var rect = element.getBoundingClientRect();

	return rect.bottom >= 0 && rect.right >= 0 &&
		rect.top <= (window.innerHeight ||
			document.documentElement.clientHeight) &&
		rect.left <= (window.innerWidth ||
			document.documentElement.clientWidth);
}


function checkSubmit(elementID, formID)
{
	if (13 == window.event.keyCode)
		return search(elementID, formID);
	return true;
}

function search(elementID, formID)
{
	if (null == formID) formID = 'sf';

	document.forms[formID]['q'].value = getElement(elementID).value;
	// errorMessage('Query: „' + document.forms[formID]['q'].value + '“');
	// return false;

	if (document.forms[formID]['q'].value == '')
	{
		emptySearchAlert();
		return false;
	}
	document.forms[formID].submit();

	return true;
}

function searchNameDay(names, formID)
{
	if (null == formID) formID = 'sf';

	document.forms[formID]['q'].value = names;

	if (document.forms[formID]['q'].value == '')
		return false;

	document.forms[formID].submit();

	return true;
}


function mdcs(address, checksum)
{
	var decode = ''; sum = 0;
	let i;

	for (i in address)
	{
		decode += String.fromCharCode
			(address[i] ^ 0x5A);
		sum += (address[i] ^ 0x5A);
	}

	if (sum == (checksum ^ 0xA55A))
		window.location.href = 'ma' +
			'il' + 'to:' + decode;
}


var onloadCallback = null;

window.onresize = function (event)
{
	/*
	var height = 1030;
	if (window.innerHeight)
	{
		// Non-IE
		height = window.innerHeight;
	}
	else if (document.documentElement &&
		document.documentElement.clientHeight)
	{
		// IE 6+ in 'standards compliant mode'
		height = document.documentElement.clientHeight;
	}
	else if (document.body && document.body.clientHeight)
	{
		// IE 4 compatible
		height = document.body.clientHeight;
	}
	height -= 280;

	// errorMessage(getElement('page-core').offsetHeight + ' ' + height);
	getElement('page-core').style.minHeight = (height + 0) + 'px';
	getElement('page-content').style.height =
		(getElement('page-core').offsetHeight - 40) + 'px';
	// errorMessage(getElement('page-core').offsetHeight);
	*/

	if (onloadCallback) onloadCallback(event);
}

var xmlRequest = null;

function initPage(event)
{
	if (null == xmlRequest) xmlRequest = new XMLHttpRequest();
	window.onresize(event);
}

function setSessionProperty(prop, value)
{
	if (null != xmlRequest)
	{
		xmlRequest.open('GET', '/session?set=' + prop + '&val=' + value, true);

		xmlRequest.onload = function (e)
		{
			if (this.status == 200)
			{
				// errorMessage(this.response);
			}
		};

		xmlRequest.send();
		return true;
	}
	return false;
}

function saveSessionTime(name)
{
	if (null != xmlRequest)
	{
		xmlRequest.open('GET', '/session?savetime=' + name, true);

		xmlRequest.onload = function (e)
		{
			if (this.status == 200)
			{
				// errorMessage(this.response);
			}
		};

		xmlRequest.send();
		return true;
	}
	return false;
}

// var isFixed = false, main_menu = null, main_menu_dummy = null;

window.onscroll = function (event)
{
	/*
	var scrollTop = document.documentElement.scrollTop ||
		document.body.scrollTop;

	if (null == main_menu) main_menu = getElement('main_menu');
	if (null == main_menu_dummy) main_menu_dummy = getElement('main_menu_dummy');

	if (null != main_menu && null != main_menu_dummy)
	{
		if (scrollTop > 173)
		{
			if (!isFixed)
			{
				main_menu.className = 'main-menu fixed';
				main_menu_dummy.style.display = '';
				isFixed = true;
			}
		}
		else
		{
			if (isFixed)
			{
				main_menu.className = 'main-menu';
				main_menu_dummy.style.display = 'none';
				isFixed = false;
			}
		}
	}
	*/
}


function addEvent(element, event, handler)
{
	if (element.addEventListener)
	{
		element.addEventListener(event, handler, false);
	}
	else if (element.attachEvent)
	{
		element.attachEvent('on' + event, handler);
	}
}

// Examples:
// addEvent(document, 'click', function (e) { console.log('document click'); });
// addEvent(window, 'unload', myFunction);


function removeEvent(element, event, handler)
{
	if (element.removeEventListener)
	{
		element.removeEventListener(event, handler, false);
	}
	else if (element.detachEvent)
	{
		element.detachEvent('on' + event, handler);
	}
}

// Example:
// removeEvent(button1, 'click', onButton1Click);


function removeDiacritics(text)
{
	if (null != xmlRequest)
	{
		xmlRequest.open('GET', '/remove-diacritics?' + text, false);
		xmlRequest.send();
		return xmlRequest.responseText;
	}
	return false;
}

function reloadPage(page)
{
	if (null != xmlRequest)
	{
		/*
		// async == true
		xmlRequest.open('GET', page, true);

		xmlRequest.onload = function (e)
		{
			if (this.status == 200)
			{
				// errorMessage(this.response);
				errorMessage('ok');
			}
		};
		*/

		// async == false
		xmlRequest.open('GET', page, false);
		xmlRequest.send();
		document.documentElement.innerHTML = xmlRequest.responseText;
		onLoadPage();
		return true;
	}
	return false;
}

function pushState(category, selectedItem, argument)
{
	myURI = '';
	if (null != category) myURI += '/' + category;
	if (null != selectedItem) myURI += '?' + selectedItem;
	if (null != argument) myURI += '&' + argument;
	if (reloadPage(myURI)) history.pushState(null, null, myURI);
}


function loadScript(scriptURL, scriptID, scriptAsync, scriptCallback)
{
	var scriptExists = document.getElementById(scriptID);

	if (scriptExists)
	{
		// console.log('Script “' + scriptURL + '” exists (ID: ' +
		// 	scriptID + ').');
		if (scriptCallback) scriptCallback();
	}
	else
	{
		// console.log('Loading script “' + scriptURL + '” (ID: ' +
		// 	scriptID + ').');
		var scriptEl = document.createElement('script');
		scriptEl.setAttribute('type', 'text/javascript');
		scriptEl.setAttribute('src', scriptURL);
		scriptEl.setAttribute('id', scriptID);
		scriptEl.setAttribute('async', scriptAsync);
		document.head.appendChild(scriptEl);
		scriptEl.onload = () => { if (scriptCallback) scriptCallback(); };
	}
}


var lazyImages = [];

function initLazyImages()
{
	lazyImages = document.getElementsByTagName('img'); cleanLazyImages();
	// console.log('Notice: ' + lazyImages.length + ' lazy images.');
}

function loadLazyImages()
{
	let i;
	for (i = 0; i < lazyImages.length; ++i)
	{
		if (isInViewport(lazyImages[i]))
		{
			if (lazyImages[i].getAttribute('data-src'))
			{
				lazyImages[i].src = lazyImages[i].getAttribute('data-src');
				lazyImages[i].removeAttribute('data-src');
			}
		}
	}

	cleanLazyImages();
}

function cleanLazyImages()
{
	lazyImages = Array.prototype.filter.call(lazyImages, function (lazy)
		{ return lazy.getAttribute('data-src'); });
}


var allPopStateHandlers = new Array();

window.onpopstate = function (event)
{
	let i;
	for (i in allPopStateHandlers)
	{
		// console.log('pop ' + i + ': ' + allPopStateHandlers[i]);
		allPopStateHandlers[i](event);
	}
}


function cropTextUnfold(crtxtid)
{
	toggleItems(
		// 'cropText_ID' + crtxtid + '_cropped',
		'cropText_ID' + crtxtid + '_full');
}

function cropTextFold(crtxtid)
{
	hideItems(
		// 'cropText_ID' + crtxtid + '_cropped',
		'cropText_ID' + crtxtid + '_full');
}
