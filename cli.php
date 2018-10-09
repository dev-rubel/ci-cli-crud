#!/usr/bin/php -q
<?php 
	// dir paths
	$layout		= 'backend/';
	$controller = 'application/controllers/'.$layout;
	$model 		= 'application/models/';
	$view 		= 'application/views/'.$layout;
	$asset 		= 'assets/'.$layout;
	// global var
	$ex_file	= 'example.php';
	$ex_dir		= 'example/';
	$ex_exs		= 'examples';
	$ex_ex		= 'example';
	$ex_up_ex	= 'Example';
	$ex_table	= 'table';
	$ex_key		= 'id';
	$view_files 	= ['index.php','add.php','modify.php'];
	$asset_files 	= ['assets.ini','style.css','script.js'];


	// remove dir and subdir/files
	function rmrf($dir) {
	    foreach (glob($dir) as $file) {
	        if (is_dir($file)) { 
	            rmrf("$file/*");
	            rmdir($file);
	        } else {
	            unlink($file);
	        }
	    }
	}

	if($argv[1] !== 'delete'):
		// var section
		$dir 			= $argv[1].'/';
		$name 			= $argv[1];
		$file 			= $argv[1].'.php';
		$table 			= $argv[2];
		$primary_key	= !empty($argv[3])?$argv[3]:'';
		// controller section
		if (!file_exists($controller.$file)) {
			if(copy($controller.$ex_file, $controller. ucfirst($file))) {
				$file_content = file_get_contents($controller. ucfirst($file));
				$file_content = str_replace($ex_exs, ucfirst($name), $file_content);
				$file_content = str_replace($ex_up_ex, ucfirst($name), $file_content);
				$file_content = str_replace($ex_ex, $name, $file_content);
				file_put_contents($controller. ucfirst($file), $file_content);
				echo ucfirst($name)." Controller Created \n";
			} else {
				echo ucfirst($name)." Controller File Copy Error!! \n";
			}
		} else {
			echo ucfirst($name)." Controller Already Created \n";
		}

		// model section
		if (!file_exists($model.$file)) {
			if(copy($model.$ex_file, $model. ucfirst($file))) {
				$file_content = file_get_contents($model. ucfirst($file));
				// file
				$file_content = str_replace($ex_ex, $name, $file_content);
				// table			
				$file_content = str_replace($ex_table, $table, $file_content);
				// primary key (if given)
				if(!empty($primary_key)) {
					$file_content = str_replace($ex_key, $primary_key, $file_content);
				}
				file_put_contents($model. ucfirst($file), $file_content);
				echo ucfirst($name)." Model Created \n";
			} else {
				echo ucfirst($name)." Model File Copy Error!! \n";
			}
		} else {
			echo ucfirst($name)." Model Already Created \n";
		}

		// view section
		if (!file_exists($model.$name)) {
			if(mkdir($view.$name, 0777, true)) {
				foreach ($view_files as $key => $data) {
					copy($view.$ex_dir.$data, $view.$dir.$data);
						$file_content = file_get_contents($view.$dir.$data);
						$file_content = str_replace($ex_up_ex, ucfirst($name), $file_content);
						$file_content = str_replace($ex_ex, $name, $file_content);
						file_put_contents($view.$dir.$data, $file_content);
				}
				echo ucfirst($name)." View Created \n";
			} else {
				echo ucfirst($name)." View Folder Create Error!! \n";
			}
		} else {
			echo ucfirst($name)." View Already Created \n";
		}

		// assets section
		if (!file_exists($asset.$name)) {
			if(mkdir($asset.$name, 0777, true)) {
				foreach ($asset_files as $key => $data) {
					copy($asset.$ex_dir.$data, $asset.$dir.$data);	
				}
				echo ucfirst($name)." Assets Created";
			} else {
				echo ucfirst($name)." Assets Folder Create Error!!";
			}
		} else {
			echo ucfirst($name)." Assets Already Created";
		}
	else: 
	// if select delete commend
		$name 			= $argv[2].'.php';
		$dir 			= $argv[2];
		if(!empty($argv[2])) {
			rmrf($controller.ucfirst($name));
			rmrf($model.ucfirst($name));
			rmrf($view.$dir);
			rmrf($asset.$dir);
			echo ucfirst($dir)." Deleted.";
		} else {
			echo "Invalid Delete Commend";
		}
	endif;
	// exit execution
	PHP_EOL;
 ?>
