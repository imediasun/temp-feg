<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewLocationSetupModuleSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "delete from tb_module where module_name='new-location-setup'";
        \DB::statement($sql);

        $sql = "INSERT INTO `tb_module` (`module_id`, `module_name`, `module_title`, `module_note`, `module_author`, `module_created`, `module_desc`, `module_db`, `module_db_key`, `module_type`, `module_config`, `module_lang`) VALUES('160','new-location-setup','Server Password Vault','Server Password Vault',NULL,'2018-12-21 04:47:24',NULL,'new_location_setups','id','ajax','eyJ0YWJsZV9kY4oIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJwcp3tYXJmXit3eSoIop3ko4w4cgFsXgN3bGVjdCoIo3NFTEVDVFxyXGa5oGm3dl9sbiNhdG3vb39zZXRlcHMuK4xcc3xuoCBsbiNhdG3vb4m1ZCA5oCA5oCA5oCA5oEFToEZFRl9JRCxcc3xuoCBJR4hsbiNhdG3vb4mhYgR1dpU5PSAwLCdDbG9zZWQnLCdPcGVuJyk5QVM5bG9jYXR1bimTdGF0dXNcc3xuR3JPTSBuZXdfbG9jYXR1bimfciV0dXBzXHJcb4A5SUmORVo5Sk9JT4BsbiNhdG3vb3xyXGa5oCA5T0a5bG9jYXR1biau6WQ5PSBuZXdfbG9jYXR1bimfciV0dXBzLpxvYiF06W9uXi3ko4w4cgFsXgd2ZXJ3oj24V0hFUkU5bpVgXixvYiF06W9uXgN3dHVwcym1ZCBJUyBOTlQ5T3VMTCosonNxbF9ncp9lcCoIo4osopdy6WQ4O3t7opZ1ZWxkoj246WQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIok3ko4w4dp33dyoIMCw4ZGV0YW3soj2wLCJzbgJ0YWJsZSoIMCw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMCw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMSw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oxMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIojE4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JsbiNhdG3vb391ZCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TG9jYXR1bia5TpFtZSosonZ1ZXc4OjEsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjEsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjEsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MjAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oyo4w4Yi9ub4oIeyJiYWx1ZCoIojE4LCJkY4oIopxvYiF06W9uo4w46iVmoj246WQ4LCJk6XNwbGFmoj24bG9jYXR1bimfbpFtZSJ9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JGRUdfSUQ4LCJhbG3hcyoIopxvYiF06W9uo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JGRUc5SUQ4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIopxvYiF06W9uUgRhdHVzo4w4YWx1YXM4O4o4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokxvYiF06W9uoFN0YXRlcyosonZ1ZXc4OjEsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjEsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4o0o4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24dXN3XgRio4w4YWx1YXM4O4JuZXdfbG9jYXR1bimfciV0dXBzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JVciU5VFY4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24NSosopNvbpa4Ons4dpFs6WQ4O4oxo4w4ZGo4O4JmZXNfbp84LCJrZXk4O4J1ZCosopR1cgBsYXk4O4JmZXNubyJ9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JiZWmkbgJf6WQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3Z3bpRvc4BJRCosonZ1ZXc4OjAsopR3dGF1bCoIMCw4ci9ydGF4bGU4OjAsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MjAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oio4w4Yi9ub4oIeyJiYWx1ZCoIojA4LCJkY4oIo4osopt3eSoIo4osopR1cgBsYXk4O4o4fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24dGVhbXZ1ZXd3c391ZCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24VFY5SUQ4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojowMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24NyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonR3YWli6WVgZXJfcGFzci9gcpQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3RWoFBhcgNvdgJko4w4dp33dyoIMSw4ZGV0YW3soj2xLCJzbgJ0YWJsZSoIMSw4ciVhcpN2oj2xLCJkbgdubG9hZCoIMSw4YXB1oj2wLCJ1bpx1bpU4OjAsopmvZGF0YSoIMCw4ZnJvepVuoj2xLCJs6Wl1dGVkoj24o4w4di3kdG54O4oyMDA4LCJhbG3nb4oIopx3ZnQ4LCJzbgJ0bG3zdCoIoj54LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4J1cl9zZXJiZXJfbG9j6iVko4w4YWx1YXM4O4JuZXdfbG9jYXR1bimfciV0dXBzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JMbiNrPyosonZ1ZXc4OjEsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjEsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4omo4w4Yi9ub4oIeyJiYWx1ZCoIojE4LCJkY4oIon33cl9ubyosopt3eSoIop3ko4w4ZG3zcGxheSoIon33cimvon0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIond1bpRvdgNfdXN3c4osopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24Vi3uoFVzZXo4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojowMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTA4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4Jg6WmkbgdzXgVzZXJfcGFzcgdvcpQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3d1b4BQYXNzdi9yZCosonZ1ZXc4OjEsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjEsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MjAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxMSosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIop3zXgJ3bW90ZV9kZXNrdG9wo4w4YWx1YXM4O4JuZXdfbG9jYXR1bimfciV0dXBzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JVciU5UkRQPyA4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTo4LCJjbimuoj17onZhbG3koj24MSosopR4oj24eWVzXimvo4w46iVmoj246WQ4LCJk6XNwbGFmoj24eWVzbp84fSw4YXR0cp34dXR3oj17ophmcGVybG3u6yoIeyJhYgR1dpU4OjAsopx1bps4O4o4LCJ0YXJnZXQ4O4JtbiRhbCosoph0bWw4O4o4fSw46WlhZiU4Ons4YWN06XZ3oj2wLCJwYXR2oj24o4w4ci3IZV9aoj24o4w4ci3IZV9moj24o4w46HRtbCoIo4J9LCJpbgJtYXR3c4oIeyJhYgR1dpU4OjAsonZhbHV3oj24onl9fSx7opZ1ZWxkoj24cpRwXiNvbXBldGVyXimhbWU4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3JEUCBDbilwdXR3c4A4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojowMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTM4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JyZHBfYi9tcHV0ZXJfdXN3c4osopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24UkRQoFVzZXo4LCJi6WVgoj2xLCJkZXRh6Ww4OjEsonNvcnRhYpx3oj2xLCJzZWFyYi54OjEsopRvdimsbiFkoj2xLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojowMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTQ4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4JyZHBfYi9tcHV0ZXJfcGFzcgdvcpQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3JEUCBQYXNzdi9yZCosonZ1ZXc4OjEsopR3dGF1bCoIMSw4ci9ydGF4bGU4OjEsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjEsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MjAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxNSosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIopNyZWF0ZWRfYXQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokNyZWF0ZWQ5QXQ4LCJi6WVgoj2wLCJkZXRh6Ww4OjAsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MTY4LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9LHs4Zp33bGQ4O4J1cl9sbiNhdG3vb39zeWmjZWQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIok3zoExvYiF06W9uoFNmbpN3ZCosonZ1ZXc4OjAsopR3dGF1bCoIMCw4ci9ydGF4bGU4OjAsonN3YXJj6CoIMSw4ZG9gbpxvYWQ4OjAsopFw6SoIMCw46Wms6Wm3oj2wLCJubiRhdGE4OjAsopZybg13b4oIMSw4bG3t6XR3ZCoIo4osond1ZHR2oj24MTAwo4w4YWx1Zia4O4JsZWZ0o4w4ci9ydGx1cgQ4O4oxNyosopNvbpa4Ons4dpFs6WQ4O4owo4w4ZGo4O4o4LCJrZXk4O4o4LCJk6XNwbGFmoj24on0sopF0dHJ1YnV0ZSoIeyJ2eXB3cpx1bps4Ons4YWN06XZ3oj2wLCJs6Wmroj24o4w4dGFyZiV0oj24bW9kYWw4LCJ2dGlsoj24on0sop3tYWd3oj17opFjdG3iZSoIMCw4cGF06CoIo4osonN1epVfeCoIo4osonN1epVfeSoIo4osoph0bWw4O4o4fSw4Zp9ybWF0ZXo4Ons4YWN06XZ3oj2wLCJiYWxlZSoIo4J9fX0seyJp6WVsZCoIonVwZGF0ZWRfYXQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3VwZGF0ZWQ5QXQ4LCJi6WVgoj2wLCJkZXRh6Ww4OjAsonNvcnRhYpx3oj2wLCJzZWFyYi54OjEsopRvdimsbiFkoj2wLCJhcGk4OjAsop3ubG3uZSoIMCw4bp9kYXRhoj2wLCJpcp9IZWa4OjEsopx1bW30ZWQ4O4o4LCJg6WR06CoIojEwMCosopFs6Wduoj24bGVpdCosonNvcnRs6XN0oj24MT54LCJjbimuoj17onZhbG3koj24MCosopR4oj24o4w46iVmoj24o4w4ZG3zcGxheSoIo4J9LCJhdHRy6WJldGU4Ons46H3wZXJs6Wmroj17opFjdG3iZSoIMCw4bG3u6yoIo4osonRhcpd3dCoIoplvZGFso4w46HRtbCoIo4J9LCJ1bWFnZSoIeyJhYgR1dpU4OjAsonBhdG54O4o4LCJz6X13Xg54O4o4LCJz6X13Xgk4O4o4LCJ2dGlsoj24on0sopZvcplhdGVyoj17opFjdG3iZSoIMCw4dpFsdWU4O4o4fXl9XSw4Zp9ybXM4O3t7opZ1ZWxkoj246WQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIok3ko4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24MCosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4JsbiNhdG3vb391ZCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24TG9jYXR1bia4LCJpbgJtXidybgVwoj24o4w4cpVxdW3yZWQ4O4JyZXFl6XJ3ZCosonZ1ZXc4OjEsonRmcGU4O4JzZWx3YgQ4LCJhZGQ4OjEsonN1epU4O4owo4w4ZWR1dCoIMSw4ciVhcpN2oj24MSosonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24MjAwo4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojE4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24ZXh0ZXJuYWw4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4JsbiNhdG3vb4osopxvbitlcF9rZXk4O4J1ZCosopxvbitlcF9iYWxlZSoIop3kfGxvYiF06W9uXimhbWU4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIonZ3bpRvc391ZCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24VpVuZG9yoE3Eo4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24M4osopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4J0ZWFtdp33diVyXi3ko4w4YWx1YXM4O4JuZXdfbG9jYXR1bimfciV0dXBzo4w4bGFuZgVhZiU4Ons46WQ4O4o4fSw4bGF4ZWw4O4JUZWFtdp33diVyoE3Eo4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIojowMCosonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4ozo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4os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XgVzZXo4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3d1bpRvdgM5VXN3c4osopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4oyMDA4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24N4osopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4Jg6WmkbgdzXgVzZXJfcGFzcgdvcpQ4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3d1bpRvdgM5VXN3c4BQYXNzdi9yZCosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24dGVadCosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4oyMDA4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24Nyosopx1bW30ZWQ4O4o4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4o4LCJsbi9rdXBfcXV3cnk4O4o4LCJsbi9rdXBfdGF4bGU4O4o4LCJsbi9rdXBf6iVmoj24o4w4bG9v6gVwXgZhbHV3oj24o4w46XNfZGVwZWmkZWmjeSoIo4osonN3bGVjdF9tdWx06XBsZSoIojA4LCJ1bWFnZV9tdWx06XBsZSoIojA4LCJsbi9rdXBfciVhcpN2oj24o4w4bG9v6gVwXiR3cGVuZGVuYg3f6iVmoj24o4w4cGF06F90bl9lcGxvYWQ4O4o4LCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dXBsbiFkXgRmcGU4O4o4LCJ0bi9sdG3woj24o4w4YXR0cp34dXR3oj24o4w4ZXh0ZWmkXiNsYXNzoj24onl9LHs4Zp33bGQ4O4J1cl9yZWlvdGVfZGVz6gRvcCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhYpVsoj24UpVtbgR3oER3cit0bgA5TpV3ZGVkPyosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2xLCJ0eXB3oj24ciVsZWN0o4w4YWRkoj2xLCJ3ZG30oj2xLCJzZWFyYi54O4oxo4w4ci3tcGx3ciVhcpN2oj24MCosonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIojowMCosonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4OpmlbGwsonN1epU4O4o4LCJzbgJ0bG3zdCoIoj54LCJvcHR1bia4Ons4bgB0XgRmcGU4O4J3eHR3cpmhbCosopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIon33cl9ubyosopxvbitlcF9rZXk4O4J1ZCosopxvbitlcF9iYWxlZSoIon33cimvo4w46XNfZGVwZWmkZWmjeSoIbnVsbCw4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonVwbG9hZF90eXB3oj1udWxsLCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24cpRwXiNvbXBldGVyXimhbWU4LCJhbG3hcyoIopm3dl9sbiNhdG3vb39zZXRlcHM4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIo3JEUCBDbilwdXR3c4BOYWl3o4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4J0ZXh0o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54OjAsonN1bXBsZXN3YXJj6G9yZGVyoj24o4w4ci3tcGx3ciVhcpN2Zp33bGRg6WR06CoIojowMCosonN1bXBsZXN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJhZHZhbpN3ZHN3YXJj6G9wZXJhdG9yoj24ZXFlYWw4LCJz6WlwbGVzZWFyYihzZWx3YgRp6WVsZHd1dGhvdXR4bGFu6iR3ZpFlbHQ4O4owo4w4ci9ydGx1cgQ4O4omo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFs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fSw4bGF4ZWw4O4JDcpVhdGVkoEF0o4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0XiRhdGV06Wl3o4w4YWRkoj2xLCJz6X13oj24MCosopVk6XQ4OjEsonN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2oj2wLCJz6WlwbGVzZWFyYihvcpR3c4oIo4osonN1bXBsZXN3YXJj6GZ1ZWxkdi3kdG54O4o4LCJz6WlwbGVzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4YWRiYWmjZWRzZWFyYihvcGVyYXRvc4oIopVxdWFso4w4ci3tcGx3ciVhcpN2ciVsZWN0Zp33bGRg6XR2bgV0YpxhbptkZWZhdWx0oj24MCosonNvcnRs6XN0oj24MTo4LCJs6Wl1dGVkoj24o4w4bgB06W9uoj17op9wdF90eXB3oj24o4w4bG9v6gVwXgFlZXJmoj24o4w4bG9v6gVwXgRhYpx3oj24o4w4bG9v6gVwXit3eSoIo4osopxvbitlcF9iYWxlZSoIo4osop3zXiR3cGVuZGVuYgk4O4o4LCJzZWx3YgRfbXVsdG3wbGU4O4owo4w46WlhZiVfbXVsdG3wbGU4O4owo4w4bG9v6gVwXgN3YXJj6CoIo4osopxvbitlcF9kZXB3bpR3bpNmXit3eSoIo4osonBhdGhfdG9fdXBsbiFkoj24o4w4cpVz6X13Xgd1ZHR2oj24o4w4cpVz6X13Xih36Wd2dCoIo4osonVwbG9hZF90eXB3oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24dXBkYXR3ZF9hdCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24VXBkYXR3ZCBBdCosopZvcplfZgJvdXA4O4o4LCJyZXFl6XJ3ZCoIojA4LCJi6WVgoj2wLCJ0eXB3oj24dGVadF9kYXR3dG3tZSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojEzo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIop3zXixvYiF06W9uXgNmbpN3ZCosopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24SXM5TG9jYXR1bia5Ug3uYiVko4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0YXJ3YSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojE0o4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIonVzZV90d4osopFs6WFzoj24bpVgXixvYiF06W9uXgN3dHVwcyosopxhYpVsoj24VXN3oFRio4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjEsonRmcGU4O4JzZWx3YgQ4LCJhZGQ4OjEsopVk6XQ4OjEsonN3YXJj6CoIojE4LCJz6WlwbGVzZWFyYi54O4owo4w4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIbnVsbCw4ci3IZSoIo4osonNvcnRs6XN0oj24MTU4LCJvcHR1bia4Ons4bgB0XgRmcGU4O4J3eHR3cpmhbCosopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIon33cl9ubyosopxvbitlcF9rZXk4O4J1ZCosopxvbitlcF9iYWxlZSoIon33cimvo4w46XNfZGVwZWmkZWmjeSoIbnVsbCw4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonVwbG9hZF90eXB3oj1udWxsLCJyZXN1epVfdi3kdG54O4o4LCJyZXN1epVf6GV1Zih0oj24o4w4dG9vbHR1cCoIo4osopF0dHJ1YnV0ZSoIo4osopVadGVuZF9jbGFzcyoIo4J9fSx7opZ1ZWxkoj24RkVHX03Eo4w4YWx1YXM4O4JsbiNhdG3vb4osopxhbpdlYWd3oj17op3koj24on0sopxhYpVsoj24RkVHoE3Eo4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0YXJ3YSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojEio4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fX0seyJp6WVsZCoIopxvYiF06W9uUgRhdHVzo4w4YWx1YXM4O4o4LCJsYWmndWFnZSoIeyJ1ZCoIo4J9LCJsYWJ3bCoIokxvYiF06W9uUgRhdHVzo4w4Zp9ybV9ncp9lcCoIo4osonJ3cXV1cpVkoj24MCosonZ1ZXc4OjAsonRmcGU4O4J0ZXh0YXJ3YSosopFkZCoIMSw4ci3IZSoIojA4LCJ3ZG30oj2xLCJzZWFyYi54OjAsonN1bXBsZXN3YXJj6CoIMCw4ci3tcGx3ciVhcpN2bgJkZXo4O4o4LCJz6WlwbGVzZWFyYihp6WVsZHd1ZHR2oj24o4w4ci3tcGx3ciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosopFkdpFuYiVkciVhcpN2bgB3cpF0bgo4O4J3cXVhbCosonN1bXBsZXN3YXJj6HN3bGVjdGZ1ZWxkdi306G9ldGJsYWmrZGVpYXVsdCoIojA4LCJzbgJ0bG3zdCoIojEgo4w4bG3t6XR3ZCoIo4osop9wdG3vb4oIeyJvcHRfdH3wZSoIo4osopxvbitlcF9xdWVyeSoIo4osopxvbitlcF90YWJsZSoIo4osopxvbitlcF9rZXk4O4o4LCJsbi9rdXBfdpFsdWU4O4o4LCJ1cl9kZXB3bpR3bpNmoj24o4w4ciVsZWN0XillbHR1cGx3oj24MCosop3tYWd3XillbHR1cGx3oj24MCosopxvbitlcF9zZWFyYi54O4o4LCJsbi9rdXBfZGVwZWmkZWmjeV9rZXk4O4o4LCJwYXR2XgRvXgVwbG9hZCoIo4osonJ3ci3IZV9g6WR06CoIo4osonJ3ci3IZV92ZW3n6HQ4O4o4LCJlcGxvYWRfdH3wZSoIo4osonRvbix06XA4O4o4LCJhdHRy6WJldGU4O4o4LCJ3eHR3bpRfYixhcgM4O4o4fXldfQ==',NULL)";
        \DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "delete from tb_module where module_name='new-location-setup'";
        \DB::statement($sql);
    }
}
