<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<style>

@font-face {
    font-family: 'vazir';
    font-style: normal;
    font-weight: normal;
    src: url('file:///{{ str_replace('\\', '/', public_path("fonts/Vazir.ttf")) }}') format('truetype');
}

html {
    direction: rtl;
    unicode-bidi: bidi-override;
}

body {
    font-family: 'vazir', DejaVu Sans, sans-serif;
    direction: rtl;
    unicode-bidi: bidi-override;
    text-align: right;
    font-size: 12px;
}

.ltr {
    direction: ltr;
    unicode-bidi: isolate;
    text-align: left;
    font-family: 'vazir', DejaVu Sans, sans-serif;
}


h1 {

    text-align:center;

    font-size:20px;

    margin-bottom:20px;

}



.header {

    margin-bottom:20px;

}



.summary {

    width:100%;

    border-collapse:collapse;

    margin-bottom:20px;

}



.summary td {

    border:1px solid #999;

    padding:8px;

    text-align:center;

}




table {

    width:100%;

    border-collapse:collapse;

}



th {

    background:#eee;

    font-weight:bold;

}



th, td {

    border:1px solid #999;

    padding:6px;

    text-align:center;

}



.ltr {

    direction:ltr;
    text-align:left;
    font-family: 'vazir', DejaVu Sans, sans-serif;
}



</style>


</head>



<body>



<h1>
گزارش حضور و غیاب کارکنان
</h1>



<div class="header">

بازه گزارش :

<span class="ltr">
{{ $from }}
</span>

تا

<span class="ltr">
{{ $to }}
</span>


</div>





<table class="summary">


<tr>

<td>
مجموع کارکرد
</td>

<td>
{{ $summary['worked_minutes'] }} دقیقه
</td>


<td>
تاخیر
</td>

<td>
{{ $summary['late_minutes'] }} دقیقه
</td>


<td>
اضافه کاری
</td>

<td>
{{ $summary['overtime_minutes'] }} دقیقه
</td>


</tr>


</table>






<table>


<thead>

<tr>

<th>
تاریخ
</th>


<th>
کد پرسنلی
</th>


<th>
نام
</th>


<th>
نام خانوادگی
</th>


<th>
ورود
</th>


<th>
خروج
</th>


<th>
کارکرد
</th>


<th>
تاخیر
</th>


<th>
اضافه کاری
</th>


</tr>

</thead>



<tbody>


@foreach($attendances as $item)

<tr>


<td class="ltr">

{{ $item->work_date->format('Y-m-d') }}

</td>



<td class="ltr">

{{ $item->employee->employee_code }}

</td>



<td>

{{ $item->employee->first_name }}

</td>



<td>

{{ $item->employee->last_name }}

</td>



<td class="ltr">

{{ $item->check_in ?? '-' }}

</td>



<td class="ltr">

{{ $item->check_out ?? '-' }}

</td>



<td class="ltr">

{{ $item->worked_minutes }}

</td>



<td class="ltr">

{{ $item->late_minutes }}

</td>



<td class="ltr">

{{ $item->overtime_minutes }}

</td>



</tr>


@endforeach


</tbody>


</table>



</body>

</html>