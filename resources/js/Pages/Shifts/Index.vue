<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



interface Shift {

    id:number;

    name:string;

    start_time:string;

    end_time:string;

    description:string|null;

    is_active:boolean;

}



interface PaginationLink {

    url:string|null;

    label:string;

    active:boolean;

}



interface ShiftsData {

    data:Shift[];

    links:PaginationLink[];

}



const props = defineProps<{

    shifts:ShiftsData;

}>();






function destroy(id:number)
{

    if(
        !confirm('آیا از حذف این شیفت مطمئن هستید؟')
    ){

        return;

    }



    router.delete(

        route(
            'shifts.destroy',
            id
        )

    );

}



</script>





<template>


<Head title="مدیریت شیفت‌ها"/>





<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

مدیریت شیفت‌ها

</h2>


</template>







<div class="p-6">



<div class="rounded-lg bg-white shadow">






<div class="flex items-center justify-between border-b p-6">


<h3 class="text-lg font-semibold">

لیست شیفت‌های کاری

</h3>





<Link

:href="route('shifts.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white"

>

➕ شیفت جدید

</Link>



</div>







<div class="overflow-x-auto p-6">



<table class="w-full text-right">



<thead>


<tr class="border-b bg-gray-50">


<th class="p-4">

نام

</th>


<th class="p-4">

شروع

</th>


<th class="p-4">

پایان

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

v-for="shift in shifts.data"

:key="shift.id"

class="border-b hover:bg-gray-50"

>



<td class="p-4 font-semibold">

{{ shift.name }}

</td>




<td class="p-4">

{{ shift.start_time }}

</td>




<td class="p-4">

{{ shift.end_time }}

</td>





<td class="p-4">


<span

v-if="shift.is_active"

class="rounded bg-green-100 px-3 py-1 text-green-700"

>

فعال

</span>



<span

v-else

class="rounded bg-red-100 px-3 py-1 text-red-700"

>

غیرفعال

</span>



</td>





<td class="p-4 space-x-2">



<Link

:href="route('shifts.edit',shift.id)"

class="rounded bg-yellow-500 px-3 py-2 text-white"

>

ویرایش

</Link>





<button

@click="destroy(shift.id)"

class="rounded bg-red-600 px-3 py-2 text-white"

>

حذف

</button>




</td>





</tr>




</tbody>





</table>





</div>







<div class="flex justify-center gap-2 border-t p-4">


<Link

v-for="link in shifts.links"

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




</div>






</AuthenticatedLayout>




</template>