@if($setting['view-method'] =='native')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> <?php echo $pageTitle;?>
                <small>{{ $pageNote }}</small>
                <a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger"
                   onclick="ajaxViewClose('#{{ $pageModule }}')">
                    <i class="fa fa fa-times"></i></a>
            </h4>
        </div>
        <div class="sbox-content">
            @endif
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Asset Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
                    </td>
                    <td>{{ $row->id }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->game_name,$nodata['game_name']) }} </td>

                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Game Type ', (isset($fields['game_type_id']['language'])? $fields['game_type_id']['language'] : array())) }}
                    </td>
                    <td>{!! SiteHelpers::gridDisplayView($row->game_type_id,'game_type_id','1:game_type:id:game_type',$nodata['game)type_id'])
                        !!}
                    </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->notes,$nodata['notes']) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->serial,$nodata['serial']) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Status ', (isset($fields['status_id']['language'])? $fields['status_id']['language'] : array())) }}
                    </td>
                    <td>{!! SiteHelpers::gridDisplayView($row->status_id,'status_id','1:game_status:id:game_status',$nodata['status_id'])
                        !!}
                    </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Intended First Location', (isset($fields['intended_first_location']['language'])? $fields['intended_first_location']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->intended_first_location,$nodata['intended_first_location']) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('For Sale', (isset($fields['for_sale']['language'])? $fields['for_sale']['language'] : array())) }}
                    </td>
                    <td>{!! SiteHelpers::gridDisplayView($row->for_sale,'for_sale','1:yes_no:id:yesno',$nodata['for_sale]) !!}</td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Sale Price', (isset($fields['sale_price']['language'])? $fields['sale_price']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatZeroValue($row->sale_price,$nodata['sale_price']) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Version', (isset($fields['version']['language'])? $fields['version']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->version,$nodata['version']) }} </td>
                </tr>
                </tbody>
            </table>
            @if($setting['form-method'] =='native')
        </div>
    </div>
@endif

<script>
    $(document).ready(function () {

    });
</script>	