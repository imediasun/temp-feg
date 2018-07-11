<?php

namespace App\Http\Controllers;

use App\Models\PageCMSFile;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class PageCMSFileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PageCMSFile();
    }

    public function downloadFile($downloadKey)
    {
        if (!empty($downloadKey)) {
            $data = $this->model->where("file_unique_key", "=", $downloadKey)->first();
            if ($data) {
                if($this->model->isFileExists($data->filename)) {
                    $file = $this->model->getDownloadPath($data->filename);
                    $headers = array(
                        'Content-type: application/octet-stream',
                    );
                    return Response::download($file, $data->filename, $headers);
                }
                return "file doesn't exists";
            }
            return "file doesn't exists";
        } else {
            return "file doesn't exists";
        }
    }
}
