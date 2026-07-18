<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';



interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

}



interface Attendance {

    id:number;

    work_date:string;

    check_in:string|null;

    check_out:string|null;

    worked_minutes:number;

    late_minutes:number;

    overtime_minutes:number;

    employee:Employee;

}



interface PaginationLink {

    url:string|null;

    label:string;

    active:boolean;

}



interface Props {


    attendances:{

        data:Attendance[];

        links:PaginationLink[];

    };


    employees:Employee[];


    filters:{

        from:string;

        to:string;

        employee_id:number|null;

    };


    summary:{

        worked_minutes:number;

        late_minutes:number;

        overtime_minutes:number;

    };


}



const props = defineProps<Props>();





const from = ref(
    props.filters.from
);


const to = ref(
    props.filters.to
);


const employeeId = ref(
    props.filters.employee_id ?? ''
);





function search()
{

    router.get(

        route(
            'attendance.reports'
        ),

        {

            from:from.value,

            to:to.value,

            employee_id:employeeId.value

        },

        {

            preserveState:true,

            replace:true

        }

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


<Head title="گزارش حضور و غیاب"/>



<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold text-gray-800">

گزارش حضور و غیاب

</h2>

</template>




<div class="p-6 space-y-6">





<!-- Filters -->


<div class="rounded-lg bg-white p-6 shadow">


<div class="grid grid-cols-1 gap-4 md:grid-cols-4">



<div>

<label class="text-sm">
از تاریخ
</label>


<input

v-model="from"

type="date"

class="mt-2 w-full rounded border"

>

</div>




<div>

<label class="text-sm">
تا تاریخ
</label>


<input

v-model="to"

type="date"

class="mt-2 w-full rounded border"

>

</div>





<div>

<label class="text-sm">
پرسنل
</label>


<select

v-model="employeeId"

class="mt-2 w-full rounded border"

>


<option value="">
همه پرسنل
</option>



<option

v-for="employee in employees"

:key="employee.id"

:value="employee.id"

>

{{employee.first_name}}

{{employee.last_name}}

-

{{employee.employee_code}}

</option>


</select>


</div>




<div class="flex items-end">


<button

@click="search"

class="rounded bg-blue-600 px-5 py-2 text-white"

>

نمایش گزارش

</button>


</div>




</div>


</div>







<!-- Summary -->


<div class="grid grid-cols-1 gap-5 md:grid-cols-3">



<div class="rounded-lg bg-white p-5 shadow">

<p class="text-gray-500">

کارکرد

</p>


<p class="mt-2 text-3xl font-bold">

{{formatMinutes(summary.worked_minutes)}}

</p>


</div>





<div class="rounded-lg bg-white p-5 shadow">

<p class="text-gray-500">

تاخیر

</p>


<p class="mt-2 text-3xl font-bold text-red-600">

{{formatMinutes(summary.late_minutes)}}

</p>


</div>





<div class="rounded-lg bg-white p-5 shadow">

<p class="text-gray-500">

اضافه کاری

</p>


<p class="mt-2 text-3xl font-bold text-green-600">

{{formatMinutes(summary.overtime_minutes)}}

</p>


</div>



</div>








<!-- Table -->


<div class="rounded-lg bg-white shadow overflow-hidden">


<table class="w-full text-right">


<thead>

<tr class="border-b bg-gray-50">


<th class="p-4">
تاریخ
</th>


<th class="p-4">
پرسنل
</th>


<th class="p-4">
ورود
</th>


<th class="p-4">
خروج
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


</tr>


</thead>





<tbody>


<tr

v-for="item in attendances.data"

:key="item.id"

class="border-b"

>


<td class="p-4">

{{item.work_date}}

</td>



<td class="p-4">

{{item.employee.first_name}}

{{item.employee.last_name}}

</td>




<td class="p-4">

{{item.check_in ?? '-'}}

</td>




<td class="p-4">

{{item.check_out ?? '-'}}

</td>



<td class="p-4">

{{formatMinutes(item.worked_minutes)}}

</td>




<td class="p-4 text-red-600">

{{formatMinutes(item.late_minutes)}}

</td>




<td class="p-4 text-green-600">

{{formatMinutes(item.overtime_minutes)}}

</td>




</tr>



</tbody>


</table>


</div>







<!-- Pagination -->


<div class="flex justify-center gap-2">


<Link

v-for="link in attendances.links"

:key="link.label"

:href="link.url ?? ''"

v-html="link.label"

class="rounded px-3 py-2"

:class="{

'bg-blue-600 text-white':link.active,

'bg-gray-100':!link.active

}"

></Link>


</div>




</div>



</AuthenticatedLayout>


</template>