<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



interface Shift {

    id:number;

    name:string;

    start_time:string;

    end_time:string;

}



interface Attendance {

    id:number;

    check_in:string|null;

    check_out:string|null;

    worked_minutes:number;

    late_minutes:number;

    overtime_minutes:number;

}



interface Holiday {

    id:number;

    title:string;

    type:string;

    holiday_date:string;

}



interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

    shift?:Shift|null;

    attendances:Attendance[];

    holiday?:Holiday|null;

}





interface Props {

    employees:Employee[];

    filters:{

        date:string;

    };

}



const props = defineProps<Props>();






function getAttendance(employee:Employee)
{

    return employee.attendances?.[0];

}






function formatTime(time:string|null)
{

    if(!time)
        return '-';


    return time.substring(11,16);

}






function checkIn(id:number)
{

    router.post(

        route(
            'attendances.checkIn',
            id
        ),

        {},

        {

            preserveScroll:true

        }

    );

}






function checkOut(id:number)
{

    router.post(

        route(
            'attendances.checkOut',
            id
        ),

        {},

        {

            preserveScroll:true

        }

    );

}







function formatMinutes(minutes:number)
{

    const hours=Math.floor(minutes/60);

    const mins=minutes%60;


    return `${hours}:${mins
        .toString()
        .padStart(2,'0')}`;

}




</script>





<template>


<Head title="حضور و غیاب"/>



<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

حضور و غیاب کارکنان

</h2>


</template>







<div class="p-6">



<div class="rounded-lg bg-white shadow">





<div class="flex justify-between border-b p-6">



<div>


<h3 class="text-lg font-semibold">

لیست حضور امروز

</h3>


<div class="text-sm text-gray-500">

تاریخ:
{{ filters.date }}

</div>


</div>




<Link

:href="route('attendances.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white"

>

ثبت دستی

</Link>



</div>







<div class="overflow-x-auto p-6">


<table class="w-full text-right">



<thead>


<tr class="border-b bg-gray-50">


<th class="p-3">
پرسنل
</th>


<th class="p-3">
شیفت
</th>


<th class="p-3">
ورود
</th>


<th class="p-3">
خروج
</th>


<th class="p-3">
کارکرد
</th>


<th class="p-3">
تاخیر
</th>


<th class="p-3">
اضافه کاری
</th>


<th class="p-3">
عملیات
</th>


</tr>


</thead>







<tbody>



<tr

v-for="employee in employees"

:key="employee.id"

class="border-b hover:bg-gray-50"

>



<td class="p-3">


<div class="font-semibold">

{{employee.first_name}}
{{employee.last_name}}

</div>


<div class="text-sm text-gray-500">

{{employee.employee_code}}

</div>


</td>







<td class="p-3">


<div v-if="employee.shift">


<div class="font-semibold">

{{employee.shift.name}}

</div>


<div class="text-sm text-gray-500">

{{employee.shift.start_time}}

تا

{{employee.shift.end_time}}

</div>


</div>


<span v-else>

بدون شیفت

</span>


</td>









<td class="p-3">

{{formatTime(getAttendance(employee)?.check_in ?? null)}}

</td>





<td class="p-3">

{{formatTime(getAttendance(employee)?.check_out ?? null)}}

</td>







<td class="p-3">


{{formatMinutes(
getAttendance(employee)?.worked_minutes ?? 0
)}}


</td>







<td class="p-3 text-red-600">


{{formatMinutes(
getAttendance(employee)?.late_minutes ?? 0
)}}


</td>







<td class="p-3 text-green-600">


{{formatMinutes(
getAttendance(employee)?.overtime_minutes ?? 0
)}}


</td>







<td class="p-3 space-x-2">






<button

v-if="!getAttendance(employee)?.check_in"

@click="checkIn(employee.id)"

class="rounded bg-green-600 px-3 py-2 text-white"

>

ورود

</button>







<button

v-else-if="!getAttendance(employee)?.check_out"

@click="checkOut(employee.id)"

class="rounded bg-red-600 px-3 py-2 text-white"

>

خروج

</button>








<Link

v-if="getAttendance(employee)"

:href="route(
'attendances.edit',
getAttendance(employee)!.id
)"

class="rounded bg-yellow-500 px-3 py-2 text-white"

>

ویرایش

</Link>







</td>





</tr>



</tbody>




</table>



</div>





</div>



</div>





</AuthenticatedLayout>


</template>