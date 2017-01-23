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
            {!! Form::open(array('url'=>'gamesintransit/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'gamesintransitFormAjax')) !!}
            <div class="col-md-12">
                <fieldset>
                    <legend> Games In Transit</legend>


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
                            <input type="hidden" name="game_name" id="game_name" />
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="For Sale" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])?
                            $fields['for_sale']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <input type="hidden" name="for_sale" value="0"/>
                           <input @if($row['test_piece'] == 1) checked @endif type="checkbox" name="for_sale" id="for_sale" value="1">

                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <div class="form-group  ">
                        <label for="sale_price" class=" control-label col-md-4 text-left">
                            {!! SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])?
                            $fields['sale_price']['language'] : array())) !!}
                        </label>

                        <div class="col-md-6">
                            <input type="number" value="0.00" step=".1" class="form-control" name="sale_price" id="sale_price"  required="required">
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
        $(".select4").select2({width: "98%"});
        $('.date').datepicker({format: 'mm/dd/yyyy', autoClose: true})
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
        var form = $('#gamesintransitFormAjax');
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