<?php 
error_reporting(E_ALL);
ini_set('display_errors', true);
Route::get('test', function () {
	// Base path 
   $base =  base_path('config');
	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open($base.'\backup.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

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

});
