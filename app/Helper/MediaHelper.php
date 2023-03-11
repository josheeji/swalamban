<?php

namespace App\Helper;

use App\Models\AdminType;
use Illuminate\Support\Facades\Storage;
use Image;

class MediaHelper
{
    /**
     * THUMBNAIL SIZE
     */
    const THUMB_QUALITY = 100;
    const THUMB_WIDTH = 800;
    const THUMB_HEIGHT = 700;

    public static function upload($file, $directory = '', $thumbnail = true, $aspectRatio = false)
    {
        ini_set('memory_limit', '256M');
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $fileNameToStore = self::rename($filename, $file);
        $location = self::location($directory);
        $file->storeAs($location['path'], $fileNameToStore,  ['disk' => self::storageDisk()]);
        if ($thumbnail == true) {
            $photo = Image::make($file);
            $thumbnailSize = self::thumbSize($directory);
            if ($aspectRatio != true) {
                $photo->fit($thumbnailSize['width'], $thumbnailSize['height'], function ($constraint) {
                    // $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $photo->resize($thumbnailSize['width'], $thumbnailSize['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            $photo->encode(self::fileExtension($file), self::THUMB_QUALITY);
            Storage::disk(self::storageDisk())->put($location['thumbPath'] . '/' . $fileNameToStore, $photo);
        }
        $location['filename'] = $fileNameToStore;
        $location['storage'] = $location['path'] . '/' . $fileNameToStore;
        return $location;
    }

    public static function uploadDocument($file, $directory, $filename)
    {
        ini_set('memory_limit', '256M');
        $location = self::location($directory);
        $file->storeAs($location['path'], $filename, ['disk' => self::storageDisk()]);
        return $location['path'] . '/' . $filename;;
    }

    protected static function storageDisk()
    {
        return 'public';
    }

    protected static function location($directory = '')
    {
        $result = [];
        $location = $directory . '/' . date('Y') . '/' . date('m');
        $result['path'] = $location;
        $result['thumbPath'] = 'thumbs/' . $location;
        return $result;
    }

    protected static function rename($filename, $file)
    {
        $extension = self::fileExtension($file);
        return Helper::slug($filename) . '_' . time() . '.' . $extension;
    }

    protected static function fileExtension($file)
    {
        $extension = $file->getClientOriginalExtension();
        return strtolower($extension);
    }

    public static function destroy($file)
    {
        Storage::disk(self::storageDisk())->delete($file);
        Storage::disk(self::storageDisk())->delete('thumbs/' . $file);
    }

    public static function thumbSize($page)
    {
        switch ($page) {
            case 'blog':
                $size['width'] = 800;
                $size['height'] = 700;
                break;
            default:
                $size['width'] = self::THUMB_WIDTH;
                $size['height'] = self::THUMB_HEIGHT;
                break;
        }
        return $size;
    }
}