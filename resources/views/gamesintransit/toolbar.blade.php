<div class="row m-b">
	<div class="col-md-9">
			<a href="{{ URL::to( $pageModule .'/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advanced Search'); return false;" ><i class="fa fa-search"></i>Advanced Search</a>
        @if(SiteHelpers::isModuleEnabled($pageModule))
        <a href="{{ URL::to('tablecols/arrange-cols/'.$pageModule) }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Column Selector'); return false;" ><i class="fa fa-bars"></i> Arrange Columns</a>
        @if(!empty($colconfigs))
        <select class="form-control" style="width:25%!important;display:inline;" name="col-config"
                id="col-config">
            <option value="0">Select Configuraton</option>
            @foreach( $colconfigs as $configs )
            <option @if($config_id == $configs['config_id']) selected
            @endif value={{ $configs['config_id'] }}> {{ $configs['config_name'] }}   </option>
            @endforeach
        </select>
                @if(\Session::get('uid') ==  \SiteHelpers::getConfigOwner($config_id))
                    <a id="edit-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/edit') }}" class="btn btn-sm btn-white tips"
                       onclick="SximoModal(this.href,'Column Selector'); return false;" title="Edit Arrange">  <i class="fa fa-pencil-square-o"></i></a>
                    <button id="delete-cols" href="{{ URL::to('tablecols/arrange-cols/'.$pageModule.'/delete') }}" class="btn btn-sm btn-white tips" title="Clear Arrange">  <i class="fa fa-trash-o"></i></button>
                @endif
            @endif
        @endif
        <button class="btn btn-sm btn-white addNewGameButton" ><i class="fa fa-plus"></i>Add Game</button>

    </div>
    <div id="myModal" class="modal fade" role="dialog" tabindex="4">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div id="mycontent" class="modal-content">
                <div id="myheader" class="modal-header">
                    <button type="button " class="btn-xs collapse-close btn btn-danger pull-right" data-dismiss="modal"  aria-hidden="true"><i class="fa fa fa-times"></i></button>
                    <h4 >Add New Game</h4>
                </div>
                <div class="modal-body col-md-offset-1 col-md-10">
                    {!! Form::open(array('url'=>'gamesintransit/add-new-game/',
                    'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
                    'addnewgameFormAjax')) !!}
                        <div class="form-group">
                        <label class="control-label col-md-4" for="game_title">Game Title*</label>
                        <div class="col-md-8">
                            <select  name="game_title" id="game_title" class="form-control select2" required ></select>
                             
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-4" for="asset_number">Asset Number*</label>
                        <div class="col-md-8">
                            <input type="text" name="asset_number" id="asset_number" class="form-control" 
                                   required
                                   parsley-minlength="8" 
                                   parsley-maxlength="8" 
                                   parsley-whitespace="trim" 
                                   parsley-type="digits"
                                   parsley-minlength-message="Asset Number must have 8 digits."
                                   parsley-maxlength-message="Asset Number must have 8 digits."
                                   value=""/>
                        <p id="asset_available" style="display:none"><i class="fa" id="status-icon"></i> </p>
                        </div>
                        </div>
                         
                        <div class="form-group">
                            <label class="control-label col-md-4" for="serial">Serial #</label>
                        <div class="col-md-8">
                            <input type="text" class="element text medium form-control" name="serial" id="serial" value=""/>
                               </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="notes">Notes</label>
                        <div class="col-md-8">
                            <textarea class="form-control" cols="5" rows="6"  name="notes" id="notes"/></textarea>  
                        </div>
                        </div>

                            <div class="form-group">
                                <label class="control-label col-md-4" for="test_piece">Game On Test</label>
                        <div class="col-md-8">
                            <input name="test_piece" id="test_piece" type="hidden" value="0"/>
                            <input name="test_piece" id="test_piece" type="checkbox" value="1"/>
                            </div>
                            </div>

                       <div class="col-md-offset-6 col-md-6">  <button type="submit" class="btn btn-primary btn-lg "><i
                                    class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button> </div>
                    {!! Form::close() !!}
                </div>
                <div class="clearfix"></div>

            </div>

        </div>
    </div>

    <div class="col-md-3">
        <?php
        $isExcel = isset($access['is_excel']) && $access['is_excel'] == 1;
        $isCSV = isset($access['is_csv'])  ? ($access['is_csv'] == 1) : $isExcel;
        $isPDF = isset($access['is_pdf'])  && $access['is_pdf'] == 1;
        $isWord = isset($access['is_word'])  && $access['is_word'] == 1;
        $isPrint = isset($access['is_print'])  ? ($access['is_print'] == 1) : $isExcel;
        $isExport = $isExcel || $isCSV || $isPDF || $isWord || $isPrint;
        ?>
        @if($isExport)
            <div class="pull-right">
                @if($isExcel)
                    <a href="{{ URL::to( $pageModule .'/export/excel?exportID='.uniqid('excel', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Excel</a>
                @endif
                @if($isCSV)
                    <a href="{{ URL::to( $pageModule .'/export/csv?exportID='.uniqid('csv', true).'&return='.$return) }}" class="btn btn-sm btn-white"> CSV </a>
                @endif
                @if($isPDF)
                    <a href="{{ URL::to( $pageModule .'/export/pdf?exportID='.uniqid('pdf', true).'&return='.$return) }}" class="btn btn-sm btn-white"> PDF</a>
                @endif
                @if($isWord)
                    <a href="{{ URL::to( $pageModule .'/export/word?exportID='.uniqid('word', true).'&return='.$return) }}" class="btn btn-sm btn-white"> Word</a>
                @endif
                @if($isPrint)
                    <a href="{{ URL::to( $pageModule .'/export/print?exportID='.uniqid('print', true).'&return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" > Print</a>
                @endif
            </div>
        @endif
    </div>
</div>

