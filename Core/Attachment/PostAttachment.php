<?php

namespace App\Attachment;

use Intervention\Image\ImageManager;

use App\Helper\Image;

class PostAttachment
{
    public static function UploadImage($image, $fileDestination = '')
    {
        $filePath = $image['tmp_name'];
        $fileExt = Image::getImageExt($filePath);

        $file_directory =  UPLOADS_PATH . DIRECTORY_SEPARATOR . 'post';

        if (!is_dir($file_directory)) {
            mkdir($file_directory, 0777, true);
        }

        $fileName =  uniqid() . '.' . $fileExt;
        $path = $file_directory . DIRECTORY_SEPARATOR . $fileName;

        $manager = new ImageManager(array('driver' => 'imagick'));
        $manager->make($filePath)->fit(500, 500)->save($path);
        return $fileName;
    }
    public static function deleteImage($image)
    {
        if ($image === 'cat-01.jpg') {
            return false;
        }
        $fullPath = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR . $image;

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }
        return false;
    }
}
