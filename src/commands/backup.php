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

    protected $signature = 'to1:backup {path=all} {--database}  {--exclude=vendor}';

    protected $description = 'Command description';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
			
		$path = $this->argument('path');
		$exclude = $this->option('exclude');
		$database = $this->option('database');

  		$base = base_path();
    	if ($path !== "all")
    		$base = base_path($path);

		if ($database){
    		$success = $this->dumpMySQL();
    		$this->info("Database was dumped successfully");
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
			// $folder_name = basename($file)
			// $answer = $this->ask($folder_name);
		    // if($folder_name == 'vendor')
			$directory = basename(dirname($file));

		if (isset($exclude)) {
				if (strpos($file, $exclude) !== false) {
					// $answer = $this->ask($directory);
						continue;
					}
		}
	
		    // Skip directories (they would be added automatically)
		    if (!$file->isDir())
		    {
		        // Get real and relative path for current file
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($base) + 1);
				$this->info($file);
		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }else{

		    }
		}
		$zip->close();
    }

    public function dumpMySQL(){
    	$mysqlDatabaseName =  env('DB_DATABASE', 'test');
		$mysqlUserName = env('DB_USERNAME', '');
		$mysqlPassword = env('DB_PASSWORD', '');
		$mysqlHostName = env('DB_HOST', '');
		$dir = base_path();
		$table_name = "users";
	    $mysqlExportPath =base_path('storage')."\users.sql";
		// DB::statement("SELECT * INTO OUTFILE '".addslashes($mysqlExportPath)."' FROM users");


		try {
 			$backup_file = $mysqlDatabaseName . date("Y-m-d-H-i-s") . '.gz';
   			$command = "mysqldump --opt -h ".$mysqlHostName." -u ".$mysqlUserName." -p ".$mysqlPassword." ". "".$mysqlDatabaseName." | gzip > $backup_file";
			passthru( $command );
		}
		catch(Exception $Exception){
		}
	}

}