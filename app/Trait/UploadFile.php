<?php

namespace App\trait;

trait UploadFile
{
    public function Upload_image($request, $name, $folder_name)
    {
        $name_of_file = $request->file($name)->hashName();
        $request->file($name)->move($folder_name, $name_of_file);
        return $name_of_file;
    }
}
