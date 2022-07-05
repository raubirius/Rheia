
Introduction
============

Rheia is able to import special modules to work in article or html sources.

Doing so (note that the keyword „Modul“ is in Slovak):

	#Modul(«moduleName»[, «parameters»])

Rheia then mediates the communication between «moduleName» and the
current source. The «moduleName» receives the list of parameters as an array
in the mandatory parameter of the run function (see further).

To work correctly each module must contain the definition of the class. The
class name must be identical with file/module name. The class must contain the
defintion of the function/method named run($args). The method run must return
string which becomes the module output.

The module is executed in real time (during the page is displayed to the user).


Example:
========

Filename: TestModule.php
---------

<?php

class TestModule
{
	function run($args)
	{
		$return = '';

		$return .= '<pre>test'.EOL;
		foreach ($args as $key => $val)
		$return .= $key.' : '.$val.EOL;
		$return .= '</pre>';

		return $return;
	}
}

?>

Ussage: any-source.txh
-------

#Modul(TestModule, A, B, C)

Output: (viewing as HTML document in browser)
-------

test
0 : A
1 : B
2 : C
