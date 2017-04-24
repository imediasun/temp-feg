{{--*/      $ID = @$row['id']                   /*--}}
{{--*/      $isEdit = !empty($ID)               /*--}}
{{--*/      $soldValue = @$row['sold']          /*--}}
{{--*/      $isSold = $soldValue === 1          /*--}}
{{--*/      $soldTo = @$row['sold_to']          /*--}}
{{--*/      $soldDate = @$row['date_sold']      /*--}}
{{--*/      $soldDateFormatted = DateHelpers::formatDate($soldDate)  /*--}}

@if($setting['form-method'] =='native')

    <div class="sbox">
        <div class="sbox-title">
            <h4>
            @if ($isEdit)
                    <i class="fa fa-pencil"></i>
                Edit {{ $row['game_title'] }} ({{ $row['id'] }})
                @if (!empty($row['location_id']))
                <small>at {{ $row['location_id'] }} || {{ $row['location_name'] }}  </small>
                @endif
            @else
                    <i class="fa fa-plus"></i>
                Add a new Game
            @endif
            
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}', this)"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content addEditGame">
            @endif
            {!! Form::open(array('url'=>'mylocationgame/save/'.$ID, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mylocationgameFormAjax')) !!}
            <div class="col-md-12 gameInputsContainer clearfix">
                <fieldset>
                    <div class="form-group  clearfix" >
                        <label for="Test Piece" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="hidden" name="test_piece" value="{{ $row['test_piece'] }}"/>
                            <input type='checkbox' 
                                   data-proxy-input='test_piece' name='_test_piece' 
                                   value="{{ $row['test_piece'] }}"
                                   @if($row['test_piece']==1) checked @endif 
                            />
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group clearfix " >
                        <label for="game_title_id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Game Title ', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='game_title_id' id='game_title_id' class='select2 '  required></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group clearfix " >
                        <label for="id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Asset ID ', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input name='id' type='text' value='{{ $ID }}' class='form-control' required/>
                        </div>
                        <div class="col-md-2"></div>
                    </div>                    
                    <div class="form-group clearfix " >
                        <label for="game_name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Game Name ', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input name='game_name' type='text' value='{{ $row['game_name'] }}'  class='form-control'/>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="form-group clearfix " >
                        <label for="prev_game_name" class=" control-label col-md-4">
                            {!! SiteHelpers::activeLang('Game Converted from', (isset($fields['prev_game_name']['language'])? $fields['prev_game_name']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="prev_game_name" 
                                   class="form-control lightgray-bg" 
                                   value="{{ $row['prev_game_name'] }}" />
                        </div>
                        <div class="col-md-2"></div>
                    </div>                    
                    <div class="form-group clearfix " >
                        <label for="version_id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Version ID', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='version_id' rows='5' id='version_id' class='select2 '   ></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="version" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Alt. Version/Signage', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input name='version' type='text' value='{{ $row['version'] }}'  class='form-control'/>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    
                    <div class="form-group  " >
                        <label for="Location " class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type='hidden' name='prev_location_id' value='{{ $row['prev_location_id'] }}' />
                            <input type='hidden' name='old_location_id' value='{{ $row['location_id'] }}' />
                            <select name='location_id' id='location_id' class='select2 '  
                                @if($isEdit)  readonly='readonly' @endif
                            >                                
                            </select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="serial" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('serial', $row['serial'],array('class'=>'form-control', 'placeholder'=>'',  'required'=> 'required' )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="Manufacturer" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Manufacturer', (isset($fields['mfg_id']['language'])? $fields['mfg_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='mfg_id' rows='5' id='mfg_id' class='select2 '   ></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="Game Type " class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Game Type ', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='game_type_id' rows='5' id='game_type_id' class='select2 '   ></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group" id="multi_products" style="display: none;">
                        <label for="product_id" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Product ', (isset($fields['product_id']['language'])? $fields['product_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <select name='product_id[]' multiple rows='5' id='product_id' class='select2 '   ></select>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="Status " class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            
                            <select name='status_id' id='status_id' class='select2 ' required
                                    @if($isEdit) disabled='disabled' readonly='readonly' @endif>
                            </select>                            
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    
                    <div class="form-group  " >
                        <label for="Not Debit" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Not Debit', (isset($fields['not_debit']['language'])? $fields['not_debit']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="hidden" name="not_debit" value="{{ $row['not_debit'] }}"/>
                            <input type='checkbox' 
                                   data-proxy-input='not_debit' name='_not_debit' 
                                   value="{{ $row['not_debit'] }}"
                                   @if($row['not_debit']==1) checked @endif 
                            />                                 
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                    <div class="form-group  " >
                        <label for="not_debit_reason" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Not Debit Reason', (isset($fields['not_debit_reason']['language'])? $fields['not_debit_reason']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('not_debit_reason', $row['not_debit_reason'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  " >
                        <label for="notes" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
					  <textarea name='notes' rows='5' id='notes' class='form-control '
                                required  >{{ $row['notes'] }}</textarea>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <input type="hidden" name="for_sale" value="{{ $row['for_sale'] }}"/>
                    @if (isset($pass) && !empty($pass['Edit '. FEGFormat::field2title('for_sale')]))
                    <div class="form-group  " >
                        <label for="for_sale" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])? $fields['for_sale']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">                            
                            <input type='checkbox' 
                                   data-proxy-input='for_sale' name='_for_sale' 
                                   value="{{ $row['for_sale'] }}"
                                   @if($row['for_sale']==1) checked @endif 
                            />
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    @endif
                    <div class="form-group  " >
                        <label for="sale_price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])? $fields['sale_price']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            {!! Form::text('sale_price', $row['sale_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <input type="hidden" name="sale_pending" value="{{ $row['sale_pending'] }}"/>
                    @if (isset($pass) && !empty($pass['Edit '. FEGFormat::field2title('sale_pending')]))
                    <div class="form-group  " >
                        <label for="sale_pending" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Sale Pending', (isset($fields['sale_pending']['language'])? $fields['sale_pending']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">                            
                            <input type='checkbox' 
                                   data-proxy-input='sale_pending' name='_sale_pending' 
                                   value="{{ $row['sale_pending'] }}"
                                   @if($row['sale_pending']==1) checked @endif 
                            />                                 
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    @endif
                    @if($isEdit)
                    <div class="soldInputs clearfix @if($isSold) gameIsSold @endif" >                                        
                        <div class="form-group  " >
                            <label for="sold" class="control-label col-md-4 text-left text-danger text-bold">
                                {!! SiteHelpers::activeLang('Sold', (isset($fields['sold']['language'])? $fields['sold']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <input type="hidden" name="_oldSoldStatus" value="{{ $soldValue }}"/>
                                <input type="hidden" name="sold" value="{{ $soldValue }}"/>
                                <input type="checkbox" name="_sold" id="sold" 
                                       data-proxy-input='sold'
                                       value="{{ $soldValue }}"     
                                       data-original-value='{{ $soldValue }}' 
                                       @if($isSold) checked @endif                       
                                    />                        
                            </div>
                            <div class="col-md-2"></div>
                        </div>  
                
                        <div class="soldDetails" 
                            @if(!$isSold) style="display: none;" @endif>
                            <div class="form-group  " >
                                <label for="date_sold" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Sold Date', (isset($fields['date_sold']['language'])? $fields['date_sold']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group" style="width:150px;">
                                        <input name='date_sold'
                                            type='text' class='form-control date'
                                               value='{{ $soldDateFormatted }}'
                                               placeholder='Sold Date' 
                                               parsley-nofocus='true' 
                                               parsley-errors-container='.dateSoldError' 
                                               @if($isSold) required='required' @endif
                                               />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <div class='dateSoldError'></div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>              
                            <div class="form-group  " >
                                <label for="sold_to" class=" control-label col-md-4 text-left">
                                    {!! SiteHelpers::activeLang('Sale Details', (isset($fields['sold_to']['language'])? $fields['sold_to']['language'] : array())) !!}
                                </label>
                                <div class="col-md-6">
                                    <textarea name='sold_to' rows='5' 
                                        id='sold_to' 
                                        class='form-control '
                                        placeholder="Describe Game Sale Details"
                                        @if($isSold) required='required' @endif
                                        >{{ $soldTo }}</textarea>
                                </div>
                                <div class="col-md-2"></div>
                            </div>                                                      
                        </div>
                    </div>     
                    @endif  
                </fieldset>
            </div>                    
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#'+pageModule)" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif


<script type="text/javascript">
    
    var mainUrl = '{{ $pageUrl }}',
        mainModule = '{{ $pageModule }}';
    
    $(document).ready(function() {
        App.modules.games.formView.init({
                'container': $('#'+pageModule+'View'),
                'moduleName': pageModule,
                'mainModule': mainModule,
                'url': pageUrl,
                'mainUrl': mainUrl
            },
            {!! json_encode($row) !!}
        );
        
    });
</script>
