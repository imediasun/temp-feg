<?php

use App\Library\FEG\System\FEGSystemHelper;
use \App\Models\Sximo;
use App\Models\Core\Groups;

class SiteHelpers
{
    public static function menus($position = 'top', $active = '1', $showAll = false)
    {
        if ($showAll) {
            $active = "all";
        }
        $data = array();
        $menu = self::nestedMenu(0, $position, $active);
        foreach ($menu as $row) {
            $child_level = array();
            $p = json_decode($row->access_data, true);


            if ($row->allow_guest == 1 || $showAll) {
                $is_allow = 1;
            } else {
                $is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
            }
            if ($is_allow == 1) {

                $menus2 = self::nestedMenu($row->menu_id, $position, $active);
                if (count($menus2) > 0) {
                    $level2 = array();
                    foreach ($menus2 as $row2) {
                        $p = json_decode($row2->access_data, true);
                        if ($row2->allow_guest == 1) {
                            $is_allow = 1;
                        } else {
                            $is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
                        }

                        if ($is_allow == 1) {

                            $menu2 = array(
                                'menu_id' => $row2->menu_id,
                                'module' => $row2->module,
                                'menu_type' => $row2->menu_type,
                                'url' => $row2->url,
                                'menu_name' => $row2->menu_name,
                                'menu_lang' => json_decode($row2->menu_lang, true),
                                'menu_icons' => $row2->menu_icons,
                                'active' => $row->active == 1 ? 'active' : 'inactive',
                                'childs' => array()
                            );

                            $menus3 = self::nestedMenu($row2->menu_id, $position, $active);
                            if (count($menus3) > 0) {
                                $child_level_3 = array();
                                foreach ($menus3 as $row3) {
                                    $p = json_decode($row3->access_data, true);
                                    if ($row3->allow_guest == 1) {
                                        $is_allow = 1;
                                    } else {
                                        $is_allow = (isset($p[Session::get('gid')]) && $p[Session::get('gid')] ? 1 : 0);
                                    }
                                    if ($is_allow == 1) {
                                        $menu3 = array(
                                            'menu_id' => $row3->menu_id,
                                            'module' => $row3->module,
                                            'menu_type' => $row3->menu_type,
                                            'url' => $row3->url,
                                            'menu_name' => $row3->menu_name,
                                            'menu_lang' => json_decode($row3->menu_lang, true),
                                            'menu_icons' => $row3->menu_icons,
                                            'active' => $row->active == 1 ? 'active' : 'inactive',
                                            'childs' => array()
                                        );
                                        $child_level_3[] = $menu3;
                                    }
                                }
                                $menu2['childs'] = $child_level_3;
                            }
                            $level2[] = $menu2;
                        }

                    }
                    $child_level = $level2;

                }

                $level = array(
                    'menu_id' => $row->menu_id,
                    'module' => $row->module,
                    'menu_type' => $row->menu_type,
                    'url' => $row->url,
                    'menu_name' => $row->menu_name,
                    'menu_lang' => json_decode($row->menu_lang, true),
                    'menu_icons' => $row->menu_icons,
                    'active' => $row->active == 1 ? 'active' : 'inactive',
                    'childs' => $child_level
                );

                $data[] = $level;
            }

        }
        //echo '<pre>';print_r($data); echo '</pre>'; exit;
        return $data;
    }

    public static function nestedMenu($parent = 0, $position = 'top', $active = '1')
    {
        $group_sql = " AND tb_menu_access.group_id ='" . Session::get('gid') . "' ";
        $active = ($active == 'all' ? "" : "AND active ='1' ");
        $Q = DB::select("
		SELECT 
			tb_menu.*
		FROM tb_menu WHERE parent_id ='" . $parent . "' " . $active . " AND position ='{$position}'
		GROUP BY tb_menu.menu_id ORDER BY ordering			
		");
        return $Q;
    }

    public static function CF_encode_json($arr)
    {
        $str = json_encode($arr);
        $enc = base64_encode($str);
        $enc = strtr($enc, 'poligamI123456', '123456poligamI');
        return $enc;
    }

    public static function CF_decode_json($str)
    {
        $dec = strtr($str, '123456poligamI', 'poligamI123456');
        $dec = base64_decode($dec);
        $obj = json_decode($dec, true);
        return $obj;
    }


    public static function columnTable($table)
    {
        $columns = array();
        foreach (DB::select("SHOW COLUMNS FROM $table") as $column) {
            //print_r($column);
            $columns[] = $column->Field;
        }


        return $columns;
    }

    public static function encryptID($id, $decript = false, $pass = '', $separator = '-', & $data = array())
    {
        $pass = $pass ? $pass : Config::get('app.key');
        $pass2 = Config::get('app.url');;
        $bignum = 200000000;
        $multi1 = 500;
        $multi2 = 50;
        $saltnum = 10000000;
        if ($decript == false) {
            $strA = self::alphaid(($bignum + ($id * $multi1)), 0, 0, $pass);
            $strB = self::alphaid(($saltnum + ($id * $multi2)), 0, 0, $pass2);
            $out = $strA . $separator . $strB;
        } else {
            $pid = explode($separator, $id);


            //    trace($pid);
            $idA = (self::alphaid($pid[0], 1, 0, $pass) - $bignum) / $multi1;
            $idB = (self::alphaid($pid[1], 1, 0, $pass2) - $saltnum) / $multi2;
            $data['id A'] = $idA;
            $data['id B'] = $idB;
            $out = ($idA == $idB) ? $idA : false;
        }
        return $out;
    }

    public static function randomString2(){
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
        return $rand;
    }
   public static function randomString($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
    public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
    {
        $index = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512', $passKey)
                : $passhash;

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        $base = strlen($index);

        if ($to_num) {
            // Digital number    <<--    alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number    -->>    alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }
  public static function getExtensionName($val,$defaultValue = '-'){
      $mime_types = array(
          "application/pdf" => "PDF Document",
          "application/octet-stream" => "exe",
          "application/zip" => "Zip Document",
          "application/msword" => "Word Document",
          "application/vnd.ms-excel" => "Excel Document",
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"=>'Excel Document',
          "application/x-ms-shortcut"=>"Document Shortcut",
          "application/vnd.ms-powerpoint" => "Powerpoint Document",
          "application/vnd.google-apps.spreadsheet" => "Excel Document",
          "application/vnd.google-apps.document"=>'PDF Dcocument',
          "image/gif" => "Gif",
          "image/png" => "PNG",
          "image/jpeg" => "JPEG",
          "image/jpg" => "JPG Image",
          "text/html" => "Html File",
          "audio/mpeg" => "mp3",
          "audio/x-wav"=>   "wav",
          "video/mpeg"=>    "mpeg",
          "video/quicktime" =>   "mov",
          "video/x-msvideo" =>  "avi",
          "video/3gpp" =>    "3gp",
          "text/css" =>"css",
          "application/javascript" =>"js"
      );
      if(in_array($val,array_keys($mime_types))){
         return $mime_types[$val];
      }
    else{
        return $defaultValue;
    }





  }

    public static function toForm($forms, $layout)
    {
        $f = '';
        //	echo '<pre>'; print_r($forms);echo '</pre>';
        //usort($forms,"_sort");
        $block = $layout['column'];
        $format = $layout['format'];
        $display = $layout['display'];
        $title = explode(",", $layout['title']);

        if ($format == 'tab') {
            $f .= '<ul class="nav nav-tabs">';

            for ($i = 0; $i < $block; $i++) {
                $active = ($i == 0 ? 'active' : '');
                $tit = (isset($title[$i]) ? $title[$i] : 'None');
                $f .= '<li class="' . $active . '"><a href="#' . trim(str_replace(" ", "", $tit)) . '" data-toggle="tab">' . $tit . '</a></li>
				';
            }
            $f .= '</ul>';
        }

        if ($format == 'tab') $f .= '<div class="tab-content">';
        for ($i = 0; $i < $block; $i++) {
            if ($block == 4) {
                $class = 'col-md-3';
            } elseif ($block == 3) {
                $class = 'col-md-4';
            } elseif ($block == 2) {
                $class = 'col-md-6';
            } else {
                $class = 'col-md-12';
            }

            $tit = (isset($title[$i]) ? $title[$i] : 'None');
            // Grid format
            if ($format == 'grid') {
                $f .= '<div class="' . $class . '">
						<fieldset><legend> ' . $tit . '</legend>
				';
            } else {
                $active = ($i == 0 ? 'active' : '');
                $f .= '<div class="tab-pane m-t ' . $active . '" id="' . trim(str_replace(" ", "", $tit)) . '">
				';
            }


            $group = array();

            foreach ($forms as $form) {
                $tooltip = '';
                $required = ($form['required'] != '0' ? '<span class="asterix"> * </span>' : '');
                if ($form['view'] != 0) {
                    if ($form['field'] != 'entry_by') {
                        if (isset($form['option']['tooltip']) && $form['option']['tooltip'] != '')
                            $tooltip = '<a href="#" data-toggle="tooltip" placement="left" class="tips" title="' . $form['option']['tooltip'] . '"><i class="icon-question2"></i></a>';
                        $hidethis = "";
                        if ($form['type'] == 'hidden') $hidethis = 'hidethis';
                        $inhide = '';
                        if (count($group) > 1) $inhide = 'inhide';
                        //$ebutton = ($form['type'] =='radio' || $form['option'] =='checkbox') ? "ebutton-radio" : "";
                        $show = '';
                        if ($form['type'] == 'hidden') $show = 'style="display:none;"';
                        if (isset($form['limited']) && $form['limited'] != '') {
                            $limited_start =
                                '
				<?php 
				$limited = isset($fields[\'' . $form['field'] . '\'][\'limited\']) ? $fields[\'' . $form['field'] . '\'][\'limited\'] :\'\';
				if(SiteHelpers::filterColumn($limited )) { ?>
							';
                            $limited_end = '
				<?php } ?>';
                        } else {
                            $limited_start = '';
                            $limited_end = '';
                        }


                        if ($form['form_group'] == $i) {
                            if ($display == 'horizontal') {
                                $f .= $limited_start;
                                $f .= '
				  <div class="form-group ' . $hidethis . ' ' . $inhide . '" ' . $show . '>
					<label for="' . $form['label'] . '" class=" control-label col-md-4 text-left">
					{!! SiteHelpers::activeLang(\'' . $form['label'] . '\', (isset($fields[\'' . $form['field'] . '\'][\'language\'])? $fields[\'' . $form['field'] . '\'][\'language\'] : array())) !!}
					</label>
					<div class="col-md-6">
					  ' . self::formShow($form['type'], $form['field'], $form['required'], $form['option']) . '
					 </div> 
					 <div class="col-md-2">
					 	' . $tooltip . '
					 </div>
				  </div> ';
                                $f .= $limited_end;
                            } else {
                                $f .= $limited_start;
                                $f .= '
				  <div class="form-group ' . $hidethis . ' ' . $inhide . '" ' . $show . '>
					<label for="ipt" class=" control-label ">
						{!! SiteHelpers::activeLang(\'' . $form['label'] . '\', (isset($fields[\'' . $form['field'] . '\'][\'language\'])? $fields[\'' . $form['field'] . '\'][\'language\'] : array())) !!}
					 ' . $required . ' ' . $tooltip . ' </label>
					  ' . self::formShow($form['type'], $form['field'], $form['required'], $form['option']) . '
				  </div> ';
                                $f .= $limited_end;

                            }
                        }
                    }

                }
            }
            if ($format == 'grid') $f .= '</fieldset>';
            $f .= '
			</div>
			
			';
        }

        //echo '<pre>'; print_r($f);echo '</pre>'; exit;
        return $f;

    }

    public static function gridClass($layout)
    {
        $column = $layout['column'];
        $format = $layout['format'];

        if ($block == 4) {
            $class = 'col-md-3';
        } elseif ($block == 3) {
            $class = 'col-md-4';
        } elseif ($block == 2) {
            $class = 'col-md-6';
        } else {
            $class = 'col-md-12';
        }


        if (format == 'tab') {
            $tag_open = '<div class="col-md-">';
            $tag_close = '<div class="col-md-">';

        } elseif ($layout['format'] == 'accordion') {

        } else {
            $tag_open = '<div class="col-md-">';
            $tag_close = '</div>';
        }


        return $class;
    }


    public static function formShow($type, $field, $required, $option = array())
    {
        //print_r($option);
        $mandatory = '';
        $attribute = '';
        $extend_class = '';
        if (isset($option['attribute']) && $option['attribute'] != '') {
            $attribute = $option['attribute'];
        }
        if (isset($option['extend_class']) && $option['extend_class'] != '') {
            $extend_class = $option['extend_class'];
        }

        $show = '';
        if ($type == 'hidden') $show = 'style="display:none;"';

        if ($required == 'required') {
            $mandatory = "'required'=>'true'";
        } else if ($required == 'email') {
            $mandatory = "'required'=>'true', 'parsley-type'=>'email' ";
        } else if ($required == 'url') {
            $mandatory = "'required'=>'true', 'parsley-type'=>'url' ";
        } else if ($required == 'date') {
            $mandatory = "'required'=>'true', 'parsley-type'=>'dateIso' ";
        } else if ($required == 'numeric') {
            $mandatory = "'required'=>'true', 'parsley-type'=>'number' ";
        } else {
            $mandatory = '';
        }

        switch ($type) {
            default;
                $form = "{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control', 'placeholder'=>'', {$mandatory}  )) !!}";
                break;

            case 'textarea';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                $form = "<textarea name='{$field}' rows='5' id='{$field}' class='form-control {$extend_class}'
				         {$mandatory} {$attribute} >{{ \$row['{$field}'] }}</textarea>";
                break;

            case 'textarea_editor';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                $form = "<textarea name='{$field}' rows='5' id='editor' class='form-control editor {$extend_class}'
						{$mandatory}{$attribute} >{{ \$row['{$field}'] }}</textarea>";
                break;


            case 'text_date';
                $form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control date')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>";
                break;

            case 'text_time';
                $form = "
					<div class=\"input-group m-b\" style=\"width:150px !important;\">
						input  type='text' name='{$field}' id='{$field}' value='{{ \$row['{$field}'] }}' 
						{$mandatory}  {$attribute}   class='form-control {$extend_class}'
						data-date-format='yyyy-mm-dd'
						 />
						 <span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
						 </div>
						 ";
                break;

            case 'text_datetime';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                $form = "
				<div class=\"input-group m-b\" style=\"width:150px !important;\">
					{!! Form::text('{$field}', \$row['{$field}'],array('class'=>'form-control datetime', 'style'=>'width:150px !important;')) !!}
					<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>
				</div>
				";
                break;

            case 'select';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                if ($option['opt_type'] == 'datalist') {
                    $optList = '';
                    $opt = explode("|", $option['lookup_query']);
                    for ($i = 0; $i < count($opt); $i++) {
                        $row = explode(":", $opt[$i]);
                        for ($i = 0; $i < count($opt); $i++) {

                            $row = explode(":", $opt[$i]);
                            $optList .= " '" . trim($row[0]) . "' => '" . trim($row[1]) . "' , ";

                        }
                    }
                    $form = "
					<?php \$" . $field . " = explode(',',\$row['" . $field . "']);
					";
                    $form .=
                        "\$" . $field . "_opt = array(" . $optList . "); ?>
					";

                    if (isset($option['select_multiple']) && $option['select_multiple'] == 1) {

                        $form .= "<select name='{$field}[]' rows='5' {$mandatory} multiple  class='select2 '  > ";
                        $form .= "
						<?php 
						foreach(\$" . $field . "_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(in_array(\$key,\$" . $field . ") ? \" selected='selected' \" : '' ).\">\$val</option>\";
						}						
						?>";
                        $form .= "</select>";
                    } else {

                        $form .= "<select name='{$field}' rows='5' {$mandatory}  class='select2 '  > ";
                        $form .= "
						<?php 
						foreach(\$" . $field . "_opt as \$key=>\$val)
						{
							echo \"<option  value ='\$key' \".(\$row['" . $field . "'] == \$key ? \" selected='selected' \" : '' ).\">\$val</option>\";
						}						
						?>";
                        $form .= "</select>";

                    }

                } else {

                    if (isset($option['select_multiple']) && $option['select_multiple'] == 1) {
                        $named = "name='{$field}[]' multiple";
                    } else {
                        $named = "name='{$field}'";

                    }
                    $form = "<select " . $named . " rows='5' id='{$field}' class='select2 {$extend_class}' {$mandatory} {$attribute} ></select>";


                }
                break;

            case 'file';
                if ($required != '0') {
                    $mandatory = 'required';
                }

                if (isset($option['image_multiple']) && $option['image_multiple'] == 1) {
                    $form = '
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles(\'' . $field . '\')"><i class="fa fa-plus"></i></a>
					<div class="' . $field . 'Upl">
					 	<input  type=\'file\' name=\'' . $field . '[]\'  />
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row[\'' . $field . '\'] = explode(",",$row[\'' . $field . '\']);
					?>
					@foreach($row[\'' . $field . '\'] as $files)
						@if(file_exists(\'.' . $option['path_to_upload'] . '\'.$files) && $files !=\'\')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url(\'' . $option['path_to_upload'] . '/\'.$files) }}" target="_blank" >{{ $files }}</a>
							<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
							<input type="hidden" name="curr' . $field . '[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					';

                } else {
                    $form = "<input  type='file' name='{$field}' id='{$field}' ";
                    $form .= "@if(\$row['$field'] =='') class='required' @endif ";
                    $form .= "style='width:150px !important;' {$attribute} />
					 	<div >
						{!! SiteHelpers::showUploadedFile(\$row['{$field}'],'$option[path_to_upload]') !!}
						
						</div>					
					";

                }
                break;

            case 'radio';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                $opt = explode("|", $option['lookup_query']);
                $form = '';
                for ($i = 0; $i < count($opt); $i++) {
                    $checked = '';
                    $row = explode(":", $opt[$i]);
                    $form .= "
					<label class='radio radio-inline'>
					<input type='radio' name='{$field}' value ='" . ltrim(rtrim($row[0])) . "' {$mandatory} {$attribute}";
                    $form .= "@if(\$row['" . $field . "'] == '" . ltrim(rtrim($row[0])) . "') checked=\"checked\" @endif";
                    $form .= " > " . $row[1] . " </label>";
                }
                break;

            case 'checkbox';
                if ($required != '0') {
                    $mandatory = 'required';
                }
                $opt = explode("|", $option['lookup_query']);
                $form = "<?php \$" . $field . " = explode(\",\",\$row['" . $field . "']); ?>";
                for ($i = 0; $i < count($opt); $i++) {

                    $checked = '';
                    $row = explode(":", $opt[$i]);
                    $form .= "
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='{$field}[]' value ='" . ltrim(rtrim($row[0])) . "' {$mandatory} {$attribute} class='{$extend_class}' ";
                    $form .= "
					@if(in_array('" . trim($row[0]) . "',\$" . $field . "))checked @endif
					";
                    $form .= " /> " . $row[0] . " </label> ";
                }
                break;

        }

        return $form;
    }

    public static function toMasterDetail($info)
    {

        if (count($info) >= 1) {
            $module = ucwords($info['module']);
            //$data['masterdetailmodel'] 	= '$this->modelview = new  \App\Models\''.$module.'();';

            $data['masterdetailinfo'] = "\$this->data['subgrid']	= (isset(\$this->info['config']['subgrid']) ? \$this->info['config']['subgrid'][0] : array()); ";
            $data['masterdetailgrid'] = "\$this->data['subgrid'] = \$this->detailview(\$this->modelview ,  \$this->data['subgrid'] ,\$id );";
            $data['masterdetailsave'] = "\$this->detailviewsave( \$this->modelview , \$request->all() , \$this->data['subgrid'] , \$id) ;";

            $tpl = array();
            require_once('../resources/views/sximo/module/template/native/masterdetailform.php');
            $data['masterdetailview'] = $tpl['masterdetailview'];
            $data['masterdetailform'] = $tpl['masterdetailform'];
            $data['masterdetailjs'] = $tpl['masterdetailjs'];
            $data['masterdetaildelete'] = $tpl['masterdetaildelete'];
            $data['masterdetailmodel'] = $tpl['masterdetailmodel'];
        }
        return $data;

    }

    public static function filterColumn($limit)
    {
        if ($limit != '') {
            $limited = explode(',', $limit);
            if (in_array(\Session::get('uid'), $limited)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function toView($grids)
    {
        $f = '';
        foreach ($grids as $grid) {
            if (isset($grid['conn']) && is_array($grid['conn'])) {
                $conn = $grid['conn'];
                //print_r($conn);exit;
            } else {
                $conn = array('valid' => 0, 'db' => '', 'key' => '', 'display' => '');
            }

            if ($grid['detail'] == '1') {
                if ($grid['attribute']['image']['active'] == '1') {
                    $val = "{!! SiteHelpers::showUploadedFile(\$row->" . $grid['field'] . ",'" . $grid['attribute']['image']['path'] . "') !!}";
                } elseif ($conn['valid'] == 1) {
                    $arr = implode(':', $conn);
                    $val = "{!! SiteHelpers::gridDisplayView(\$row->" . $grid['field'] . ",'" . $grid['field'] . "','" . $arr . "') !!}";

                } elseif (isset($grid['attribute']['formater']['active']) && $grid['attribute']['formater']['active'] == 1) {

                    $c = explode("|", $grid['attribute']['formater']['value']);
                    if (isset($c[2])) {
                        $args = explode(":", $c[2]);
                        $a = '';
                        foreach ($args as $a) {
                            $ar = '$row->' . $a . ',';
                        }
                        $val = "{{ " . $c[0] . "::" . $c[1] . "(" . substr($ar, 0, strlen($ar) - 1) . ") }}";

                    }

                } elseif (isset($attribute['hyperlink']['active']) && $attribute['hyperlink']['active'] == 1 && $attribute['hyperlink']['link'] != '') {

                    $attr = '';
                    $linked = $attribute['hyperlink']['link'];
                    foreach ($row as $k => $i) {

                        if (preg_match("/$k/", $attribute['hyperlink']['link']))
                            $linked = str_replace($k, $i, $linked);
                    }
                    if ($attribute['hyperlink']['target'] == 'modal') {
                        $attr = "onclick='SximoModal(this.href); return false'";
                    }

                    $val = "<a href='" . URL::to($linked) . "'  $attr style='display:block' >" . $val . " <span class='fa fa-arrow-circle-right pull-right'></span></a>";

                } else {
                    $val = "{{ \$row->" . $grid['field'] . " }}";
                }

                if (isset($grid['limited']) && $grid['limited'] != '') {
                    $limited_start =
                        '
				<?php 
				$limited = isset($fields[\'' . $grid['field'] . '\'][\'limited\']) ? $fields[\'' . $grid['field'] . '\'][\'limited\'] :\'\';
				if(SiteHelpers::filterColumn($limited )) { ?>
							';
                    $limited_end = '
				<?php } ?>';
                } else {
                    $limited_start = '';
                    $limited_end = '';
                }
                $f .= $limited_start;
                $f .= "
					<tr>
						<td width='30%' class='label-view text-right'>
							{{ SiteHelpers::activeLang('" . $grid['label'] . "', (isset(\$fields['" . $grid['field'] . "']['language'])? \$fields['" . $grid['field'] . "']['language'] : array())) }}
						</td>
						<td>" . $val . " </td>
						
					</tr>
				";
                $f .= $limited_end;
            }
        }
        return $f;
    }

    public static function transForm($field, $forms = array(), $bulk = false, $value = '', $typeRestricted = [])
    {
        $value = !empty($value) ? urldecode($value) : "";
        $type = '';
        $bulk = ($bulk == true ? '[]' : '');
        $mandatory = '';
        $selectMultiple = "";
        $simpleSearchOptionsBasic = '';
        $simpleSearchOptions = '';
        $simpleSearchOperator = '';
        $isSimpleSearchBetween = false;
        $simpleSearchStyle = '';
        $simpleSearchClass = '';
        $simpleSearchEndStyle = '';
        $simpleSearchEndClass = '';
        $simpleSearchPlaceholder = '';
        $simpleSearchEndPlaceholder = '';
        $isSSSFWOBD = false;

        foreach ($forms as $f) {
            $hasSimpleSearch = isset($f['generatingSimpleSearch']) ? $f['generatingSimpleSearch'] : false;
            if ($f['field'] == $field && ($f['search'] == 1 || $hasSimpleSearch)) {
                $type = ($f['type'] != 'file' ? $f['type'] : '');
                $option = $f['option'];
                $required = $f['required'];
                $selectMultiple = empty($option['select_multiple']) ? "" : " multiple='multiple' ";
                if ($required == 'required') {
                    $mandatory = "data-parsley-required='true'";
                } else if ($required == 'email') {
                    $mandatory = "data-parsley-type'='email' ";
                } else if ($required == 'date') {
                    $mandatory = "data-parsley-required='true'";
                } else if ($required == 'numeric') {
                    $mandatory = "data-parsley-type='number' ";
                } else {
                    $mandatory = '';
                }
                if ($hasSimpleSearch) {
                    $simpleSearchOperator = 'equal';
                    if (isset($f['simplesearchoperator'])) {
                        $simpleSearchOperator = $f['simplesearchoperator'];
                    }
                    $isSimpleSearchBetween = $simpleSearchOperator == 'between';
                    if ($isSimpleSearchBetween) {
                        $simpleSearchPlaceholder = "placeholder='Start'";
                        $simpleSearchEndPlaceholder = "placeholder='End'";
                        $simpleSearchStyle = "style=''";
                        $simpleSearchEndStyle = "style=''";
                        $simpleSearchClass = "betweenRangeStart";
                        $simpleSearchEndClass = "betweenRangeEnd";
                    }

                    $simpleSearchOptionsBasic = " data-simpleSearch='1' 
                        data-simpleSearchOperator='{$simpleSearchOperator}' ";
                    $simpleSearchOptions = "$simpleSearchOptionsBasic 
                        $simpleSearchPlaceholder 
                        $simpleSearchStyle ";

                    if (isset($f['simplesearchselectfieldwithoutblankdefault'])) {
                        $isSSSFWOBD = $f['simplesearchselectfieldwithoutblankdefault'] == 1;
                    }

                }
                break;
            }
        }

        switch ($type) {
            default;
                $form = '';
                break;
            case 'textarea';
                $form = "<input  type='text' name='" . $field . "{$bulk}' 
                    class='form-control input-sm $simpleSearchClass' 
                    $mandatory $simpleSearchOptions value='{$value}'/>";
                if ($isSimpleSearchBetween) {
                    $form = "<div class='clearfix' >$form"
                        . "<div class='betweenseparator pull-left'> - </div>"
                        . "<input type='text'
                                value='{$value}'
                                name='$field{$bulk}_end' 
                                class='form-control input-sm pull-left $simpleSearchEndClass' 
                                data-range-end-field='1' 
                                $mandatory 
                                $simpleSearchOptionsBasic 
                                $simpleSearchEndStyle 
                                $simpleSearchEndPlaceholder 
                                />
                            </div>";
                }
                break;

            case 'textarea_editor';
                $form = "<input  type='text' name='" . $field . "{$bulk}' 
                    class='form-control input-sm $simpleSearchClass' 
                        $mandatory $simpleSearchOptions value='{$value}'/>";
                if ($isSimpleSearchBetween) {
                    $form = "<div class='clearfix' >$form"
                        . "<div class='betweenseparator pull-left' > - </div>"
                        . "<input type='text'
                                value='{$value}'
                                name='$field{$bulk}_end' 
                                class='form-control input-sm pull-left $simpleSearchEndClass' 
                                data-range-end-field='1' 
                                $mandatory 
                                $simpleSearchOptionsBasic 
                                $simpleSearchEndStyle 
                                $simpleSearchEndPlaceholder 
                                />
                            </div>";
                }
                break;

            case 'text';
                $form = "<input  type='text' name='" . $field . "{$bulk}' 
                    class='form-control input-sm $simpleSearchClass' 
                    $mandatory $simpleSearchOptions value='{$value}'/>";
                if ($isSimpleSearchBetween) {
                    $form = "<div class='clearfix' >$form"
                        . "<div class='betweenseparator pull-left'> - </div>"
                        . "<input type='text'
                                value='{$value}'
                                name='$field{$bulk}_end' 
                                class='form-control input-sm pull-left $simpleSearchEndClass' 
                                data-range-end-field='1' 
                                $mandatory 
                                $simpleSearchOptionsBasic 
                                $simpleSearchEndStyle 
                                $simpleSearchEndPlaceholder 
                                />
                            </div>";
                }
                break;

            case 'text_date';
                $form = "<input  type='text' name='$field{$bulk}' 
                    class='date form-control input-sm $simpleSearchClass' 
                    $mandatory $simpleSearchOptions value='{$value}'/> ";
                if ($isSimpleSearchBetween) {
                    $form = "<div class='clearfix' >$form"
                        . "<div class='betweenseparator pull-left' > - </div>"
                        . "<input type='text'
                                value='{$value}'
                                name='$field{$bulk}_end' 
                                class='date form-control input-sm pull-left $simpleSearchEndClass' 
                                data-range-end-field='1' 
                                $mandatory 
                                $simpleSearchOptionsBasic 
                                $simpleSearchEndStyle 
                                $simpleSearchEndPlaceholder 
                                />
                            </div>";
                }
                break;

            case 'text_datetime';
                $form = "<input  type='text' name='$field{$bulk}' 
                    class='date form-control input-sm $simpleSearchClass'  
                    $mandatory $simpleSearchOptions value='{$value}'/> ";
                if ($isSimpleSearchBetween) {
                    $form = "<div class='clearfix' >$form"
                        . "<div class='betweenseparator pull-left' > - </div>"
                        . "<input type='text'
                                value='{$value}'
                                name='$field{$bulk}_end' 
                                class='date form-control input-sm pull-left $simpleSearchEndClass' 
                                data-range-end-field='1' 
                                $mandatory 
                                $simpleSearchOptionsBasic 
                                $simpleSearchEndStyle 
                                $simpleSearchEndPlaceholder 
                                />
                            </div>";
                }
                break;




            case 'select';
                if ($option['opt_type'] == 'external') {

                    $opts = '';
                    if ($option['lookup_table'] == 'location') {
                        $lookupParts = explode('|', $option['lookup_value']);

                        if (is_array($lookupParts) && !empty($lookupParts)) {
                            $option['lookup_value'] = $lookupParts[0];
                        }
                        $selected = '';
                        $current_user_id = Auth::id();
                        $user_ids = DB::table('user_locations')->leftjoin('location as l', 'user_locations.location_id', '=', 'l.id')->where('user_locations.user_id', $current_user_id)->orderby('l.' . $option['lookup_value'])->get();
                        foreach ($user_ids as $user_id) {
                            $locations = DB::table($option['lookup_table'])->where('id', $user_id->location_id)->orderby($option['lookup_value'])->get();
                            foreach ($locations as $location) {
                                $value1 = "";
                                foreach ($lookupParts as $lookup) {
                                    $value1 .= $location->$lookup . " - ";
                                }
                                $value1 = trim($value1, ' - ');
                                if ($value == $location->id) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = "";
                                }
                                $opts .= "<option  $selected  value='" . $location->$option['lookup_key'] . "' $mandatory > " . $value1 . " </option> ";
                            }
                        }
                    } else {
                        $fields = explode("|", $option['lookup_value']);
                        $search = isset($option['lookup_search']) ? $option['lookup_search'] : '';
                        $query = DB::table($option['lookup_table']);
                        if (!empty($search)) {
                            $searchParts = explode(':', urldecode($search));
                            if (count($searchParts) > 1) {
                                $query->where($searchParts[0], $searchParts[1]);
                            } else {
                                $query->whereRaw($search);
                            }
                        }

                        if (count($fields) > 1) {
                            $query->where($option['lookup_key'], '!=', '')
                                ->orderby($option['lookup_key'])
                                ->groupby($option['lookup_key']);
                        } else {
                            $query->where($option['lookup_value'], '!=', '')
                                ->orderby($option['lookup_value'])
                                ->groupby($option['lookup_value']);
                        }

                        if(isset($typeRestricted['isTypeRestricted'])) {

                            if ($typeRestricted['isTypeRestricted'] == true && ($option['lookup_table'] == "order_type")) {
                                $query->where($option['lookup_key'], "=", $typeRestricted['displayTypeOnly']);
                            }
                        }

                        if(isset($typeRestricted['isTypeRestrictedExclude'])) {

                            if ($typeRestricted['isTypeRestrictedExclude'] == true && ($option['lookup_table'] == "order_type")) {
                                $query->whereNotIn($option['lookup_key'], $typeRestricted['excluded']);
                            }
                        }

                        $data = $query->get();
                        foreach ($data as $row) {
                            $selected = '';
                            if ($value == $row->$option['lookup_key']) $selected = 'selected="selected"';

                            //print_r($fields);exit;
                            $val = "";
                            foreach ($fields as $item => $v) {
                                if ($v != "") $val .= $row->$v . " ";
                            }
                            $opts .= "<option $selected value='" . $row->$option['lookup_key'] . "' $mandatory > " . $val . " </option> ";
                        }
                    }
                } else {
                    $opt = explode("|", $option['lookup_query']);
                    $opts = '';
                    for ($i = 0; $i < count($opt); $i++) {
                        $selected = '';
                        $row = explode(":", $opt[$i]);
                        if ($value == ltrim(rtrim($row[0]))) $selected = 'selected="selected"';
                        $opts .= "<option $selected value ='" . trim($row[0]) . "' > " . $row[1] . " </option> ";
                    }

                }

                $multipleClass = "";
                $multiple = false;
                if (!empty($selectMultiple)) {
                    $multipleClass = "sel-search-multiple";
                    $multiple = true;
                }
                $disableField = "";
                if($option['lookup_table'] == "product_type"){
                    $disableField = 'disabled="disabled"';
                }

                $form = "<select name='$field{$bulk}' $disableField  class='form-control select3 sel-search $multipleClass' $mandatory $selectMultiple $simpleSearchOptions>" .
                    (empty($selectMultiple) && !$isSSSFWOBD ? "<option value=''> -- Select  -- </option>" : "") .
                    "	$opts
						</select>";

                if($f['alias'] == 'game_service_history')
                {
                	$random = str_random(4);
                	$form = "<select data-name='$field{$bulk}' style='display:none' class=''>" .
                    (empty($selectMultiple) && !$isSSSFWOBD ? "<option value=''> -- Select  -- </option>" : "") .
                    "	$opts
						</select>";
                    $form .= "<input type='hidden' name='$field{$bulk}' id='$field{$bulk}' class='form-control custom-select sel-search $multipleClass' $mandatory $selectMultiple $simpleSearchOptions value='$value'>
					<script>
                        (function (){
                            var data_$random = [], cache = {};

                            $.each($('select[data-name=$field{$bulk}]').prop('options'), function(i, opt) {
                                cache[opt.value] = opt.textContent;
                                data_$random.push({id: ''+opt.value, text: ''+opt.textContent});
                            });

                            $('.form-control[name=$field{$bulk}]').select2({

                                initSelection: function(element, callback) {
                                    var data = [];
                                    $((element.val()||'').split(',')).each(function () {
                                        var obj = { id: this, text: cache[this] || this };
                                        //data.push(obj);
                                    });
                                    callback(data);
                                },
                                query: function(options){
                                    var pageSize = 100;
                                    var startIndex  = (options.page - 1) * pageSize;
                                    var filteredData = data_$random;

                                    if( options.term && options.term.length > 0 ){
                                        if( !options.context ){
                                            var term = options.term.toLowerCase();
                                            options.context = data_$random.filter( function(metric){
                                                return ( metric.text.toLowerCase().indexOf(term) !== -1 );
                                            });
                                        }
                                        filteredData = options.context;
                                    }

                                    options.callback({
                                        context: filteredData,
                                        results: filteredData.slice(startIndex, startIndex + pageSize),
                                        more: (startIndex + pageSize) < filteredData.length
                                    });
                                }

                                "
                                .($multiple == true ? ', multiple:  true': '').
                                "
                            });
                        }());
					</script>
                    ";
                }

                break;

            case 'radio':
            case 'checkbox':

                $opt = explode("|", $option['lookup_query']);
                $opts = '';
                for ($i = 0; $i < count($opt); $i++) {
                    $checked = '';
                    $row = explode(":", $opt[$i]);
                    $opts .= "<option value ='" . $row[0] . "' > " . $row[1] . " </option> ";
                }
                $form = "<select name='$field{$bulk}' class='form-control' $mandatory $simpleSearchOptions>" .
                    ($isSSSFWOBD ? "" : "<option value=''> -- Select  -- </option")
                    . ">$opts</select>";
                break;


        }

        return $form;
    }

    /**
     *
     * @param type $field
     * @param type $forms
     * @param type $bulk
     * @param type $value
     * @return type
     */
    public static function transInlineForm($field, $forms = array(), $bulk = false, $value = '')
    {
        $type = '';
        $bulk = is_string($bulk) ? $bulk : ($bulk === true ? '[]' : '');
        $mandatory = '';
        $attribute = '';
        $extend_class = '';
        $selectMultiple = '';

        foreach ($forms as $f) {
            $hasShow = isset($f['view']) ? $f['view'] == 1 : false;
            if ($f['field'] == $field && $hasShow) {
                $type = ($f['type'] != 'file' ? $f['type'] : '');
                $option = $f['option'];
                $required = $f['required'];
                if ($required == 'required') {
                    $mandatory = "required='required' data-parsley-required='true'";
                } else if ($required == 'email') {
                    $mandatory = "required='required' data-parsley-type'='email' ";
                } else if ($required == 'date') {
                    $mandatory = "required='required' data-parsley-required='true'";
                } else if ($required == 'numeric') {
                    $mandatory = "required='required' data-parsley-type='number' ";
                } else {
                    $mandatory = '';
                }

                if (!empty($option['attribute'])) {
                    $attribute = $option['attribute'];
                }
                if (!empty($option['extend_class'])) {
                    $extend_class = $option['extend_class'];
                }
                $selectMultiple = empty($option['select_multiple_inline']) ? "" : "multiple='multiple'";
                break;
            }
        }

        switch ($type) {
            default:
                $form = '';
                break;
            case 'textarea':
            case 'textarea_editor':
            case 'text':
                $form = "<input  type='text' name='" . $field . "{$bulk}' class='form-control input-sm' $mandatory value='{$value}'/>";
                break;

            case 'text_date':
                $form = "<input  type='text' name='$field{$bulk}' class='date form-control input-sm' $mandatory value='{$value}'/> ";
                break;

            case 'text_datetime':
                $form = "<input  type='text' name='$field{$bulk}'  class='datetime form-control input-sm'  $mandatory value='{$value}'/> ";
                break;

            case 'select':

                if ($option['opt_type'] == 'external') {

                    $opts = '';
                    if ($option['lookup_table'] == 'location') {
                        $lookupParts = explode('|', $option['lookup_value']);
                        if (is_array($lookupParts) && !empty($lookupParts)) {
                            $option['lookup_value'] = $lookupParts[0];
                        }
                        $selected = '';
                        $current_user_id = Auth::id();
                        $user_ids = DB::table('user_locations')->leftjoin('location as l', 'user_locations.location_id', '=', 'l.id')->where('user_locations.user_id', $current_user_id)->orderby('l.' . $option['lookup_value'])->get();
                        foreach ($user_ids as $user_id) {
                            $locations = DB::table($option['lookup_table'])->where('id', $user_id->location_id)->orderby($option['lookup_value'])->get();
                            foreach ($locations as $location) {
                                $value = "";
                                foreach ($lookupParts as $lookup) {
                                    $value .= $location->$lookup . " - ";
                                }
                                $value = trim($value, ' - ');
                                $opts .= "<option $selected  value='" . $location->$option['lookup_key'] . "' $mandatory > " . $value . " </option> ";
                            }

                        }

                    } else {
                        $fields = explode("|", $option['lookup_value']);
                        $search = isset($option['lookup_search']) ? $option['lookup_search'] : '';

                        $query = DB::table($option['lookup_table']);
                        if (!empty($search)) {
                            $searchParts = explode(':', urldecode($search));
                            if (count($searchParts) > 1) {
                                $query->where($searchParts[0], $searchParts[1]);
                            } else {
                                $query->whereRaw($search);
                            }
                        }

                        if ($option['lookup_table'] == 'order_type') {
                            $data = $query->where('can_request', '=', '1')
                                ->orderby($option['lookup_value'])
                                ->groupby($option['lookup_key']);
                        } else {
                            if (count($fields) > 1) {
                                $data = $query
                                    ->where($option['lookup_key'], '!=', '')
                                    ->orderby($option['lookup_key'])
                                    ->groupby($option['lookup_key']);
                            } else {
                                $data = $query
                                    ->where($option['lookup_value'], '!=', '')
                                    ->orderby($option['lookup_value'])
                                    ->groupby($option['lookup_value']);
                            }
                        }
                        $data = $query->get();
                        foreach ($data as $row):
                            $selected = '';
                            $values = explode(',', $value);
                            $valueFound = count($values) > 1 ? in_array($row->$option['lookup_key'], $values) : false;
                            if ($value == $row->$option['lookup_key'] || $valueFound) {
                                $selected = 'selected="selected"';
                            }

                            //print_r($fields);exit;
                            $val = "";
                            foreach ($fields as $item => $v) {
                                if ($v != "") $val .= $row->$v . " ";
                            }
                            $opts .= "<option $selected value='" . $row->$option['lookup_key'] . "' $mandatory > " . $val . " </option> ";
                        endforeach;
                    }

                } else {
                    $opt = explode("|", $option['lookup_query']);
                    $datalistOptions = \FEGHelp::parseStringToArray($option['lookup_query']);
                    $values = explode(',', $value);
                    $opts = '';
                    for ($i = 0; $i < count($opt); $i++) {
                        $selected = '';
                        if ($value == ltrim(rtrim($opt[0]))) {
                            $selected = 'selected="selected"';
                        }
                        $row = explode(":", $opt[$i]);
                        if (count($values) > 1) {
                            $valueFound = in_array(trim($row[0]), $values);
                            if ($valueFound) {
                                $selected = 'selected="selected"';
                            }
                        }

                        $opts .= "<option $selected value ='" . trim($row[0]) . "' > " . $row[1] . " </option> ";
                    }

                }
                $form = "<select name='$field{$bulk}'  class='sel-inline $field{$bulk}' $mandatory {$selectMultiple}>" .
                    "<option value=''> -- Select  -- </option>" .
                    "	$opts
						</select>";
                break;

            case 'radio':
            case 'checkbox':
                $opt = explode("|", $option['lookup_query']);
                $opts = '';
                for ($i = 0; $i < count($opt); $i++) {
                    $checked = '';
                    $row = explode(":", $opt[$i]);
                    $opts .= "<option value ='" . $row[0] . "' > " . $row[1] . " </option> ";
                }
                $form = "<select name='$field{$bulk}' class='sel-inline' $mandatory ><option value=''> -- Select  -- </option>$opts</select>";
                break;

            case '__checkbox':
                $form = "<input type='hidden' name='{$field}{$bulk}' value='$value'/>
                    <input type='checkbox' 
                            data-proxy-input='{$field}' 
                            data-parsley-excluded='true'
                            parsley-excluded='true'
                            {$mandatory} {$attribute} 
                            class='{$extend_class}' 
                            value='{$value}' 
                            " . ($value ? "checked='checked'" : "") . "
                        />";
                break;
            case '__textarea':
                $form = "<textarea rows='1' 
                                name='{$field}{$bulk}' 
                                class='form-control {$extend_class}' 
                                {$mandatory} {$attribute}  
                            >{$value}</textarea>";
                break;
        }

        return $form;
    }

    public static function bulkForm($field, $forms = array(), $value = '')
    {
        $type = '';
        $bulk = 'true';
        $bulk = ($bulk == true ? '[]' : '');
        $mandatory = '';
        foreach ($forms as $f) {
            if ($f['field'] == $field && $f['search'] == 1) {
                $type = ($f['type'] != 'file' ? $f['type'] : '');
                $option = $f['option'];
                $required = $f['required'];

                if ($required == 'required') {
                    $mandatory = "data-parsley-required='true'";
                } else if ($required == 'email') {
                    $mandatory = "data-parsley-type'='email' ";
                } else if ($required == 'date') {
                    $mandatory = "data-parsley-required='true'";
                } else if ($required == 'numeric') {
                    $mandatory = "data-parsley-type='number' ";
                } else {
                    $mandatory = '';
                }
            }
        }
        $field = 'bulk_' . $field;

        switch ($type) {
            default;
                $form = '';
                break;

            case 'text';
                $form = "<input  type='text' name='" . $field . "{$bulk}' class='form-control input-sm' $mandatory value='{$value}'/>";
                break;

            case 'text_date';
                $form = "<input  type='text' name='$field{$bulk}' class='date form-control input-sm' $mandatory value='{$value}'/> ";
                break;

            case 'text_datetime';
                $form = "<input  type='text' name='$field{$bulk}'  class='date form-control input-sm'  $mandatory value='{$value}'/> ";
                break;

            case 'select';


                if ($option['opt_type'] == 'external') {

                    $data = DB::table($option['lookup_table'])->get();
                    $opts = '';
                    foreach ($data as $row):
                        $selected = '';
                        if ($value == $row->$option['lookup_key']) $selected = 'selected="selected"';
                        $fields = explode("|", $option['lookup_value']);
                        //print_r($fields);exit;
                        $val = "";
                        foreach ($fields as $item => $v) {
                            if ($v != "") $val .= $row->$v . " ";
                        }
                        $opts .= "<option $selected value='" . $row->$option['lookup_key'] . "' $mandatory > " . $val . " </option> ";
                    endforeach;

                } else {
                    $opt = explode("|", $option['lookup_query']);
                    $opts = '';
                    for ($i = 0; $i < count($opt); $i++) {
                        $selected = '';
                        if ($value == ltrim(rtrim($opt[0]))) $selected = 'selected="selected"';
                        $row = explode(":", $opt[$i]);
                        $opts .= "<option $selected value ='" . trim($row[0]) . "' > " . $row[1] . " </option> ";
                    }

                }
                $form = "<select name='$field{$bulk}'  class='form-control' $mandatory >
							<option value=''> -- Select  -- </option>
							$opts
						</select>";
                break;

            case 'radio';

                $opt = explode("|", $option['lookup_query']);
                $opts = '';
                for ($i = 0; $i < count($opt); $i++) {
                    $checked = '';
                    $row = explode(":", $opt[$i]);
                    $opts .= "<option value ='" . $row[0] . "' > " . $row[1] . " </option> ";
                }
                $form = "<select name='$field{$bulk}' class='form-control' $mandatory ><option value=''> -- Select  -- </option>$opts</select>";
                break;

        }

        return $form;
    }

    public static function viewColSpan($grid)
    {
        $i = 0;
        foreach ($grid as $t):
            if ($t['view'] == '1') ++$i;
        endforeach;
        return $i;
    }

    public static function blend($str, $data)
    {
        $src = $rep = array();

        foreach ($data as $k => $v) {
            $src[] = "{" . $k . "}";
            $rep[] = $v;
        }

        if (is_array($str)) {
            foreach ($str as $st) {
                $res[] = trim(str_ireplace($src, $rep, $st));
            }
        } else {
            $res = str_ireplace($src, $rep, $str);
        }

        return $res;

    }

    public static function toJavascript($forms, $app, $class)
    {
        $f = '';
        foreach ($forms as $form) {
            if ($form['view'] != 0) {
                if (preg_match('/(select)/', $form['type'])) {
                    if ($form['option']['opt_type'] == 'external') {
                        $table = $form['option']['lookup_table'];
                        $val = $form['option']['lookup_value'];
                        $key = $form['option']['lookup_key'];
                        $search = isset($form['option']['lookup_search']) ? $form['option']['lookup_search'] : '';
                        $lookey = '';
                        if ($form['option']['is_dependency']) $lookey .= $form['option']['lookup_dependency_key'];
                        $f .= self::createPreCombo($form['field'], $table, $key, $val, $app, $class, $lookey, $search);

                    }

                }

            }

        }
        return $f;

    }

    public static function createPreCombo($field, $table, $key, $val, $app, $class, $lookey = null, $search = null)
    {
        $parent = null;
        $parent_field = null;
        if ($lookey != null) {
            $parent = " parent: '#" . $lookey . "',";
            $parent_field = "&parent={$lookey}:";
        }
        $searchQuery = '';
        if (!empty($search)) {
            $searchQuery = "&search=" . urlencode($search);
        }
        $pre_jCombo = "
        \$(\"#{$field}\").jCombo(\"{{ URL::to('{$class}/comboselect?filter={$table}:{$key}:{$val}') }}$parent_field{$searchQuery}\",
        { " . $parent . " selected_value : '{{ \$row[\"{$field}\"] }}' });
        ";
        return $pre_jCombo;
    }

    static public function showNotification()
    {
        $status = Session::get('msgstatus');
        if (Session::has('msgstatus')): ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    toastr.<?php echo $status;?>("", "<?php echo Session::get('messagetext');?>");
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": "toast-bottom-right",
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"

                    }
                });
            </script>
        <?php endif;
    }

    public static function alert($task, $message)
    {
        if ($task == 'error') {
            $alert = '
			<div class="alert alert-danger  fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-cancel-circle"></i> ' . $message . ' </div>
			';
        } elseif ($task == 'success') {
            $alert = '
			<div class="alert alert-success fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-checkmark-circle"></i> ' . $message . ' </div>
			';
        } elseif ($task == 'warning') {
            $alert = '
			<div class="alert alert-warning fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-warning"></i> ' . $message . ' </div>
			';
        } else {
            $alert = '
			<div class="alert alert-info  fade in block-inner">
				<button data-dismiss="alert" class="close" type="button"> x </button>
			<i class="icon-info"></i> ' . $message . ' </div>
			';
        }
        return $alert;

    }

    public static function _sort($a, $b)
    {

        if ($a['sortlist'] == $a['sortlist']) {
            return strnatcmp($a['sortlist'], $b['sortlist']);
        }
        return strnatcmp($a['sortlist'], $b['sortlist']);
    }


    static public function cropImage($nw, $nh, $source, $stype, $dest)
    {
        $size = getimagesize($source); // ukuran gambar
        $w = $size[0];
        $h = $size[1];
        switch ($stype) { // format gambar
            default :
                $simg = imagecreatefromjpeg($source);
                break;

            case 'gif':
                $simg = imagecreatefromgif($source);
                break;

            case 'png':
                $simg = imagecreatefrompng($source);
                break;
        }
        $dimg = imagecreatetruecolor($nw, $nh); // menciptakan image baru
        $wm = $w / $nw;
        $hm = $h / $nh;
        $h_height = $nh / 2;
        $w_height = $nw / 2;
        if ($w > $h) {
            $adjusted_width = $w / $hm;
            $half_width = $adjusted_width / 2;
            $int_width = $half_width - $w_height;
            imagecopyresampled($dimg, $simg, -$int_width, 0, 0, 0, $adjusted_width, $nh, $w, $h);
        } elseif (($w < $h) || ($w == $h)) {
            $adjusted_height = $h / $wm;
            $half_height = $adjusted_height / 2;
            $int_height = $half_height - $h_height;
            imagecopyresampled($dimg, $simg, 0, -$int_height, 0, 0, $nw, $adjusted_height, $w, $h);
        } else {
            imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $nw, $nh, $w, $h);
        }
        imagejpeg($dimg, $dest, 100);
    }


    public static function showUploadedFile($file, $path, $width = 50, $circle = true, $id = 0,$setdescription=false,$description='',$hidenav=true)
    {
        $files = public_path() . $path . $file;

        if (file_exists($files) && $file != "") {

            $info = pathinfo($files);
            if ($info['extension'] == "jpg" || $info['extension'] == "jpeg" || $info['extension'] == "png" || $info['extension'] == "gif" || $info['extension'] == "JPG") {

                $path_file = str_replace("./", "", $path);
                if ($circle) {
                    $class = "img-circle";
                } else {
                    $class = 'img';
                }
                $rel="";
                if($hidenav == true) {
                    $rel = "gallery" . $id;
                }
                $onclick="";
                if($setdescription==true){
                    $onclick = "onclick='showImageModal(10,this); return false;'";
                }else{
                    $onclick=' class="previewImage fancybox" ';
                }
                return '<p><a image-description="'.$description.'"  '.$onclick.'  href="' . url($path_file . $file) . '" target="_blank"  data-fancybox-group="' . $rel . '"  rel="' . $rel . '">
				<img style="box-shadow:1px 1px 5px gray" src="' . asset($path_file . $file) . '" border="0" width="' . $width . '" class="' . $class . '"  /></a></p>';
            } else {
                $path_file = str_replace("./", "", $path);
                return '<p> <a  href="' . url($path_file . $file) . '" target="_blank"> ' . $file . ' </a>';
            }

        } else {

            return "<img src='" . asset('/upload/images/no-image.png') . "' border='0' width='" . $width . "' /></a>";

        }

    }

    public static function globalXssClean()
    {
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }

    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }

        return $result;
    }

    public static function writeEncoder($val)
    {
        return base64_encode($val);
    }

    public static function readEncoder($val)
    {
        return base64_decode($val);
    }

    public static function gridDisplay($val, $field, $arr)
    {


            if (isset($arr['valid']) && $arr['valid'] == 1) {
                $fields = str_replace("|", ",", $arr['display']);
                $Q = DB::select(" SELECT " . $fields . " FROM " . $arr['db'] . " WHERE " . $arr['key'] . " = '" . $val . "' ");
                if (count($Q) >= 1) {
                    $row = $Q[0];
                    $fields = explode("|", $arr['display']);
                    $v = '';
                    if (isset($fields[0]) && (empty($row->$fields[0]) || $row->$fields[0] == 0)) {
                        $v = "No Data";
                    } else {
                        $v .= (isset($fields[0]) && $fields[0] !== '' ? $row->$fields[0] . ' ' : '');
                    }

                    if (isset($fields[1]) && (empty($row->$fields[1]) || $row->$fields[1] == 0)) {
                        $v = "No Data";
                    } else {
                        $v .= (isset($fields[1]) && $fields[1] !== '' ? $row->$fields[1] . ' ' : '');
                    }
                    if (isset($fields[2]) && (empty($row->$fields[2]) || $row->$fields[2] == 0)) {
                        $v = "No Data";
                    } else {
                        $v .= (isset($fields[2]) && $fields[2] !== '' ? $row->$fields[2] . ' ' : '');
                    }
                    /*    $v .= (isset($fields[0]) && $fields[0] != '' ? $row->$fields[0] . ' ' : '');
                        $v .= (isset($fields[1]) && $fields[1] != '' ? $row->$fields[1] . ' ' : '');
                        $v .= (isset($fields[2]) && $fields[2] != '' ? $row->$fields[2] . ' ' : '');*/


                    return $v;
                } else {
                    return '';
                }
            } else {
                if (empty($val) || $val===0) {
                    $val = "No Data";
                }
                return $val;
            }

    }

    public static function gridDisplayView($val, $field, $arr,$nodata=0)
    {

        $arr = explode(':', $arr);

        if (isset($arr['0']) && $arr['0'] == 1 && (is_numeric($val))) {
            $Q = DB::select(" SELECT " . str_replace("|", ",", $arr['3']) . " FROM " . $arr['1'] . " WHERE " . $arr['2'] . " = '" . $val . "' ");
            if (count($Q) >= 1) {

                $row = $Q[0];
                $fields = explode("|", $arr['3']);
                $v = '';
                $v .= (isset($fields[0]) && $fields[0] != '' ? $row->$fields[0] . ' ' : '');
                if (isset($fields[1]) && empty($row->$fields[1])) {
                    $v = "No Data";
                } else {
                    $v .= (isset($fields[1]) && $fields[1] != '' ? $row->$fields[1] . ' ' : '');
                }

                if (isset($fields[2]) && !empty($row->$fields[2])) {
                    $v .= (isset($fields[2]) && $fields[2] != '' ? $row->$fields[2] . ' ' : '');
                }
                return $v;
            } else {
                $val = "";

            }

        }
        if (($val === "0" || $val === 0 || $val === NULL || $val === "" || empty($val)) && $nodata == 0) {
            $val = "No Data";
        }
        return $val;
    }

    public static function langOption()
    {
        $path = base_path() . '/resources/lang/';
        $lang = scandir($path);

        $t = array();
        foreach ($lang as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (is_dir($path . $value)) {
                $fp = file_get_contents($path . $value . '/info.json');
                $fp = json_decode($fp, true);
                $t[] = $fp;
            }

        }
        return $t;
    }


    public static function themeOption()
    {

        $path = base_path() . '/resources/views/layouts/';
        $lang = scandir($path);
        $t = array();
        foreach ($lang as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (is_dir($path . $value)) {
                $fp = file_get_contents($path . $value . '/info.json');
                $fp = json_decode($fp, true);
                $t[] = $fp;
            }

        }
        return $t;
    }

    public static function avatar($width = 75)
    {
        $avatar = '<img alt="" src="' . url() . '/silouette.png" class="img-circle" width="' . $width . '" />';
        $Q = DB::table("users")->where("id", '=', Session::get('uid'))->get();
        if (count($Q) >= 1) {
            $row = $Q[0];
            $files = './uploads/users/' . $row->avatar;
            if ($row->avatar != '') {
                if (file_exists($files)) {
                    return '<img src="' . URL::to('uploads/users') . '/' . $row->avatar . '" border="0" width="' . $width . '" class="img-circle" />';
                } else {
                    return $avatar;
                }
            } else {
                return $avatar;
            }
        }
    }


    public static function BBCode2Html($text)
    {

        $emotion = URL::to('sximo/js/plugins/markitup/images/emoticons/');

        $text = trim($text);

        // BBCode [code]
        if (!function_exists('escape')) {
            function escape($s)
            {
                global $text;
                $text = strip_tags($text);
                $code = $s[1];
                $code = htmlspecialchars($code);
                $code = str_replace("[", "&#91;", $code);
                $code = str_replace("]", "&#93;", $code);
                return '<pre class="prettyprint linenums"><code>' . $code . '</code></pre>';
            }
        }
        $text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);

        // Smileys to find...
        $in = array(':)',
            ':D',
            ':o',
            ':p',
            ':(',
            ';)'
        );
        // And replace them by...
        $out = array('<img alt=":)" src="' . $emotion . 'emoticon-happy.png" />',
            '<img alt=":D" src="' . $emotion . 'emoticon-smile.png" />',
            '<img alt=":o" src="' . $emotion . 'emoticon-surprised.png" />',
            '<img alt=":p" src="' . $emotion . 'emoticon-tongue.png" />',
            '<img alt=":(" src="' . $emotion . 'emoticon-unhappy.png" />',
            '<img alt=";)" src="' . $emotion . 'emoticon-wink.png" />'
        );
        $text = str_replace($in, $out, $text);

        // BBCode to find...
        $in = array('/\[b\](.*?)\[\/b\]/ms',
            '/\[div\="?(.*?)"?](.*?)\[\/div\]/ms',
            '/\[i\](.*?)\[\/i\]/ms',
            '/\[u\](.*?)\[\/u\]/ms',
            '/\[img\](.*?)\[\/img\]/ms',
            '/\[email\](.*?)\[\/email\]/ms',
            '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
            '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
            '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
            '/\[quote](.*?)\[\/quote\]/ms',
            '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
            '/\[list\](.*?)\[\/list\]/ms',
            '/\[\*\]\s?(.*?)\n/ms'
        );
        // And replace them by...
        $out = array('<strong>\1</strong>',
            '<div class="\1">\2</div>',
            '<em>\1</em>',
            '<u>\1</u>',
            '<img src="\1" alt="\1" />',
            '<a href="mailto:\1">\1</a>',
            '<a href="\1">\2</a>',
            '<span style="font-size:\1%">\2</span>',
            '<span style="color:\1">\2</span>',
            '<blockquote>\1</blockquote>',
            '<ol start="\1">\2</ol>',
            '<ul>\1</ul>',
            '<li>\1</li>'
        );
        $text = preg_replace($in, $out, $text);

        // paragraphs
        $text = str_replace("\r", "", $text);
        $text = "<p>" . preg_replace("/(\n){2,}/", "</p><p>", $text) . "</p>";
        $text = nl2br($text);

        // clean some tags to remain strict
        // not very elegant, but it works. No time to do better ;)
        if (!function_exists('removeBr')) {
            function removeBr($s)
            {
                return str_replace("<br />", "", $s[0]);
            }
        }
        $text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
        $text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);

        $text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
        $text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);

        return $text;
    }

    public static function seoUrl($str, $separator = 'dash', $lowercase = FALSE)
    {
        if ($separator == 'dash') {
            $search = '_';
            $replace = '-';
        } else {
            $search = '-';
            $replace = '_';
        }

        $trans = array(
            '&\#\d+?;' => '',
            '&\S+?;' => '',
            '\s+' => $replace,
            '[^a-z0-9\-\._]' => '',
            $replace . '+' => $replace,
            $replace . '$' => $replace,
            '^' . $replace => $replace,
            '\.+$' => ''
        );

        $str = strip_tags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        if ($lowercase === TRUE) {
            $str = strtolower($str);
        }

        return trim(stripslashes(strtolower($str)));
    }


    static function renderHtml($html)
    {

        $html = preg_replace('/(\.+\/)+uploads/Usi', URL::to('uploads'), $html);
        //	$content = str_replace($pattern , URL::to('').'/', $content );
        preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all("#</([a-z]+)>#iU", $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= "</" . $openedtags[$i] . ">";
            } else {
                unset ($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;


    }

    public static function activeLang($label, $l)
    {

        $activeLang = \Session::get('lang');
        $lang = (isset($l[$activeLang]) ? $l[$activeLang] : $label);
        return $lang;

    }

    public static function infoLang($label, $l, $t = 'title')
    {
        $activeLang = Session::get('lang');
        $lang = (isset($l[$t][$activeLang]) ? $l[$t][$activeLang] : $label);
        return $lang;

    }

    public static function auditTrail($request, $note)
    {
        $data = array(
            'module' => $request->segment(1),
            'task' => $request->segment(2),
            'user_id' => \Session::get('uid'),
            'ipaddress' => $request->getClientIp(),
            'note' => $note
        );

        \DB::table('tb_logs')->insert($data);

    }

    static function storeNote($args)
    {
        $args = array_merge(array(
            'url' => '#',
            'userid' => '0',
            'title' => '',
            'note' => '',
            'created' => date("Y-m-d H:i:s"),
            'icon' => 'fa fa-envelope',
            'is_read' => 0
        ), $args);


        \DB::table('tb_notification')->insert($args);
    }

    static function isModuleEnabled($moduleName)
    {
        //$Q = DB::select(" SELECT ".$fields." FROM ".$arr['db']." WHERE ".$arr['key']." = '".$val."' ");
        $result = DB::select("SELECT module_id FROM tb_module WHERE module_name = '$moduleName'");
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    static function getColsConfigs($module_id)
    {
        $result = \DB::select("SELECT * FROM user_module_config where module_id='$module_id'");
        return $result;
    }

    static function showRequiredCols($tableGrid, $cols)
    {
        $cols = explode(',', $cols);
        $table = array();
        $i = 0;
        foreach ($cols as $col) {
            foreach ($tableGrid as $t) {

                if ($col == $t['field']) {
                    $table[$i] = $t;
                    $i++;
                }
            }
        }
        return $table;
    }

    static function showRequiredCols_v2($tableGrid, $cols)
    {
        $columns = explode(',', $cols);
        $table = [];
        $i = 0;
        foreach ($tableGrid as $t) {
            $fieldName = $t['field'];
            if (in_array($fieldName, $columns)) {
                $index = array_search($fieldName, $columns);
                $table[$index] = $t;
            }
        }
        return array_values($table);
    }

    static function getAllGroups()
    {
        $groups = \DB::table('tb_groups')->get();
        return $groups;
    }

    static function getRequiredConfigs($module_id)
    {
        $group_id = Session::get('gid');
        $user_id = Session::get('uid');
        $configs = array();
        $i = 0;
        //get all the configurations against a module
        $result = \DB::table('user_module_config')->where(array('module_id' => $module_id))->get();

        foreach ($result as $t) {
            // if configuration is private only show to owner
            if ($t->is_private == 1) {
                if ($t->user_id == $user_id) {
                    $configs[$i] = array('config_name' => $t->config_name, 'config_id' => $t->id);
                    $i++;
                }
            } // if configuration is public check for the group
            else {
                if ($t->group_id == $group_id) {
                    $configs[$i] = array('config_name' => $t->config_name, 'config_id' => $t->id);
                    $i++;
                } //show configuration for owner of the configuration
                elseif ($t->user_id == $user_id) {
                    $configs[$i] = array('config_name' => $t->config_name, 'config_id' => $t->id);
                    $i++;
                }
            }

        }

        return $configs;

    }

    static function getGameImage($game_title_id)
    {
        $img = \DB::table('game_title')->where('id', '=', $game_title_id)->pluck('img');
        return $img;
    }

    static function getDateDiff($first, $second)
    {
        $datetime1 = new DateTime($first);
        $datetime2 = new DateTime($second);
        if ($second != 00 && $first != 00) {
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format("%a");
            return $days;
        } else {
            return "N/A";
        }

    }

    static function showLink()
    {


    }

    public function validate()
    {
        $cols = 'U.id,
						   U.user_name,
						   U.first_name,
						   U.last_name,
						   U.email,
						   U.user_level,
						   U.company_id,
						   U.loc_1,
						   U.loc_2,
						   U.loc_3,
						   U.loc_4,
						   U.loc_5,
						   U.loc_6,
						   U.loc_7,
						   U.loc_8,
						   U.loc_9,
						   U.loc_10,
						   U.get_locations_by_region,
						   U.email_2,
						   U.primary_phone,
						   U.secondary_phone,
						   U.street,
						   U.city,
						   U.state,
						   U.zip,
						   U.reg_id,
						   U.restricted_mgr_email,
						   U.restricted_user_email';


        /* if(!empty($google_email))
        {
            $this->db->from('users U');
            $this->db->where('U.email', $google_email);
            $this->db->where('U.active', 1);
            $query = $this->db->get();
        }*/
//        else
        //      {
        //$password = $this->input->post('password');
        $row = \DB::Select($cols . 'FROM users U WHERE U.id=' . \Session::get('uid'));

        //    }

        if (count($row) == 1) {
            $data['is_logged_in'] = TRUE;
            $data['last_url'] = $this->input->post('last_url');
            $data['selected_location'] = $data['loc_1'];
            $this->db->select('location_name_short');
            $this->db->from('location');
            $this->db->where('id', $data['loc_1']);
            $locQuery = $this->db->get();

            if ($locQuery->num_rows == 1) {
                $locData = $locQuery->row_array();
                $data['selected_location_name'] = $locData['location_name_short'];
            }

            if ($data['user_level'] == Groups::DISTRICT_MANAGER || $data['reg_id'] > 1) // IF USER IS DISTRICT MANAGER OR LOCATIONS ARE BASED ON REGION (TYPICALLY USED FOR MANY, ROUTE LOCATIONS)
            {
                if ($data['user_level'] == Groups::DISTRICT_MANAGER) {
                    $distMgrQuery = $this->db->query("SELECT DISTINCT GROUP_CONCAT(L.id) AS LocationIdList
														 FROM location L
														WHERE L.region_id IN(
																SELECT R.id
																  FROM region R
																 WHERE R.dist_mgr_id =" . $data['id'] . "
																)
														   OR L.id IN(" . $data['loc_1'] . "," . $data['loc_2'] . "," . $data['loc_3'] . "," . $data['loc_4'] . "," . $data['loc_5'] . "," . $data['loc_6'] . "," . $data['loc_7'] . "," . $data['loc_8'] . "," . $data['loc_9'] . "," . $data['loc_10'] . ")
													 	  AND L.active = 1
													 ORDER BY L.id");

                    foreach ($distMgrQuery->result() as $row) {
                        $reg_loc_ids = $row->LocationIdList;
                    }
                } else {
                    $locByRegQuery = $this->db->query("SELECT DISTINCT GROUP_CONCAT(L.id) AS LocationIdList
														 FROM location L
														WHERE L.region_id IN(" . $data['reg_id'] . ")
														   OR L.id IN(" . $data['loc_1'] . "," . $data['loc_2'] . "," . $data['loc_3'] . "," . $data['loc_4'] . "," . $data['loc_5'] . "," . $data['loc_6'] . "," . $data['loc_7'] . "," . $data['loc_8'] . "," . $data['loc_9'] . "," . $data['loc_10'] . ")
													      AND L.active = 1
													    ORDER BY L.id");

                    foreach ($locByRegQuery->result() as $row) {
                        $reg_loc_ids = $row->LocationIdList;
                    }
                }
            } else {
                $reg_loc_ids = $data['loc_1'];
                if (!empty($data['loc_2'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_2'];
                }
                if (!empty($data['loc_3'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_3'];
                }
                if (!empty($data['loc_4'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_4'];
                }
                if (!empty($data['loc_5'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_5'];
                }
                if (!empty($data['loc_6'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_6'];
                }
                if (!empty($data['loc_7'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_7'];
                }
                if (!empty($data['loc_8'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_8'];
                }
                if (!empty($data['loc_9'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_9'];
                }
                if (!empty($data['loc_10'])) {
                    $reg_loc_ids = $reg_loc_ids . ',' . $data['loc_10'];
                }
            }

            $data['reg_loc_ids'] = $reg_loc_ids;
            if (!empty($google_email)) {
                $data['login_type'] = 'google';
            } else {
                $data['login_type'] = 'standard';
            }

            $this->load->helper('date');
            $update = array('last_login' => date('Y-m-d H:i:s', now()));
            $this->db->where('id', $data['id']);
            $this->db->update('users', $update);

            $this->session->set_userdata($data);
            return TRUE;
        }
    }

    static function getGamesName()
    {
        $row = \DB::table('game')->select('id', 'game_name')->get();
        return $row;
    }

    static function getBudgetYears()
    {
        $row = \DB::select('select YEAR(budget_date) as year from location_budget group by YEAR(budget_date)');
        return $row;
    }

    static function getDebitTypes()
    {
        return \DB::table('debit_type')->get();
    }

    /**
     * Get any field's value from location table. For example, location_name
     * @param number $loc_id
     * @param string $field
     * @param string $default
     * @return mixed    string value if $field is specified else an object. $default is not found.
     */
    public static function getLocationInfoById($loc_id = null, $field = null, $default = "")
    {
        if (is_null($field)) {
            $query = \DB::select("SELECT * FROM location WHERE id = '$loc_id'");
        } else {
            $query = \DB::select("SELECT $field FROM location WHERE id = '$loc_id'");
        }

        $data = [];
        if (isset($query[0])) {
            $data = $query[0];
        }
        if (is_null($field)) {
            return $data;
        }
        if (!empty($data)) {
            if (is_array($data)) {
                $value = $data[$field];
            } else {
                $value = $data->$field;
            }
        }
        if (empty($value)) {
            $value = $default;
        }
        return $value;
    }

    /**
     * Get Locations details of locations assigned to a user
     * @param number $id User ID
     * @return array
     */
    static function getLocationDetails($id,$canSeeAllLocations = false, $extra = [])
    {
    	if($canSeeAllLocations)
	    {
            $locations = \DB::table('user_locations')
                ->join('location', 'user_locations.location_id', '=', 'location.id')
                ->leftJoin('debit_type', 'debit_type.id', '=', 'location.debit_type_id')
                ->select(DB::raw(implode(',', [
                    'DISTINCT location.id',
                    'location.location_name',
                    'location.location_name_short',
                    'location.debit_type_id',
                    'debit_type.company',
                    'location.street1',
                    'location.state',
                    'location.city',
                    'location.zip'])))
                ->where('location.active', 1)
                ->orderBy('id', 'asc')
                ->get();
	    }
	    else{
            $locations = \DB::table('user_locations')
                ->join('location', 'user_locations.location_id', '=', 'location.id')
                ->leftJoin('debit_type', 'debit_type.id', '=', 'location.debit_type_id')
                ->select(DB::raw(implode(',', [
                    'DISTINCT location.id',
                    'location.location_name',
                    'location.location_name_short',
                    'location.debit_type_id',
                    'debit_type.company',
                    'location.street1',
                    'location.state',
                    'location.city',
                    'location.zip'])))
                ->where('location.active', 1)
                ->where('user_locations.user_id', '=', $id)->orderBy('id', 'asc');

            if(!empty($extra) && isset($extra['method']) && isset($extra['field']) && isset($extra['data'])) {
                $locations = $locations->{$extra['method']}($extra['field'], $extra['data']);
            }

            $locations = $locations->get();
	    }

        return $locations;
    }

    /**
     * Get Location IDs in an indexed array from list of locations
     * @param type $userLocations
     * @return type
     */
    static function getIdsFromLocationDetails($userLocations)
    {
        $locations = array();
        if (!empty($userLocations)) {
            foreach ($userLocations as $location) {
                $locations[] = $location->id;
            }
        }
        return $locations;
    }

    public static function getCurrentUserLocationsFromSession($asArray = false)
    {
//        $locations = array();        
//        $hasAllLocations = \Session::get('user_has_all_locations') == 1;
//        if ($hasAllLocations) {
//            $locations = \Session::get('user_location_ids');            
//        }       

        $locations = \Session::get('user_location_ids');
        if ($locations === null) {
            $locations = array();
        }
        if (!$asArray) {
            $locations = implode(',', $locations);
        }
        return $locations;
    }

    static function getQueryStringForLocation($table, $fieldName = 'location_id', $addOnLocations = array(), $orClause = '',$canSeeAllLocations = false)
    {
        $locationsData = self::getLocationDetails(\Session::get('uid'),$canSeeAllLocations);
        $locations = is_array($addOnLocations) ? $addOnLocations : array();
        foreach ($locationsData as $locationItem) {
            $locations[] = "'" . $locationItem->id . "'";
        }
        $locationsCSV = implode(',', $locations);
        if (empty($locationsCSV)) {
            $locationsCSV = 'NULL';
        }
        $queryString = " AND ($table.$fieldName IN ($locationsCSV) " .
            (!empty($orClause) ? $orClause : '') . ")";

        return $queryString;
    }

    static function checkLocationIdIsExistOrNot($id)
    {
        $queryString = '';
        $locations = self::getLocationDetails(\Session::get('uid'));
        foreach ($locations as $index => $location) {
            if ($location->id == $id) {
                $queryString .= " AND service_requests.location_id = '$id'";
                break;
            }
        }
        if (empty($queryString)) {
            $queryString = self::getQueryStringForLocation();
        }
        return $queryString;
    }

    static function getOrderHistory()
    {
        \DB::enableQueryLog();
        $loc1 = \Session::get('selected_location');
        $reg_id = \Session::get('reg_id');
        $curMonth = date('M');
        $prevMonth = date("M", strtotime("-1 month"));

        $curMonthFull = date("F");
        $prevMonthFull = date("F", strtotime("-1 month"));
        $curMonthNumber = date('m');
        $prevMonthNumber = date("m", strtotime("-1 month"));
        $curYear = date('Y');

        if ($curMonth == 'Jan') {
            $prevMonthYear = date("Y", strtotime("-1 year"));
        } else {
            $prevMonthYear = $curYear;
        }
        $user_level = \Session::get('gid');
        if ($user_level == Groups::USER || $user_level == Groups::PARTNER || $user_level == Groups::DISTRICT_MANAGER || $user_level == Groups::PARTNER_PLUS || $user_level == Groups::TECHNICAL_MANAGER) {
            $query = \DB::select('SELECT (SELECT SUM(budget_value) FROM location_budget
											WHERE location_id=' . $loc1 . ' AND MONTH(budget_date) = ' . $curMonthNumber . ' AND YEAR(budget_date)=' . $curYear . ')
											   AS monthly_merch_budget,
										  (SELECT SUM(budget_value) FROM location_budget
											WHERE location_id=' . $loc1 . ' AND MONTH(budget_date) = ' . $prevMonthNumber . ' AND YEAR(budget_date)=' . $curYear . ')
											   AS last_month_merch_budget,
										  (SELECT SUM(order_total)
										  	 FROM orders
											WHERE order_type_id IN(7,8)
											  AND MONTH(date_ordered)=' . $curMonthNumber . '
											  AND YEAR(date_ordered)=' . $curYear . '
											  AND location_id=' . $loc1 . ')
										  	   AS monthly_merch_order_total,
									      (SELECT SUM(order_total)
										     FROM orders
										    WHERE order_type_id IN(7,8)
										      AND MONTH(date_ordered)=' . $prevMonthNumber . '
											  AND YEAR(date_ordered)=' . $prevMonthYear . '
										      AND location_id=' . $loc1 . ')
										 	   AS last_month_merch_order_total,
									      (SELECT SUM(order_total)
									 	     FROM orders
									 	    WHERE order_type_id NOT IN(7,8,18,27,28)
									 	      AND MONTH(date_ordered)=' . $curMonthNumber . '
											  AND YEAR(date_ordered)=' . $curYear . '
									 	      AND location_id=' . $loc1 . ')
									 		   AS monthly_else_order_total,
									      (SELECT SUM(order_total)
										     FROM orders
										    WHERE order_type_id not in(27,28) and YEAR(date_ordered)=' . $curYear . '
										      AND location_id=' . $loc1 . ')
											   AS annual_order_total');
            $data['user_group'] = "regusers";
        } else if ($user_level == Groups::DISTRICT_MANAGER) {
            $query = \DB::select('SELECT (SELECT SUM(budget_value) FROM location_budget left join location on location_budget.location_id = location.id where MONTH(budget_date) = ' . $curMonthNumber . ' AND YEAR(budget_date)=' . $curYear . '
										 AND location.region_id=' . $reg_id . ')
										   AS monthly_merch_budget,
									  (SELECT  SUM(budget_value) FROM location_budget left join location on location_budget.location_id = location.id where MONTH(budget_date) = ' . $prevMonthNumber . ' AND YEAR(budget_date)=' . $curYear . '
										  AND location.region_id=' . $reg_id . ')
										   AS last_month_merch_budget,
									  (SELECT SUM(O.order_total) FROM orders O, location L
										WHERE O.location_id = ' . $loc1 . '
										  AND order_type_id IN(7,8)
										  AND MONTH(O.date_ordered)=' . $curMonthNumber . '
										  AND YEAR(O.date_ordered)=' . $curYear . '
										  AND L.region_id=' . $reg_id . ')
											AS monthly_merch_order_total,
									   (SELECT SUM(O.order_total) FROM orders O, location L
										WHERE O.location_id = ' . $loc1 . '
										  AND order_type_id IN(7,8)
										  AND MONTH(O.date_ordered)=' . $prevMonthNumber . '
										  AND YEAR(O.date_ordered)=' . $prevMonthYear . '
										  AND L.region_id=' . $reg_id . ')
											AS last_month_merch_order_total,
									   (SELECT SUM(O.order_total) FROM orders O, location L
									   	 WHERE O.location_id = ' . $loc1 . '
									 	   AND order_type_id NOT IN(7,8,18,27,28)
										   AND MONTH(O.date_ordered)=' . $curMonthNumber . '
										   AND YEAR(O.date_ordered)=' . $curYear . '
										   AND L.region_id=' . $reg_id . ')
											AS monthly_else_order_total,
									   (SELECT SUM(O.order_total) FROM orders O, location L
										 WHERE O.order_type_id not in(27,28) and YEAR(O.date_ordered)=' . $curYear . '
									   	   AND O.location_id = ' . $loc1 . '
										   AND L.region_id=' . $reg_id . ')
										    AS annual_order_total');
            $data['user_group'] = "distmgr";
        } else {
            $query = \DB::select('SELECT (SELECT SUM(budget_value) FROM location_budget where location_id=' . $loc1 . ' AND MONTH(budget_date) =' . $curMonthNumber . ' AND YEAR(budget_date)=' . $curYear . ' )
										   AS monthly_merch_budget,
									  (SELECT SUM(budget_value) FROM location_budget where location_id=' . $loc1 . ' AND MONTH(budget_date) =' . $prevMonthNumber . ' AND YEAR(budget_date)=' . $prevMonthYear . ')
										   AS last_month_merch_budget,
									  (SELECT SUM(order_total) FROM orders
										WHERE MONTH(date_ordered)=' . $curMonthNumber . '
										  AND YEAR(date_ordered)=' . $curYear . '
										  AND location_id=' . $loc1 . '
										  AND order_type_id IN(7,8))
										   AS monthly_merch_order_total,
									  (SELECT SUM(order_total) FROM orders
										WHERE MONTH(date_ordered)=' . $prevMonthNumber . '
										  AND YEAR(date_ordered)=' . $prevMonthYear . '
										  AND location_id=' . $loc1 . '
										  AND order_type_id IN(7,8))
										   AS last_month_merch_order_total,
									  (SELECT SUM(order_total) FROM orders
										WHERE MONTH(date_ordered)=' . $curMonthNumber . '
										  AND YEAR(date_ordered)=' . $curYear . '
										  AND location_id=' . $loc1 . '
										  AND order_type_id NOT IN(7,8,27,28))
										   AS monthly_else_order_total,
									  (SELECT SUM(order_total) FROM orders
										WHERE order_type_id not in(27,28) and YEAR(date_ordered)=' . $curYear . '
										AND location_id=' . $loc1 . ')
										   AS annual_order_total');
            $data['user_group'] = "";
        }
        $data['monthly_merch_budget'] = $query[0]->monthly_merch_budget;
        $data['monthly_merch_order_total'] = '$'.number_format($query[0]->monthly_merch_order_total, 2, '.', ',');
        $data['monthly_else_order_total'] = '$'.number_format($query[0]->monthly_else_order_total, 2, '.', ',');
        $data['annual_order_total'] = $query[0]->annual_order_total;
        $data['last_month_merch_budget'] = $query[0]->last_month_merch_budget;
        $data['last_month_merch_order_total'] = $query[0]->last_month_merch_order_total;
        $data['monthly_merch_remaining'] = '$'.number_format(($data['monthly_merch_budget'] - $query[0]->monthly_merch_order_total), 2, '.', ',');
        $data['last_month_merch_remaining'] = '$'.number_format(($data['last_month_merch_budget'] - $data['last_month_merch_order_total']), 2, '.', ',');
        $data['curMonthFull'] = $curMonthFull;
        $data['prevMonthFull'] = $prevMonthFull;
        $data['curMonth'] = $curMonth;
        $data['curYear'] = $curYear;
        $data['selected_location'] = $loc1;
        $data['reg_id'] = $reg_id;
        $queries = \DB::getQueryLog();

        return $data;
    }

    static function getRegionName($id = null)
    {
        $region_name = \DB::select('select region from region where id=' . $id);
        return $region_name[0]->region;
    }

    static function getLocationName($id = null)
    {
        $location_name = \DB::select('select location_name from location where id=' . $id);
        return !empty($location_name) ? $location_name[0]->location_name : "";
    }

    static function configureSimpleSearchForm($data)
    {
        $newArray = array();
        foreach ($data as $item) {
            if (isset($item['simplesearch']) && $item['simplesearch'] == '1') {
                $item['generatingSimpleSearch'] = true;
                $newArray[] = $item;
            }
        }

        foreach ($newArray as $key => &$item) {

            $width = $item['simplesearchfieldwidth'];
            $widthClass = "";
            $widthStyle = "";
            if (preg_match('/^[\_a-zA-Z]/', $width) == 1) {
                $widthClass = $width;
            } else {
                $widthStyle = 'width:' . $width . ';';
            }
            $item['widthClass'] = $widthClass;
            $item['widthStyle'] = $widthStyle;

        }

        uasort($newArray, function ($a, $b) {
            return ($a['simplesearchorder'] >= $b['simplesearchorder'] ? 1 : -1);
        });

        return $newArray;
    }


    static function getProductName($id)
    {
        return \DB::table('products')->where('id', $id)->pluck('vendor_description');
    }

    static function getConfigOwner($config_id)
    {
        $user_id = \DB::table('user_module_config')->where('id', '=', $config_id)->pluck('user_id');
        return $user_id;
    }

    /**
     * Add all unassigned locations to users set with All Locations = true
     * (having has_all_locations=1)
     * The two parameters of the function can use used when an existing location id has been modified
     * @param number $location [optional] Location ID after changed
     * @param number $replaceLocation [optional] Location ID before changed
     * @param bool $skipInactive [optional] When set to false would also assign inactive locations
     */
    public static function addLocationToAllLocationUsers($location = null, $replaceLocation = null, $skipInactive = true)
    {
        $table = "user_locations";

        // update renamed location id
        if (!empty($replaceLocation) && !empty($location)) {
            $data = array('location_id' => $location);
            $query = \DB::table($table)->where('location_id', $replaceLocation);
            $query->update($data);
        }

//        $q = "SELECT l.id AS location_id, u.id AS user_id 
//                FROM users u, location l 
//                WHERE u.has_all_locations=1
//                AND NOT EXISTS (SELECT * FROM user_locations WHERE user_id=u.id AND location_id=l.id)
//                AND l.active=1 ";

        // add all unassigned locations to users having has_all_locations=1
        $q = "INSERT INTO user_locations (location_id, user_id) 
                SELECT l.id AS location_id, u.id AS user_id 
                FROM users u, location l 
                WHERE u.has_all_locations=1
                AND NOT EXISTS (SELECT * FROM user_locations WHERE user_id=u.id AND location_id=l.id) ";

        if ($skipInactive) {
            $q .= " AND l.active=1 ";
        }

        \DB::insert($q);

        self::cleanUpUserLocations();

    }

    /**
     * Clean up orphan user-location assignments.
     * Orphan records are created when either a user or a location is deleted
     * @param bool $skipInactive [optional] Would not delete inactive locations when set to true.
     */
    public static function cleanUpUserLocations($skipInactive = false)
    {
        $table = "user_locations";

//        $q = "SELECT * FROM $table
//                WHERE NOT EXISTS (
//                    SELECT u.id AS user_id, l.id AS location_id 
//                        FROM users u, location l 
//                              WHERE u.id=user_locations.user_id AND l.id=user_locations.location_id
//                      )
//                    OR user_locations.location_id IN (SELECT id FROM location WHERE active=0) ";
        $q = "DELETE FROM $table
                WHERE 
                    NOT EXISTS (
                    SELECT u.id AS user_id, l.id AS location_id 
                        FROM users u, location l 
                        WHERE u.id=user_locations.user_id AND l.id=user_locations.location_id
                    ) ";

        if (!$skipInactive) {
            $q .= " OR user_locations.location_id IN (SELECT id FROM location WHERE active=0) ";
        }

        \DB::delete($q);
    }

    public static function generateSimpleSearchButton($setting = array())
    {
        $width = isset($setting['simplesearchbuttonwidth']) ? trim($setting['simplesearchbuttonwidth']) : '';

        $widthClass = $widthStyle = $buttonStyle = "";
        if (preg_match('/^[\_a-zA-Z]/', $width) == 1) {
            $widthClass = $width . ' add-pad-right';
        } elseif (!empty($width)) {
            $widthClass = 'add-pad-right';
            $widthStyle = 'width:' . $width . ';';
        }
        if (!empty($width)) {
           // $buttonStyle = "width: 100%;";
            $buttonStyle = "";
        }
        $button = '<div class="sscol-submit col-sm-12 col-md-2  col-lg-1 ' . $widthClass . '"
            style="' . $widthStyle . '"><br/>
            <button type="button" name="search" style="' . $buttonStyle . '"
                    class="doSimpleSearch btn btn-sm btn-primary"> Search </button>
            </div>';

        return $button;
    }

    public static function getUserGroup($id = null)
    {
        $groupId = '';
        if (empty($id)) {
            $id = \Session::get('uid');
            $groupId = \Session::get('gid');
        } else {
            $groupId = \DB::table('users')->where('id', '=', $id)->pluck('group_id');
        }
        return $groupId;
    }

    public static function getUsersInGroup($gid = null)
    {
    }

    public static function  getUserDetails($id = null)
    {
        if (empty($id)) {
            $id = \Session::get('uid');
        }
        $data = \App\Models\Core\Users::where('id', $id)->first()->toArray();
        if (empty($data)) {
            $data = [];
        }
        return $data;
    }

    public static function getStatus($value)
    {
        if ($value == 1)
            return "Yes";
        elseif ($value == 0)
            return "No";
        elseif ($value == -1)
            return "Both";
        return $value;
    }

    public static function refreshUserLocations($userId)
    {

        $user_locations = self::getLocationDetails($userId);
        $user_location_ids = self::getIdsFromLocationDetails($user_locations);

        $selectedLocation = \Session::get('selected_location');
        \Session::put('user_locations', $user_locations);
        \Session::put('user_location_ids', $user_location_ids);
        if(!empty($user_locations) && empty($selectedLocation)) {
            \Session::put('selected_location', $user_locations[0]->id);
            \Session::put('selected_location_name', $user_locations[0]->location_name_short);
        } else if(empty($user_locations)) {
            \Session::put('selected_location', 0);
        }
    }

    public static function getModuleSetting($moduleName, $setting = '')
    {
        $returnValue = null;
        $data = \App\Models\Sximo\Module::where('module_name', $moduleName)->pluck('module_config');
        if (!empty($data)) {
            $config = self::CF_decode_json($data);
            if (!is_array($setting)) {
                $setting = ['setting', $setting];
            }
            if (!empty($config)) {
                if (is_array($setting)) {
                    foreach ($setting as $property) {
                        if (is_null($config)) {
                            break;
                        }
                        $config = isset($config[$property]) ? $config[$property] : null;
                        if (in_array($property, ['forms', 'grid'])) {
                            $newArray = [];
                            foreach ($config as $item) {
                                $newArray[$item['field']] = $item;
                            }
                            $config = $newArray;
                        }
                    }
                }
                $returnValue = $config;
            }
        }
        return $returnValue;
    }

    public static function getModuleFormFieldDropdownOptions($module, $fieldName, $defaults = array())
    {
        $minutes = 60;
        $cacheKey = md5("getModuleFormFieldDropdownOptions-$module-$fieldName");
        $options = Cache::remember($cacheKey, $minutes, function () use ($module, $fieldName, $defaults) {
            $optionsString = self::getModuleSetting($module, ['forms', $fieldName, 'option', 'lookup_query']);
            $options = FEGSystemHelper::parseStringToArray($optionsString, '|', ':', $defaults);
            return $options;
        });
        return $options;
    }

    /**
     *
     * @param string $type
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public static function getUniqueLocationUserAssignmentMeta($type = 'id', $field = null, $value = '')
    {
        $minutes = 60;
        $cacheKey = md5("getUniqueLocationUserAssignmentMeta-$type-$field");
        //return Cache::remember($cacheKey, $minutes, function () use ($type, $returnType, $value) {
        $q = \DB::table('location_user_roles_master');
        if ($value !== '') {
            if (!empty($field)) {
                return $q->where($field, $value)->pluck($type);
            }
            return null;
        }
        $q->select('id', 'group_id', 'role_title', 'proxy_field_name', 'unique_assignment');

        if ($field == 'non-grouped') {
            $q->whereRaw(" group_id NOT IN (SELECT group_id from tb_groups)");
        }
        if ($field == 'grouped') {
            $q->whereRaw(" group_id IN (SELECT group_id from tb_groups)");
        }

        if ($type != 'all' || $field == 'non-grouped' || $field == 'grouped') {
            $q->where('unique_assignment', 1);
        }
        if ($type == 'sql') {
            $sqlSelect = [];
            $sqlJoins = [];
            $data = $q->get();
            foreach ($data as $item) {
                $gid = $item->group_id;
                $pfn = $item->proxy_field_name;
                $sqlSelect[] = "$pfn.user_id as $pfn";
                $sqlJoins[] = "LEFT JOIN user_locations $pfn ON $pfn.location_id = location.id AND $pfn.group_id='$gid'";
            }

            $sqlSelectString = implode(", ", $sqlSelect);
            $sqlJoinString = implode(" \r\n ", $sqlJoins);

            return ['select' => $sqlSelectString, 'join' => $sqlJoinString];
        }
        $data = [];
        $records = $q->get();
        foreach ($records as $item) {
            $gid = $item->group_id;
            $pfn = $item->proxy_field_name;
            $rt = $item->role_title;
            $item = ['group_id' => $gid, 'field' => $pfn, 'label' => $rt];
            switch ($type) {
                case "field":
                    $data[$pfn] = $item;
                    break;
                case "-field":
                    $data[] = $pfn;
                    break;
                case "field-":
                    $data[$pfn] = '';
                    break;
                case "field-id":
                    $data[$pfn] = $gid;
                    break;
                case "field-label":
                    $data[$pfn] = $rt;
                    break;
                case "-id":
                    $data[] = $gid;
                    break;
                case "id-":
                    $data[$gid] = '';
                    break;
                case "id-field":
                    $data['' . $gid] = $pfn;
                    break;
                case "id-label":
                    $data['' . $gid] = $rt;
                    break;
                default:
                    $data[$gid] = $item;
            }
        }
        return $data;
        //});                
    }

    public static function isNoData($tableGrid)
    {

        $noDataArray=array();
        if(!is_null($tableGrid)) {
            foreach ($tableGrid as $f) {
                if(isset($f['nodata']) && isset($f['field'])) {
                    $noDataArray[$f['field']] = $f['nodata'];
                }
            }
        }
        return $noDataArray;
    }

    /**
     * @param $string
     * @param string $replacer
     * @return string
     */
    public static function removeSpecialCharacters($string,$replacer = ''){
        $convertedString = $string;
        if(!empty($string)) {
            $stringToArray = explode(' ', $string);
           // $resultentArray = (array) preg_replace('/[^a-zA-Z0-9\.]/', $replacer, $stringToArray);
            $cleanedArray = [];
            foreach ($stringToArray as $item){
                $cleanedArray[] = str_replace(['&','\'','"',','],$replacer,$item);
            }
            $convertedString = trim(implode(' ', $cleanedArray));
            $convertedString = trim(preg_replace('/\s+/',' ', $convertedString));
        }
        return (string) $convertedString;
    }

    /**
     * @param $string
     * @param $limit
     * @param bool $ellipsis
     * @return string
     */
    public static function truncateStringToSpecifiedLimit($string,$limit,$ellipsis = false){
        if(strlen($string) > $limit){
            $string = substr($string,0,$limit);
        }
        return ($ellipsis == true) ? $string.'...' : $string;
    }

    /**
     * @param $rows
     * @param $field
     * @param string $sign
     * @param $conditionField
     * @param array $conditionFieldValues
     * @param bool $isPostFix
     * @param bool $spaceAfter
     * @param bool $spaceBefore
     * @return mixed
     */
    public static function addPostPreFixToField($rows ,$field ,$sign = '',$conditionField, $conditionFieldValues = [], $isPostFix = true , $spaceAfter = false , $spaceBefore = false){

        foreach ($rows as $index => $row) {
            if (isset($row->$field)) {

                if(in_array($row->$conditionField, $conditionFieldValues)) {

                    $sign = ($spaceAfter == true) ? $sign . ' ' : $sign;
                    $sign = ($spaceBefore == true) ? ' ' . $sign : $sign;
                    $value = ($isPostFix == true) ? $rows[$index]->$field . $sign : $sign . $rows[$index]->$field;
                    $rows[$index]->$field = $value;
                }
            }
        }
        return $rows;
    }

    /**
     * @param string $fileWithPath
     * @return array
     */
    public static function getVendorFileImportData($fileWithPath = ''){

        $rows = \SiteHelpers::getCSVFileData($fileWithPath);
        $dataRows = [];
        foreach ($rows as $row){
            $internalRow = [];
            foreach ($row as $key => $value){
                if(!empty(trim($key))) {
                    if(in_array($key,['product_id'])){
                        $value = $value;
                    }
                    if(in_array($key,['ticket_value','reserved_qty','item_per_case'])){
                        $value = (int) $value;
                    }
                    if ($key =='sku'){
                        if (is_numeric($value)){
                            $value = (int) $value;
                        }else{
                            $value = trim($value);
                        }
                    }
                    if(in_array($key,['case_price','unit_price'])){
                        $value = \CurrencyHelpers::formatPrice($value,5,false);
                    }
                    $internalRow[$key] = $value;
                }
            }
            $dataRows[] = $internalRow;
        }
        return $dataRows;
    }

    /**
     * @param $filePath
     * @return array
     */
    public static function getCSVFileData($filePath){
        $file = fopen($filePath,"r");
        $fileArray = [];
        while(! feof($file))
        {
            $fileArray[] = fgetcsv($file);
        }

        fclose($file);
        $fileArray = self::stipTagsFromArray($fileArray,'="','"');
        $fileArray = self::makeAssociatedAarry($fileArray);
        return $fileArray;
    }

    /**
     * @param array $data
     * @param string $tagBefore
     * @param string $tagAfter
     * @return array
     */
    public static function stipTagsFromArray($data = [],$tagBefore = '="',$tagAfter = '"'){
        $dataMatched = [];
        foreach ($data as $item){
            $itemValue = [];
            if(is_array($item)) {
                foreach ($item as $value) {
                    $itemValue[] = \SiteHelpers::getTextBetweenTags($value, $tagBefore, $tagAfter);
                }
            }
            if(count($itemValue) > 3) {
                $dataMatched[] = $itemValue;
            }
        }
        return $dataMatched;
    }
    /**
     * @param array $data
     * @return array
     */
    public static function makeAssociatedAarry($data = []){

        $tableHeadings = [];
        if(!empty($data[0])) {
            foreach ($data[0] as $heading) {
                //"=\"61605 Party Supplies\""

                $tableHeadings[0][] = strtolower(str_replace(array('/','\\',' '),'_',$heading));
            }
        }

        for ($row = 1; $row <= count($data)-1; $row++){
            //  Read a row of data into an array
            $rows[] = array_combine($tableHeadings[0], $data[$row]);

        }
        return $rows;
    }

    /**
     * @param $string
     * @param $tagBefore
     * @param $tagAfter
     * @return mixed
     */
    public static function getTextBetweenTags($string, $tagBefore,$tagAfter) {
        $pattern = "/$tagBefore(.*)$tagAfter/";
        preg_match($pattern, $string, $matches);
       if(!empty($matches)){
           return $matches[1];
       }else{
           return $string;
       }

    }
}
