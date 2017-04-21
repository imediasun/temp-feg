@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content clearfix"> 
@endif
        {!! Form::open(array(
            'url'=>'managefreightquoters/update/'.$row['freight_order_id'],
            'class'=>'form-vertical', 'parsley-validate'=>'','novalidate'=>' ',
            'id'=> 'managefreightquotersFormAjax')) 
        !!}
        <input type="hidden" id="freight_order_id" name="freight_order_id" value="{{ $row['freight_order_id'] }}" >
        <input type="hidden" id="num_games_per_destination" name="num_games_per_destination" value="{{ $row['num_games_per_destination'] }} " >
        <input type="hidden" id="ship_to_type" name="ship_to_type" value="{{ $row['ship_to_type'] }}" >
        <input type="hidden" id="freight_contents" name="freight_contents" value=' ' >
        <input type="hidden" id="current_status_id" name="current_status_id" value='{{ $row["current_status_id"] }}' >
        <input type="hidden" id="contact_email" name="contact_email" value=' {{ $row["contact_email"] }}' >
		<div class="col-md-8 col-md-offset-2" style="background-color:#FFF;box-shadow: 1px 1px 5px lightgray;padding:30px">
            <div class="clearfix freightOrderBasicDetailsContainer">
            <h2 class="text-center">Freight Order</h2>
            <hr/>
            <div class="form-group clearfix">
                <label class="label-control col-md-3">Status:</label>
                <div class="col-md-9">
                    {!!  $row['status'] !!}
                    @if(!strpos($row['status'],'Paid'))
                        <a class="m-l-sm-f" id="markPaid" style="font-size: 12px" href="{{ url()}}/managefreightquoters/paid/{{$row['freight_order_id']}}">MARK PAID</a>
                    @endif
                    @if(strpos($row['status'],'Paid'))
                    <b style="font-size: 12px !important;"><span class="m-l-sm-f">on</span> <span class="m-l-sm-f">{{  date("m/d/Y", strtotime($row['date_paid'])) }}</span></b>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix" >
                <label class="label-control col-md-3">From:</label>
                <div class="col-md-9">
                    <input type="hidden" id="from_loc" name="from_loc" value="{{ $row['loc_from_id'] }}" >
                    {!! trim($row['from_address'], "<br/>") !!}
                </div>
            </div>
            @if(!empty($row['vend_to']) || !empty($row['to_add_street']))
                <div class="form-group clearfix">
                    <label class="label-control col-md-3" >To:</label>
                    <div class="col-md-9">
                        <p style="text-align:left; width: 200px;">
                        {!! trim($row['to_address'], "<br/>") !!}
                        </p>
                    </div>
                </div>
            @endif
            <hr/>
            </div>
            
            <div class="freightContentsContainer clearfix">
                <h3 class="m-b-md-f m-l-f">Freight Contents:</h3>
                @for($i = 0; $i < count($row['description']); $i++)
                <input type="hidden" name="freight_pallet_id[]" value="{{ isset($row['freight_pallet_id'][$i])?$row['freight_pallet_id'][$i]:'' }}"/>
                <div class="form-group m-b-xs-f clearfix">
                    <label class="label-control col-md-3">Pallet  {{ $i+1 }}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="description[]" value="{{ isset($row['description'][$i])?$row['description'][$i]:'' }}"/>
                    </div>
                </div>
                <div class="form-group m-b-xs-f clearfix">
                    <label class="label-control col-md-3">Dimension  {{ $i+1 }}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="dimension[]" value="{{ isset($row['dimensions'][$i])?$row['dimensions'][$i]:'' }}"/>
                    </div>
                </div>
                @endfor
                <div class="form-group clearfix">
                    <label class="label-control col-md-3">Shipment Notes:</label>
                    <div class="col-md-9">
                        <textarea name="notes" rows="6" cols="20" id="notes" class="form-control">{{ isset($row['notes'])?$row['notes']:'' }}</textarea>
                    </div>
                </div>
                <hr/>
            </div>
           
            <div class="clearfix freightDatesContainer">
                <div class="form-group clearfix">
                    <label class="label-control col-md-3">Date Submitted</label>
                    <div class="col-md-9">
                        {{ \DateHelpers::formatDate($row['date_submitted']) }}
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="label-control col-md-3">Date Booked</label>
                    <div class="col-md-9">
                        {{ \DateHelpers::formatDate($row['date_booked']) }}
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="label-control col-md-3">Date Paid</label>
                    <div class="col-md-9">
                        {{ \DateHelpers::formatDate($row['date_paid']) }}
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="label-control col-md-3">Damage or Delays</label>
                    <div class="col-md-9">
                        @if(is_string($row['ship_exception']))
                            {{ $row['ship_exception'] }}
                            @else
                            {{ implode(',',$row['ship_exception']) }}
                            @endif
                    </div>
                </div> 
               <hr/>
           </div>
            
            @if(!empty($row['vend_to']) || !empty($row['to_add_street']))
            <div class="vendorToCustomAdddressContainer clearfix">
                <div class="form-group m-b-xs-f clearfix">
                    <label class="col-md-3 label-control">
                        Booked Through:
                    </label>
                    <div class="col-md-9">
                          <input type="hidden" name="freight_company_1" id="freight_company_1"   value="{{ isset($row['freight_company_1'])?$row['freight_company_1']:0 }}" >
                    </div>
                </div>                
                <div class="form-group m-b-xs-f clearfix">
                    <label class="label-control col-md-3">Quoted Price: $</label>
                    <div class="col-md-9">
                        <input id="external_ship_quote" name="external_ship_quote" value="{{ $row['external_ship_quote']  }}" class="form-control">
                    </div>
                </div>
                <div class="form-group m-b-xs-f clearfix">
                    <label class="label-control col-md-3">Trucking Line:</label>
                    <div class="col-md-9">
                          <input id="external_ship_trucking_co" name="external_ship_trucking_co" class="form-control" value="{{$row['external_ship_trucking_co']}}" >
                      </div>
                </div>
                <div class="form-group m-b-xs-f clearfix">
                    <label class="label-control col-md-3">Pro Number:</label>
                     <div class="col-md-9">
                          <input id="external_ship_pro" name="external_ship_pro" value="{{$row['external_ship_pro']}}" class="form-control">
                     </div>
                </div>
                <hr/>
            </div>
            @endif
            
            @if(isset($row['freight_loc_info']['location']) && !empty($row['freight_loc_info']['location']))
            <div class="allLocationsContainer clearfix">
            @for($i=0;$i < count($row['freight_loc_info']['location']);$i++)
                <div class="eachLocationContainer clearfix">
                    <input type="hidden" name="freight_loc_to_id[]" 
                           value="{{ $row['freight_loc_info']['freight_loc_to_id'][$i] }}"/>

                    <div class="form-group m-b-sm-f clearfix">                    
                        <label class="label-control col-md-3">Booked Through</label>
                        <div class="col-md-9">
                            <input type="hidden" name="company[]" id="company{{$i}}" value="{{$row['freight_loc_info']['freight_company'][$i]}}"/>
                        </div>
                    </div>
                    <div class="form-group m-b-sm-f clearfix">
                        <input type="hidden" name="freight_company[]" id="freight_company_'.$i.'"   value="{{ $row['freight_loc_info']['freight_company'][$i] }}" />                
                        <label class="label-control col-md-3">Ship To {{ $i+1 }}</label>
                        <div class="col-md-9">
                            @if($row['freight_loc_info']['location'][$i]!=0)
                            <strong>
                                {{$row['freight_loc_info']['location'][$i]}} | {{$row['freight_loc_info']['location_name'][$i]}}
                            </strong>
                            @endif
                            <input type="hidden" id="loc_{{$i}}" name="loc[]" value="{{ $row['freight_loc_info']['location'][$i] }}" >
                        </div>
                    </div>
                    @if ($row['num_games_per_destination'] > 0)
                    <div class="clearfix perLocationGamesContainer">
                    @for($j=0; $j < $row['num_games_per_destination']; $j++)
                        <div class="form-group m-b-xs-f clearfix">                        
                            <label class="label-control col-md-3">Add Game # {{ $j+1 }}</label>
                            <div class="col-md-9">
                                <input type="hidden" 
                                       name="freight_loc_game_id[{{$i}}][{{$j}}]" 
                                       value="{{ isset($row['freight_loc_info']['freight_loc_game_id'][$i][$j])?$row['freight_loc_info']['freight_loc_game_id'][$i][$j]:0}}"
                                       />
                                <input type="hidden"                                
                                       name="loc_game[{{$i}}][{{$j}}]" 
                                       id="loc_game_{{$i}}_{{$j}}" 
                                       value="{{ isset($row['freight_loc_info']['loc_game'][$i][$j])?  $row['freight_loc_info']['loc_game'][$i][$j]:0 }}" 
                                />
                            </div>
                        </div>
                    @endfor
                    </div>
                    @endif

                    <div class="form-group m-b-xs-f clearfix">
                        <label class="label-control col-md-3">Quoted Price $:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="quoted_price[]" value="{{ $row['freight_loc_info']['location_quote'][$i] }}"/>
                        </div>
                    </div>
                    <div class="form-group m-b-xs-f clearfix">
                        <label class="label-control col-md-3">Trucking Line:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="trucking_line[]" value="{{ $row['freight_loc_info']['location_trucking_co'][$i] }}"/>
                        </div>
                    </div>
                    <div class="form-group m-b-xs-f clearfix">
                        <label class="label-control col-md-3">Pro Number:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="pro_number[]" value="{{ $row['freight_loc_info']['location_pro'][$i] }}"/>
                        </div>
                    </div>
                    <div class="form-group m-b-xs-f clearfix">
                        <label class="label-control col-md-3">Include In Email:</label>
                        <div class="col-md-9">
                            <input type="hidden"  name="include_in_email[]" value="1"/>
                            <input type="checkbox" data-proxy-input='include_in_email' name="_include_in_email[]" value="1" id="include_in_email{{$i}}" checked/>
                        </div>
                    </div>
                    <hr/>
                </div>
            @endfor
            </div>
            @endif
            <div class="freightMessageContainer clearfix">
                <div class="form-group m-b-sx-f clearfix">
                    <label class="label-control col-md-3">Personalized Email Message:</label>
                    <div class="col-md-9">
                        <textarea  name="email_notes" rows="7" cols="20" id="email_notes" class="form-control">{{ $row['email_notes'] }}</textarea>
                    </div>
                </div>
                <div class="clearfix form-group text-right">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <label class="label-control col-md-9 text-right" style="margin-left: 20%;">Send Email Update:
                        @if(!empty($row['contact_email']))
                             to <b style="font-size:1.2em;">{{  $row['contact_email']}}</b>
                        @endif
                        </label>
                        <input type="hidden" name="email" value="1"/>
                        <input type="checkbox"  data-proxy-input='email' name="_email" value="1" id="send_email_update" checked/>

                    </div>
                </div>                
            </div>
             <div class="freightSubmitContainer clearfix">
                <div class="m-t-f clearfix">
                    <div class="col-md-offset-5 col-md-2 text-center">
                        <button type="submit" class="btn btn-primary btn-sm "><i
                                    class="fa  fa-save "></i> Update </button>
                    </div>
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
        $('#markPaid').click(function (e) {
            $('.ajaxLoading').show();
            e.preventDefault();
            var me = $(this);
            console.log($(this));
            $.get("{{ url()}}/managefreightquoters/paid/{{$row['freight_order_id']}}",function (data) {
               // console.log(data);
               // console.log($(this));
                /*me.text('MARKED PAID');
                me.removeAttr('id');*/

                $('#'+pageModule+'View').html(data);
                $('.ajaxLoading').hide();
                notyMessage("{{\Lang::get('core.note_freight_paid')}}");

            })
            .fail(function (data) {
                $('.ajaxLoading').hide();
                notyMessageError('An Error Occurred');
              //  console.log(data);
            })
        });
        App.modules.freight.view.init({
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
