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
                        {{ SiteHelpers::activeLang('Po Note', (isset($fields['po_note']['language'])? $fields['po_note']['language'] : array())) }}
                    </td>
                    <td>{{ $row->po_note }} </td>

                </tr>

                <tr>
                    <td width='30%' class='label-view text-right'>
                        {{ SiteHelpers::activeLang('Is Merchandiseorder', (isset($fields['is_merchandiseorder']['language'])? $fields['is_merchandiseorder']['language'] : array())) }}
                    </td>
                    <td>{{ $row->is_merchandiseorder }} </td>

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