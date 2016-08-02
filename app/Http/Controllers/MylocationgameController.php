<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Mylocationgame;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class MylocationgameController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'mylocationgame';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Mylocationgame();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'mylocationgame',
            'pageUrl' => url('mylocationgame'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('mylocationgame.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'mylocationgame')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
            \Session::put('config_id', $config_id);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        if(is_null($request->input('search')))
        {
            $filter = \SiteHelpers::getQueryStringForLocation('game');
        }
        else
        {
            $filter = $this->buildSearch();
        }

        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );
        // Get Query
        $results = $this->model->getRows($params);
        foreach ($results['rows'] as $result) {

            if ($result->dba == 1) {
                $result->dba = "Yes";

            } else {
                $result->dba = "No";
            }
            if ($result->sacoa == 1) {
                $result->sacoa = "Yes";

            } else {
                $result->sacoa = "No";
            }
            if ($result->embed == 1) {
                $result->embed = "Yes";

            } else {
                $result->embed = "No";
            }
            if ($result->for_sale == 1) {
                $result->for_sale = "Yes";

            } else {
                $result->for_sale = "No";
            }
            if ($result->sale_pending == 1) {
                $result->sale_pending = "Yes";

            } else {
                $result->sale_pending = "No";
            }
            if ($result->sold == 1) {
                $result->sold = "Yes";

            } else {
                $result->sold = "No";
            }
            if ($result->test_piece == 1) {
                $result->test_piece = "Yes";

            } else {
                $result->test_piece= "No";
            }
            if ($result->linked_to_game == 1) {
                $result->linked_to_game = "Yes";

            } else {
                $result->linked_to_game= "No";
            }
            if ($result->not_debit == 1) {
                $result->not_debit = "Yes";

            } else {
                $result->num_prize_meters= "No";
            }
            if ($result->num_prize_meters == 1) {
                $result->num_prize_meters = "Yes";

            } else {
                $result->not_debit= "No";
            }
            if ($result->num_prizes == 1) {
                $result->num_prizes = "Yes";

            } else {
                $result->num_prizes= "No";
            }
            if ($result->mfg_id == 0) {
                $result->mfg_id= "";

            }


        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;





        if(count($results['rows']) == $results['total']){
            $params['limit'] = $results['total'];
        }


        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('mylocationgame/data');
        $this->data['param'] = $params;
        $this->data['rowData'] = $results['rows'];
        // Build Pagination
        $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = ($page * $params['limit']) - $params['limit'];
        // Grid Configuration
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['colspan'] = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['setting'] = $this->info['setting'];

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        //get the image for game

// Render into template
        return view('mylocationgame.table', $this->data);

    }


    function getUpdate(Request $request, $id = null)
    {
        if ($id == null) {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != null) {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('game');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('mylocationgame.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        $row['service_history'] = $this->model->getServiceHistory($id);
        $row['move_history'] = $this->model->getMoveHistory($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('game');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('mylocationgame.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM game ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO game (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM game WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        echo"Okay";
        if ($validator->passes()) {
            $data = $this->validatePost('game');

            $id = $this->model->insertRow($data, $id);

echo "<br> If Part <br>".$data;
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            echo"<br> Else Part";
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }

    }

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
            die;

        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_delete')
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));

        }


    }

    public function postUpdate(Request $request, $id = null)
    {
        $request = $request->all();
        $request = array_filter($request);
        array_shift($request);
        array_pull($request, 'submit');
        $service_data['id'] = array_pull($request, 'game_service_id');
        if ($request['status_id'] == 2) {
            $service_data['date_down'] = array_pull($request, 'date_down');
            $service_data['problem'] = array_pull($request, 'problem');
            \DB::table('game_service_history')->where('id', '=', $service_data['id'])->update($service_data);
        }
        $id = $this->model->insertRow($request, $id);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    public function postGamelocation(Request $request)
    {
        $this->data['pageTitle'] = 'game in location';
        $request = $request->all();
        $results = \DB::table('game')->where('game_title_id', '=', $request['game_title_id'])->where('location_id', '=', $request['location_id'])->get();
        $info = $this->model->makeInfo($this->module);
         $rows = $results;
        $fields = $info['config']['grid'];
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
        );
        return view('sximo.module.utility.csv', $content);
    }
    public function getHistory()
    {
            $rows = $this->model->getMoveHistory();
            $fields = array('game', 'From Location', 'Sent by', 'From Date', 'To Location', 'Accepted by', 'To Date');
            $this->data['pageTitle'] = 'game move history';
            $content = array(
                'fields' => $fields,
                'rows' => $rows,
                'type' => 'move',
                'title' => $this->data['pageTitle'],
            );
        return view('mylocationgame.csvhistory', $content);
    }
    function getPending()
    {
             $this->data['pageTitle'] = 'game pending list';
             $fields=array("Manufacturer","Game Title","Version","Serial","Id","Location Id","City","State","WholeSale","Retail","Notes");
             $rows=$this->model->getPendingList();
             $content = array(
                'fields' => $fields,
                'rows' => $rows,
                'type' => 'pending',
                'title' => $this->data['pageTitle'],
            );
        return view('mylocationgame.csvhistory', $content);
    }
    function getForsale()
    {
        $this->data['pageTitle'] = 'game for-sale list';
        $fields=array("Manufacturer","Game Title","Version","Serial","Date In Service","Location Id","City","State","WholeSale","Retail");
        $rows=$this->model->getForSaleList();
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'forsale',
            'title' => $this->data['pageTitle'],
        );
        return view('mylocationgame.csvhistory', $content);
    }
    function generate_asset_tag($id = null)
    {
        //// THE SCRIPT BELOW CIRCULATES THROUGH THE ASSET IDs IN THE COMMA SEPARATED STRING BELOW AND CREATES A QR TAG - ACTIVATE BY VISITING THIS PAGE - CURRENTLY COMMENTED //////////////
        //// START /////

        // $gameString = '20002114,20002146,20002147,20002149,20002150,20002151,20002152,20002153,20002154,20002155,20002157,20002159,20002160,20002161,20002162,20002164,20002165,20002166,20002167,20002168,20002169,20002170,20002171,20002172,20002173,20002174,20002175,20002176,20002177,20002178,20002179,20002180,20002181,20002182,20002183,20002184,20002185,20002186,20002187,20002188,20002189,20002190,20002191,20002192,20002193,20002194,20002195,20002196,20002197,20002198,20002199,20002200,20002201,20002202,20002203,20002204,20002205,20002206,20002207,20002208,20002209,20002210,20002211,20002212,20002214,20002215,20002216,20002217,20002218,20002219,20002220,20002221,20002222,20002223,20002224,20002225,20002226,20002227,20002228,20002229,20002230,20002231,20002232,20002233,20002234,20002235,20002236,20002237,20002240,20002241,20002242,20002243,20002244,20002247,30007187,30007188,30007253,30007263,30007264,30007265,30007266,30007267,30007268,30007269,30007270,30007271,30007272,30007274,30007275,30007277,30007278,30007279,30007280,3000728';

        // $item_count = substr_count($gameString, ',')+1;

        // for($i=1;$i <= $item_count;$i++)
        // {
        // 	$id = substr($gameString, 0, 8);

        ////// END ///// PLUS CLOSING TAG BELOW /////
        $filename = public_path().'/qr/'.$id.'.png';
        $data=url('/')."mylocationgame/show/".$id;

        \QrCode::format('png');
        \QrCode::size(200);
        \QrCode::errorCorrection('H');
        \QrCode::generate($data,$filename);
  // $this->model->get_detail($id);

        $row=\DB::select("SELECT G.id, T.game_title FROM game G LEFT JOIN game_title T ON T.id = G.game_title_id WHERE G.id=$id");

        //

//        $newSizeW = 135;
//        $newSizeH = 147;
//        $topPadding = 11;
//        $smallerSizeFactor = .95;
//        $smallSizeW = round($newSizeW * $smallerSizeFactor);
//        $smallSizeH = round($newSizeH * $smallerSizeFactor);
//
//        // redude size of barcode
//        $this->load->library('image_lib');
//        $config['source_image']	= $filename;
//        $config['quality'] = '100%';
//        $config['width'] = $smallSizeW;
//        $config['height'] = $smallSizeH;
//        $config['overwrite'] = TRUE;
//        $this->image_lib->initialize($config);
//        $this->image_lib->resize();
//        $this->image_lib->clear();

        // add to canvas
//        $oldimage = imagecreatefrompng($filename);
//        $oldw = imagesx($oldimage);
//        $oldh = imagesy($oldimage);
//        $newimage = imagecreate($newSizeW, $newSizeH); // Creates a black image
//        // Fill it with white (optional)
//        $white = imagecolorallocate($newimage, 255, 255, 255);
//        //imagefill($newimage, 0, 0, $white);
//        //$background_color = imagecolorallocate($im, 0, 0, 0);
//        imagecopy($newimage, $oldimage, ($newSizeH-$oldw)/2, $topPadding, 0, 0, $oldw, $oldh);
//        imagepng($newimage, $filename);
//        imagedestroy($newimage);

        if ($row )
        {

            $id = $row[0]->id;
            $game_name = $row[0]->game_title;
          /*  die();
            $this->load->library('image_lib');
            $config['source_image']	= $filename;
            $config['quality'] = '100%';
            $config['wm_text'] = $id;
            $config['wm_type'] = 'text';
            //$config['wm_font_path'] = './system/fonts/PTC55F.ttf';
            //$config['wm_font_path'] = './system/fonts/texb.ttf';
            //$config['wm_font_path'] = './system/fonts/Segan-Light.ttf';
            $config['wm_font_path'] = './system/fonts/EncodeSansWide-Regular.ttf';
            //$config['wm_font_path'] = './system/fonts/DISCO_W.ttf';
            //$config['wm_font_path'] = './system/fonts/arialnarrow.ttf';
            //$config['wm_font_path'] = './system/fonts/arial.ttf';
            $config['wm_font_size']	= '15';
            $config['wm_font_color'] = 'black';
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'left';
            $config['wm_vrt_offset'] = '-6';
            $config['wm_hor_offset'] = '16';
            $config['overwrite'] = TRUE;
            $this->image_lib->initialize($config);
            $this->image_lib->watermark();
            $this->image_lib->clear();

            $this->load->library('image_lib');
            $config['source_image']	= $filename;
            $config['quality'] = '100%';
            $config['wm_text'] = $game_name;
            $config['wm_type'] = 'text';
            //$config['wm_font_path'] = './system/fonts/arialnarrow.ttf';
            $config['wm_font_path'] = './system/fonts/pf_tempesta_seven_condensed.ttf';
            //$config['wm_font_path'] = './system/fonts/pf_ronda_seven.ttf';
            //$config['wm_font_path'] = './system/fonts/hellovetica.ttf';
            $config['wm_font_size']	= '6';
            $config['wm_font_color'] = 'black';
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'left';
            $config['wm_vrt_offset'] = '0';
            $config['wm_hor_offset'] = '3';
            $config['overwrite'] = TRUE;
            $this->image_lib->initialize($config);
            $this->image_lib->watermark();
            $this->image_lib->clear();*/
        }
        // ////
        // $gameString = substr($gameString, 9);
        // }
        // ////
    }
    function postAssettag(Request $request,$asset_ids = null)
    {
        $asset_ids = $request->get('asset_ids');
        if(!empty($asset_ids)) {
            $zip = new \ZipArchive();
            $zip_name = "qr/QRCodes.zip"; // Zip name
            $zip->open($zip_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $item_count = substr_count($asset_ids, ',') + 1;
            if ($item_count > 1) {
                for ($i = 1; $i <= $item_count; $i++) {
                    $id = substr($asset_ids, 0, 8);
                    $asset_ids = substr($asset_ids, 9);
                    $this->generate_asset_tag($id);
                    $location = $this->get_game_info_by_id($id, 'location_id');
                    $file = public_path() . '\\qr\\' . $id . '.png';
// Quick check to verify that the file exists
                    if (file_exists($file)) {
                        $zip->addFile($file);
                    }
                }
                if (file_exists($zip_name)) {
                return (\Response::download($zip_name));
            }
                else
                {
                    echo "sorry";
                }
            }
            else {
                if (file_exists("qr/" . $asset_ids . ".png")) {
                    return (\Response::download("qr/" . $asset_ids . ".png"));
                }
            }
        }
    }
    public function get_game_info_by_id($asset_id = null, $field = null)
    {
        $query = \DB::select('SELECT '.$field.'
								 FROM game_title T
						 	LEFT JOIN game G ON G.game_title_id = T.id
							    WHERE G.id = '.$asset_id);
            $game_info = $query[0]->location_id;
        if(empty($game_info))
        {
            $game_info = 'NONE';
        }
        return $game_info;
    }
    function getDowload()
    {

    }
}