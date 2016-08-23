@if($setting['form-method'] =='native')

        <div class="sbox">
            <div class="sbox-title">
                <h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
                    <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
                </h4>
            </div>

            <div class="sbox-content">
                @endif
                {!! Form::open(array('url'=>'mylocationgame/save/'.$row['id'], 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'mylocationgameFormAjax')) !!}
                <div class="col-md-12">
                    <fieldset><legend> My Location Game</legend>
                        <div class="form-group  " >
                            <label for="Test Piece" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php $test_piece = explode(",",$row['test_piece']); ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" name="test_piece" value="0"/>
                                    <input type='checkbox' name='test_piece' value ='1' @if($row['test_piece']==1) checked @endif   class='' />  </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Game " class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Game ', (isset($fields['game_title_id']['language'])? $fields['game_title_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='game_title_id' rows='5' id='game_title_id' class='select2 '   ></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Version " class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Version ', (isset($fields['version_id']['language'])? $fields['version_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='version_id' rows='5' id='version_id' class='select2 '   ></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Location " class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Location ', (isset($fields['location_id']['language'])? $fields['location_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='location_id' rows='5' id='location_id' class='select2 '   ></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Serial" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('serial', $row['serial'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
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
                        <div class="form-group  " >
                            <label for="Status " class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <select name='status_id' rows='5' id='status_id' class='select2 '   ></select>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  " >
                            <label for="For Sale" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])? $fields['for_sale']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php $for_sale = explode(",",$row['for_sale']); ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" name="for_sale" value="0"/>
                                    <input type='checkbox' name='for_sale' value ='1'  class=''
                                           @if(in_array('1',$for_sale))checked @endif
                                            />  </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Sale Price" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])? $fields['sale_price']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('sale_price', $row['sale_price'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Sale Pending" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Sale Pending', (isset($fields['sale_pending']['language'])? $fields['sale_pending']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php $sale_pending = explode(",",$row['sale_pending']); ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" name="sale_pending" value="0"/>
                                    <input type='checkbox' name='sale_pending' value ='1'   class=''
                                           @if(in_array('1',$sale_pending))checked @endif
                                            />  </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Not Debit" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Not Debit', (isset($fields['not_debit']['language'])? $fields['not_debit']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                <?php $not_debit = explode(",",$row['not_debit']); ?>
                                <label class='checked checkbox-inline'>
                                    <input type="hidden" name="not_debit" value="0"/>
                                    <input type='checkbox' name='not_debit' value ='1'   class=''
                                           @if(in_array('1',$not_debit))checked @endif
                                            /> </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>

                        <div class="form-group  " >
                            <label for="Not Debit Reason" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Not Debit Reason', (isset($fields['not_debit_reason']['language'])? $fields['not_debit_reason']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
                                {!! Form::text('not_debit_reason', $row['not_debit_reason'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Notes" class=" control-label col-md-4 text-left">
                                {!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
                            </label>
                            <div class="col-md-6">
					  <textarea name='notes' rows='5' id='notes' class='form-control '
                                required  >{{ $row['notes'] }}</textarea>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                    </fieldset>
                </div>
                <div style="clear:both"></div>

                <div class="form-group">
                    <label class="col-sm-4 text-right">&nbsp;</label>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                        <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                    </div>
                </div>
                {!! Form::close() !!}


                @if($setting['form-method'] =='native')
            </div>
        </div>
    @endif




    <script type="text/javascript">
        $(document).ready(function() {

            $("#game_title_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_title:id:game_title') }}",
                    {  selected_value : '{{ $row["game_title_id"] }}' });

            $("#game_type_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_type:id:game_type') }}",
                    {  selected_value : '{{ $row["game_type_id"] }}' });

            $("#version_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_version:id:version') }}",
                    {  selected_value : '{{ $row["version_id"] }}' });

            $("#location_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=location:id:location_name') }}",
                    {  selected_value : '{{ $row["location_id"] }}' });

            $("#mfg_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=vendor:id:vendor_name') }}",
                    {  selected_value : '{{ $row["mfg_id"] }}' });

            $("#status_id").jCombo("{{ URL::to('mylocationgame/comboselect?filter=game_status:id:game_status') }}",
                    {  selected_value : '{{ $row["status_id"] }}' });


            $('.editor').summernote();
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            $(".select2").select2({ width:"98%"});
            $('.date').datepicker({format:'mm/dd/yyyy',autoClose:true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square_green'
            });
            $('.removeCurrentFiles').on('click',function(){
                var removeUrl = $(this).attr('href');
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });
            var form = $('#mylocationgameFormAjax');
            form.parsley();
            form.submit(function(){

                if(form.parsley('isValid') == true){
                    var options = {
                        dataType:      'json',
                        beforeSubmit :  showRequest,
                        success:       showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }

            });

        });

        function showRequest()
        {
            $('.ajaxLoading').show();
        }
        function showResponse(data)  {

            if(data.status == 'success')
            {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }

    </script>