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
                        {{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}
                    </td>
                    <td>{{ $row->id }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Game Name', (isset($fields['game_name']['language'])? $fields['game_name']['language'] : array())) }}
                    </td>
                    <td>{{ \SiteHelpers::formatStringValue($row->game_name) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Prev Location', (isset($fields['prev_location_id']['language'])? $fields['prev_location_id']['language'] : array())) }}
                    </td>
                    <td>{!!
                        SiteHelpers::gridDisplayView($row->prev_location_id,'prev_location_id','1:location:id:location_name')
                        !!}
                    </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Notes', (isset($fields['notes']['language'])? $fields['notes']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->notes) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Serial', (isset($fields['serial']['language'])? $fields['serial']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->serial) }} </td>

                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Date Sold', (isset($fields['date_sold']['language'])? $fields['date_sold']['language'] : array())) }}


                    </td>
                    <td>{{  \DateHelpers::formatDate($row->date_sold )  }}</td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Sold To', (isset($fields['sold_to']['language'])? $fields['sold_to']['language'] : array())) }}
                    </td>
                    <td>{{ \DateHelpers::formatStringValue($row->sold_to) }} </td>
                </tr>
                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Test Piece', (isset($fields['test_piece']['language'])? $fields['test_piece']['language'] : array())) }}
                    </td>
                    <td>{!! SiteHelpers::gridDisplayView($row->test_piece,'test_piece','1:yes_no:id:yesno') !!}</td>
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