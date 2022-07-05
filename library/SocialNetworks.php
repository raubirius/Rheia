<?php

// Note: this is a module used by the RheiaMainClass.
// For more information see: modules-readme.txt

class SocialNetworks
{
	// http://www.ebizmba.com/articles/social-networking-websites

	function run($args)
	{
		global $style;
		$style .= ' div.article-footer { margin-top: 25px; padding-top: 20px; padding-left: 22px; border-top: 1px solid #ddd; }'.EOL;

		// return '<div style="color: silver; height:140px; width:560px; border: 1px solid silver;">Miesto pre sociálny modul.</div>';

		$facebook = true; // $googlePlus = true;
		$twitter = true; $linkedIn = true;
		$FBComments = true;

		foreach ($args as $arg)
		{
			if ('noFacebookLike' == $arg)
				$facebook = false;
			/*else if ('noGoogle+' == $arg)
				$googlePlus = false;*/
			else if ('noTwitter' == $arg)
				$twitter = false;
			else if ('noLinkedIn' == $arg)
				$linkedIn = false;
			else if ('noFBComments' == $arg)
				$FBComments = false;
			else if ('addFacebookLike' == $arg)
				$facebook = true;
			/*else if ('addGoogle+' == $arg)
				$googlePlus = true;*/
			else if ('addTwitter' == $arg)
				$twitter = true;
			else if ('addLinkedIn' == $arg)
				$linkedIn = true;
			else if ('addFBComments' == $arg)
				$FBComments = true;
			else if ('onlyFacebookLike' == $arg)
			{
				$facebook   = true;
				// $googlePlus = false;
				$twitter    = false;
				$linkedIn   = false;
				$FBComments = false;
			}
			/*else if ('onlyGoogle+' == $arg)
			{
				$facebook   = false;
				$googlePlus = true;
				$twitter    = false;
				$linkedIn   = false;
				$FBComments = false;
			}*/
			else if ('onlyTwitter' == $arg)
			{
				$facebook   = false;
				// $googlePlus = false;
				$twitter    = true;
				$linkedIn   = false;
				$FBComments = false;
			}
			else if ('onlyLinkedIn' == $arg)
			{
				$facebook   = false;
				// $googlePlus = false;
				$twitter    = false;
				$linkedIn   = true;
				$FBComments = false;
			}
			else if ('onlyFBComments' == $arg)
			{
				$facebook   = false;
				// $googlePlus = false;
				$twitter    = false;
				$linkedIn   = false;
				$FBComments = true;
			}
			else if (preg_match('/^fbAppID:(.*)/', $arg, $matches))
			{
				HTMLHeadManagement::setMetaProperty('fb:app_id', $matches[1]);
			}
		}

		$return = '';
		$script = '';

		$url = $_SERVER['REQUEST_URI'];
		if (empty($url)) $url = '/';
		else if ('/' != $url[0]) $url = '/'.$url;
		$url = RheiaMainClass::$protocol.'://'.RheiaMainClass::$domain.$url;

		// https://developers.facebook.com/docs/plugins/like-button/
		if ($facebook) $return .=
			'<div id="fb-root"></div>'.EOL2;

		if ($facebook || $FBComments)
		{
			$script .= '(function(d, s, id) {'.EOLT.
				'var js, fjs = d.getElementsByTagName(s)[0];'.EOLT.
				'if (d.getElementById(id)) return;'.EOLT.
				'js = d.createElement(s); js.id = id;'.EOLT.
				'js.src = "//connect.facebook.net/sk_SK/all.js#xfbml=1";'.
				EOLT.'fjs.parentNode.insertBefore(js, fjs);'.EOL.
				'}(document, "script", "facebook-jssdk"));'.EOL2;
		}

		if ($facebook) $return .=
			'<div class="fb-like" data-href="'.$url.'" data-layout='.
			'"button_count" data-action="recommend" data-show-faces="true" '.
			'data-share="true" data-width="225"></div>'.EOL2;

		// https://developers.google.com/+/web/+1button/
		/*if ($googlePlus) $script .= 'window.___gcfg = {lang: "sk"};'.
			EOL2.'(function() {'.EOLT.
			'var po = document.createElement("script");'.EOLT.
			'po.type = "text/javascript"; po.async = true;'.EOLT.
			'po.src = "https://apis.google.com/js/platform.js";'.EOLT.
			'var s = document.getElementsByTagName("script")[0];'.EOLT.
			's.parentNode.insertBefore(po, s);'.EOL.'})();'.EOL2;

		if ($googlePlus) $return .=
			'<div class="g-plusone" data-size="medium" '.
			'data-width="225"'.
			//' data-href="'.$url.'"'.
			'></div>'.EOL2;*/

		// https://about.twitter.com/resources/buttons#tweet
		if ($twitter) $return .=
			'<a href="https://twitter.com/share" class="twitter-'.
			'share-button">Tweet</a>'.EOL2;

		if ($twitter) $script .=
			'!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],'.
			'p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById'.
			'(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.'.
			'twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}'.
			'(document, "script", "twitter-wjs");'.EOL2;

		// https://developer.linkedin.com/plugins
		if ($linkedIn) $return .=
			'<script src="//platform.linkedin.com/in.js" type='.
			'"text/javascript">'.EOLT.'lang: en_US'.EOL.'</script>'.EOL.
			'<script type="IN/Share" data-counter="right"></script>';

		// if ($linkedIn) $script .= '';

		// https://developers.facebook.com/docs/plugins/comments/
		/*<!-- div id="fb-root"></div>
		<-- script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/sk_SK/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));</script -->*/
		if ($FBComments) $return .=
			'<div class="fb-comments" data-href="'.$url.
			'" data-numposts="25" data-colorscheme="light"></div>'.EOL;

		// Finalize
		$return .= EOL.'<script type="text/javascript">'.EOL2.
			$script.'</script>'.EOL;

		return $return;
		// return '<pre>'.htmlspecialchars($return).'</pre>';
	}
}

?>