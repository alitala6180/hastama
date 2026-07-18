<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



interface Attendance {

    id:number;

    check_in:string|null;

    check_out:string|null;

    worked_minutes:number;

    late_minutes:number;

    overtime_minutes:number;

}



interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

    attendances:Attendance[];

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





function checkIn(employeeId:number)
{

    router.post(

        route(
            'attendances.checkIn',
            employeeId
        )

    );

}





function checkOut(employeeId:number)
{

    router.post(

        route(
            'attendances.checkOut',
            employeeId
        )

    );

}





function formatMinutes(minutes:number)
{

    if(!minutes)
        return '0:00';



    const hours = Math.floor(
        minutes / 60
    );


    const mins = minutes % 60;



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



<!-- Header -->


<div class="flex items-center justify-between border-b p-6">


<div>


<h3 class="text-lg font-semibold">

لیست حضور امروز

</h3>


<p class="mt-1 text-sm text-gray-500">

تاریخ:
{{ filters.date }}

</p>


</div>




<Link

:href="route('attendances.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"

>

➕ ثبت حضور دستی

</Link>



</div>







<div class="overflow-x-auto p-6">



<table class="w-full text-right">



<thead>


<tr class="border-b bg-gray-50 text-sm text-gray-600">


<th class="p-4">
پرسنل
</th>


<th class="p-4">
ساعت ورود
</th>


<th class="p-4">
ساعت خروج
</th>


<th class="p-4">
کارکرد
</th>


<th class="p-4">
تاخیر
</th>


<th class="p-4">
اضافه کاری
</th>


<th class="p-4">
عملیات
</th>


</tr>


</thead>





<tbody>



<tr

v-for="employee in props.employees"

:key="employee.id"

class="border-b hover:bg-gray-50"

>


<td class="p-4">


<div class="font-semibold">

{{ employee.first_name }}

{{ employee.last_name }}

</div>


<div class="text-sm text-gray-500">

کد:
{{ employee.employee_code }}

</div>


</td>





<td class="p-4">


{{ getAttendance(employee)?.check_in ?? '-' }}


</td>





<td class="p-4">


{{ getAttendance(employee)?.check_out ?? '-' }}


</td>





<td class="p-4">


{{ formatMinutes(

getAttendance(employee)?.worked_minutes ?? 0

) }}


</td>





<td class="p-4 text-red-600">


{{ formatMinutes(

getAttendance(employee)?.late_minutes ?? 0

) }}


</td>





<td class="p-4 text-green-600">


{{ formatMinutes(

getAttendance(employee)?.overtime_minutes ?? 0

) }}


</td>





<td class="p-4 space-x-2">



<button

v-if="!getAttendance(employee)?.check_in"

@click="checkIn(employee.id)"

class="rounded-lg bg-green-600 px-4 py-2 text-white"

>

🟢 ورود

</button>





<button

v-else-if="!getAttendance(employee)?.check_out"

@click="checkOut(employee.id)"

class="rounded-lg bg-red-600 px-4 py-2 text-white"

>

🔴 خروج

</button>





<Link

v-if="getAttendance(employee)"

:href="route(

'attendances.edit',

getAttendance(employee)?.id

)"

class="rounded-lg bg-yellow-500 px-4 py-2 text-white"

>

ویرایش

</Link>





<span

v-if="

getAttendance(employee)?.check_in &&

getAttendance(employee)?.check_out

"

class="text-gray-500"

>

تکمیل شده

</span>



</td>



</tr>




</tbody>



</table>



</div>



</div>



</div>



</AuthenticatedLayout>



</template>