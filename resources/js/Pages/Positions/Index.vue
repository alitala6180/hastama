<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';


interface Department {
    id:number;
    name:string;
}


interface Position {

    id:number;
    name:string;
    description:string|null;
    is_active:boolean;
    department?:Department;

}



interface PaginationLink {

    url:string|null;
    label:string;
    active:boolean;

}



interface PositionsData {

    data:Position[];
    links:PaginationLink[];

}



const props = defineProps<{

    positions:PositionsData;

    filters:{
        search:string|null;
    }

}>();



const search = ref(props.filters.search ?? '');



function searchPosition(){

    router.get(

        route('positions.index'),

        {
            search:search.value
        },

        {
            preserveState:true,
            replace:true
        }

    );

}



function deletePosition(id:number){

    if(confirm('آیا از حذف این سمت شغلی اطمینان دارید؟')){


        router.delete(
            route('positions.destroy',id)
        );


    }

}



</script>




<template>


<Head title="مدیریت سمت‌های شغلی"/>



<AuthenticatedLayout>



<template #header>

<h2 class="text-xl font-semibold text-gray-800">

مدیریت سمت‌های شغلی

</h2>

</template>




<div class="p-6">



<div class="rounded-lg bg-white shadow">



<div class="flex items-center justify-between border-b p-6">


<h3 class="text-lg font-semibold">

لیست سمت‌های شغلی

</h3>



<Link

:href="route('positions.create')"

class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"

>

افزودن سمت

</Link>



</div>





<div class="flex gap-3 p-6">


<input

v-model="search"

@keyup.enter="searchPosition"

type="text"

placeholder="جستجوی سمت..."

class="w-80 rounded border px-3 py-2"

/>



<button

@click="searchPosition"

class="rounded bg-gray-800 px-5 py-2 text-white"

>

جستجو

</button>


</div>





<div class="overflow-x-auto p-6">



<table class="w-full text-right">



<thead>


<tr class="border-b text-sm text-gray-500">


<th class="pb-3">

نام سمت

</th>



<th class="pb-3">

واحد سازمانی

</th>



<th class="pb-3">

توضیحات

</th>



<th class="pb-3">

وضعیت

</th>



<th class="pb-3">

عملیات

</th>



</tr>


</thead>






<tbody>



<tr

v-for="position in positions.data"

:key="position.id"

class="border-b hover:bg-gray-50"

>



<td class="py-4">

{{ position.name }}

</td>




<td class="py-4">

{{ position.department?.name ?? '-' }}

</td>




<td class="py-4">

{{ position.description ?? '-' }}

</td>




<td class="py-4">


<span

v-if="position.is_active"

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





<td class="py-4 flex gap-2">


<Link

:href="route('positions.edit',position.id)"

class="rounded bg-yellow-500 px-3 py-1 text-white"

>

ویرایش

</Link>




<button

@click="deletePosition(position.id)"

class="rounded bg-red-600 px-3 py-1 text-white"

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

v-for="link in positions.links"

:key="link.label"

:href="link.url ?? ''"

v-html="link.label"

class="rounded px-3 py-2 text-sm"

:class="{

'bg-blue-600 text-white':link.active,

'bg-gray-100':!link.active

}"

/>



</div>





</div>



</div>




</AuthenticatedLayout>



</template>