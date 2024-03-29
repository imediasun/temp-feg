@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'gamesdisposed/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'gamesdisposedFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Disposed Games</legend>

                    <!--div class="form-group  ">
                        <label for="Test Piece" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])?
                            $fields['test_piece']['language'] : array())) !!}
                        </label>
                        <div class="col-md-6">
                            <input type="hidden" name="test_piece" value="0"/>
                          <input @if($row['test_piece'] == 1) checked @endif type="checkbox" name="test_piece" id="test_piece" value="1">
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div-->

                    <div class="form-group  ">
                        <label for="Game Name" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])?
                            $fields['game_name']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <select name="game_title_id" id="game_title_id" class="select4" required="required">
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="game_name" id="game_name"/>
                        </div>
                    </div>

                    <div class="form-group" >
                        
                    <label for="Notes" class="control-label col-md-4 text-left">
                        {!! SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) !!}
                    </label>
                    
                    <div class="col-md-6">
					  <textarea name='notes' rows='5' id='notes' class='form-control'
                                required  >{{ $row['notes'] }}
                        </textarea>
                    </div>
                    
                    <div class="col-md-2">

                    </div>
                    
                    </div>
            
                </fieldset>
            </div>
            <div style="clear:both"></div>

            <div class="form-group">
               <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm "><i
                                class="fa  fa-save "></i>  {{ Lang::get('core.sb_save') }} </button>
                    <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm">
                        <i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                </div>
            </div>
            {!! Form::close() !!}


            @if($setting['form-method'] =='native')
        </div>
    </div>
    @endif
    <script type="text/javascript">
        $(document).ready(function () {
            $("#game_title_id").jCombo("{{ URL::to('gamesintransit/comboselect?filter=game_title:id:game_title') }}",
                    {selected_value: '{{ $row['game_title_id'] }}', initial_text: 'Select Game'});
            getGameName();
            $('.editor').summernote();
            $('.previewImage').fancybox();
            $('.tips').tooltip();
            renderDropdown($(".select4 "), { width:"100%"});
            $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
            $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
            $('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square_green'
            });
            $('.removeCurrentFiles').on('click', function () {
                var removeUrl = $(this).attr('href');
                $.get(removeUrl, function (response) {
                });
                $(this).parent('div').empty();
                return false;
            });
            var form = $('#gamesdisposedFormAjax');
            form.parsley();
            form.submit(function () {

                if (form.parsley('isValid') == true) {
                    var options = {
                        dataType: 'json',
                        beforeSubmit: showRequest,
                        success: showResponse
                    }
                    $(this).ajaxSubmit(options);
                    return false;

                } else {
                    return false;
                }

            });

        });

        function showRequest() {
            $('.ajaxLoading').show();
        }
        function showResponse(data) {

            if (data.status == 'success') {
                ajaxViewClose('#{{ $pageModule }}');
                ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
                notyMessage(data.message);
                $('#sximo-modal').modal('hide');
            } else {
                notyMessageError(data.message);
                $('.ajaxLoading').hide();
                return false;
            }
        }
        $("#game_title_id").on('change',function(){
            getGameName();
        });
        function getGameName()
        {
            var game_name=$("#select2-chosen-1").text();
            $('#game_name').val(game_name);
        }

    </script>