<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{!! env('APP_NAME') !!}</title>
</head>
<body>
<div style="font-size:12px; font-family:Arial, Helvetica, sans-serif; width:700px;">
    Dear ,  {{$article_comment->full_name}} 
    <br/><br/>
    Article Comment is forwarded for Website For Verification.
    <table width="700px" border="1" cellspacing="0" cellpadding="4" style=" margin:15px 0;">
        <tr>
            <td>Article</td>
            <td colspan="3">{{$article->title}}</td>
        </tr>
        <tr>
            <td class="gray-bg">Full Name</td>
            <td>
            {{$article_comment->full_name}}
            </td>
            <td class="gray-bg">Email</td>
            <td>
            {{$article_comment->email}} 
            </td>
        </tr>
       
        <tr>
            <td>Comment</td>
            <td colspan="3">{{$article_comment->comment}}</td>
        </tr>
          
    </table>

   

    <br/><br/>
</div>
</body>
</html>