@if($setting['form-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4>@if($id)
                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Freight Quote
                @else
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Create New Freight Quote
                @endif
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
            </h4>
        </div>

        <div class="sbox-content">
            @endif
            {!! Form::open(array('url'=>'managefreightquoters/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'managefreightquotersFormAjax')) !!}
            <div class="col-md-8 col-md-offset-2" style="background: #FFF;box-shadow:1px 1px 5px lightgray">
                <fieldset>
                    <input type="hidden" value="" id="recipient_to" name='recipients[to]'/>
                    <input type="hidden" value="" id="recipient_cc" name='recipients[cc]'/>
                    <input type="hidden" value="" id="recipient_bcc" name='recipients[bcc]'>

                    <div class="form-group">
                        <label class="col-md-4">
                            From :
                        </label>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="from_type" value="location" id="radio_from_loc">&nbsp&nbsp Our
                                Location </label>
                            <label class="radio-inline">
                                <input type="radio" name="from_type" value="vendor" id="radio_from_vend">&nbsp&nbsp
                                Vendor on File
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="from_type" value="blank" id="radio_from_blank"> &nbsp&nbsp
                                Blank Form
                            </label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">

                        <div id="from_div">
                            <div class="col-md-6 col-md-offset-4" id="location_from_div">
                                <select name="location_from" id="location_id" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-6 col-md-offset-4" id="vend_from_div">
                                <select name="vend_from" id="vendor_id" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-12" id="std_from_div">
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_from_add_name">Name</label>

                                    <div class="col-md-6">
                                        <input type="text" name="from_add_name" id="from_add_name"
                                               value="<?php //echo //set_value('from_add_name'); ?>"
                                               placeholder="Vendor/Customer Name" class="form-control" />
                                    </div>
                                    <div class="col-md-2"></div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_from_add_street">Street
                                        Address</label>

                                    <div class="col-md-6">
                                        <input type="text" name="from_add_street" id="from_add_street"
                                               value="<?php //echo set_value('from_add_street'); ?>"
                                               placeholder="Street Address" class="form-control" />
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_from_add_city_state_zip">City
                                        | State | Zip</label>

                                    <div class="col-md-6">
                                        <input type="text" name="from_add_city" id="from_add_city"
                                               value="<?php //echo// set_value('from_add_city'); ?>" placeholder="City"
                                               class="form-control pull-left" style="width:50%"/>
                                        <input type="text" name="from_add_state" id="from_add_state"
                                               value="<?php //echo //set_value('from_add_state'); ?>" placeholder="ST"
                                               class="form-control pull-left"  maxlength="2"
                                               style="width:24%;margin-left:1%"/>
                                        <input type="text" name="from_add_zip" id="from_add_zip"
                                               value="<?php //echo// set_value('from_add_zip'); ?>"
                                               placeholder="Zip Code" class="form-control pull-right"
                                               style="width:24%"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group"><label class="control-label col-md-4 text-left"
                                                               id="lbl_from_contact_name">Contact Name</label>

                                    <div class="col-md-6">
                                        <input type="text" name="from_contact_name" id="from_contact_name"
                                               value="<?php //echo set_value('from_contact_name'); ?>"
                                               placeholder="Contact Name" class="form-control"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_from_contact_phone_email">Contact
                                        Phone | Email</label>

                                    <div class="col-md-6">
                                        <input type="phone" name="from_contact_phone" id="from_contact_phone"
                                               value="<?php //echo set_value('from_contact_phone'); ?>"
                                               placeholder="Phone #" class="form-control" style="width:33%;float:left"/>
                                        <input type="email" name="from_contact_email" id="from_contact_email"
                                               value="<?php //echo set_value('from_contact_email'); ?>"
                                               placeholder="Email Address" class="form-control"
                                               style="width:66%;float:right"/>

                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_from_loading_info">Shipping
                                        Restrictions</label>

                                    <div class="col-md-6">
                                        <input type="text" name="from_loading_info" id="from_loading_info" value=""
                                               placeholder="Shipping Restrictions" class="form-control"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_add_from_vendor_to_list"
                                           style="float:left ;color:#A11C1C;">ADD TO VENDOR LIST</label>

                                    <div class="col-md-6">
                                        <input type="checkbox" name="add_from_vendor_to_list"
                                               value=""
                                               id="add_from_vendor_to_list">

                                        <div id="from_vendor_type_div" class="pull-right"
                                             style="margin-right:40px ">
                                            <?php
                                            // $is_game_checked = true;
                                            // $setvalue = set_value('from_vendor_type');
                                            //  if ($setvalue == 'merch') {
                                            //       $is_game_checked = false;
                                            //   }
                                            ?>
                                            <div>
                                                <label>
                                                    <input type="radio" name="from_vendor_type"
                                                       checked    value="games" <?php //echo $is_game_checked ? 'checked' : ''; ?>>&nbsp&nbsp
                                                    GAMES Vendor<br/>
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <input type="radio" name="from_vendor_type"
                                                           value="merch" <?php //echo $is_game_checked ? '' : 'checked'; ?>>
                                                    &nbsp&nbsp MERCH Vendor
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <hr/>

                    <div class="form-group  ">
                        <label class="col-md-4">
                            To :
                        </label>
                        <div class="col-md-8 ">
                            <label class="radio-inline">
                                <input type="radio" name="to_type" value="location" id="radio_to_loc">&nbsp&nbsp
                                Our Location </label>
                            <label class="radio-inline">
                                <input type="radio" name="to_type" value="vendor" id="radio_to_vend">&nbsp&nbsp
                                Vendor on File
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="to_type" value="blank" id="radio_to_blank"> &nbsp&nbsp
                                Blank Form
                            </label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div id="from_div">
                            <div class="col-md-6 col-md-offset-4" id="location_to_div">
                                <div class="clone clonedInput">
                                    <div class="col-md-12" style="padding:0;margin-bottom: 15px">
                                        <select name="location_to[]" style="width: 100%" id="location_to_id" class="form-control">
                                        </select>
                                        <div class="col-md-1" style="position: absolute;right: -47px;top: 5px;">
                                            <a onclick=" $(this).parents('.clonedInput').remove();$(this).removeAttr('required');  reInitParcley();  return false"
                                               href="#" class="remove btn btn-xs btn-danger">-</a>
                                            <input type="hidden" name="counter[]">
                                        </div>
                                    </div>

                                </div>
                                <div class="text-center col-md-2 col-md-offset-3" style="padding-left:30px">
                                    <a style="display:inline-block;margin-top:10px;" href="javascript:void(0);"
                                       class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> Add
                                        Location</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-4" id="vend_to_div">
                                <select name="vend_to" id="vendor_to_id" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-12" id="std_to_div">
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_to_add_name">Name</label>

                                    <div class="col-md-6">
                                        <input type="text" name="to_add_name" id="to_add_name"
                                               value="<?php //echo //set_value('from_add_name'); ?>"
                                               placeholder="Vendor/Customer Name" class="form-control" />
                                    </div>
                                    <div class="col-md-2"></div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_to_add_street">Street
                                        Address</label>

                                    <div class="col-md-6">
                                        <input type="text" name="to_add_street" id="to_add_street"
                                               value="<?php //echo set_value('from_add_street'); ?>"
                                               placeholder="Street Address" class="form-control" />
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_to_add_city_state_zip">City
                                        | State | Zip</label>

                                    <div class="col-md-6">
                                        <input type="text" name="to_add_city" id="to_add_city"
                                               value="<?php //echo// set_value('from_add_city'); ?>" placeholder="City"
                                               class="form-control pull-left"  style="width:50%"/>
                                        <input type="text" name="to_add_state" id="to_add_state"
                                               value="<?php //echo //set_value('from_add_state'); ?>" placeholder="ST"
                                               class="form-control pull-left"  maxlength="2"
                                               style="width:24%;margin-left:1%"/>
                                        <input type="text" name="to_add_zip" id="to_add_zip"
                                               value="<?php //echo// set_value('from_add_zip'); ?>"
                                               placeholder="Zip Code" class="form-control pull-right"
                                               style="width:24%"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group"><label class="control-label col-md-4 text-left"
                                                               id="lbl_to_contact_name">Contact Name</label>

                                    <div class="col-md-6">
                                        <input type="text" name="to_contact_name" id="to_contact_name"
                                               value="<?php //echo set_value('from_contact_name'); ?>"
                                               placeholder="Contact Name" class="form-control"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_to_contact_phone_email">Contact
                                        Phone | Email</label>

                                    <div class="col-md-6">
                                        <input type="phone" name="to_contact_phone" id="to_contact_phone"
                                               value="<?php //echo set_value('from_contact_phone'); ?>"
                                               placeholder="Phone #" class="form-control" style="width:33%;float:left"/>
                                        <input type="email" name="to_contact_email" id="to_contact_email"
                                               value="<?php //echo set_value('from_contact_email'); ?>"
                                               placeholder="Email Address" class="form-control"
                                               style="width:66%;float:right"/>

                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_to_loading_info">Shipping
                                        Restrictions</label>

                                    <div class="col-md-6">
                                        <input type="text" name="to_loading_info" id="to_loading_info" value=""
                                               placeholder="Shipping Restrictions" class="form-control"/>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 text-left" id="lbl_add_to_vendor_to_list"
                                           style="float:left ;color:#A11C1C;">ADD TO VENDOR LIST</label>

                                    <div class="col-md-6">
                                        <input type="checkbox" name="add_to_vendor_to_list"
                                               value=""
                                               id="add_to_vendor_to_list">

                                        <div id="to_vendor_type_div" class="pull-right"
                                             style="margin-right:40px ">
                                            <?php
                                            // $is_game_checked = true;
                                            // $setvalue = set_value('from_vendor_type');
                                            //  if ($setvalue == 'merch') {
                                            //       $is_game_checked = false;
                                            //   }
                                            ?>
                                            <div>
                                                <label>
                                                    <input type="radio" name="to_vendor_type"
                                                           checked value="games" <?php //echo $is_game_checked ? 'checked' : ''; ?>>&nbsp&nbsp
                                                    GAMES Vendor<br/>
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <input type="radio" name="to_vendor_type"
                                                           value="merch" <?php //echo $is_game_checked ? '' : 'checked'; ?>>
                                                    &nbsp&nbsp MERCH Vendor
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                    <hr/>
                    <h3>Pallet Details:</h3>

<div class="testbtn">
                    <div class="clone1 clonedInput">
                        <div class="form-group">
                            <label for="" class="control-label col-md-4 text-left">
                                <span class="counter"> </span>  Description
                            </label>

                            <div class="col-md-6">
                                <input type="text" name="description[]" id="description"
                                       value="<?php //echo set_value('description'); ?>" class="form-control" required/>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-md-4 text-left">
                                <span class="counter1"> </span>  Dimensions <span id="#"></span></label>

                            <div class="col-md-6">
                                <input type="text" name="dimensions[]" id="dimensions"
                                       value="<?php //echo set_value('dimensions'); ?>" class="form-control" required/>
                            </div>
                            <div class="col-md-2" >
                                <p id="hide-button"
                                   onclick="removeRow(this.id);"
                                   class="remove btn btn-xs btn-danger">-
                                </p>
                                <input type="hidden" name="counter[]">
                            </div>
                        </div>


                    </div>
    </div>
                    <div class="text-center col-md-3 col-md-offset-5" style="padding-left:60px" id="add_section_btn">
                        <a style="display:inline-block;margin:10px;" href="javascript:void(0);"
                           class="addC btn btn-xs btn-info" rel=".clone1" id="newpallet"><i class="fa fa-plus"></i> Add
                        Section</a>
                    </div><div class="clearfix"></div>

                    <div class="clearfix"></div>
                    <hr/>
                    <div class="form-group">
                        <label for="" class="control-label col-md-4 text-left">
                            Shipment Notes
                        </label>

                        <div class="col-md-6">
                            <textarea class="form-control" rows="6" cols="6" name="ship_notes"
                                      id="ship_notes"></textarea>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-md-4 text-left">
                            # Games Per Destination
                        </label>

                        <div class="col-md-6">
                            <select class="form-control" name="games_per_location" id="games_per_location">
                                @for($i =0 ; $i <= 12 ; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm "><i
                                        class="fa  fa-save "></i>  Email Freight Quote </button>
                        </div>
                    </div>

                </fieldset>
            </div><div class="clearfix"></div>
            {!! Form::close() !!}
            @if($setting['form-method'] =='native')
        </div>
    </div>
    <!-- popup for sending email  -->

    <div class="modal" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div id="mycontent" class="modal-content">
                <div id="myheader" class="modal-header">
                    <button type="button " class="btn-xs collapse-close btn btn-danger pull-right"
                            data-dismiss="modal" aria-hidden="true"><i class="fa fa fa-times"></i>
                    </button>
                    <h4>Send Email</h4>
                </div>
                <div class="modal-body col-md-offset-1 col-md-10">
                    {!! Form::open(array('url'=>'order/saveorsendemail',
                    'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'
                    ','id'=>'sendFormAjax')) !!}

                    <div class="form-group">
                        <label class="control-label col-md-4" for="to">To</label>

                        <div class="col-md-8">
                            <input name="to" id="to" value="{{$recipients['to']}}" data-value="" class="form-control orderEmailAutoComplete" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="cc">CC</label>
                        <div class="col-md-8">
                            <input name="cc" id="cc" value="{{$recipients['cc']}}" data-value="" multiple class="form-control orderEmailAutoComplete" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="bcc">BCC</label>

                        <div class="col-md-8">
                            <input name="bcc" id="bcc" value="{{$recipients['bcc']}}" multiple data-value="" class="form-control orderEmailAutoComplete" />
                        </div>
                    </div>
                    <input type="hidden" name="type" value="send"/>
                    <div class="col-md-offset-6 col-md-6">
                        <div class="form-group" style="margin-top:10px;">
                            <button type="submit" name="submit" value="sendemail" id="send-email"
                                    data-button="create" onclick="//submitForm()"
                                    class="btn btn-info btn-lg" style="width:33%" title="SEND"><i
                                        class="fa fa-sign-in  "></i>&nbsp {{ Lang::get('core.sb_send') }}
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="clearfix"></div>

            </div>

        </div>
    </div>
    <!-- popup for sending email ends here.. -->
@endif
<script type="text/javascript">
    $(document).ready(function () {
        var orderEmailAutoComplete = $('.orderEmailAutoComplete')
        App.initAutoComplete(orderEmailAutoComplete,
        {
            url: siteUrl+'/order/email-history',
            params: {'search': orderEmailAutoComplete}
        });
        $('#std_from_div,#vend_from_div,#location_from_div,#from_vendor_type_div,#std_to_div,#vend_to_div,#location_to_div,#to_vendor_type_div').hide();
        $("#location_id").jCombo("{{ URL::to('managefreightquoters/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '', initial_text: 'Select Location'});
        $("#vendor_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name:hide:0:status:1') }}",
                {selected_value: '', initial_text: 'Select Vendor'});
        $('[id^="location_to_id"]').jCombo("{{ URL::to('managefreightquoters/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '', initial_text: 'Select Location'});
        $("#vendor_to_id").jCombo("{{ URL::to('product/comboselect?filter=vendor:id:vendor_name:hide:0:status:1') }}",
                {selected_value: '', initial_text: 'Select Vendor'});
        $('.editor').summernote();
        $('.addC').relCopy({});
        $('.previewImage').fancybox();
        $('.tips').tooltip();
        renderDropdown($(".select2, .select3, .select4, .select5"), { width:"100%"});
        $('.date').datepicker({format: 'mm/dd/yyyy', autoclose: true})
        $('.datetime').datetimepicker({format: 'mm/dd/yyyy hh:ii:ss'});
        $('input[type="checkbox"],input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        $('.removeCurrentFiles').on('click', function () {
            var removeUrl = $(this).attr('href');
            $.get(removeUrl, function (response) {
            });
            $(this).parent('div').empty();
            return false;
        });
        var form = $('#managefreightquotersFormAjax');
        form.parsley();
        form.submit(function () {
            if (form.parsley('isValid') == true) {
                $('#myModal').modal('show');

                return false;

            } else {
                return false;
            }

        });


    });
$("#radio_to_loc").click();
    function showRequest() {
        $('.ajaxLoading').show();
    }
    function showResponse(data) {

        if (data.status == 'success') {
            ajaxViewClose('#{{ $pageModule }}');
            {{ \Session::put('freight_status', 'requested') }}
            ajaxFilter('#{{ $pageModule }}', '{{ $pageUrl }}/data');
            notyMessage(data.message);
            $('#sximo-modal').modal('hide');
        } else {
            notyMessageError(data.message);
            $('.ajaxLoading').hide();
            return false;
        }
    }
    $('#radio_from_loc,#radio_from_vend,#radio_from_blank').on('ifChecked', function (event) {
        var id = $(this).attr('id');
        fromType(id);
    });
    $(".addC").click(function(){
        reInitParcley();
    });
    $('#radio_to_loc,#radio_to_vend,#radio_to_blank').on('ifChecked', function (event) {
        var id = $(this).attr('id');
        if(id == "radio_to_loc")
        {
            $('[id^=location_to_id]').attr('required','required');
            reInitParcley();
        }
        else
        {
          $('[id^=location_to_id]').removeAttr('required');
            $('#location_to_id').removeAttr('required');
           // $('[id^=location_to_id]').attr('required',false);
            reInitParcley();
        }
        toType(id);
    });
    $('#add_from_vendor_to_list').on('ifChecked', function (event) {
        $("#from_vendor_type_div").show();
    });
    $('#add_from_vendor_to_list').on('ifUnchecked', function (event) {
        $("#from_vendor_type_div").hide();
    });
    $('#add_to_vendor_to_list').on('ifChecked', function (event) {
        $("#to_vendor_type_div").show();
    });
    $('#add_to_vendor_to_list').on('ifUnchecked', function (event) {
        $("#to_vendor_type_div").hide();
    });

    function fromType(type) {
        if (type == 'radio_from_loc') {
            $('#location_from_div').show();
            $('#std_from_div,#vend_from_div').hide();
        }
        else if (type == 'radio_from_vend') {
            $('#vend_from_div').show();
            $('#std_from_div,#location_from_div').hide();
        }
        else {
            $('#std_from_div').show();
            $('#vend_from_div,#location_from_div').hide();
        }
    }
    function toType(type) {
        if (type == 'radio_to_loc') {
            $('#location_to_div').show();
            $('#std_to_div,#vend_to_div').hide();
        }
        else if (type == 'radio_to_vend') {
            $('#vend_to_div').show();
            $('#std_to_div,#location_to_div').hide();

        }
        else {
            $('#std_to_div').show();
            $('#vend_to_div,#location_to_div').hide();
        }
    }
    var counter=0;
    $("#newpallet").click(function(){
        handleItemCount('add');
    });
    function removeRow(id) {
        reInitParcley();
        if (counter > 2) {
            $("#" + id).parents('.clonedInput').remove();
        }
        else {
            notyMessageError("You can't remove first item.");
        }
        decreaseCounter();
        return false;
    }
    function handleItemCount(mode) {
        $('.counter').each(function (index, value) {
            $(value).text("#"+ ++index+ "." );
            counter = index + 1;
        });
        $('.counter1').each(function (index, value) {
            $(value).text("#"+ ++index+ "." );
            counter = index + 1;
        });
    }
    function decreaseCounter() {

        handleItemCount('remove');
    }
    function reInitParcley()
    {
        $('#managefreightquotersFormAjax').parsley().destroy();
        $('#managefreightquotersFormAjax').parsley(
                {
                    excluded: 'input[type=button], input[type=submit], input[type=reset]',
                    inputs: 'input, textarea, select, input[type=hidden], :hidden'
                } );
    }
    //sendFormAjax
    $(function () {
        var popupForm =  $('#sendFormAjax');

        popupForm.parsley().destroy();
        popupForm.parsley();
        popupForm.submit(function (e) {
            e.preventDefault();
            if (popupForm.parsley('isValid') == true) {

                $('#recipient_to').val($('#to').val());
                $('#recipient_cc').val($('#cc').val());
                $('#recipient_bcc').val($('#bcc').val());

                var options = {
                    dataType: 'json',
                    beforeSubmit: showRequest,
                    success: showResponse
                }
                $('#managefreightquotersFormAjax').ajaxSubmit(options);
                $('#myModal').modal('hide');

                return false;

            } else {
                return false;
            }

        });
    });
    function submitForm() {/*
        $('#recipient_to').val($('#to').val());
        $('#recipient_cc').val($('#cc').val());
        $('#recipient_bcc').val($('#bcc').val());

    /!*    setTimeout(function () {
            $('#managefreightquotersFormAjax').submit();
            $('#myModal').modal('hide');
        }, 100)*!/

        var options = {
            dataType: 'json',
            beforeSubmit: showRequest,
            success: showResponse
        }
        $('#managefreightquotersFormAjax').ajaxSubmit(options);
        $('#myModal').modal('hide');*/
    }
</script>
<style>
    .clone:first-of-type a {
        display: none;
    }
   .clone1:first-of-type a.remove
    {
        display:none;
    }
</style>





