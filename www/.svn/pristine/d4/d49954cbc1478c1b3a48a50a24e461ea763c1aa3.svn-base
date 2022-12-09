<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title>{{$data["subject"]}}</title>
</head>
<body style="padding-top: 50px;">
<div style=" box-sizing: border-box; width: 90%; max-width: 800px; border: 1px solid #EEEEEE; margin: 0 auto; padding: 45px; background-color: #FFFFFF; box-shadow: 3px 5px 10px rgba(0,0,0,0.025); border-radius: 5px; ">
    <div style="display: block; padding: 10px 25px; margin: 0 -25px; margin-bottom: 30px; background-color: #5E5EC6; color: #FFFFFF; border-radius: 5px;">
        <img style="display: inline-block; vertical-align: middle; height: 40px; margin-right: 10px;" src="{{$data["logo"]}}">
        {{config('app.name')}}
    </div>
    <div style="padding: 0; margin: 0; margin-bottom: 1em;">尊敬的{{$data["toUserName"]}}，您好!</div>
    <div style="margin-bottom: 25px;">告警内容：{{$data["message"]}}</div>
    <div style="margin-bottom: 15px; font-weight: bold;">涉及主机：</div>
    <table cellspacing="0" cellpadding="0" bordercolor="#666666" border="0" bgcolor="#FFFFFF"
           style=" width: 100%; font-size:12px;font-weight:normal;border-collapse:collapse; margin-bottom: 30px; text-align: center;">
        <tr style="background-color:#F8F8F8; font-size: 14px;">
            <th style="border:1px solid #EEEEEE; padding: 8px;">主机</th>
            <th style="border:1px solid #EEEEEE; padding: 8px;">IP</th>
        </tr>
        <tr style="background-color:#FFFFFF;">
            <td style="border:1px solid #EEEEEE; padding: 8px;">{{$data["hostName"]}}</td>
            <td style="border:1px solid #EEEEEE; padding: 8px;">{{$data["hostIP"]}}</td>
        </tr>
    </table>
    <div style="margin-bottom: 20px; text-align: right;">时间：{{$data["dateTime"]}}</div>
    <div style="color: #888888; text-align: right;">此为系统邮件请勿回复</div>
</div>
<div style=" box-sizing: border-box; width: 90%; max-width: 800px; display: block; text-align: center; line-height: 1.6em; font-size: 12px; color: #888888; margin: 0 auto; padding: 20px 45px;"><br/>{{config("app.copyright")}}</div>
</body>
</html>
