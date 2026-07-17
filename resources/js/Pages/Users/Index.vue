<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Role {
    id:number;
    name:string;
}

interface Employee{
    id:number;
    first_name:string;
    last_name:string;
}

interface User{
    id:number;
    name:string;
    email:string;
    employee?:Employee;
    roles:Role[];
}

interface PaginationLink{
    url:string|null;
    label:string;
    active:boolean;
}

interface UsersData{
    data:User[];
    links:PaginationLink[];
}

const props = defineProps<{

    users:UsersData;

    filters:{
        search:string|null;
    }

}>();

const search = ref(
    props.filters.search ?? ''
);

watch(search,(value)=>{

    router.get(
        route('users.index'),
        {
            search:value
        },
        {
            preserveState:true,
            replace:true
        }
    );

});

function destroy(id:number){

    if(
        confirm('آیا از حذف این کاربر مطمئن هستید؟')
    ){

        router.delete(
            route('users.destroy',id)
        );

    }

}
</script>

<template>

<Head title="مدیریت کاربران"/>

<AuthenticatedLayout>

<template #header>

<h2 class="text-xl font-semibold">
مدیریت کاربران
</h2>

</template>

<div class="p-6">

<div class="rounded-lg bg-white shadow">

<div class="flex items-center justify-between border-b p-6">

<input

v-model="search"

placeholder="جستجوی کاربر..."

class="rounded-lg border px-4 py-2 w-72"

/>

<Link

:href="route('users.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white"

>

کاربر جدید

</Link>

</div>

<div class="overflow-x-auto">

<table class="w-full text-right">

<thead>

<tr class="border-b bg-gray-50">

<th class="p-4">
نام
</th>

<th class="p-4">
ایمیل
</th>

<th class="p-4">
پرسنل
</th>

<th class="p-4">
نقش
</th>

<th class="p-4">
عملیات
</th>

</tr>

</thead>

<tbody>

<tr

v-for="user in users.data"

:key="user.id"

class="border-b"

>

<td class="p-4">

{{ user.name }}

</td>

<td class="p-4">

{{ user.email }}

</td>

<td class="p-4">

{{ user.employee
? user.employee.first_name+' '+user.employee.last_name
: '-'
}}

</td>

<td class="p-4">

{{ user.roles.length
? user.roles[0].name
: '-'
}}

</td>

<td class="p-4 space-x-2">

<Link

:href="route('users.edit',user.id)"

class="rounded bg-yellow-500 px-3 py-2 text-white"

>

ویرایش

</Link>

<button

@click="destroy(user.id)"

class="rounded bg-red-600 px-3 py-2 text-white"

>

حذف

</button>

</td>

</tr>

</tbody>

</table>

</div>

<div class="flex justify-center gap-2 p-5">

<Link

v-for="link in users.links"

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