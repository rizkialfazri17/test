<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BaseController;

class ImageHandler extends BaseController
{
    public function uploadImage()
    {
        if ($this->request->getFile('file')) {
            $dataFile = $this->request->getFile('file');
            $fileName = $dataFile->getRandomName();
            $dataFile->move(FCPATH . '/uploads/', $fileName);

            return base_url('/uploads/' . $fileName);
        }
    }

    public function deleteImage()
    {
        $src = $this->request->getVar('src');

        if ($src) {
            $file_name = str_replace(base_url(), "", $src);
            if (unlink($file_name)) {
                return "Successfuly has delete image!";
            }
        }
    }
}
