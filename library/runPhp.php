<?php
function runPhp($url, $phpCommand = null, $phpScript = null)
{
	global $domain;
	if (empty($domain)) $domain = 'pdf.truni.sk';

	if (empty($phpCommand)) $phpCommand = 'php';
	// $phpCommand = 'C:/PHP7/php.exe';
	$iniPath = php_ini_loaded_file();
	if (!empty($iniPath))
	{
		$iniPath = dirname($iniPath);
		$phpCommand .= ' -c '.$iniPath;
	}
	elseif (file_exists('c:\\Apache24\\conf'))
		$phpCommand .= ' -c c:\\Apache24\\conf';

	$stdios = array(array('pipe', 'r'), array('pipe', 'w'), array('pipe', 'w'));
	$pipes = null;
	$workDir = null;
	$envVars = null;
	$options = array('bypass_shell' => true);
	$process = proc_open($phpCommand, $stdios, $pipes,
		$workDir, $envVars, $options);

	if (empty($phpScript)) $phpScript = 'getFileInfo.php';
	if (!file_exists($phpScript)) $phpScript = '../library/'.$phpScript;
	for ($i = 0; $i < 10; ++$i)
		if (!file_exists($phpScript)) $phpScript = '../'.$phpScript;

	if (is_resource($process))
	{
		fwrite($pipes[0], '<?php $domain = \''.addslashes($domain).'\'; ');
		fwrite($pipes[0], '$url = \''.addslashes($url).'\'; ?>');
		fwrite($pipes[0], file_get_contents($phpScript));
		fclose($pipes[0]);

		$phpReturnContents = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$phpReturnValue = proc_close($process);
	}

	if (!file_exists($phpScript)) $phpReturnContents .=
		'X-Error: Local PHP Script doesn’t exist: '.$phpScript.EOL;

	return array($phpReturnContents, $phpReturnValue);
}
?>