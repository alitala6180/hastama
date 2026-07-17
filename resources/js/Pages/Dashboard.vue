<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


interface Department {
    id: number;
    name: string;
}


interface Position {
    id: number;
    name: string;
}


interface Employee {
    id: number;
    employee_code: string;
    first_name: string;
    last_name: string;
    status: string;
    department?: Department;
    position?: Position;
}


interface Stats {
    employees: number;
    activeEmployees: number;
    inactiveEmployees: number;
    departments: number;
    positions: number;
}


interface AuthUser {
    name: string;
    email: string;
}


defineProps<{
    stats: Stats;
    latestEmployees: Employee[];
    authUser: AuthUser;
}>();


</script>


<template>

<Head title="داشبورد" />


<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold text-gray-800">
    داشبورد مدیریت هستما
</h2>

</template>



<div class="space-y-6">


<!-- Welcome -->

<div class="rounded-lg bg-white p-6 shadow">

    <h3 class="text-lg font-semibold text-gray-800">
        خوش آمدید {{ authUser.name }}
    </h3>

    <p class="mt-2 text-sm text-gray-500">
        {{ authUser.email }}
    </p>

</div>



<!-- Statistics -->


<div class="grid grid-cols-1 gap-6 md:grid-cols-5">


<div class="rounded-lg bg-white p-6 shadow">

<p class="text-sm text-gray-500">
کل پرسنل
</p>

<p class="mt-3 text-3xl font-bold">
{{ stats.employees }}
</p>

</div>



<div class="rounded-lg bg-white p-6 shadow">

<p class="text-sm text-gray-500">
پرسنل فعال
</p>

<p class="mt-3 text-3xl font-bold text-green-600">
{{ stats.activeEmployees }}
</p>

</div>



<div class="rounded-lg bg-white p-6 shadow">

<p class="text-sm text-gray-500">
پرسنل غیرفعال
</p>

<p class="mt-3 text-3xl font-bold text-red-600">
{{ stats.inactiveEmployees }}
</p>

</div>



<div class="rounded-lg bg-white p-6 shadow">

<p class="text-sm text-gray-500">
واحد سازمانی
</p>

<p class="mt-3 text-3xl font-bold">
{{ stats.departments }}
</p>

</div>



<div class="rounded-lg bg-white p-6 shadow">

<p class="text-sm text-gray-500">
سمت شغلی
</p>

<p class="mt-3 text-3xl font-bold">
{{ stats.positions }}
</p>

</div>


</div>




<!-- Latest Employees -->


<div class="overflow-hidden rounded-lg bg-white shadow">


<div class="border-b p-6">

<h3 class="text-lg font-semibold text-gray-800">
آخرین پرسنل ثبت شده
</h3>

</div>



<div class="overflow-x-auto p-6">


<table class="w-full text-right">


<thead>

<tr class="border-b text-sm text-gray-500">

<th class="pb-3">
کد پرسنلی
</th>

<th class="pb-3">
نام و نام خانوادگی
</th>

<th class="pb-3">
واحد
</th>

<th class="pb-3">
سمت
</th>

<th class="pb-3">
وضعیت
</th>

</tr>

</thead>



<tbody>


<tr
v-for="employee in latestEmployees"
:key="employee.id"
class="border-b"
>


<td class="py-3">
{{ employee.employee_code }}
</td>



<td class="py-3">
{{ employee.first_name }}
{{ employee.last_name }}
</td>



<td class="py-3">
{{ employee.department?.name ?? '-' }}
</td>



<td class="py-3">
{{ employee.position?.name ?? '-' }}
</td>



<td class="py-3">


<span
:class="
employee.status === 'active'
? 'text-green-600'
: 'text-red-600'
"
>

{{ employee.status === 'active' ? 'فعال' : 'غیرفعال' }}

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