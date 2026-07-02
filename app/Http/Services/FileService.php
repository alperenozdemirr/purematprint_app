<?php
namespace App\Http\Services;

use App\Enums\ContentType;
use App\Enums\FileType;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class FileService
{
    protected const fileDirectory = "shared_directory/files/users";
    protected const userImageDirectory = "shared_directory/images/users";
    protected const productDirectory = "shared_directory/images/products";
    protected const otherDirectory = "shared_directory/images/other";
    //protected const categoryIconDirectory = "shared_directory/image/category";
    protected const addToDatabase = true;


    public function imageUpload($file, $contentType = null,$keyId = null,$number = null)
    {
        $file_type = FileType::IMAGE;
        $baseDirectory = null;
        $user = null;
        if ($contentType == null || $contentType == ContentType::USER){
            $baseDirectory = self::userImageDirectory;
            $contentType = ContentType::USER;
            $user = Auth::user()->id;
        }elseif ($contentType == ContentType::OTHER){
            $baseDirectory = self::otherDirectory;
            $contentType = ContentType::OTHER;
        }elseif ($contentType == ContentType::PRODUCT){
            $baseDirectory = self::productDirectory;
            $contentType = ContentType::PRODUCT;
        }

        $fileName = $file->hashName();

        if ($baseDirectory === null) {
            throw new \InvalidArgumentException('Geçersiz içerik tipi veya dosya yolu.');
        }

        $targetDir = public_path($baseDirectory);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $fileName);
        if (self::addToDatabase){
            return $this->storeFile($fileName,$file_type,$contentType,$user,$keyId, $number);
        }
        return false;
    }

    protected function storeFile($fileName,$file_type,$content_type,$user = null, $keyId = null,$number = null)
    {
        $fileRecord = File::create([
            'file_name' => $fileName,
            'user_id' => $user,
            'key_id' => $keyId,
            'file_type' => $file_type,
            'content_type' =>$content_type,
            'number' =>$number,
        ]);

        return $fileRecord;
    }

    public function imageDelete($imageId, $contentType = null)
    {
        $baseDirectory = null;
        if ($contentType == null || $contentType == ContentType::USER){
            $baseDirectory = self::userImageDirectory;
        }elseif ($contentType == ContentType::OTHER){
            $baseDirectory = self::otherDirectory;
        }elseif ($contentType == ContentType::PRODUCT){
            $baseDirectory = self::productDirectory;
        }
        $deleteItem = File::find($imageId);
        if ($deleteItem && $baseDirectory !== null){
            $fileFullPath = public_path($baseDirectory . '/' . $deleteItem->file_name);
            $deleteItem->delete();
            unlink($fileFullPath);
            return true;
        }else return false;
    }
}
