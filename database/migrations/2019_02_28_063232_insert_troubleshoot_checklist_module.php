<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertTroubleshootChecklistModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "INSERT INTO `tb_module` (`module_id`, `module_name`, `module_title`, `module_note`, `module_author`, `module_created`, `module_desc`, `module_db`, `module_db_key`, `module_type`, `module_config`, `module_lang`) VALUES('166','troubleshootingchecklist','Troubleshooting Check List','Troubleshooting Check List',NULL,'2019-02-28 02:05:15',NULL,'troubleshooting_check_lists','id','ajax','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o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMCw4dH3wZSoIonR3eHQ4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj2wLCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4oyo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopNyZWF0ZWRfYXQ4LCJhbG3hcyoIonRybgV4bGVz6G9vdG3uZl9j6GVj6l9s6XN0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24QgJ3YXR3ZCBBdCosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2wLCJ0eXB3oj24dGVadF9kYXR3dG3tZSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojM4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24o4w4bG9v6gVwXgFlZXJmoj24o4w4bG9v6gVwXgRhYpx3oj24o4w4bG9v6gVwXit3eSoIo4osopxvbitlcF9iYWxlZSoIo4osop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24dXBkYXR3ZF9hdCosopFs6WFzoj24dHJvdWJsZXN2bi906WmnXiN2ZWNrXix1cgRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JVcGRhdGVkoEF0o4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0XiRhdGV06Wl3o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24NCosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9XSw4ZgJ1ZCoIWgs4Zp33bGQ4O4J1ZCosopFs6WFzoj24dHJvdWJsZXN2bi906WmnXiN2ZWNrXix1cgRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JJZCosonZ1ZXc4OjAsopR3dGF1bCoIMCw4ci9ydGF4bGU4OjAsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjEsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MjA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojA4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4Jj6GVj6l9s6XN0XimhbWU4LCJhbG3hcyoIonRybgV4bGVz6G9vdG3uZl9j6GVj6l9s6XN0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24Qih3Yis5TG3zdCBOYWl3o4w4dp33dyoIMSw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMSw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4omMCU4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojE4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4J1cl9hYgR1dpU4LCJhbG3hcyoIonRybgV4bGVz6G9vdG3uZl9j6GVj6l9s6XN0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24SXM5QWN06XZ3o4w4dp33dyoIMCw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojo4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JjcpVhdGVkXiF0o4w4YWx1YXM4O4J0cp9lYpx3cihvbgR1bpdfYih3YitfbG3zdHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokNyZWF0ZWQ5QXQ4LCJi6WVgoj2wLCJkZXRh6Ww4OjAsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonVwZGF0ZWRfYXQ4LCJhbG3hcyoIonRybgV4bGVz6G9vdG3uZl9j6GVj6l9s6XN0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24VXBkYXR3ZCBBdCosonZ1ZXc4OjAsopR3dGF1bCoIMCw4ci9ydGF4bGU4OjAsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4o0o4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fV0sonN3dHR1bpc4Ons4bW9kdWx3XgJvdXR3oj24dHJvdWJsZXN2bi906WmnYih3Yits6XN0o4w4ZgJ1ZHRmcGU4O4o4LCJvcpR3cpJmoj246WQ4LCJvcpR3cnRmcGU4O4JhciM4LCJwZXJwYWd3oj24MjA4LCJpcp9IZWa4O4JpYWxzZSosopZvcp0tbWV06G9koj24bpF06XZ3o4w4dp33dyltZXR2biQ4O4JuYXR1dpU4LCJ1bpx1bpU4O4JpYWxzZSosoph1ZGVybgdjbgVudGNvbHVtb4oIonRydWU4LCJlciVz6WlwbGVzZWFyYi54O4JpYWxzZSosonBlYpx1YiFjYiVzcyoIdHJlZSw4ci3tcGx3ciVhcpN2YnV0dG9udi3kdG54O4o4LCJ26WR3YWRiYWmjZWRzZWFyYihvcGVyYXRvcnM4O4JpYWxzZSosopR1ciF4bGVwYWd1bpF06W9uoj24ZpFsciU4LCJk6XNhYpx3ci9ydCoIopZhbHN3o4w4ZG3zYWJsZWFjdG3vbpN2ZWNrYp9aoj24ZpFsciU4LCJk6XNhYpx3cp9gYWN06W9ucyoIopZhbHN3onl9',NULL);";

        \DB::statement(\DB::raw($sql));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "delete from tb_module where module_id = 166";
        \DB::statement(\DB::raw($sql));
    }
}