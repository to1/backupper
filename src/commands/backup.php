<?php

namespace to1\backupper\commands;

use Illuminate\Console\Command;
use to1\backupper\backupper;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
class backup extends Command {

    protected $signature = 'to1:backup {path=all} {--exclude=}';

    protected $description = 'Command description';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
	
		$path = $this->argument('path');
		$exclude = $this->option('exclude');
		$base = base_path();
    	if ($path !== "all")
    		$base = base_path($path);
		// Initialize archive object
		$zip = new ZipArchive();
		$zip->open($path.'_backup.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

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
				    // if($exclude == $directory)
					
					// foreach ($exclude as $key => $value) {
						
						if (strpos($file, $exclude) !== false) {
							// $answer = $this->ask($directory);
							continue;
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

					// }
		    }
		}
		$zip->close();
		
  		// $backup = new backupper();
  		// $backup->backup($path);
    }

}