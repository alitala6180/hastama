<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';



interface Department {

    id:number;

    name:string;

}



interface Shift {

    id:number;

    name:string;

}



interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

    department?:Department;

    shift?:Shift;

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



interface EmployeeSummary {


    employee:Employee;


    attendance_days:number;


    absent_days:number;


    worked_minutes:number;


    late_minutes:number;


    overtime_minutes:number;


}




interface Props {


    attendances:{

        data:Attendance[];

        links:any[];

    };


    employees:Employee[];


    departments:Department[];


    shifts:Shift[];


    employeeSummaries:EmployeeSummary[];



    filters:{

        from:string;

        to:string;

        employee_id:number|null;

        department_id:number|null;

        shift_id:number|null;

    };



    summary:{

        worked_minutes:number;

        late_minutes:number;

        overtime_minutes:number;

        days:number;

    };



}



const props = defineProps<Props>();





const from = ref(props.filters.from);

const to = ref(props.filters.to);

const employeeId = ref(props.filters.employee_id ?? '');

const departmentId = ref(props.filters.department_id ?? '');

const shiftId = ref(props.filters.shift_id ?? '');







function search()
{


    router.get(

        route('attendance.reports'),

        {

            from:from.value,

            to:to.value,

            employee_id:employeeId.value,

            department_id:departmentId.value,

            shift_id:shiftId.value,


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



    const hours = Math.floor(minutes / 60);


    const mins = minutes % 60;



    return `${hours}:${mins.toString().padStart(2,'0')}`;


}



</script>





<template>


<Head title="گزارش حضور و غیاب"/>


<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold text-gray-800">

گزارش حرفه‌ای حضور و غیاب

</h2>

</template>





<div class="p-6 space-y-6">





<!-- Filters -->

<div class="rounded-lg bg-white p-6 shadow">


<div class="grid grid-cols-1 md:grid-cols-5 gap-4">



<div>

<label>از تاریخ</label>

<input

v-model="from"

type="date"

class="w-full rounded border mt-2"

/>

</div>




<div>

<label>تا تاریخ</label>

<input

v-model="to"

type="date"

class="w-full rounded border mt-2"

/>

</div>





<div>

<label>پرسنل</label>

<select

v-model="employeeId"

class="w-full rounded border mt-2"

>

<option value="">
همه
</option>


<option

v-for="employee in employees"

:key="employee.id"

:value="employee.id"

>

{{employee.first_name}}

{{employee.last_name}}

</option>


</select>

</div>







<div>

<label>واحد</label>


<select

v-model="departmentId"

class="w-full rounded border mt-2"

>


<option value="">
همه
</option>


<option

v-for="item in departments"

:key="item.id"

:value="item.id"

>

{{item.name}}

</option>


</select>


</div>







<div>

<label>شیفت</label>


<select

v-model="shiftId"

class="w-full rounded border mt-2"

>


<option value="">
همه
</option>


<option

v-for="item in shifts"

:key="item.id"

:value="item.id"

>

{{item.name}}

</option>


</select>


</div>


</div>




<button

@click="search"

class="mt-5 rounded bg-blue-600 px-6 py-2 text-white"

>

نمایش گزارش

</button>

<a

:href="route('attendance.reports.export',{
    from:from,
    to:to,
    employee_id:employeeId
})"

class="rounded bg-green-600 px-6 py-2 text-white"

>

📥 خروجی Excel

</a>


</div>








<!-- Summary Cards -->


<div class="grid md:grid-cols-4 gap-5">


<div class="bg-white shadow rounded p-5">

<p>روزهای حضور</p>

<strong class="text-3xl">

{{summary.days}}

</strong>

</div>



<div class="bg-white shadow rounded p-5">

<p>کارکرد</p>

<strong class="text-3xl">

{{formatMinutes(summary.worked_minutes)}}

</strong>

</div>




<div class="bg-white shadow rounded p-5">

<p>تاخیر</p>

<strong class="text-3xl text-red-600">

{{formatMinutes(summary.late_minutes)}}

</strong>

</div>




<div class="bg-white shadow rounded p-5">

<p>اضافه کاری</p>

<strong class="text-3xl text-green-600">

{{formatMinutes(summary.overtime_minutes)}}

</strong>

</div>


</div>







<!-- Employee Summary -->


<div class="bg-white rounded shadow overflow-x-auto">


<h3 class="p-5 font-bold">

خلاصه عملکرد پرسنل

</h3>


<table class="w-full text-right">


<thead>

<tr class="border-b bg-gray-50">

<th class="p-4">پرسنل</th>

<th>حضور</th>

<th>غیبت</th>

<th>کارکرد</th>

<th>تاخیر</th>

<th>اضافه کاری</th>

</tr>

</thead>



<tbody>


<tr

v-for="item in employeeSummaries"

:key="item.employee.id"

class="border-b"

>


<td class="p-4">

{{item.employee.first_name}}

{{item.employee.last_name}}

</td>


<td>

{{item.attendance_days}}

</td>


<td class="text-red-600">

{{item.absent_days}}

</td>


<td>

{{formatMinutes(item.worked_minutes)}}

</td>


<td>

{{formatMinutes(item.late_minutes)}}

</td>


<td>

{{formatMinutes(item.overtime_minutes)}}

</td>


</tr>


</tbody>


</table>


</div>








<!-- Detail -->


<div class="bg-white rounded shadow overflow-x-auto">


<table class="w-full text-right">


<thead>

<tr class="border-b">

<th class="p-4">تاریخ</th>

<th>پرسنل</th>

<th>ورود</th>

<th>خروج</th>

<th>کارکرد</th>

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


<td>

{{item.employee.first_name}}

{{item.employee.last_name}}

</td>


<td>

{{item.check_in ?? '-'}}

</td>


<td>

{{item.check_out ?? '-'}}

</td>


<td>

{{formatMinutes(item.worked_minutes)}}

</td>


</tr>


</tbody>


</table>


</div>






<div class="flex justify-center gap-2">


<Link

v-for="link in attendances.links"

:key="link.label"

:href="link.url ?? ''"

v-html="link.label"

class="px-3 py-2 rounded bg-gray-100"

/>


</div>





</div>


</AuthenticatedLayout>


</template>