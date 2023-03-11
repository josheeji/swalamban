<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! env('APP_NAME') !!}</title>
</head>

<body>
    <div style="background: #02509b; width: 700px; padding: 50px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
        <div style="width: 90%; background: #fff; margin: auto; padding: 25px; text-align: left;">
            <a href="{{ url('/') }}" target="_blank"><img style="width:150px; margin: auto;" src="{{ asset('frontend/images/logo.png') }}" /></a>
            <div style="text-align: left; margin-top: 50px;">
                <div style="font-weight: bold;">Dear Candidate,</div>
                @if($applicant->career)
                    <p>This is to confirm that we have successfully received your application for the position of {{$applicant->career->title}}.</p>
                @endif
                <p>We are in the process of reviewing with your profile. Provided that your skills correspond with our requirements, we will be in touch with you via e-mail or telephone and contact you shortly.</p>

                <div>Best regards,<span style="font-weight: bold; display: block; margin-top: 5px;">{{ SettingHelper::setting('site_title') }}</span></div>
                <div style="font-size: 12px; margin-top: 15px;"><span style="color: #DE0F19;">NOTE</span>&nbsp;:&nbsp;THIS MESSAGE IS SYSTEM GENERATED. PLEASE DO NO REPLY TO THIS MESSAGE.</div>
            </div>
        </div>
    </div>
</body>

</html>