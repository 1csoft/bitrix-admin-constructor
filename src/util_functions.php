<?php
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

VarDumper::setHandler(function ($var){
	$cloner = new VarCloner();

	if(in_array(PHP_SAPI, array('cli', 'phpdbg'), true)){
		$dumper = new CliDumper();
		$dumper->dump($cloner->cloneVar($var));
	} else {
		$iteArgs = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$bt = $iteArgs[0];
		if(strlen($bt['file']) == 0){
			$bt = array_pop($iteArgs);
		}
		$dumper = new HtmlDumper();
		$dumper->setStyles([
			'default' => 'outline:none; line-height:1.2em; font:14px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; position:relative; z-index:99999; word-break: normal; margin-bottom: 0',
			'key' => 'color: #79016f',
			'num' => 'font-weight:bold; color:#1299DA',
			'const' => 'font-weight:bold',
			'str' => 'font-weight:bold; color:#000',
			'note' => 'color:#1299DA',
			'ref' => 'color:#A0A0A0',
			'public' => 'color:#0f9600',
			'protected' => 'color:#a300bf',
			'private' => 'color:#ec0000',
			'meta' => 'color:#B729D9',
			'index' => 'color:#1299DA',
			'ellipsis' => 'color:#FF8400',
		]);

		?>
		<div style='font-size:9pt; border: 1px solid #999; text-align: left'>
			<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?> [<?=$bt["line"]?>]</div>
			<div style='padding:10px; background: #fff; text-align: left'><?$dumper->dump($cloner->cloneVar($var));?></div>
		</div>
		<?
	}
});

if(!function_exists('dd')){
	function dd($var = null){
		$var = $var ?: "EMPTY";

		\Symfony\Component\VarDumper\VarDumper::dump($var);
		exit;
	}
}

if (!function_exists('PR')) {
	function PR($o, $show = false) {
		global $USER;
		if ($USER->IsAdmin() || $show) {
			$bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$bt = $bt[0];
			$dRoot = $_SERVER["DOCUMENT_ROOT"];
			$dRoot = str_replace("/", "\\", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);
			$dRoot = str_replace("\\", "/", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);
			?>
			<div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000; z-index: 700; position: relative'>
				<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?>
					[<?=$bt["line"]?>]
				</div>
				<pre style='padding:10px; background: #fff; text-align: left'><? print_r($o) ?></pre>
			</div>
			<?
		} else {
			return false;
		}
	}
}