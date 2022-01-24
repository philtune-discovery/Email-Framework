<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function json_out($data, bool $debug = true, int $shifts = 0)
{
	while ( ob_get_level() ) {
		ob_end_clean();
	}
	header('Content-Type: application/json');
	$output = $data;
	if ( $debug ) {
		$backtrace = debug_backtrace();
		$first = array_shift($backtrace);
		for ( $i = 0; $i < $shifts; $i++ ) {
			$first = array_shift($backtrace);
		}
		$file = str_replace(SYSTEM_PATH, '', $first['file'] ?? '');
		$line = $first['line'] ?? 0;

		$output = [
			//				'file'              => $file,
			//				'line'              => $first['line'],
			"Console::json_out @ $file::$line" => $data,
			'backtrace'                        => $backtrace,
		];
	}
	echo json_encode($output);
	exit;
}
