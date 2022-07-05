<?php

class TestModule
{
	function run($args)
	{
		$return = '';

		$return .= '<em>test'.EOL;
		foreach ($args as $key => $val)
		$return .= $key.' : '.$val.EOL;
		$return .= '</em>';

		return $return;
	}
}

?>