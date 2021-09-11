<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Upload;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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

    public function download($id) {
        $upload = Upload::findOrFail($id);

        Storage::disk('public')->delete('download-'.$upload->id.'.zip');

        $zip = new ZipArchive();

        $upload_path = Storage::disk('public')->path($upload->id . '.zip');

        $zip->open($upload_path);

        $zip->extractTo(storage_path('app/public/'.$upload->id));

        $files = File::where('upload_id', $upload->id)->where('status', 1)->get();

        $download = new ZipArchive;
        if ($download->open(storage_path().'/app/public/download-'.$upload->id.'.zip', ZipArchive::CREATE) === TRUE)
        {

            foreach($files as $file) {
                $download->addFile(Storage::disk('public')->path($file->file_name), $file->file_name);
            }

            // All files are added, so close the zip file.
            $download->close();
        }

        Storage::disk('public')->deleteDirectory($upload->id);

        return response()->json('/storage/download-'.$upload->id.'.zip', 200);
    }

}
