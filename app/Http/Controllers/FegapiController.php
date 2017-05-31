<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Restapi;
use App\Models\Sximo;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use Validator, Input, Redirect;

class FegapiController extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        $class = ucwords(Input::get('module'));
        if (!empty($class)) {
            if ($class == "Users") {
                $class1 = "App\\Models\\core\\" . $class;
            }
            else if($class == "Vendor"){
                $class1 = "App\\Models\\VendorAPI";
            }
            else {
                $class1 = "App\\Models\\" . $class;
            }
            $config = $class1::makeInfo($class);
            $tables = $config['config']['grid'];
            $page = (!is_null(Input::get('page')) or Input::get('page') != 0) ? Input::get('page') : 1;
            $param = array('page' => $page, 'sort' => '', 'order' => 'asc', 'limit' => '', 'createdFrom' => '', 'createdTo' => date('Y-m-d H:i:s'),
                'updatedFrom' => '', 'updatedTo' => date('Y-m-d H:i:s'), 'order_type_id' => '', 'status_id' => '', 'prod_type_id' => '', 'vendor_id' => '', 'active'=>'');
            $limit = Input::get('limit');
            $sort = Input::get('order');
            $order = Input::get('sort');

            $createdFrom = Input::get('created_from');
            $createdTo = Input::get('created_to');
            $updatedFrom = Input::get('updated_from');
            $updatedTo = Input::get('updated_to');


            $order_type_id = Input::get('order_type_id');
            $status_id = Input::get('status_id');
            $prod_type_id = Input::get('prod_type_id');
            $vendor_id = Input::get('vendor_id');
            $active = Input::get('active');


            if (!is_null($limit) or $limit != 0) $param['limit'] = $limit;
            if (is_null($limit)) $param['limit'] = 500;
            if (!is_null($order)) $param['order'] = $order;
            if (!is_null($sort)) $param['sort'] = $sort;

            if (!is_null($createdFrom)) $param['createdFrom'] = $createdFrom;
            if (!is_null($createdTo)) $param['createdTo'] = $createdTo;
            if (!is_null($updatedFrom)) $param['updatedFrom'] = $updatedFrom;
            if (!is_null($updatedTo)) $param['updatedTo'] = $updatedTo;

            if (!is_null($order_type_id)) $param['order_type_id'] = $order_type_id;
            if (!is_null($status_id)) $param['status_id'] = $status_id;
            if (!is_null($prod_type_id)) $param['prod_type_id'] = $prod_type_id;
            if (!is_null($vendor_id)) $param['vendor_id'] = $vendor_id;
            if (!is_null($active)) $param['active'] = $active;

            if($class != 'Order' && $class != "Itemreceipt")
            {
                $results = $class1::getRows($param);
            }
            else
            {
                $results = $class1::getRows($param , 'only_api_visible');
            }
            $json = array();
            //condition necessary to show additional fields in api response
            if ($class == "Itemreceipt") {
                $json = $results['rows'];
            } else {
                foreach ($results['rows'] as $row) {
                    $rows = array();
                    foreach ($tables as $table) {
                        $conn = (isset($table['conn']) ? $table['conn'] : array());
                        if(isset($row->$table['field'])){
                            $rows[$table['field']] = $row->$table['field'];
                        }
                    }
                    $json[] = $rows;
                }
            }


            $json = $class1::processApiData($json, $param);

            $jsonData = array(
                'total' => $results['total'],
                'records' => count($json),
                'rows' => $json,
                'control' => $param,
                'key' => $config['key']
            );
            $option = Input::get('option');
            if (!is_null($option) && $option == 'true') {
                $label = array();
                foreach ($tables as $table) {
                    $label[] = $table['label'];
                }
                $field = array();
                foreach ($tables as $table) {
                    $field[] = $table['field'];
                }

                $jsonData['option'] = array(
                    'label' => $label,
                    'field' => $field
                );
            }

            return \Response::json($jsonData, 200);
        } else {
            return \Response::json(array('Status' => 'Error', 'Message' => \Lang::get('restapi.EmptyModule')));
        }
    }

    public function show($id)
    {
        $class = ucwords(Input::get('module'));
        if ($class == "Users") {
            $class1 = "App\\Models\\core\\" . $class;
        } else {
            $class1 = "App\\Models\\" . $class;
        }
        $config = $class1::makeInfo($class);
        $tables = $config['config']['grid'];
        $jsonData = $class1::getRow($id);
        if (!empty($jsonData)) {
            return \Response::json($jsonData, 200);
        } else {
            return \Response::json(array('Status' => \Lang::get('restapi.StatusError'), "Message" => \Lang::get('restapi.NothingFound')));
        }
    }

    public function show_by_status($status)
    {
        $class = ucwords(Input::get('module'));
        if ($class == "Users") {
            $class1 = "App\\Models\\core\\" . $class;
        } else {
            $class1 = "App\\Models\\" . $class;
        }
        $config = $class1::makeInfo($class);
        $tables = $config['config']['grid'];
        $jsonData = $class1::getRowStatus($status);
        if (!empty($jsonData)) {
            return \Response::json($jsonData, 200);
        } else {
            return \Response::json(array('Status' => \Lang::get('restapi.StatusError'), "Message" => \Lang::get('restapi.NothingFound')));
        }
    }
    /*
    public function store()
    {

        $class = ucwords(Input::get('module'));

        $class1 = "App\\Models\\" . $class;
        $obj = new $class1();
        $this->info = $class1::makeInfo($class);

        $data = $this->info['table'];

        $data = $this->validatePost($this->info['table']);

        unset($data['entry_by']);
        $id = $obj->insertRow($data, NULL);
        if ($id) {
            return \Response::json(array('Statusf' => \Lang::get('restapi.StatusSuccess'), 'Message' => \Lang::get('restapi.StroredSuccess')), 200);
        } else {
            return \Response::json(array('Statusf' => \Lang::get('restapi.StatusError'), 'Message' => \Lang::get('restapi.StoreError')));
        }

    }

    public function update($id)
    {
        $class = ucwords(Input::get('module'));
        $class1 = "App\\Models\\" . $class;

        $this->info = $class1::makeInfo($class);
        $data = $this->validatePost($this->info['table']);
        unset($data['entry_by']);
        $obj = new $class1();
        $id = $obj->insertRow($data, $id);
        return \Response::json(array('Status' => \Lang::get('restapi.StatusSuccess'), 'Message' => \Lang::get('restapi.UpdatedSuccess')), 200);
    }

    public function destroy($id)
    {
        $class = ucwords(Input::get('module'));
        $class1 = "App\\Models\\" . $class;
        $results = $class1::find($id);
        if (is_null($results)) {
            return \Response::json(array("Status" => \Lang::get('restapi.StatusError'), "Message" => \Lang::get('restapi.NothingFound')), 500);
        }
        $success = $results->delete();
        if (!$success) {
            return \Response::json(array("Status" => \Lang::get('restapi.StatusError'), "Message" => \Lang::get('restapi.DeleteError')), 500);
        }

        return \Response::json(array("Status" => \Lang::get('restapi.StatusSuccess'), "Message" => \Lang::get('restapi.DeleteSuccess')), 200);
    }

    function validatePost($table, $skipFieldsMissingInRequest = false)
    {

        //die;
        $request = new Request;
        $str = $this->info['config']['forms'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json, true);
        $_POST = $obj;

        $data = array();
        foreach ($str as $f) {
            $field = $f['field'];

            if ($f['type'] == 'textarea_editor' || $f['type'] == 'textarea') {

                $content = (isset($_POST[$field]) ? $_POST[$field] : '');
                $data[$field] = $content;
            } else {
                $r = \Request::get($field);
                if (isset($_POST[$field]) or isset($r)) {
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    } elseif (isset($r)) {
                        $data[$field] = \Request::get($field);
                    }
                }
                // if post is file or image

                if ($f['type'] == 'file') {


                    $files = '';
                    if (isset($f['option']['image_multiple']) && $f['option']['image_multiple'] == 1) {

                        if (isset($_POST['curr' . $field])) {
                            $curr = '';
                            for ($i = 0; $i < count($_POST['curr' . $field]); $i++) {
                                $files .= $_POST['curr' . $field][$i] . ',';
                            }
                        }

                        if (!is_null(Input::file($field))) {
                            $destinationPath = '.' . $f['option']['path_to_upload'];
                            foreach ($_FILES[$field]['tmp_name'] as $key => $tmp_name) {
                                $file_name = $_FILES[$field]['name'][$key];
                                $file_tmp = $_FILES[$field]['tmp_name'][$key];
                                if ($file_name != '') {
                                    move_uploaded_file($file_tmp, $destinationPath . '/' . $file_name);
                                    $files .= $file_name . ',';

                                }

                            }
                            if ($files != '') $files = substr($files, 0, strlen($files) - 1);
                        }
                        $data[$field] = $files;


                    } else {


                        if (!is_null(Input::file($field))) {

                            $file = Input::file($field);
                            $destinationPath = public_path() . $f['option']['path_to_upload'];
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                            $rand = rand(1000, 100000000);
                            $newfilename = strtotime(date('Y-m-d H:i:s')) . '-' . $rand . '.' . $extension;
                            $uploadSuccess = $file->move($destinationPath, $newfilename);
                            if ($f['option']['resize_width'] != '0' && $f['option']['resize_width'] != '') {
                                if ($f['option']['resize_height'] == 0) {
                                    $f['option']['resize_height'] = $f['option']['resize_width'];
                                }
                                $orgFile = $destinationPath . '/' . $newfilename;
                                \SiteHelpers::cropImage($f['option']['resize_width'], $f['option']['resize_height'], $orgFile, $extension, $orgFile);
                            }

                            if ($uploadSuccess) {
                                $data[$field] = $newfilename;
                            }
                        } else {
                            unset($data[$field]);
                        }
                    }
                }


                // if post is checkbox
                if ($f['type'] == 'checkbox') {
                    $r1 = \Request::get($field);
                    if (!is_null($_POST[$field]) or !is_null($r1)) {
                        if (!is_null($_POST[$field]))
                            $data[$field] = $_POST[$field];
                        elseif (!is_null($r1)) {
                            $data[$field] = $r1;
                        }
                    }
                }
                // if post is date
                if ($f['type'] == 'date') {

                    $data[$field] = date("Y-m-d", strtotime($request->input($field)));
                }

                // if post is seelct multiple
                //
                if ($f['type'] == 'select') {
                    $r2 = \Request::get($field);
                    //echo '<pre>'; print_r( $_POST[$field] ); echo '</pre>';
                    if (isset($f['option']['select_multiple']) && $f['option']['select_multiple'] == 1) {
                        if (isset($_POST[$field])) {
                            $multival = (is_array($_POST[$field]) ? implode(",", $_POST[$field]) : $_POST[$field]);
                        } elseif (isset($r2)) {

                            $multival = (is_array($r2) ? implode(",", $r2) : $r2);
                        }

                        $data[$field] = $multival;
                    } else {
                        if (isset($_POST[$field]))
                            $data[$field] = $_POST[$field];
                        elseif (isset($r2))
                            $data[$field] = $r2;
                    }
                }
            }

        }
        $global = (isset($this->access['is_global']) ? $this->access['is_global'] : 0);

        if ($global == 0)
            $data['entry_by'] = \Session::get('uid');

        return $data;
    }*/
}
