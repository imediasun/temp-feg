<div class="mainContent">
    <div class="alertContent"></div>
    <div class="content tasksContent">
        @foreach ($rowData as $row)
            @include('feg.system.tasks.tableitems', array('row' => $row))
        @endforeach
    <div class="table-responsive">
        @if(!empty($topMessage))
        <h5 class="topMessage">{{ $topMessage }}</h5>
        @endif
        @if(count($rowData)>=1)
        <table class="table table-striped datagrid " id="{{ $pageModule }}Table" data-module="{{ $pageModule }}" data-url="{{ $pageUrl }}">
            <thead>
                <tr>
                    @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                    <th width="35"> No </th>
                    @endif
                    @if($setting['disableactioncheckbox']=='false')
                    <th width="30"> <input type="checkbox" class="checkall" /></th>
                    @endif
                    @if($setting['view-method']=='expand') <th>  </th> @endif
                    <?php foreach ($tableGrid as $t) :
                        if($t['view'] =='1'):
                            $limited = isset($t['limited']) ? $t['limited'] :'';
                            if(SiteHelpers::filterColumn($limited ))
                            {
                                $sortBy = $param['sort'];
                                $orderBy = strtolower($param['order']);
                                $colField = $t['field'];
                                $colIsSortable = $t['sortable'] == '1';
                                $colIsSorted = $colIsSortable && $colField == $sortBy;
                                $colClass = $colIsSortable ? ' dgcsortable' : '';
                                $colClass .= $colIsSorted ? " dgcsorted dgcorder$orderBy" : '';
                                $th = '<th'.
                                        ' class="'.$colClass.'"'.
                                        ' data-field="'.$colField.'"'.
                                        ' data-sortable="'.$colIsSortable.'"'.
                                        ' data-sorted="'.($colIsSorted?1:0).'"'.
                                        ' data-sortedOrder="'.($colIsSorted?$orderBy:'').'"'.
                                        ' align="'.$t['align'].'"'.
                                        ' width="'.$t['width'].'"';
                                $th .= '>';
                                $th .= \SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array()));
                                $th .= '</th>';
                                echo $th;
                            }
                        endif;
                    endforeach; ?>
                    @if($setting['disablerowactions']=='false')
                    <th width="70"><?php echo Lang::get('core.btn_action') ;?></th>
                    @endif
                  </tr>
            </thead>

            <tbody>
                @if($access['is_add'] =='1' && $setting['inline']=='true')
                <tr id="form-0" >
                    <td> # </td>
                    @if($setting['disableactioncheckbox']=='false')
                    <td> </td>
                    @endif
                    @if($setting['view-method']=='expand') <td> </td> @endif
                    @foreach ($tableGrid as $t)
                        @if($t['view'] =='1')
                        <?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
                            @if(SiteHelpers::filterColumn($limited ))
                            <td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
                                {!! SiteHelpers::transForm($t['field'] , $tableForm) !!}
                            </td>
                            @endif
                        @endif
                    @endforeach
                    <td >
                        <button onclick="saved('form-0')" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-save"></i></button>
                    </td>
                  </tr>
                  @endif

                    <?php foreach ($rowData as $row) :
                          $id = $row->id;
                    ?>
                    <tr class="editable" id="form-{{ $row->id }}">
                        @if(!isset($setting['hiderowcountcolumn']) || $setting['hiderowcountcolumn'] != 'true')
                        <td class="number"> <?php echo ++$i;?>  </td>
                        @endif
                        @if($setting['disableactioncheckbox']=='false')
                        <td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->id ;?>" />  </td>
                        @endif
                        @if($setting['view-method']=='expand')
                        <td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->id }}" data-url="{{ url($pageModule.'/show/'.$id) }}"><i class="fa fa-plus " ></i></a></td>
                        @endif
                         <?php foreach ($tableGrid as $field) :
                            if($field['view'] =='1') :
                                $conn = (isset($field['conn']) ? $field['conn'] : array() );


                                $value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn);
                                ?>
                                <?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
                                @if(SiteHelpers::filterColumn($limited ))
                                     <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                        {!! $value !!}
                                     </td>
                                @endif
                        <?php
                             endif;
                            endforeach;
                          ?>
                      @if($setting['disablerowactions']=='false')     
                     <td data-values="action" data-key="<?php echo $row->id ;?>">
                        {!! AjaxHelpers::buttonAction($pageDetails, $access, $id, $setting) !!}
                        {!! AjaxHelpers::buttonActionInline($row->id,'id') !!}
                    </td>
                    @endif
                    </tr>
                    @if($setting['view-method']=='expand')
                    <tr style="display:none" class="expanded" id="row-{{ $row->id }}">
                        <td class="number"></td>
                        <td></td>
                        <td></td>
                        <td colspan="{{ $colspan}}" class="data"></td>
                        <td></td>
                    </tr>
                    @endif
                <?php endforeach;?>

            </tbody>

        </table>
        @else

        <div style="margin:100px 0; text-align:center;">
            @if(!empty($message))
                <p class='centralMessage'>{{ $message }}</p>
            @else
                <p class='centralMessage'> No Record Found </p>
            @endif
        </div>

        @endif
        @if(!empty($bottomMessage))
        <h5 class="bottomMessage">{{ $bottomMessage }}</h5>
        @endif

        </div>	
    </div>
    <div class='hidden taskTemplateContent'>
        @include('feg.system.tasks.tableitems', array('row' => new StdClass))
    </div>
</div>

@section ('beforebodyend')
    <script type="text/javascript">
        var pageModule = '{{$pageModule}}',
            pageUrl = '{{$pageUrl}}';
    </script>  
    <script type="text/javascript" src="{{ asset('sximo/js/elm5tasks.js') }}"></script>  
@end
