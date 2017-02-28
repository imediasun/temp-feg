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
            {!! Form::open(array('url'=>'managefreightquoters/save/'.$row['id'],
            'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
            'managefreightquotersFormAjax')) !!}
            <div class="col-md-8 col-md-offset-2" style="background: #FFF;box-shadow:1px 1px 5px lightgray">
                <fieldset>
                    <legend>Get Freight Quote</legend>

                    <div class="form-group  ">
                        <div class="col-md-8 col-md-offset-2">
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
                        <label class="col-md-6 col-md-offset-3">
                            From :
                        </label>
                        <div id="from_div">
                            <div class="col-md-6 col-md-offset-4" id="location_from_div">
                                <select name="location_from" id="location_id" class="select3">
                                </select>
                            </div>
                            <div class="col-md-6 col-md-offset-4" id="vend_from_div">
                                <select name="vend_from" id="vendor_id" class="select3">
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
                        <div class="col-md-8 col-md-offset-2">
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
                        <label class="col-md-6 col-md-offset-3">
                            To :
                        </label>

                        <div id="from_div">
                            <div class="col-md-6 col-md-offset-4" id="location_to_div">
                                <div class="clone clonedInput">
                                    <div class="col-md-10" style="padding:0px">
                                        <select name="location_to[]" style="" id="location_to_id" class="form-control">
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a onclick=" $(this).parents('.clonedInput').remove();  return false"
                                           href="#" class="remove btn btn-xs btn-danger">-</a>
                                        <input type="hidden" name="counter[]">
                                    </div>
                                </div>
                                <div class="text-center col-md-2 col-md-offset-3" style="padding-left:30px">
                                    <a style="display:inline-block;margin-top:10px;" href="javascript:void(0);"
                                       class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> Add
                                        Location</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-4" id="vend_to_div">
                                <select name="vend_to" id="vendor_to_id" class="select3">
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
                    <div class="text-center col-md-3 col-md-offset-5" style="padding-left:60px" id="add_section_btn">
                        <a style="display:inline-block;margin:10px;" href="javascript:void(0);"
                           class="addC btn btn-xs btn-info" rel=".clone1" id="newpallet" "><i class="fa fa-plus"></i> Add
                            Section</a>
                    </div><div class="clearfix"></div>
<div class="testbtn">
                    <div class="clone1 clonedInput">
                        <div class="form-group">
                            <label for="" class="control-label col-md-4 text-left">
                                Description <span id="#"></span>
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
                                Dimensions <span id="#"></span></label>

                            <div class="col-md-6">
                                <input type="text" name="dimensions[]" id="dimensions"
                                       value="<?php //echo set_value('dimensions'); ?>" class="form-control" required/>
                            </div>
                            <div class="col-md-2" >
                                <a onclick=" $(this).parents('.clonedInput').remove(); return false"
                                   href="#"
                                   class=" remove btn btn-xs btn-danger">-</a>
                                <input type="hidden" name="counter[]">
                            </div>
                        </div>

                        <hr/>
                    </div>
    </div>

                    <div class="clearfix"></div>
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
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-4 text-right">&nbsp;</label>
                        <div class="col-sm-6 text-center">
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
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $('#std_from_div,#vend_from_div,#location_from_div,#from_vendor_type_div,#std_to_div,#vend_to_div,#location_to_div,#to_vendor_type_div').hide();
        $("#location_id").jCombo("{{ URL::to('managefreightquoters/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '', initial_text: 'Select Location'});
        $("#vendor_id").jCombo("{{ URL::to('managefreightquoters/comboselect?filter=vendor:id:vendor_name') }}",
                {selected_value: '', initial_text: 'Select Vendor'});
        $('[id^="location_to_id"]').jCombo("{{ URL::to('managefreightquoters/comboselect?filter=location:id:id|location_name') }}",
                {selected_value: '', initial_text: 'Select Location'});
        $("#vendor_to_id").jCombo("{{ URL::to('managefreightquoters/comboselect?filter=vendor:id:vendor_name') }}",
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
    $('#radio_from_loc,#radio_from_vend,#radio_from_blank').on('ifChecked', function (event) {
        var id = $(this).attr('id');
        fromType(id);
    });
    $('#radio_to_loc,#radio_to_vend,#radio_to_blank').on('ifChecked', function (event) {
        var id = $(this).attr('id');
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




