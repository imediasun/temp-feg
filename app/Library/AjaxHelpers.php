<?php


class AjaxHelpers
{

	public static function gridFormater($val , $row, $attribute = array() , $arr = array()) 
	{

		if($attribute['image']['active'] =='1' && $attribute['image']['active'] !='') {
			$val =  SiteHelpers::showUploadedFile($val,$attribute['image']['path']) ;
		}
        
        if (!empty($arr['datalist']) && !empty($arr['options'])) {
            $datalistOptions = \FEGHelp::parseStringToArray($arr['options']);
            $val = \FEGHelp::getLabelFromOptions($val, $datalistOptions);
        }
		// Handling Quick Display As 
		if((!empty($val) || $val==='0' || $val===0) && isset($arr['valid']) && $arr['valid'] ==1)
		{
			$fields = str_replace("|",",",$arr['display']);			
            $val=addslashes($val);
			if(isset( $arr['multiple']) && $arr['multiple'] =='1') {
				$Q = DB::select(" SELECT ".$fields." FROM ".$arr['db']." WHERE ".$arr['key']." IN (".$val.") ");
				if(count($Q) >= 1 )
				{	
					$fields = explode("|",$arr['display']);


					$val = array();
					foreach($Q as $values)
					{
						$v= '';
						$v .= (isset($fields[0]) && $fields[0] !='' ?  $values->$fields[0].' ' : '');
						$v .= (isset($fields[1]) && $fields[1] !=''  ? $values-> $fields[1].' ' : '');
						$v .= (isset($fields[2]) && $fields[2] !=''  ? $values->$fields[2].' ' : '');
						$val[] = trim($v);
					}

					$val = trim(implode(", ",$val));
				}	

			} 
            else {
				$Q = DB::select(" SELECT ".$fields." FROM ".$arr['db']." WHERE ".$arr['key']." = '".$val."' ");
				if(count($Q) >= 1 )
				{					
					$rowObj = $Q[0];
					$fields = explode("|",$arr['display']);
					$v= '';
					$v .= (isset($fields[0]) && $fields[0] !=='' ?  $rowObj->$fields[0].' ' : '');
					$v .= (isset($fields[1]) && $fields[1] !==''  ? $rowObj-> $fields[1].' ' : '');
					$v .= (isset($fields[2]) && $fields[2] !==''  ? $rowObj->$fields[2].' ' : '');
					$val = trim($v);
				} 	
			}				
		} 	
		
		// Handling format function 	
		if(isset($attribute['formater']['active']) and $attribute['formater']['active']  ==1)
		{
			$fval = $attribute['formater']['value'];
            
            list($className, $methodName, $serialisedParams) = explode('|', $fval.'||');
            $serialisedParams = trim($serialisedParams);
            $methodName = trim($methodName);
            $className = trim($className);
            if (method_exists($className, $methodName)) {
                if ($serialisedParams == '') {
                    $params = [$val];
                    //$serialisedParams = $val;
                }
                else {
                    $params = explode(':', $serialisedParams);
                    foreach ($params as $index => $fieldName) {
                        if (is_array($row)) {
                            if (isset($row[$fieldName])) {
                                $params[$index] = $row[$fieldName];
                            }                        
                        }
                        else {
                            if (isset($row->$fieldName)) {
                                $params[$index] = $row->$fieldName;
                            }                        
                        }
                    }
                }
                //$serialisedParams = implode(",", $params);
                //$val = call_user_func(array($className, $methodName), $serialisedParams);
                $val = call_user_func_array(array($className, $methodName), $params);
            }
//            
//            
//			foreach($row as $k=>$i)
//			{
//				if (preg_match('/\b('.$k.')\b/',$fval))
//                $fval = str_replace($k,$i,$fval);
//			}
//			$c = explode("|",$fval);
//
//			if(isset($c[0]) && class_exists($c[0]))
//			{
//                if(isset($c[2]) && ($c[2] != null || empty($c[2]))) {
//                    if ($c[1] == "formatDate" || $c[1] == "formatDateTime") {
//                        $val = call_user_func(array($c[0], $c[1]), $c[2]);
//                    } else {
//                        $val = call_user_func(array($c[0], $c[1]), str_replace(":", ",", $c[2]));
//                    }
//                }
//			}

		}
		// Handling Link  function 	
		if((!empty(trim($val)) || trim($val)=== 0 || trim($val)=== '0') &&
                isset($attribute['hyperlink']['active']) &&
                $attribute['hyperlink']['active'] ==1 &&
                $attribute['hyperlink']['link'] != '')
		{	
	
			$attr = '';
			$linked = $attribute['hyperlink']['link'];
			foreach($row as $k=>$i)
			{
//				if (preg_match("/$k/",$attribute['hyperlink']['link'])) {
//                    $linked = str_replace($k,$i, $linked);
//                }
                $linked = str_replace('{{'.$k.'}}', $i, $linked);
			}
			if($attribute['hyperlink']['target'] =='modal')
			{
				$attr = "class='gridHyperlinkValue' onclick='SximoModal(this.href, \"".htmlentities($val)."\"); return false'";
			}
			
//			$val =  "<a href='".URL::to($linked)."'  $attr style='display:block' >".$val." <span class='fa fa-arrow-circle-right pull-right'></span></a>";
			$val =  "<a href='".URL::to($linked)."'  $attr >".$val."</a>";
		}
if($val==="0" || $val === 0)
{
  $val="";
}
		return $val;
	}	
	
	static public function fieldLang( $fields ) 
	{ 
		$l = array();
		foreach($fields as $fs)
		{			
			foreach($fs as $f)
				$l[$fs['field']] = $fs; 									
		}
		return $l;	
	}	
	
	static public function instanceGrid(  $class) 
	{
		$data = array(
			'class'	=> $class ,
		);
		return View::make('admin.module.utility.instance',$data);
	
	}  

	static function inlineFormType( $field  ,$forms )
	{
		$type = '';
		foreach($forms as $f)
		{
			if($f['field'] == $field )
			{
				$type = ($f['type'] !='file' ? $f['type'] : ''); 			
			}	
		}
		if($type =='select' || $type=="radio" || $type =='checkbox')
		{
			$type = 'select';
		} else if($type=='file') {
			$type = '';
		} else if($type=='text_date') {
			$type = 'text_date';
		} else if($type=='text_datetime') {
			$type = 'text_datetime';
		} else if($type=='textarea' || $type=='textarea_editor') {
			$type = 'textarea';
		}
		else {
			$type = 'text';
		}
		return $type;
	}

	static public function buttonAction( $module, $access, $id, $setting, $edit=null)
	{
        
        $url = $containerID = $module;
        if (is_array($module)) {
            $moduleData = $module;
            $module = $moduleData['module'];
            $url = isset($moduleData['url']) ? $moduleData['url']: $module;
            $containerID = isset($moduleData['containerID']) ? $moduleData['containerID']: $module;
            
        }
        $containerID = '#'.preg_replace('/\/?[^\/]+?\//', '', $containerID);

		$html ='<div class=" action dropup" >';
		if($access['is_detail'] ==1) {
			if($setting['view-method'] != 'expand')
			{
				$onclick = " onclick=\"ajaxViewDetail('$containerID',this.href); return false; \"" ;
				if($setting['view-method'] =='modal') {
                    $onclick = " onclick=\"SximoModal(this.href,'View Detail'); return false; \"" ;
                }
				$html .= '<a href="'.URL::to($url.'/show/'.$id).'" '.
                        $onclick.
                        ' class="btn btn-xs btn-white tips" title="'.
                        Lang::get('core.btn_view').'"><i class="fa fa-search"></i></a>';
			}
		}
        if($edit == null)
        {
            if($access['is_edit'] ==1) {
                $onclick = " onclick=\"ajaxViewDetail('$containerID',this.href); return false; \"" ;
                if($setting['form-method'] =='modal') {
                    $onclick = " onclick=\"SximoModal(this.href,'Edit Form'); return false; \"" ;			
                }
                $html .= ' <a href="'.URL::to($url.'/update/'.$id).'" '.
                        $onclick.
                        '  class="btn btn-xs btn-white tips" title="'.
                        Lang::get('core.btn_edit').'"><i class="fa  fa-edit"></i></a>';
            }
        }
		$html .= '</div>';
		return $html;
	}
    
	static public function GamestitleButtonAction( $module , $access , $id , $setting,$edit=null)
	{

		$html ='<div class="action">';
		if($access['is_detail'] ==1) {
			if($setting['view-method'] != 'expand')
			{
				$onclick = " onclick=\"ajaxViewDetail('#".preg_replace('/\/?[^\/]+?\//', '', $module)."',this.href); return false; \"" ;
				if($setting['view-method'] =='modal')
					$onclick = " onclick=\"SximoModal(this.href,'View Detail'); return false; \"" ;
				$html .= '<a href="'.URL::to($module.'/show/'.$id).'" '.$onclick.' class="btn btn-xs btn-white tips" title="'.Lang::get('core.btn_view').'"><i class="fa fa-search"></i></a>';
			}
		}
		if($edit == null)
		{
			if($access['is_edit'] ==1) {
				$onclick = " onclick=\"ajaxViewDetail('#".preg_replace('/\/?[^\/]+?\//', '', $module)."',this.href); return false; \"" ;
				if($setting['form-method'] =='modal')
					$onclick = " onclick=\"SximoModal(this.href,'Edit Form'); return false; \"" ;

				$html .= ' <a href="'.URL::to($module.'/update/'.$id).'" '.$onclick.'  class="btn btn-xs btn-white tips" title="'.Lang::get('core.btn_edit').'"><i class="fa  fa-edit"></i></a>';
			}
		}
		$html .= '</div>';
		return $html;
	}
	static public function buttonActionInline( $id ,$key ,$actionColumnHidden = 0)
	{
        $divid = 'form-'.$id;
        $outeridvid="divOverlay_".$id;
		$html = '
		<div id="'.$outeridvid.'" class="actionopen" style="visibility: hidden" >
			<a href="#" onclick="saveInlineForm(\''.$divid.'\', event, this)" class="tips btn btn-primary btn-xs"  title="Save"><i class="fa  fa-save"></i></a>
			<a href="#" onclick="cancelInlineEdit(\''.$divid.'\', event, this,'.$actionColumnHidden.')" class="tips btn btn-danger btn-xs"  title="Cancel"><i class="fa  fa-times"></i></a>
			<input type="hidden" value="'.$id.'" name="'.$key.'">
		</div>
		';
		return $html;
	}			

	static public function buttonActionCreate( $module  ,$method = 'newpage',$title=null)
	{

		$onclick = " onclick=\"ajaxViewDetail('#".preg_replace('/\/?[^\/]+?\//', '', $module)."',this.href); return false; \"" ;
		if($method['form-method'] =='modal')
				$onclick = " onclick=\"SximoModal(this.href,'Create Detail'); return false; \"" ;


		$html = '
			<a href="'.URL::to($module.'/update').'" class="tips btn btn-sm btn-white"  title="'.Lang::get('core.btn_create').'" '.$onclick.'>
			<i class="fa fa-plus "></i> '.Lang::get('core.btn_create').'</a>
		';
                if($title)
                {
                    $html=str_replace('Create',$title,$html);
                }

		return $html;
	}

	static public function htmlExpandGrid()
	{

		return View::make('sximo.module.utility.extendgrid');
	}

	static public function oneToMany( $field , $field2 ='' , $field3 = '')
	{

		return $field . $field2 . $field3;
	}

	public static function myFunc( $param )
	{

		return 'hahai = '.$param ;
	}




}
