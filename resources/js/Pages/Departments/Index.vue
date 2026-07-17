<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';


interface Department {

    id:number;
    name:string;
    code:string;
    description:string|null;
    is_active:boolean;

}


interface PaginationLink {

    url:string|null;
    label:string;
    active:boolean;

}


interface DepartmentsData {

    data:Department[];
    links:PaginationLink[];

}



const props = defineProps<{

    departments:DepartmentsData;

    filters:{
        search:string|null;
    };

}>();



function deleteDepartment(id:number)
{

    if(confirm('آیا از حذف این واحد سازمانی مطمئن هستید؟'))
    {

        router.delete(
            route(
                'departments.destroy',
                id
            )
        );

    }

}


</script>



<template>


<Head title="واحدهای سازمانی"/>


<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold text-gray-800">
مدیریت واحدهای سازمانی
</h2>

</template>



<div class="p-6">


<div class="rounded-lg bg-white shadow">



<div class="flex items-center justify-between border-b p-6">


<h3 class="text-lg font-semibold">
لیست واحدها
</h3>



<Link

href="/departments/create"

class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"

>

افزودن واحد

</Link>


</div>




<div class="overflow-x-auto p-6">


<table class="w-full text-right">


<thead>

<tr class="border-b text-sm text-gray-500">


<th class="pb-3">
کد
</th>


<th class="pb-3">
نام واحد
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

v-for="department in departments.data"

:key="department.id"

class="border-b hover:bg-gray-50"

>


<td class="py-4">

{{department.code}}

</td>



<td class="py-4">

{{department.name}}

</td>




<td class="py-4">

{{department.description ?? '-'}}

</td>




<td class="py-4">


<span

v-if="department.is_active"

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





<td class="py-4 space-x-2 space-x-reverse">


<Link

:href="route('departments.edit', department.id)"

class="rounded bg-yellow-500 px-3 py-1 text-white"

>

ویرایش

</Link>




<button

@click="deleteDepartment(department.id)"

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

v-for="link in departments.links"

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