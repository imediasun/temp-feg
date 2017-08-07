
    <div class="table-footer">
	<div class="row">
	 <div class="col-sm-5">
	  <div class="table-actions" style=" padding: 10px 0" id="<?php echo $pageModule;?>Filter">        
	   {!! Form::open(array('url'=> (isset($pageUrl) ? $pageUrl : $pageModule) .'/filter')) !!}
		   {{--*/ $pages = array(10,20,30,50,100) /*--}}
		   {{--*/ $orders = array('asc','desc') /*--}}
        <input type="hidden" name="page" value="{{ @$param['page']}}" />
		<input type="hidden" name="search" value="<?php if(!is_null(Input::get('search'))) echo Input::get('search') ;?>" />
		<input type="hidden" name="simplesearch" value="<?php if(!is_null(Input::get('simplesearch'))) echo Input::get('simplesearch') ;?>" />
        @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
        <?php $setRows = isset($pager['rows']) ? $pager['rows'] : @$setting['perpage']; ?>
		<select name="rows" data-placeholder="{{ Lang::get('core.grid_show') }}" 
                class="select-alt"  data-setvalue="{{ $setRows }}" >		  
		  @foreach($pages as $p)
                <option value="{{ $p }}" @if($setRows == $p) selected="selected" @endif	
		  >{{ $p }}</option>
		  @endforeach
            @if($pageModule != 'order')
                <option value="0" @if($setRows == '0') selected="selected"  @endif
                >All</option>
            @endif          
		</select>
        @endif
        
        @if(!isset($setting['disablesort']) || $setting['disablesort'] == 'false')
		<select name="sort" data-placeholder="{{ Lang::get('core.grid_sort') }}" class="select-alt"  >
		  <option value=""> {{ Lang::get('core.grid_sort') }} </option>	 
		  @foreach($tableGrid as $field)
		   @if($field['view'] =='1' && $field['sortable'] =='1') 
			  <option value="{{ $field['field'] }}" 
				@if((isset($param['sort']) && $param['sort'] == $field['field']) || (isset($pager['sort']) && $pager['sort'] == $field['field'])) 
					selected="selected"
				@endif	
			  >{{ $field['label'] }}</option>
			@endif	  
		  @endforeach
		 
		</select>	
		<select name="order" data-placeholder="{{ Lang::get('core.grid_order') }}" class="select-alt">
		  <option value=""> {{ Lang::get('core.grid_order') }}</option>
		   @foreach($orders as $o)
		  <option value="{{ $o }}"
			@if(isset($param['order']) && $param['order'] == $o) || (isset($pager['order']) && $pager['order'] == $o))
				selected="selected"
			@endif	
		  >{{ ucwords($o) }}</option>
		 @endforeach
		</select>
        @endif
        
        @if((!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false') || (!isset($setting['disablesort']) || $setting['disablesort'] == 'false'))
		<button type="submit" class="btn btn-primary btn-sm">GO</button>	
        @endif        
		<input type="hidden" name="md" value="{{ (isset($masterdetail['filtermd']) ? $masterdetail['filtermd'] : '') }}" />
	  {!! Form::close() !!}
	  </div>					
	  </div>
<!--	   <div class="col-sm-3">
		<p class="text-center" style=" padding: 25px 0">
		Total : <b>{{ $pagination->total() }}</b>
		</p>		
	   </div>-->
        @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
		<div class="col-sm-7">			 
            {!! urldecode($pagination->appends($pager)->render()) !!}
        </div>
        @endif
	  </div>
	</div>	
	
	