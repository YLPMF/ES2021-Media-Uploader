<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UploadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     */
    private function getClientIP(){
        // Gets real user IP address
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER["REMOTE_ADDR"];
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }

        return '';
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|alpha|min:2|max:2',
            'archive' => 'required|file|mimes:zip'
        ]);

        $upload = Upload::create([
            'country_code' => $request->country_code,
            'ip_address' => $this->getClientIP(),
            'archive_name' => $request->file('archive')->getClientOriginalName()
        ]);

        $zip = new ZipArchive();
        $file = $request->file('archive');
        Storage::disk('public')->putFileAs('', $file, $upload->id.'.zip');
        $zip->open($file->path());

        $zip->extractTo(storage_path('app/public/'.$upload->id));

        $files = Storage::disk('public')->allFiles('/'.$upload->id);

        $validatedFiles = [];

        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type

        foreach($files as $file) {
            if(substr($file, -4) == '.mp3' && finfo_file($finfo, storage_path('app/public/').$file) == 'audio/mpeg') {
                $validatedFiles[] = $file;
                File::create([
                    'upload_id' => $upload->id,
                    'file_name' => $file,
                    'status' => 1,
                ]);
            }else {
                File::create([
                    'upload_id' => $upload->id,
                    'file_name' => $file,
                    'status' => 0,
                ]);
            }
        }

        // Delete unarchived files after verification
        Storage::disk('public')->deleteDirectory($upload->id);

        return $validatedFiles;
    }

}
