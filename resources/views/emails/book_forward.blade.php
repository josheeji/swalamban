<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{!! env('APP_NAME') !!}</title>
</head>
<body>
<div style="font-size:12px; font-family:Arial, Helvetica, sans-serif; width:700px;">
    Dear ,  {{$book->f_name}}&nbsp;{{$book->l_name}} 
    <br/><br/>
    Your Booking Details From Website.
    <table width="700px" border="1" cellspacing="0" cellpadding="4" style=" margin:15px 0;">
        <tr>
            <td class="gray-bg">First Name</td>
            <td>
            {{$book->f_name}}
            </td>
            <td class="gray-bg">Last Name</td>
            <td>
            {{$book->l_name}} 
            </td>
        </tr>
        <tr>
            <td class="gray-bg">Mobile No.</td>
            <td>{{$book->mobile_no}}</td>
            <td class="gray-bg">Email</td>
            <td>{{$book->email}}</td>
        </tr>

         <tr>
            <td class="gray-bg">Address</td>
            <td>{{$book->address}}</td>
            <td class="gray-bg">Country</td>
            <td>{{$country->country_name}}</td>
        </tr>

         <tr>
            <td class="gray-bg">Destination</td>
            <td>{{$destination->name}}</td>
            <td class="gray-bg">Package</td>
            <td>{{$package->title}}</td>
        </tr>


      <tr>
        <td>No of Person</td>
        <td >{{$book->no_person}}</td>
        <td>Rate</td>
        <td>{{$book->rate_amount}}</td>
      </tr>
       
        <tr>
            <td>Message</td>
            <td colspan="3">{{$book->message}}</td>
        </tr>

      @if($book->fixed_departure_id!=null)
        <tr>
          <td >Fixed Departure</td>
          <td colspan="3">{{$book->fixed_departure->departure_date}}To{{$book->fixed_departure->return_date}}</td>
        </tr>
      @endif
      @if(($book->departure_date!='')||($book->departure_date!='NULL'))
        <tr>
          <td >Departure Date</td>
          <td>{{$book->departure_date}}</td>
          <td >Duration </td>
          <td>{{$package->duration}}</td>
        </tr>
      @endif
          
    </table>

   

    <br/><br/>
    Kind Regards, <br/>
   {!! env('APP_NAME') !!}
 
</div>
</body>
</html>