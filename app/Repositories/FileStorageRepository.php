<?php
namespace App\Repositories;

use Storage;

class FileStorageRepository
{
	protected $storageDisk;
	/*
	* To set storage disk
	*/
	public function setStorageDisk(string $disk = 'local')
	{
		$this->storageDisk = $disk;
	}
	/*
	* To get storage disk
	*/
	public function getStorageDisk()
	{
		return $this->storageDisk;
	}
	/*
	* To upload file on specified path to storage disk
	* return path along with filename
	* $filePath need file name along with it
	*/
	public function uploadFile($filePath, $file)
	{
		return Storage::disk($this->getStorageDisk())->putFileAs('', $file, $filePath);
	}

	public function uploadStreamFile($filePath, $file)
	{
		return Storage::disk($this->getStorageDisk())->put($filePath, $file);
	}
	/*
	* To upload file on specified path to storage disk
	* $filePath need file name along with it
	*/
	public function removeFile($filePath)
	{
		if ($this->isFileExists($filePath)) {
			return Storage::disk($this->getStorageDisk())->delete($filePath);	
		}
		return;
	}
	/*
	* To get file from storage disk
	*/
	public function getFile($filePath)
	{
		return Storage::disk($this->getStorageDisk())->get($filePath);
	}

	/*
	* To get all files from storage disk
	*/
	public function getAllFiles($directory)
	{
		return Storage::disk($this->getStorageDisk())->files($directory);
	}

	/*
	* To check file exists or not on storage side
	*/
	public function isFileExists($filePath)
	{
		return Storage::disk($this->getStorageDisk())->exists($filePath);
	}

	/*
	* To download file from specified disk
	*/
	public function downloadFile($filePath, $fileName = 'download')
	{
        $mime = Storage::disk($this->getStorageDisk())->getDriver()->getMimetype($filePath);
        $size = Storage::disk($this->getStorageDisk())->getDriver()->getSize($filePath);
        $response =  [
        	'Content-Type' => $mime,
          	'Content-Length' => $size,
          	'Content-Description' => 'File Transfer',
          	'Content-Disposition' => "attachment; filename={$fileName}",
          	'Content-Transfer-Encoding' => 'binary',
        ];
        ob_end_clean();
        return \Response::make($this->getFile($filePath), 200, $response);
	}

}