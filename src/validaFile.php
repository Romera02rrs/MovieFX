<?php

class validaFile
{
    private $fileAr;
    private $maxSize;
    private $validTypes;
    private $path;

    public function __construct(
        array $fileAr,
        int $maxSize = (1024*1000),
        array $validTypes = ["image/jpeg", "image/jpg"],
        string $path = "posters")
    {
        $this->fileAr = $fileAr;
        $this->maxSize = $maxSize;
        $this->validTypes = $validTypes;
        $this->path = $path;
        valida($this->fileAr);
    }

    private function valida(array $fileAr){

        if (empty($fileAr["size"])){
            throw new NoUploadedFileException();
        }
        $fileName = $fileAr["name"];                                    // Thor.jpg
        $fileTmp= $fileAr["tmp_name"];                                  // C:\xampp\tmp\phpB37E.tmp
        $fileSize = $fileAr["size"];                                    // 51186    // bytes
        $fileErr = $fileAr["error"];                                    // 0
        $mimeType = getFileExtension($fileTmp);                         // image/jpeg
        // $mimeType = $fileAr["type"];                                 // image/jpeg
        $extension = explode("/", $mimeType)[1];              // jpeg
        $hashFilename = md5((string)rand()) . "." . $extension;         // 402c030133ac861998a20de2d56d81c1.jpeg
        $newFullFilename = Movie::PATH_POSTERS . "/" . $hashFilename;   // posters/new/d8f4a9ebb970e91153108dbf62d15bf7.jpeg

        $validTypes = ["image/jpeg", "image/jpg"];

        if (empty($fileAr)){
            throw new NoUploadedFileException();
        }
        if ($fileErr != UPLOAD_ERR_OK){
            throw new FileUploadException();
        }
        if (!in_array($mimeType, $validTypes)) {
            throw new InvalidTypeFileException();
        }
        if ($fileSize > MAX_SIZE) {
            throw new TooBigFileException();
        }
        if (!move_uploaded_file($fileTmp, $newFullFilename)) {
            throw new FileUploadException();
        }
        return $hashFilename;
    }
}