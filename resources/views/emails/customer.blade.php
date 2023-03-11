<html>
<head></head>
<body style="">
Your booking details has been received.
The information of the booking are as follows:
<br>


<table border="0">
    <thead>

    <tr>
        <th>
            Check In date
        </th>
        <td>
            {{ $request['check_in_date'] }}
        </td>
    </tr>

    <tr>
        <th>
            Check Out Date
        </th>
        <td>
            {{ $request['check_out_date'] }}
        </td>
    </tr>

    <tr>
        <th>
            Room
        </th>
        <td>
            {{ $request->roomType->name }}
        </td>
    </tr>

    <tr>
        <th>
            Number Of Rooms
        </th>
        <td>
            {{ $request->number_of_rooms }}
        </td>
    </tr>

    <tr>
        <th>
            Number Of Adults
        </th>
        <td>
            {{ $request['number_of_adults'] }}
        </td>
    </tr>

    <tr>
        <th>
            Number of Childrens
        </th>
        <td>
            {{ $request['number_of_childrens'] }}
        </td>
    </tr>

    </thead>
</table>

</body>
</html>