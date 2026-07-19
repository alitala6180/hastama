<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

}



interface Leave {


    id:number;

    type:string;

    start_date:string;

    end_date:string;

    days:number;

    reason:string|null;

    status:string;

    employee:Employee;


}





interface PaginationLink {

    url:string|null;

    label:string;

    active:boolean;

}





interface LeavesData {

    data:Leave[];

    links:PaginationLink[];

}





const props = defineProps<{

    leaves:LeavesData;

}>();







function approve(id:number)
{


    if(!confirm('تایید این مرخصی انجام شود؟'))
        return;



    router.patch(

        route(

            'leaves.approve',

            id

        )

    );


}







function reject(id:number)
{


    if(!confirm('رد این مرخصی انجام شود؟'))

        return;



    router.patch(

        route(

            'leaves.reject',

            id

        )

    );


}







function typeText(type:string)
{


    const types:any = {


        annual:'استحقاقی',

        sick:'استعلاجی',

        hourly:'ساعتی',

        unpaid:'بدون حقوق'


    };



    return types[type] ?? type;


}







function statusText(status:string)
{


    const statuses:any = {


        pending:'در انتظار',

        approved:'تایید شده',

        rejected:'رد شده'


    };



    return statuses[status] ?? status;


}



</script>







<template>


<Head title="مدیریت مرخصی"/>





<AuthenticatedLayout>




<template #header>


<h2 class="text-xl font-semibold text-gray-800">

مدیریت مرخصی‌ها

</h2>


</template>







<div class="p-6">



<div class="rounded-lg bg-white shadow">






<div class="flex justify-between border-b p-6">



<h3 class="text-lg font-semibold">

لیست درخواست‌ها

</h3>




<Link

:href="route('leaves.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white"

>

➕ درخواست مرخصی

</Link>



</div>









<div class="overflow-x-auto p-6">



<table class="w-full text-right">



<thead>


<tr class="border-b bg-gray-50">


<th class="p-4">

کارمند

</th>


<th class="p-4">

نوع

</th>


<th class="p-4">

از تاریخ

</th>


<th class="p-4">

تا تاریخ

</th>


<th class="p-4">

روز

</th>


<th class="p-4">

وضعیت

</th>


<th class="p-4">

عملیات

</th>


</tr>


</thead>







<tbody>



<tr

v-for="leave in leaves.data"

:key="leave.id"

class="border-b"

>



<td class="p-4">


{{leave.employee.first_name}}

{{leave.employee.last_name}}



<div class="text-sm text-gray-500">

{{leave.employee.employee_code}}

</div>


</td>







<td class="p-4">

{{typeText(leave.type)}}

</td>







<td class="p-4">

{{leave.start_date}}

</td>







<td class="p-4">

{{leave.end_date}}

</td>







<td class="p-4">

{{leave.days}}

</td>







<td class="p-4">


<span

v-if="leave.status==='approved'"

class="rounded bg-green-100 px-3 py-1 text-green-700"

>

{{statusText(leave.status)}}

</span>




<span

v-else-if="leave.status==='rejected'"

class="rounded bg-red-100 px-3 py-1 text-red-700"

>

{{statusText(leave.status)}}

</span>




<span

v-else

class="rounded bg-yellow-100 px-3 py-1 text-yellow-700"

>

{{statusText(leave.status)}}

</span>



</td>








<td class="p-4 space-x-2">



<button

v-if="leave.status==='pending'"

@click="approve(leave.id)"

class="rounded bg-green-600 px-3 py-1 text-white"

>

تایید

</button>





<button

v-if="leave.status==='pending'"

@click="reject(leave.id)"

class="rounded bg-red-600 px-3 py-1 text-white"

>

رد

</button>




</td>





</tr>





</tbody>



</table>



</div>






<div class="flex justify-center gap-2 border-t p-4">


<Link

v-for="link in leaves.links"

:key="link.label"

:href="link.url ?? ''"

v-html="link.label"

class="rounded px-3 py-2"

:class="{

'bg-blue-600 text-white':link.active,

'bg-gray-100':!link.active

}"

>



</Link>



</div>






</div>


</div>





</AuthenticatedLayout>





</template>