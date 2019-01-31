<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTbGoogledriveearningreportToTbModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('insert into `tb_module` (`module_id`, `module_name`, `module_title`, `module_note`, `module_author`, `module_created`, `module_desc`, `module_db`, `module_db_key`, `module_type`, `module_config`, `module_lang`) values(\'159\',\'googledriveearningreport\',\'Google Drive Earning Reports\',\'Google Drive Earning Reports\',NULL,\'2018-11-30 06:46:00\',NULL,\'google_drive_earning_reports\',\'id\',\'ajax\',\'eyJ0YWJsZV9kY4oIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJwcp3tYXJmXit3eSoIop3ko4w4cgFsXgN3bGVjdCoIo3NFTEVDVCBnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzL425LCA5Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cymtbiR1Zp33ZF906Wl3oEFToGRhdGVfcgRhcnQsoCBnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzLplvZG3p6WVkXgR1bWU5QVM5ZGF0ZV93bpQsoCBsbiNhdG3vb4mnZF9wYXJ3bnRfZp9sZGVyXimhbWU5XHJcbkZST005Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyA5oExFR3Q5Sk9JT4BsbiNhdG3vb4A5T0a5Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cymsbiNf6WQ5PSBsbiNhdG3vb4m1ZCosonNxbF9g6GVyZSoIo3doRVJFoGdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHMu6WQ5SVM5Tk9UoEmVTEw4LCJzcWxfZgJvdXA4O4o4LCJncp3koj1beyJp6WVsZCoIop3ko4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JJZCosonZ1ZXc4OjAsopR3dGF1bCoIMCw4ci9ydGF4bGU4OjAsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxo4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24Zi9vZix3XiZ1bGVf6WQ4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokdvbidsZSBG6Wx3oE3ko4w4dp33dyoIMCw4ZGV0YW3soj2xLCJzbgJ0YWJsZSoIMSw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojo4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JgZWJfdp33dl9s6Wmro4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JXZWo5Vp33dyBM6Wmro4w4dp33dyoIMCw4ZGV0YW3soj2xLCJzbgJ0YWJsZSoIMSw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojM4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4J1Yi9uXix1bps4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIok3jbia5TG3u6yosonZ1ZXc4OjAsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4o0o4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24bG9jYXR1bimfbpFtZSosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TG9jYXR1bia5TpFtZSosonZ1ZXc4OjAsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTUwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oio4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24Zp3sZV9uYWl3o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JG6Wx3oEmhbWU4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24NyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIopNyZWF0ZWRfdG3tZSosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24QgJ3YXR3ZCBU6Wl3o4w4dp33dyoIMCw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIoj54LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4Jt6Wl3XgRmcGU4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokZ1bGU5oFRmcGU4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24OSosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIoplvZG3p6WVkXgR1bWU4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokRhdGU5TW9k6WZ1ZWQ4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTA4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMSw4dpFsdWU4O4JEYXR3SGVscGVycgxpbgJtYXREYXR3fGlvZG3p6WVkXgR1bWU4fXl9LHs4Zp33bGQ4O4JwYXJ3bnRf6WQ4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3BhcpVudCBJZCosonZ1ZXc4OjAsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxMSosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonBhdG54LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3BhdG54LCJi6WVgoj2wLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTo4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JsbiNf6WQ4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokxvYyBJZCosonZ1ZXc4OjAsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxMyosopNvbpa4Ons4dpFs6WQ4O4oxo4w4ZGo4O4JsbiNhdG3vb4osopt3eSoIop3ko4w4ZG3zcGxheSoIopxvYiF06W9uXimhbWV86WR86WQ4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24ZiRfcGFyZWm0XiZvbGR3c39uYWl3o4w4YWx1YXM4O4JsbiNhdG3vb4osopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TG9jYXR1bia5UGFyZWm0oEZvbGR3c4BOYWl3o4w4dp33dyoIMSw4ZGV0YW3soj2xLCJzbgJ0YWJsZSoIMSw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMSw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojU4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JkYXR3XgN0YXJ0o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JEYXR3oFN0YXJ0o4w4dp33dyoIMCw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojE0o4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24ZGF0ZV93bpQ4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokRhdGU5RWmko4w4dp33dyoIMCw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojElo4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fV0sopZvcplzoj1beyJp6WVsZCoIop3ko4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JJZCosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24MCosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4Jnbi9nbGVfZp3sZV91ZCosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24Ri9vZix3oEZ1bGU5SWQ4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonR3eHQ4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj24MSosonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojE4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24o4w4bG9v6gVwXgFlZXJmoj24o4w4bG9v6gVwXgRhYpx3oj24o4w4bG9v6gVwXit3eSoIo4osopxvbitlcF9iYWxlZSoIo4osop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24diV4XgZ1ZXdfbG3u6yosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24ViV4oFZ1ZXc5TG3u6yosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24M4osopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4J1Yi9uXix1bps4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIok3jbia5TG3u6yosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24Myosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4JtbiR1Zp33ZF906Wl3o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JUbyosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadF9kYXR3dG3tZSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24Yi9sLWlkLTo4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIonNtYWxsZXJfZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24YpV0diV3b4osonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojQ4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24o4w4bG9v6gVwXgFlZXJmoj24o4w4bG9v6gVwXgRhYpx3oj24o4w4bG9v6gVwXit3eSoIo4osopxvbitlcF9iYWxlZSoIo4osop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24YgJ3YXR3ZF906Wl3o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JGcp9to4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0XiRhdGV06Wl3o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4JjbiwtbWQtM4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24Yp3nZiVyXiVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopJ3dHd3ZWa4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4olo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopl1bWVfdH3wZSosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TW3tZSBUeXB3o4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4oio4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIonBhcpVudF91ZCosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24UGFyZWm0oE3ko4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4ogo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopxvYiF06W9uXimhbWU4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokxvYiF06W9uoGmhbWU4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonN3bGVjdCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIoj54LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24ZXh0ZXJuYWw4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4JsbiNhdG3vb4osopxvbitlcF9rZXk4O4J1ZCosopxvbitlcF9iYWxlZSoIopxvYiF06W9uXimhbWV86WR86WQ4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopxvYl91ZCosopFs6WFzoj24Zi9vZix3XiRy6XZ3XiVhcpm1bpdfcpVwbgJ0cyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TG9jYXR1bia4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonN3bGVjdCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj24MSosonN1bXBsZXN3YXJj6G9yZGVyoj24MSosonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4JjbiwtbWQtMyosonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4omo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIopVadGVybpFso4w4bG9v6gVwXgFlZXJmoj24o4w4bG9v6gVwXgRhYpx3oj24bG9jYXR1bia4LCJsbi9rdXBf6iVmoj246WQ4LCJsbi9rdXBfdpFsdWU4O4J1ZHxsbiNhdG3vb39uYWl3o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4JwYXR2o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JQYXR2o4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4oxMCosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4Jp6Wx3XimhbWU4LCJhbG3hcyoIopdvbidsZV9kcp3iZV93YXJu6WmnXgJ3cG9ydHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3N3YXJj6CosopZvcplfZgJvdXA4O4owo4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonR3eHQ4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj2wLCJz6WlwbGVzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2bgJkZXo4O4o0o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIopNvbCltZC0zo4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4Js6Wt3o4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopx16iU4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4oxM4osopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4JkYXR3XgN0YXJ0o4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JGcp9to4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0XiRhdGU4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj24MSosonN1bXBsZXN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYihvcpR3c4oIojo4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojEyo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopRhdGVfZWmko4w4YWx1YXM4O4Jnbi9nbGVfZHJ1dpVfZWFybp3uZl9yZXBvcnRzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JUbyosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadF9kYXR3o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2bgJkZXo4O4ozo4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4oxMyosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4JnZF9wYXJ3bnRfZp9sZGVyXimhbWU4LCJhbG3hcyoIopxvYiF06W9uo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JHZCBQYXJ3bnQ5Rp9sZGVyoEmhbWU4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4owo4w4dp33dyoIMSw4dH3wZSoIonR3eHRhcpVho4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIo4osonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24bG3rZSosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojE0o4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fXldfQ==\',NULL)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $id = 159;
        \DB::statement("DELETE from tb_module where module_id = '.$id.'");

    }
}