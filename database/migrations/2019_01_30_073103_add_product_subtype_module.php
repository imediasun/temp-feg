<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductSubtypeModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "INSERT INTO `tb_module` (`module_id`, `module_name`, `module_title`, `module_note`, `module_author`, `module_created`, `module_desc`, `module_db`, `module_db_key`, `module_type`, `module_config`, `module_lang`) VALUES (165, 'productsubtype', 'Product Subtype', 'Product Subtype', NULL, '2019-01-21 03:26:28', NULL, 'product_type', 'id', 'ajax', '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biRlYgRfdH3wZSosopxvbitlcF9rZXk4O4J0eXB3XiR3ciNy6XB06W9uo4w4bG9v6gVwXgZhbHV3oj24dH3wZV9kZXNjcp3wdG3vb4osop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24dH3wZV9kZXNjcp3wdG3vb4osopFs6WFzoj24cHJvZHVjdF90eXB3o4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JUeXB3oER3ciNy6XB06W9uo4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4JzZWx3YgQ4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj24MSosonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojo4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24ZXh0ZXJuYWw4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4Jwcp9kdWN0XgRmcGU4LCJsbi9rdXBf6iVmoj24dH3wZV9kZXNjcp3wdG3vb4osopxvbitlcF9iYWxlZSoIonRmcGVfZGVzYgJ1cHR1bia4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIonJ3cXV3cgRfdH3wZV91ZCosopFs6WFzoj24cHJvZHVjdF90eXB3o4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JSZXFlZXN0oFRmcGU5SWQ4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonN3bGVjdCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojM4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24ZXh0ZXJuYWw4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4JvcpR3c390eXB3o4w4bG9v6gVwXit3eSoIop3ko4w4bG9v6gVwXgZhbHV3oj24bgJkZXJfdH3wZSosop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fV0sopdy6WQ4O3t7opZ1ZWxkoj246WQ4LCJhbG3hcyoIonBybiRlYgRfdH3wZSosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24SWQ4LCJi6WVgoj2xLCJkZXRh6Ww4OjAsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MCosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonBybiRlYgRfdH3wZSosopFs6WFzoj24cHJvZHVjdF90eXB3o4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JQcp9kdWN0oFNlY4BUeXB3o4w4dp33dyoIMSw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMSw4YXB1oj2wLCJ1bpx1bpU4OjEsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojE4LCJjbimuoj17onZhbG3koj24MSosopR4oj24cHJvZHVjdF90eXB3o4w46iVmoj24cHJvZHVjdF90eXB3o4w4ZG3zcGxheSoIonBybiRlYgRfdH3wZSJ9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4J0eXB3XiR3ciNy6XB06W9uo4w4YWx1YXM4O4Jwcp9kdWN0XgRmcGU4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3RmcGU5RGVzYgJ1cHR1bia4LCJi6WVgoj2wLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMSw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24M4osopNvbpa4Ons4dpFs6WQ4O4oxo4w4ZGo4O4Jwcp9kdWN0XgRmcGU4LCJrZXk4O4J1ZCosopR1cgBsYXk4O4J0eXB3XiR3ciNy6XB06W9uon0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonJ3cXV3cgRfdH3wZV91ZCosopFs6WFzoj24cHJvZHVjdF90eXB3o4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JSZXFlZXN0oFRmcGU5SWQ4LCJi6WVgoj2wLCJkZXRh6Ww4OjAsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fXldfQ==', NULL);";

        \DB::statement(\DB::raw($sql));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DELETE FROM tb_module where module_name = 'productsubtype'";
        \DB::statement(\DB::raw($sql));
    }
}