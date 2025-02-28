<?php
# Meta
// Name: Ritchey Get Lines By Prefix i1 v1
// Description: Returns a string on success. Returns "FALSE" on failure.
// Notes: Optional arguments can be "NULL" to skip them in which case they will use default values.
// Arguments: Source File (required) is the file to get lines from. Prefix (required) is a label line to search for (e.g., "Content: "). Tag (optional) is a string that is used immediately before and after the lines to get (e.g., "<value>" or '"'). Offset (optional) is the matching instance to capture if there's multiple in the file. Display Errors (optional) specifies if errors should be displayed after the function runs.
// Arguments (For Machines): source_file: file, required. prefix: string, required. tag: string, optional. offset: number, optional. display_errors: bool, optional.
# Content
if (function_exists('ritchey_get_lines_by_prefix_i1_v1') === FALSE){
function ritchey_get_lines_by_prefix_i1_v1($source_file, $prefix, $tag = NULL, $offset = NULL, $display_errors = NULL){
	## Prep
	$errors = array();
	$location = realpath(dirname(__FILE__));
	if (@is_file($source_file) === FALSE){
		$errors[] = "source_file";
	}
	if (@is_string($prefix) === FALSE or @empty($prefix) === TRUE){
		$errors[] = "source_file";
	}
	if ($tag === NULL){
		$tag = '"';
	} else if (@is_string($tag) === TRUE){
		// Do nothing
	} else {
		$errors[] = "tag";
	}
	if ($offset === NULL){
		$offset = 1;
	} else if (@is_int($offset) === TRUE){
		// Do nothing
	} else {
		$errors[] = "offset";
	}
	if ($display_errors === NULL){
		$display_errors = TRUE;
	} else if ($display_errors === TRUE){
		// Do nothing
	} else if ($display_errors === FALSE){
		// Do nothing
	} else {
		$errors[] = "display_errors";
	}
	## Task
	if (@empty($errors) === TRUE){
		### Read the file line by line. When a line starting with the prefix is found, and it is followed by the tag, and it is the correct offset, start capturing the lines after the tag line, until another tag line is found.
		$n = 0;
		$state = 0;
		$handle = @fopen($source_file, 'r');
		$line = '';
		$lines = array();
		while (@feof($handle) !== TRUE AND $state !== TRUE) {
			$line = @fgets($handle);
			// Stage 1: Search for prefix
			if ($state === 0){
				$prefix_length = @intval(@strlen($prefix));
				if (substr($line, 0, $prefix_length) === $prefix){
					$state = 1;
				}
			}
			// Stage 2: Check prefix is followed by tag, and increase n if it is. If not, reset to state to 0. Check if offset matches as well.
			else if ($state === 1){
				if (rtrim($line, "\r\n") === $tag){
					$state = 2;
					$n++;
					if ($n === $offset){
						$state = 3;
					} else {
						$state = 0;
					}
				} else {
					$state = 0;
				}
			}
			// Stage 3: Start capturing lines. If line is tag, then set state to true to stop capturing.
			else if ($state === 3){
				if (rtrim($line, "\r\n") === $tag){
					$state = TRUE;
				} else {
					$lines[] = $line;
				}
			}	
		}
		@fclose($handle);
		$lines = @implode($lines);
	}
	cleanup:
	## Cleanup
		// Do nothing
	result:
	## Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_get_lines_by_prefix_i1_v1_format_error') === FALSE){
				function ritchey_get_lines_by_prefix_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_get_lines_by_prefix_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	## Return
	if (@empty($errors) === TRUE){
		return $lines;
	} else {
		return FALSE;
	}
}
}
?>