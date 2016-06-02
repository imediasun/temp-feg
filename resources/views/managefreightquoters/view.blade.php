@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content"> 
@endif
        {!! Form::open(array('url'=>'managefreightquoters/update/'.$row['freight_order_id'],
        'class'=>'form-vertical','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=>
        'managefreightquotersFormAjax')) !!}
        <input type="hidden" id="freight_order_id" name="freight_order_id" value="{{ $row['freight_order_id'] }}" >
        <input type="hidden" id="num_games_per_destination" name="num_games_per_destination" value="{{ $row['num_games_per_destination'] }} " >
        <input type="hidden" id="ship_to_type" name="ship_to_type" value="{{ $row['ship_to_type'] }}" >
        <input type="hidden" id="freight_contents" name="freight_contents" value=' ' >
        <input type="hidden" id="current_status_id" name="current_status_id" value='{{ $row["current_status_id"] }}' >
        <input type="hidden" id="contact_email" name="contact_email" value=' {{ $row["contact_email"] }}' >
		<div class="col-md-8 col-md-offset-2" style="background-color:#FFF;box-shadow: 1px 1px 5px lightgray;padding:30px">
            <h2 class="text-center">Freight Order</h2>
            <hr/>
            <div class="form-group">
                <label class="label-control col-md-3">Status:</label>
                <div class="col-md-9">
                    <?php echo  $row['status'] ?> &nbsp&nbsp&nbsp
                        @if(!strpos($row['status'],'Paid'))
                        <a href="{{ url()}}/managefreightquoters/paid/{{$row['freight_order_id']}}" onclick="return confirm('Confirm?');"> MARK PAID</a>
                        @endif
                        @if(strpos($row['status'],'Paid'))
                        <b> on &nbsp&nbsp&nbsp{{ $row['date_paid'] }}</b>
                        @endif
                </div>
            </div>
            <div class="form-group" style="border-bottom:1px solid #eee;margin:20px 0px" >
                <label class="label-control col-md-3">From:</label>
                <div class="col-md-9">
                    <input type="hidden" id="from_loc" name="from_loc" value="{{ $row['loc_from_id'] }}" >
                    <?php echo  $row['from_address'] ?> &nbsp&nbsp&nbsp
                </div>
                <div class="clearfix"></div>
            </div>
            @if(!empty($row['vend_to']) || !empty($row['to_add_street']))
                <div class="form-group">
                        <label class="label-control col-md-3" >TO:</label>
                    <div class="col-md-9">
                        <p style="text-align:left; width: 200px;">
                   <?php echo  $row['to_address'] ?>
                        </p>
                    </div>
                </div>
                    @endif

            <h3>Freight Contents:</h3>
            @for($i=0;$i<count($row['description']);$i++)
                <input type="hidden" name="freight_pallet_id[]" value="{{ $row['freight_pallet_id'][$i] }}"/>
            <div class="form-group">
                <br/>
                <label class="label-control col-md-3">Pallet  {{ $i+1 }}</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="description[]" value="{{ $row['description'][$i] }}"/>
                </div>
            </div>
                <div class="form-group">
                    <br/>
                    <label class="label-control col-md-3">Dimension  {{ $i+1 }}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="dimension[]" value="{{ $row['dimensions'][$i] }}"/>
                    </div>
                </div>
                @endfor
            <div class="form-group">
                <br/>
                <label class="label-control col-md-3">Shipment Notes:</label>
                <div class="col-md-9">
                <textarea name="notes" rows="6" cols="20" id="notes" class="form-control">{{ $row['notes'] }}</textarea>
                </div>
            </div><div class="clearfix"></div>
           <div style="border-bottom:1px solid #eee;margin:20px 0px;widht:100%"></div>
            <div class="form-group">
                <label class="label-control col-md-3">Date Submitted</label>
                <div class="col-md-9">
                    {{ $row['date_submitted'] }}
                </div>
            </div>
            <div class="form-group">
                <br/><br/>
                <label class="label-control col-md-3">Date Booked</label>
                <div class="col-md-9">
                    {{ $row['date_booked'] }}
                </div>
            </div>
            <div class="form-group">
                <br/><br/>
                <label class="label-control col-md-3">Date Paid</label>
                <div class="col-md-9">
                    {{ $row['date_paid'] }}
                      </div>
            </div>
            <div class="form-group">
                <br/><br/>
                <label class="label-control col-md-3">Damage or Delays</label>
                <div class="col-md-9">
                    @if(is_string($row['ship_exception']))
                        {{ $row['ship_exception'] }}
                        @else
                        {{ implode(',',$row['ship_exception']) }}
                        @endif
                </div>
            </div><div class="clearfix"></div>
            <div style="border-bottom:1px solid #eee;margin:20px 0px;width:100%"></div>
            @if(!empty($row['vend_to']) || !empty($row['to_add_street']))
                <div class="form-group">
                    <label class="col-md-3 label-control">
                        Booked Through:
                    </label>
                    <div class="col-md-9">
                          <input type="hidden" name="freight_company_1" id="freight_company_1"   value="{{ isset($row['freight_company_1'])?$row['freight_company_1']:0 }}" >
                </div>
                </div>
                <div class="clearfix"></div>
                      <div class="form-group">
                          <br/>
                          <label class="control-label col-md-3">Quoted Price: $</label>
                       <div class="col-md-9">
                           <input id="external_ship_quote" name="external_ship_quote" value="{{ $row['external_ship_quote']  }}" class="form-control">
                      </div>
                          </div>
                      <div class="form-group">
                          <br/>
                          <label class="control-label col-md-3">Trucking Line:</label>
                          <div class="col-md-9">
                          <input id="external_ship_trucking_co" name="external_ship_trucking_co" class="form-control" value="{{$row['external_ship_trucking_co']}}" >
                      </div>
                          </div>
                      <div class="form-group">
                          <br/>
                          <label class="control-label col-md-3">Pro Number:</label>
                     <div class="col-md-9">
                          <input id="external_ship_pro" name="external_ship_pro" value="{{$row['external_ship_pro']}}" class="form-control">
                     </div>
                      </div>

            @endif
            @if(isset($row['freight_loc_info']['location']) && !empty($row['freight_loc_info']['location']))

            @for($i=0;$i < count($row['freight_loc_info']['location']);$i++)
                    <input type="hidden" name="freight_loc_to_id[]" value="{{ $row['freight_loc_info']['freight_loc_to_id'][$i] }}"/>

                    <div class="form-group">
                <br/><br/>
                <label class="label-control col-md-3">Booked Trhough</label>
                <div class="col-md-9">
                    <input type="hidden" name="company[]" id="company{{$i}}" value="{{$row['freight_loc_info']['freight_company'][$i]}}"/>
                    {{--<select name="company[]" class="form-control" id="company">
                        <option disabled selected>Select Freight Company</option>
                        @foreach($row['companies_dropdown'] as $d)
                            <option @if($d->id == $row['freight_loc_info']['freight_company'][$i]) selected @endif value="{{ $d->id }}">{{ $d->company_name }}</option>
                        @endforeach
                    </select>\
                    --}}
                </div>
            </div>
            <div>
                <input type="hidden" name="freight_company[].'" id="freight_company_'.$i.'"   value="{{ $row['freight_loc_info']['freight_company'][$i] }}" />
                <br/><br/>
                <label class="label-control col-md-3">To Location {{ $i+1 }}</label>
                <div class="col-md-9">
                   {{$row['freight_loc_info']['location'][$i]}} | {{$row['freight_loc_info']['location_name'][$i]}}
                    <input type="hidden" id="loc_{{$i}}" name="loc[]" value="{{ $row['freight_loc_info']['location'][$i] }}" >
                </div>
        </div><div class="clearfix"></div>
            @for($j=0;$j < $row['num_games_per_destination'];$j++)
                <div>
<br/><br/>

                    <label class="label-control col-md-3">Add Game # {{ $j+1 }}</label>
                    <div class="col-md-9">
                        <input type="hidden" name="freight_loc_game_id[{{$i}}][{{$j}}]" value="{{ isset($row['freight_loc_info']['freight_loc_game_id'][$i][$j])?$row['freight_loc_info']['freight_loc_game_id'][$i][$j]:0}}"/>
                        <input type="hidden" name="loc_game[{{$i}}][{{$j}}]" id="loc_game_{{$i}}_{{$j}}" value="{{ isset($row['freight_loc_info']['loc_game'][$i][$j])?  $row['freight_loc_info']['loc_game'][$i][$j]:0 }}"
                        {{--<select name="loc_game[{{$i}}][{{$j}}]" id="loc_game{{ $j }}" class="form-control">
                            <option disabled selected>Select Game</option>
                            @foreach($row['game_drop_dwon'] as $game)
                            <option @if(isset($row['freight_loc_info']['loc_game'][$i][$j]) && $row['freight_loc_info']['loc_game'][$i][$j]== $game->id) selected @endif value="{{$game->id}}">
                                {{$game->text}}
                            </option>
                                @endforeach
                        </select> --}}

                    </div>
                </div>

                @endfor

                <div>
                    <br/><br/>
                    <label class="label-control col-md-3">Quoted Price: $</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="quoted_price[]" value="{{ $row['freight_loc_info']['location_quote'][$i] }}"/>
                    </div>
                </div>
                <div>
                    <br/><br/>
                    <label class="label-control col-md-3">Trucking Line:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="trucking_line[]" value="{{ $row['freight_loc_info']['location_trucking_co'][$i] }}"/>
                    </div>
                </div>
                <div>
                    <br/><br/>
                    <label class="label-control col-md-3">Pro Number:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="pro_number[]" value="{{ $row['freight_loc_info']['location_pro'][$i] }}"/>
                    </div>
                </div>
                <div>
                    <br/><br/>
                    <label class="label-control col-md-3">INCLUDE IN EMAIL:</label>
                    <div class="col-md-9">
                        <input type="hidden"  name="include_in_email[]" value="0"/>
                        <input type="checkbox"  name="include_in_email[]" value="1" id="include_in_email{{$i}}" checked/>
                    </div>
                </div>
            @endfor
            @endif

            <div class="clearfix"></div>
            <div style="border-bottom:1px solid #eee;margin:20px 0px;widht:100%"></div>
            <div>
                <label class="label-control col-md-3">PERSONALIZED EMAIL MESSAGE:</label>
                <div class="col-md-9">
                    <textarea  name="email_notes" rows="7" cols="20" id="email_notes" class="form-control">
                    {{ $row['email_notes'] }}
                    </textarea>
                </div>
            </div>

            <div class="form-group text-right">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <br/>
                    <label class="label-control col-md-9 text-right">Send Email Update:
                    @if(!empty($row['contact_email']))
                         to <b style="font-size:1.2em;">{{  $row['contact_email']}}</b>
                    @endif
                    </label>
                    <input type="hidden"  name="email" value="0"/>
                    <input type="checkbox"  name="email" value="1" id="send_email_update" checked/>

                </div>
            </div>
            <div class="form-group ">

                <div class="col-md-offset-5 col-md-2 text-center">
                    <button type="submit" class="btn btn-primary btn-sm "><i
                                class="fa  fa-save "></i> Update </button>
                </div>
            </div>
        </div><div class="clearfix"></div>
        {!! Form::close() !!}
@if($setting['form-method'] =='native')
	</div>	<div class="clearfix"></div>
</div>	
@endif
<script>
$(document).ready(function(){
    var to_contact_name=<?php echo json_encode($row['to_contact_name']) ?>;
    var email_notes = <?php echo json_encode($row['email_notes']) ?>;
    $("select[id^='location']").jCombo("{{ URL::to('managefreightquoters/comboselect?filter=location:id:id|location_name') }}",
            {selected_value: '', initial_text: 'Select Location'});
    if(to_contact_name !== "" && email_notes == "")
    {
        $("#email_notes").val("Hi"+to_contact_name+",");
    }
    $("input[id^='company']").select2({
        width: '100%',
        data: <?php echo json_encode($row['companies_dropdown'])?>,
        placeholder: "Select Company"
    });
    $("#freight_company_1").select2({
        width:'100%',
        data:<?php echo json_encode($row['companies_dropdown'])?>,
        placeholder: "Select Company"
    });
    console.log($("input[id^='loc_game_']"));
    $("input[id^='loc_game_']").select2({
        placeholder: "Select Game",
        width: '100%',
        data:   <?php echo json_encode($row['game_drop_dwon'])?>  });

});
  $("#send_email_update").on('change',function(){
        if($(this).is(':checked'))
        {
            $("input[id^=include_in_email]").attr('checked','checked');
        }
        else{
            $("input[id^=include_in_email]").removeAttr('checked');
        }
    });
  
$("#email").change(function() {
    if(this.checked) {
        $( ":checkbox" ).prop('checked', true);
        $( "checkbox[id^='new_ship_']").prop('checked', false);
    }
    else
    {
        $( ":checkbox" ).prop('checked', false);
    }
});

</script>	