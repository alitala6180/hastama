<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



interface Holiday {

    id:number;

    title:string;

    holiday_date:string;

    type:string;

    description:string|null;

    is_active:boolean;

}



interface PaginationLink {

    url:string|null;

    label:string;

    active:boolean;

}



interface HolidaysData {

    data:Holiday[];

    links:PaginationLink[];

}



const props = defineProps<{

    holidays:HolidaysData;

}>();







function destroy(id:number)
{


    if(
        !confirm('آیا از حذف این تعطیلی مطمئن هستید؟')
    ){

        return;

    }




    router.delete(

        route(
            'holidays.destroy',
            id
        ),

        {

            preserveScroll:true

        }

    );


}







function typeName(type:string)
{

    switch(type){

        case 'official':

            return 'رسمی';


        case 'weekly':

            return 'هفتگی';


        case 'company':

            return 'سازمانی';


        default:

            return '-';

    }

}



</script>





<template>


<Head title="مدیریت تعطیلات"/>




<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

مدیریت تعطیلات

</h2>


</template>








<div class="p-6">



<div class="rounded-lg bg-white shadow">






<div class="flex items-center justify-between border-b p-6">



<h3 class="text-lg font-semibold">

لیست تعطیلات

</h3>






<Link

:href="route('holidays.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"

>

➕ تعطیلی جدید

</Link>



</div>










<div class="overflow-x-auto p-6">



<table class="w-full text-right">



<thead>


<tr class="border-b bg-gray-50 text-sm text-gray-600">


<th class="p-4">

عنوان

</th>


<th class="p-4">

تاریخ

</th>


<th class="p-4">

نوع

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

v-for="holiday in holidays.data"

:key="holiday.id"

class="border-b hover:bg-gray-50"

>




<td class="p-4 font-semibold">

{{ holiday.title }}

</td>





<td class="p-4">

{{ holiday.holiday_date }}

</td>





<td class="p-4">

{{ typeName(holiday.type) }}

</td>






<td class="p-4">



<span

v-if="holiday.is_active"

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

:href="route('holidays.edit',holiday.id)"

class="rounded bg-yellow-500 px-3 py-2 text-white"

>

ویرایش

</Link>






<button

@click="destroy(holiday.id)"

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

v-for="link in holidays.links"

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