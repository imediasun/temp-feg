
<?php
$pages = array(10,20,30,50,100);
$orders = array('asc','desc');
?>
	<div class="table-footer">
	<div class="row">
	 <div class="col-md-5 col-sm-12 col-xs-12">
	  <div class="table-actions" style=" padding: 10px 0" id="<?php echo $pageModule;?>Filter">
  			<input type="hidden" name="page" value="{{ isset($param['page'])?$param['page']:""}}" />
			<input type="hidden" name="search" value="<?php if(!is_null(Input::get('search'))) echo Input::get('search') ;?>" />
           @if(isset($TID) && !is_null($TID))
            <input type="hidden" name="v1" value="T<?php  echo $TID ?>"/>
          @endif
          @if(isset($LID) && !is_null($LID))
          <input type="hidden" name="v2" value="L<?php  echo $LID ?>"/>
          @endif
          @if(isset($VID) && !is_null($VID))
              <input type="hidden" name="v3" value="V<?php  echo $VID ?>"/>
          @endif
        @if(isset($view) && !is_null($view))
              <input type="hidden" name="view" value="<?php  echo $view ?>"/>
        @endif
          @if(isset($type) && !is_null($type))
              <input type="hidden" name="type" value="<?php  echo $type ?>"/>
          @endif
          @if(isset($isactive) && !is_null($isactive))
              <input type="hidden" name="active_inactive" value="<?php  echo $isactive ?>"/>
          @endif
          @if(isset($order_type) && !is_null($order_type))
              <input type="hidden" name="order_type" value="<?php  echo $order_type ?>"/>
          @endif
          @if(isset($product_type) && !is_null($product_type))
              <input type="hidden" name="product_type" value="<?php  echo $product_type ?>"/>
          @endif
          @if(isset($product_list_type) && !is_null($product_list_type))
              <input type="hidden" name="prod_list_type" value="<?php  echo \Session::get('product_type') ?>"/>
          @endif
          @if(isset($sub_type) && !is_null($sub_type))
              <input type="hidden" name="sub_type" value="<?php  echo $sub_type ?>"/>
          @endif
          @if(isset($active) && !is_null($active))
              <input type="hidden" name="active" value="<?php  echo $active ?>"/>
          @endif
        @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
        <?php $setRows = isset($pager['rows']) ? $pager['rows'] : $setting['perpage']; ?>
		<select name="rows" class="select-alt" style="width:70px; float:left;"
                data-setvalue="{{ $setRows }}" >

            @foreach($pages as $p)
                <option value="{{ $p }}"  @if($setRows == $p) selected="selected" @endif
                >{{ $p }}</option>
            @endforeach
            @if($pageModule != 'order')
                <option value="0" @if($setRows == '0') selected="selected"  @endif
                >All</option>
            @endif
		</select>
        @endif

        @if(!isset($setting['disablesort']) || $setting['disablesort'] == 'false')
		<select name="sort" class="select-alt footer-button-sort" style="" >
		  <option value=""><?php echo Lang::get('core.grid_sort');?></option>
		  @foreach($tableGrid as $field)
		   @if($field['view'] =='1' && $field['sortable'] =='1')
			  <option value="{{ $field['field'] }}"
				@if($param['sort'] == $field['field'] || (isset($pager['sort']) && $pager['sort'] == $field['field']))
					selected="selected"
				@endif
			  >{{ $field['label'] }}</option>
			@endif
		  @endforeach

		</select>
		<select name="order" class="select-alt" style="width:70px;float:left;">
		  <option value="">{{ Lang::get('core.grid_order') }}</option>
		   @foreach($orders as $o)
		  <option value="{{ $o }}"
			@if($param['order'] == $o || (isset($pager['order']) && $pager['order'] == $o))
				selected="selected"
			@endif
		  >{{ ucwords($o) }}</option>
		 @endforeach
		</select>
        @endif

        @if((!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false') || (!isset($setting['disablesort']) || $setting['disablesort'] == 'false'))
		<button type="button" class="btn  btn-primary btn-sm" onclick="ajaxFilter('#<?php echo $pageModule;?>','{{ $pageUrl }}/data')" style="float:left;"><i class="fa  fa-search"></i> GO</button>
        @endif
	  </div>
	  </div>

        @if(!isset($setting['disablepagination']) || $setting['disablepagination'] == 'false')
		<div class="col-md-6 col-sm-12 col-xs-12" id="<?php echo $pageModule;?>Paginate">
            {!! urldecode($pagination->appends($pager)->render()) !!}
        </div>
        @endif
	  </div>
	</div>

