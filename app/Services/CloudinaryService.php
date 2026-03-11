<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected static function cloudinary()
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_KEY'),
                'api_secret' => env('CLOUDINARY_SECRET')
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    // upload ảnh
    public static function upload($file, $folder = null)
    {
        $cloudinary = self::cloudinary();

        $upload = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder
            ]
        );

        return [
            'url' => $upload['secure_url'],
            'public_id' => $upload['public_id']
        ];
    }

    // xóa ảnh
    public static function destroy($publicId)
    {
        if (!$publicId) {
            return;
        }

        $cloudinary = self::cloudinary();

        return $cloudinary->uploadApi()->destroy($publicId);
    }
}
