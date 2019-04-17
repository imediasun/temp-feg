<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageCMSFile extends Sximo
{
    protected $table = 'page_cms_files';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public function requestHasFile($request)
    {
        return $request->hasFile('upload_file');
    }

    public function uploadFile($request, $path = '')
    {
        $fileName = $this->storeFileinDirectory($request->file('upload_file'));
        $data = ['filename' => $fileName, 'file_behaviour' => $request->input('file_behaviour')];
        $id = $this->insertRow($data, 0);
        $data['downloadKey'] =$this->updateFileKey($id);
        $data['FileOpenUrl'] = $this->generateDownloadUrl("open",$data);
        $data['FileDownloadUrl'] = $this->generateDownloadUrl("download",$data);
        return $data;
    }

    public function updateFileKey($id)
    {
        $uploadedFile = $this->getInsertRecordObject($id);
        $fileUniqueKey = \SiteHelpers::encryptID($uploadedFile->id);
        $uploadedFile->file_unique_key = $fileUniqueKey;
        $uploadedFile->save();
        return $fileUniqueKey;
    }

    public function storeFileinDirectory($file, $path = 'pageCmsFiles')
    {
        $filePath = public_path('upload/' . $path);
        $name = mt_rand() . '_' . $file->getClientOriginalName();
        $file->move($filePath, $name);
        return $name;
    }
    public function generateDownloadUrl($urlType,$fileData = array()){
        if($urlType == 'open'){
            return Url('upload/pageCmsFiles/'.$fileData['filename']);

        }
        if($urlType == 'download'){
            return Url('/pagecmsfile/file/'.$fileData['downloadKey']);
        }
    }
    public function isFileExists($filename){
        return file_exists($file = public_path() . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . "pageCmsFiles" . DIRECTORY_SEPARATOR . $filename);
    }
    public function getDownloadPath($filename){

        return $file = public_path() . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . "pageCmsFiles" . DIRECTORY_SEPARATOR . $filename;
    }
}
