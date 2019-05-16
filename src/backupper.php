<?php
namespace to1\backupper;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
class backupper
{
    // Build wonderful things

    public function backup($path){
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

		    // Skip directories (they would be added automatically)
		    if (!$file->isDir())
		    {
		        // Get real and relative path for current file
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($base) + 1);

		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }
		}
		$zip->close();
    }
}