@if (isset($pageModule) && !empty($access) && $access['is_add'] == "1" && \SiteHelpers::getModuleSetting($pageModule, 'inline') == 'true')

@section ('inlinedit')
<script type="text/javascript" src="{{ asset('sximo/js/modules/utilities/inline-edit.js') }}"></script>          
    <script type="text/javascript">
        (function (){
            "use strict";
            var initData = {
                    'today': '{{ \DateHelpers::formatDate(date("Y-m-d")) }}',
                    'todayDateTime': '{{ \DateHelpers::formatDateCustom(date("Y-m-d H:i:s")) }}',                
                    'container': $('#{{$pageModule}}Grid'),
                    'moduleName': "{{$pageModule}}",
                    'isAccessAllowed': true,
                    'isInlineEnable': true,
                    'pageUrl': "{{ isset($pageUrl) ? $pageUrl : '' }}",
                    'siteUrl': "{{url()}}"
                };
            
            App.autoCallbacks.registerCallback('reloaddata', function (params) {
                initData.container = this;
                App.modules.utilities.inlineEdit.init(initData);
            });
            $(document).ready(function() {
                App.modules.utilities.inlineEdit.init(initData);
            });        

        }());
    </script>
       
@endsection

@endif