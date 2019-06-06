<?php

namespace to1\backupper\commands;

use Illuminate\Console\Command;
use to1\backupper\backupper;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use DB;
use PDO;
use Storage;
class backup extends Command {

    protected $signature = 'to1:backup {path=all} {--database=}';

    protected $description = 'Back up you project files';

    public function __construct() {
        parent::__construct();
    }


    public function handle() {

			
		$path = $this->argument('path');
		$database = $this->option('database');
		$exclude = config('backupper.exclude');
		$pages = 1;
		if ($database && $database != 'only'){
			$pages++;
			$this->output->write("<info>Archiving files and exporting the database => </info>");
		}else
			$this->output->write("<info>Archiving files => </info>");

		$progressBar = $this->output->createProgressBar($pages);

		
        $progressBar->start();

		//Base path varibale : Change this if you wanna change the path of where to save the zip file
  		$base = base_path();
    	if ($path !== "all")
    		$base = base_path($path);

    	//If the database options are set we want to dump the datbase
		if ($database){
    		$this->dumpMySQL();
    		if( $database == 'only'){
    			// $this->output->write("<info>Exporting the database => </info>");
    			$progressBar->advance();
    			$progressBar->finish();
    			return;
    		}
 
		}
   
		// Initialize archive object
		$zip = new ZipArchive();
		$app_name = config('app.name');
		$zip->open($app_name.date("Y-m-d").'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
		    new RecursiveDirectoryIterator($base),
		    RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file)
		{
			$directory = basename(dirname($file));
			
			if (isset($exclude)) {
				$flag = false;
				foreach ($exclude as $key => $value) {
					if (strpos($file, $value) !== false) {
						$flag = true;
					}
				}
				if($flag)
					continue;
			}
	
		    // Skip directories (they would be added automatically)
		    if (!$file->isDir())
		    {
		        // Get real and relative path for current file
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($base) + 1);
				// $this->info($file);
		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }else{

		    }
		}


		$progressBar->advance();
		$progressBar->finish();
		$zip->close();
    }

	/**
	*	Function to dump the database 
	**/
    public function dumpMySQL(){
    	$mysqlDatabaseName =  env('DB_DATABASE', '');
		$mysqlUserName = env('DB_USERNAME', '');
		$mysqlPassword = env('DB_PASSWORD', '');
		$mysqlHostName = env('DB_HOST', '');
		$dir = base_path();

		try {
 			$backup_file = $mysqlDatabaseName . date("Y-m-d-H-i-s") . '.gz';
   			$command = "mysqldump -h ".$mysqlHostName." -u ".$mysqlUserName." -p ".$mysqlPassword." --quick ". "".$mysqlDatabaseName." | gzip > $backup_file";
			passthru( $command );
		}
		catch(Exception $Exception){
		}
	}

}