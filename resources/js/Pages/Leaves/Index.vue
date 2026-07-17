<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Employee{

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

}

interface User{

    id:number;

    name:string;

}

interface Leave{

    id:number;

    type:string;

    from_date:string;

    to_date:string;

    days:number;

    status:string;

    employee:Employee;

    approver?:User;

}

interface PaginationLink{

    url:string|null;

    label:string;

    active:boolean;

}

interface LeavesData{

    data:Leave[];

    links:PaginationLink[];

}

const props = defineProps<{

    leaves:LeavesData;

    filters:{

        status:string|null;

    }

}>();

const status = ref(
    props.filters.status ?? ''
);

watch(status,(value)=>{

    router.get(

        route('leaves.index'),

        {

            status:value

        },

        {

            preserveState:true,

            replace:true

        }

    );

});

function approve(id:number){

    if(confirm('مرخصی تایید شود؟')){

        router.patch(

            route('leaves.approve',id)

        );

    }

}

function reject(id:number){

    if(confirm('مرخصی رد شود؟')){

        router.patch(

            route('leaves.reject',id)

        );

    }

}

</script>

<template>

<Head title="مدیریت مرخصی ها"/>

<AuthenticatedLayout>

<template #header>

<h2 class="text-xl font-semibold">

مدیریت مرخصی ها

</h2>

</template>

<div class="p-6">

<div class="rounded-lg bg-white shadow">

<div class="flex items-center justify-between border-b p-6">

<select

v-model="status"

class="rounded-lg border px-4 py-2"

>

<option value="">

همه وضعیت ها

</option>

<option value="pending">

در انتظار

</option>

<option value="approved">

تایید شده

</option>

<option value="rejected">

رد شده

</option>

</select>

<Link

:href="route('leaves.create')"

class="rounded-lg bg-blue-600 px-5 py-2 text-white"

>

ثبت مرخصی

</Link>

</div>

<div class="overflow-x-auto">

<table class="w-full text-right">

<thead>

<tr class="border-b bg-gray-100">

<th class="p-4">

کارمند

</th>

<th class="p-4">

نوع

</th>

<th class="p-4">

بازه

</th>

<th class="p-4">

روز

</th>

<th class="p-4">

وضعیت

</th>

<th class="p-4">

تایید کننده

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

{{ leave.employee.first_name }}

{{ leave.employee.last_name }}

</td>

<td class="p-4">

{{ leave.type }}

</td>

<td class="p-4">

{{ leave.from_date }}

<br>

{{ leave.to_date }}

</td>

<td class="p-4">

{{ leave.days }}

</td>

<td class="p-4">

<span

v-if="leave.status==='pending'"

class="rounded bg-yellow-100 px-3 py-1 text-yellow-700"

>

در انتظار

</span>

<span

v-else-if="leave.status==='approved'"

class="rounded bg-green-100 px-3 py-1 text-green-700"

>

تایید شده

</span>

<span

v-else

class="rounded bg-red-100 px-3 py-1 text-red-700"

>

رد شده

</span>

</td>

<td class="p-4">

{{ leave.approver?.name ?? '-' }}

</td>

<td class="p-4 space-x-2">

<button

v-if="leave.status==='pending'"

@click="approve(leave.id)"

class="rounded bg-green-600 px-3 py-2 text-white"

>

تایید

</button>

<button

v-if="leave.status==='pending'"

@click="reject(leave.id)"

class="rounded bg-red-600 px-3 py-2 text-white"

>

رد

</button>

</td>

</tr>

</tbody>

</table>

</div>

<div class="flex justify-center gap-2 p-5">

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