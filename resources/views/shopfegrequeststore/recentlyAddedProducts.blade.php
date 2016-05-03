@extends('layouts.app')

@section('content')
    <div class="sbox">
        <div class="sbox-title">
            <h4><i class="fa fa-table"></i> {{ $pageTitle }}
                <small>{{ $pageNote }}</small>
            </h4>
        </div>
        <div class="sbox-content">
            <div class=" col-md-12">

                <div class="table-responsive" style="padding:30px">
                    <h1> Recently Added Products </h1>
                    <table width="100%" class="table table-bordered table-hover">
                        @if(isset($recent_products['new_products']))
                            @foreach($recent_products['new_products'] as $row)
                                @if(isset($row['item']))
                                    <tr>
                                        <td style="padding:15px; color:black;  vertical-align:middle; line-height:1.2em;">
                                            <a href="./show/{{$row['new_product_id'] }}"
                                               style="color:black"
                                               class="btn btn-xs btn-green tips"
                                               target="_blank"> <?php echo $row['item'] . '<br/>' ?></a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection