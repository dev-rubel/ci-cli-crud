#!/usr/bin/php -q

<?php 
/**
 * READ ME
 * One commend can create
 * 1. Create Controller with given name
 * 2. Create Model with given name
 * 3. Create View folder and basic necessary file with given name
 * 4. Create Assets folder and basic necessary file with given name
 * 5. Create Table with primary key
 * 6. Remove CRUD
 * 7. Remove Controller
 * 8. Remove Model
 * 
 * !! CAUTION !!
 * I use it for my personal project which is created by my own style. So not every one can use this.
 * 
  */
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
	$ex_table	= '_table';
	$ex_key		= 'id';
	$view_files 	= ['index.php','add.php','modify.php'];
	$asset_files 	= ['assets.ini','style.css','script.js'];	
	
	// database connection and query execution
	function tryCatch($query, $type = 'query') {
		// database var
		$db_host = '127.0.0.1';
		$db_db   = 'shebalagbe';
		$db_user = 'root';
		$db_pass = '';
		// database connection (PDO)
		$db_dsn = "mysql:host=$db_host;dbname=$db_db";
		$db_options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$pdo = new PDO($db_dsn, $db_user, $db_pass, $db_options);
		} catch (\PDOException $e) {
			throw new \PDOException($e->getMessage(), (int)$e->getCode());
			die(); 
			PHP_EOL; // end execution 
		}
		// end database connection
		try {
			return $pdo->$type($query);
		} catch(\PDOException $e) {
			throw new \PDOException($e->getMessage(), (int)$e->getCode());
			die();
			PHP_EOL; // end execution 
		}
	}
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
		
	// regular CRUD commend
	// commend example -- php cli.php crud (name_of_module) (table_name-t(t for create also table)) (primary_key(optional))
	if($argv[1] == 'crud'):
		// var section
		$dir 			= $argv[2].'/';
		$name 			= $argv[2];
		$file 			= $argv[2].'.php';
		$table 			= $argv[3];
		$primary_key	= !empty($argv[4])?$argv[4]:'';

		// if set to create database table
		$checkTBL = explode('-',$table);
		if(!empty($checkTBL[1])) {			
			$table_name = $checkTBL[0];
			// if set primary key
			$tbl_primary = !empty($primary_key) ? $primary_key : $table_name.'_id';
			// create table query
			$create_table_query = 'CREATE TABLE '.$table_name.'(
					'.$tbl_primary.' INT NOT NULL AUTO_INCREMENT,
					isActive TINYINT(1) NOT NULL DEFAULT 1,
					created DATETIME NOT NULL,
					modified DATETIME NOT NULL,
					PRIMARY KEY ( '.$tbl_primary.'));';
			// execute query
			tryCatch($create_table_query, 'exec');
			// set table name as var table
			$table = $checkTBL[0];
		}
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
		if (!file_exists($model.$name.'_model.php')) {
			if(copy($model.$ex_file, $model.ucfirst($name).'_model.php')) {
				$file_content = file_get_contents($model. ucfirst($name).'_model.php');
				// file
				$file_content = str_replace($ex_ex, $name, $file_content);
				$file_content = str_replace($ex_up_ex, ucfirst($name), $file_content);
				// table			
				$file_content = str_replace($ex_table, $table, $file_content);
				// primary key (if given)
				if(!empty($primary_key)) {
					$file_content = str_replace($ex_key, $primary_key, $file_content);
				}
				file_put_contents($model. ucfirst($name).'_model.php', $file_content);
				echo ucfirst($name)." Model Created \n";
			} else {
				echo ucfirst($name)." Model File Copy Error!! \n";
			}
		} else {
			echo ucfirst($name)." Model Already Created \n";
		}
		// view section
		if (!file_exists($view.$name)) {
			if(mkdir($view.$name, 0777, true)) {
				foreach ($view_files as $key => $data) {
					copy($view.$ex_dir.$data, $view.$dir.$data);
						$file_content = file_get_contents($view.$dir.$data);
						$file_content = str_replace($ex_ex, $name, $file_content);
						$file_content = str_replace($ex_up_ex, ucfirst($name), $file_content);
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
	// end regular CURD section
	// model commned
	elseif($argv[1] == 'model'):
		// var section
		$dir 			= $argv[2].'/';
		$name 			= $argv[2];
		$file 			= $argv[2].'.php';
		$table 			= $argv[3];
		$primary_key	= !empty($argv[4])?$argv[4]:'';
		// model section
		if (!file_exists($model.$name.'_model.php')) {
			if(copy($model.$ex_file, $model.ucfirst($name).'_model.php')) {
				$file_content = file_get_contents($model. ucfirst($name).'_model.php');
				// file
				$file_content = str_replace($ex_ex, $name, $file_content);
				$file_content = str_replace($ex_up_ex, ucfirst($name), $file_content);
				// table			
				$file_content = str_replace($ex_table, $table, $file_content);
				// primary key (if given)
				if(!empty($primary_key)) {
					$file_content = str_replace($ex_key, $primary_key, $file_content);
				}
				file_put_contents($model. ucfirst($name).'_model.php', $file_content);
				echo ucfirst($name)." Model Created \n";
			} else {
				echo ucfirst($name)." Model File Copy Error!! \n";
			}
		} else {
			echo ucfirst($name)." Model Already Created \n";
		}
	// controller commend
	elseif($argv[1] == 'controller'):
		// var section
		$dir 			= $argv[2].'/';
		$name 			= $argv[2];
		$file 			= $argv[2].'.php';
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
	// delete commend
	elseif($argv[1] == 'delete'): 
		// if select delete commend
		if(!empty($argv[3])):			
			$name 			= $argv[3];
			$file 			= $argv[3].'.php';
			if($argv[2] == 'crud'):
				if(!empty($argv[2])) {
					rmrf($controller.ucfirst($file));
					rmrf($model.ucfirst($name).'_model.php');
					rmrf($view.$name);
					rmrf($asset.$name);
					echo ucfirst($name)." CRUD deleted.";					
				} else {
					echo "Invalid CRUD delete commend";
				}	
			elseif($argv[2] == 'model'):
				if(!empty($argv[2])) {
					rmrf($model.ucfirst($name).'_model.php');
					echo ucfirst($name)." model deleted.";
				} else {
					echo "Invalid model delete commend";
				}	
			elseif($argv[2] == 'controller'):
				if(!empty($argv[2])) {
					rmrf($controller.ucfirst($file));
					echo ucfirst($name)." controller deleted.";
				} else {
					echo "Invalid controller delete commend";
				}	
			else:
				echo "Invalid delete commend";
			endif;	
		else:			
			echo "Missing 3rd parameter !!";
		endif;
	// no valid commend found
	else:
		echo 'Invalid Commend !!!';
	endif;
	// exit execution
	PHP_EOL;
 ?>
