<?php

namespace StudentAdmission\Service;

class FileManagerService implements StudentAdmissionServiceInterface
{
	
	// The directory where we save research publication files.
	private $saveToDir = './data/newstudent/';
	
	// Returns path to the directory where we save the publication files.
	public function getSaveToDir()
	{
		return $this->saveToDir;
	}
	
	// Returns the array of uploaded file names.
	public function getSavedFiles()
	{
		// The directory where we plan to save uploaded files.
		
		// Check whether the directory already exists, and if not,
		// create the directory.
		if(!is_dir($this->saveToDir)) {
			if(!mkdir($this->saveToDir)) {
			throw new \Exception('Could not create directory for uploads: ' .
			error_get_last());
			}
		}
	
		// Scan the directory and create the list of uploaded files.
		$files = array();
		$handle = opendir($this->saveToDir);
		while (false !== ($entry = readdir($handle))) {
		
			if($entry=='.' || $entry=='..')
			continue; // Skip current dir and parent dir.
			
			$files[] = $entry;
		}
	
		// Return the list of uploaded files.
		return $files;
	}
	
	// Returns the path to the saved file.
	public function getFilePathByName($fileName)
	{
		// Take some precautions to make file name secure.
		str_replace("/", "", $fileName); // Remove slashes.
		str_replace("\\", "", $fileName); // Remove back-slashes.
		
		// Return concatenated directory name and file name.
		return $this->saveToDir . $fileName;
	}
	
	// Returns the file content. On error, returns boolean false.
	public function getFileContent($filePath)
	{
		return file_get_contents($filePath);
	}
	
	// Retrieves the file information (size, MIME type) by path.
	public function getFileInfo($filePath)
	{
		// Try to open file
		if (!is_readable($filePath)) {
		return false;
	}
	
	// Get file size in bytes.
	$fileSize = filesize($filePath);
	
	// Get MIME type of the file.
	$finfo = finfo_open(FILEINFO_MIME);
	$mimeType = finfo_file($finfo, $filePath);
	if($mimeType===false)
		$mimeType = 'application/octet-stream';
	
	return array(
		'size' => $fileSize,
		'type' => $mimeType
		);
	}
	
}