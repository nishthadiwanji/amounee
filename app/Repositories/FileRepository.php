<?php

namespace App\Repositories;

use App\Repositories\FileStorageRepository;
use App\Models\FileInfo\FileInfo;
use ImageInt;

class FileRepository
{
	protected $fileStorageRepo;
	protected $fileinfo;

	public function __construct(FileStorageRepository $fileStorageRepo, FileInfo $fileinfo)
	{
		$this->fileStorageRepo = $fileStorageRepo;
		$this->fileinfo = $fileinfo;
		$this->fileStorageRepo->setStorageDisk();
	}
	/*
	* Set Disk Instance
	*/
	private function setStorageDisk($disk)
	{
		return $this->fileStorageRepo->setStorageDisk($disk);
	}

	/*
	* Prepare file attributes for saving file into file_info table
	*/
	private function prepareFileAttributes($file)
	{
		if(get_class($file) == \Symfony\Component\HttpFoundation\File\File::class){
			$extension = strtolower($file->getExtension());
			return [
				'extension'	=> $extension,
				'size'	=> $file->getSize(),
				'type'	=> $file->getMimeType(),
				'name'	=> time().rand().'.'.$extension
			];
		}
		else{
			$extension = strtolower($file->getClientOriginalExtension());
			return [
				'extension'	=> $extension,
				'size'	=> $file->getClientSize(),
				'type'	=> $file->getClientMimeType(),
				'name'	=> time().rand().'.'.$extension
			];

		}
	}

	/*
	* Generate thumbnails for given image
	*/
	private function generateThumbnail($file, $width, $height)
	{
		return ImageInt::make($file)
            	->resize($width, $height)
            	->stream();
	}

	/*
	* Generate and store thumbnail on specified disk with given height and width
	*/
	private function storeThumbnail(FileInfo $fileInfo, $file, $dir, $width, $height)
	{
		$filePath = $dir."/thumbnails"."/".$fileInfo->name;
        $thumbnailImg = $this->generateThumbnail($file, $width, $height);
        return $this->fileStorageRepo->uploadStreamFile($filePath, $thumbnailImg->__toString());
	}
	/*
	* Upload file and preprare attributes of fileinfo array
	*/
	public function uploadFileOnStorage($dir, $file, $disk)
	{
		$this->setStorageDisk($disk);
		$fileAttributes = $this->prepareFileAttributes($file);
		$filePath = $dir.'/'.$fileAttributes['name'];
		$path = $this->fileStorageRepo->uploadFile($filePath, $file);
		if ($path) {
			$fileAttributes['file_url'] = $path;
		}
		return $fileAttributes;
	}
	/*
	* Remove file from storage disk
	*/
	private function removeFileFromStorage(FileInfo $fileInfo)
	{	
		if (!is_null($fileInfo->file_url)) {
			$result = $this->fileStorageRepo->removeFile($fileInfo->file_url);
			if (is_null($fileInfo->thumbnail_url)) {
				return $result;
			}
		}
		if (!is_null($fileInfo->thumbnail_url)) {
			return $this->fileStorageRepo->removeFile($fileInfo->thumbnail_url);
		}
		return;
	}
	/*
	* Upload file (not image if need to generate thumbnail) and store file info into database record
	*/
	public function uploadAndCreateFile($dir, $file, $disk)
	{
		$fileAttributes = $this->uploadFileOnStorage($dir, $file, $disk);
		return $this->fileinfo->create($fileAttributes);
	}
	/*
	* Upload image file and store file info into database record
	*/
	public function uploadAndCreateImageFile($dir, $file, $disk, $generateThumbnail = false, $width = 300, $height = 300)
	{
		$fileInfo = $this->uploadAndCreateFile($dir, $file, $disk);
		if (!$generateThumbnail) {
			return $fileInfo;
		}
		if ($fileInfo) {
            $result = $this->storeThumbnail($fileInfo, $file, $dir, $width, $height);
            if ($result) {
            	$filePath = $dir."/thumbnails"."/".$fileInfo->name;
            	$fileInfo->update([
            		'thumbnail_url' => $filePath
            	]);
            }
		}
		return $fileInfo->refresh();
	}
	/*
	* Remove old file from storage, upload new file and update file info record
	*/
	public function uploadAndUpdateFile(FileInfo $fileInfo, $dir, $file, $disk)
	{
		$this->setStorageDisk($disk);
		$this->removeFileFromStorage($fileInfo);
		$fileAttributes = $this->uploadFileOnStorage($dir, $file, $disk);
		return $fileInfo->update($fileAttributes);
	}
	/*
	* Remove old image from storage, upload new image and update file info record
	*/
	public function uploadAndUpdateImageFile(FileInfo $fileInfo, $dir, $file, $disk, $generateThumbnail = false, $width = 300, $height = 300)
	{
		$result = $this->uploadAndUpdateFile($fileInfo, $dir, $file, $disk);
		if (!$result) {
			return $result;
		}
		$fileInfo = $fileInfo->refresh();
		$path = $this->storeThumbnail($fileInfo, $file, $dir, $width, $height);
        if ($path) {
        	$filePath = $dir."/thumbnails"."/".$fileInfo->name;
            $fileInfo->update([
            	'thumbnail_url' => $filePath
            ]);
        }
        return $fileInfo->refresh();
	}
	/*
	* To remove file info along with file from storage
	*/
	public function removeFileWithFileInfo(FileInfo $fileInfo, $disk)
	{
		$this->setStorageDisk($disk);
		$this->removeFileFromStorage($fileInfo);
		return $fileInfo->forceDelete();
	}
}
