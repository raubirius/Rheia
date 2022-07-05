<?php

class InsertIFrame
{
	private $defs = array(
		'cec' => 'http://cec.truni.sk/',
		'showAsgn' => 'http://cec.truni.sk/bjpu-server/show-assignment',
		'PaA2' => 'PaA2-2015-',
		'PP2' => 'PP2-2015-',
		);

	public function run($args)
	{
		$return = ''; $style = '';

		$return .= '<iframe src="';
		foreach ($args as $key => $val)
		{
			if ('#' == $val[0])
			{
				$val = substr($val, 1);
			}
			elseif ('@' == $val[0])
			{
				$style .= substr($val, 1);
				$val = '';
			}
			else
			{
				if ($key > 1) $return .= '&';
				elseif ($key > 0) $return .= '?';
			}

			if (isSet($this->defs[$val]))
				$return .= $this->defs[$val];
			else
				$return .= $val;
		}
		$return .= '" style="'.$style.'"></iframe>';

		return $return;
	}
}

?>