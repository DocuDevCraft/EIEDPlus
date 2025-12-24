<?php

namespace Modules\FileLibrary\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\FileLibrary\Entities\FileLibrary;

class FileLibraryController extends Controller
{
    private static array $allowedFiles = [
        'jpg', 'jpeg', 'png', 'webp', 'svg',
        'pdf', 'doc', 'docx',
        'xls', 'xlsx'
    ];

    /* Upload Function */
    public static function upload($request, $file_type, $folder_name, $used, $sizes = null)
    {
        if ($attachment_path = $request) {

            $User_id = auth()->check() ? auth()->user()->id : 0;
            $attachments_id = [];

            if ($file_type == 'image') {

                File::makeDirectory(
                    storage_path() . '/app/public/' . $folder_name . '/full',
                    0755,
                    true,
                    true
                );

                if ($path = $attachment_path->store('public/' . $folder_name . '/full')) {

                    $Attachments = new FileLibrary();

                    // فقط users_id فورس شد
                    $Attachments->forceFill([
                        'users_id' => $User_id
                    ]);

                    $Attachments->used = $used;
                    $Attachments->org_name = $attachment_path->getClientOriginalName();
                    $Attachments->file_name = pathinfo($path)['filename'] . '.' . pathinfo($path)['extension'];
                    $Attachments->path = $folder_name . '/';
                    $Attachments->extension = $attachment_path->extension();
                    $Attachments->file_type = $file_type;

                    if (
                        $Attachments->extension != 'svg' &&
                        $Attachments->extension != 'gif' &&
                        $file_type == 'image'
                    ) {

                        $img = Image::make(
                            storage_path('app/public/' . $folder_name . '/full/' . $Attachments->file_name)
                        );

                        $img->backup();

                        foreach ($sizes as $size) {
                            $img->reset();

                            if ($size[2] == 'crop') {
                                $img->crop($size[0], $size[1]);
                                File::makeDirectory(
                                    storage_path() . '/app/public/' . $folder_name . '/' . $size[0],
                                    0755,
                                    true,
                                    true
                                );
                            } elseif ($size[2] == 'fit') {
                                $img->fit($size[0], $size[1], function ($constraint) {
                                    $constraint->upsize();
                                }, 'top');
                                File::makeDirectory(
                                    storage_path() . '/app/public/' . $folder_name . '/' . $size[0],
                                    0755,
                                    true,
                                    true
                                );
                            } elseif ($size[2] == 'resize') {
                                $img->resize($size[0], $size[1], function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                File::makeDirectory(
                                    storage_path() . '/app/public/' . $folder_name . '/' . $size[0],
                                    0755,
                                    true,
                                    true
                                );
                            }

                            $img->save(
                                storage_path(
                                    'app/public/' . $folder_name . '/' . $size[0] . '/' .
                                    pathinfo($path)['filename'] . '.' . pathinfo($path)['extension']
                                ),
                                100
                            );
                        }
                    }

                    if ($Attachments->save()) {
                        $attachments_id[] = $Attachments->id;
                    }
                }

            } elseif ($file_type == 'file') {

                File::makeDirectory(
                    storage_path() . '/app/public/' . $folder_name,
                    0755,
                    true,
                    true
                );

                $extension = strtolower($attachment_path->getClientOriginalExtension());
                $mime = $attachment_path->getMimeType();

                $allowedMimes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/svg+xml',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];

                if (
                    !in_array($extension, self::$allowedFiles) ||
                    !in_array($mime, $allowedMimes)
                ) {
                    abort(403, 'فرمت فایل مجاز نیست');
                }

                if ($attachment_path->getSize() > 50 * 1024 * 1024) {
                    abort(403, 'حجم فایل بیش از ۵۰ مگابایت است');
                }

                if ($path = $attachment_path->store('public/' . $folder_name)) {

                    $Attachments = new FileLibrary();

                    // فقط users_id فورس شد
                    $Attachments->forceFill([
                        'users_id' => $User_id
                    ]);

                    $Attachments->used = $used;
                    $Attachments->org_name = $attachment_path->getClientOriginalName();
                    $Attachments->file_name = pathinfo($path)['filename'] . '.' . pathinfo($path)['extension'];
                    $Attachments->path = $folder_name . '/';
                    $Attachments->extension = $attachment_path->extension();
                    $Attachments->file_type = $file_type;

                    if ($Attachments->save()) {
                        $attachments_id[] = $Attachments->id;
                    }
                }
            }

            return end($attachments_id);
        }
    }
}
