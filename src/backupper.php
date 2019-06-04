<?php
namespace to1\backupper;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
class backupper
{
    // Build wonderful things

    public function backup($path,$exclude,$database){
	
		$base = base_path();
    	if ($path !== "all")
    		$base = base_path($path);

		if ($database){
			 		$this->dumpMySQL();
    		$this->ask("test");
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
				parent::info($file);
		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }else{

					// }
		    }
		}
		$zip->close();
    }
}