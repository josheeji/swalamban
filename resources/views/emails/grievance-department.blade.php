<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! env('APP_NAME') !!}</title>
</head>

<body>
    <div
        style="background: #02509b; width: 700px; padding: 50px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
        <div style="width: 90%; background: #fff; margin: auto; padding: 25px; text-align: left;">
            <a href="{{ url('/') }}" target="_blank"><img style="width:150px; margin: auto;"
                    src="{{ asset('frontend/images/logo.png') }}" /></a>
            <div style="text-align: left; margin-top: 50px;">
                <div style="font-weight: bold;">Dear ADMIN,</div>
                <br>
                <br>
                <div style="margin-bottom: 12px;">
                    Here are the details grievance/feedback.
                </div>
                <div style="margin-bottom: 12px;">
                    <table>
                        <tr>
                            <th>Reference ID</th>
                            <td>{!! $grievance->reference_id !!}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{!! $grievance->subject !!}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{!! $grievance->full_name !!}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{!! $grievance->email !!}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{!! $grievance->mobile !!}</td>
                        </tr>
                        <tr>
                            <th>Message</th>
                            <td>{!! $grievance->message !!}</td>
                        </tr>
                    </table>
                </div>

                <div>Best regards,<span
                        style="font-weight: bold; display: block; margin-top: 5px;">{{ SettingHelper::setting('site_title') }}</span>
                </div>
                <div style="font-size: 12px; margin-top: 15px;"><span
                        style="color: #DE0F19;">NOTE</span>&nbsp;:&nbsp;THIS MESSAGE IS SYSTEM GENERATED. PLEASE DO NO
                    REPLY TO THIS MESSAGE.</div>
            </div>
        </div>
    </div>
</body>

</html>
