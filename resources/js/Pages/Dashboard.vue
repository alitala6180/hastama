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
    department?: Department;
    position?: Position;
}


interface Stats {
    employees: number;
    departments: number;
    positions: number;
}


defineProps<{
    stats: Stats;
    latestEmployees: Employee[];
}>();


</script>


<template>

    <Head title="داشبورد" />


    <AuthenticatedLayout>

        <template #header>

            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                داشبورد مدیریت هستما
            </h2>

        </template>


        <div class="py-12">

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">


                <!-- کارت های آماری -->

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">


                    <div class="rounded-lg bg-white p-6 shadow">

                        <div class="text-sm text-gray-500">
                            تعداد کارکنان
                        </div>

                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.employees }}
                        </div>

                    </div>



                    <div class="rounded-lg bg-white p-6 shadow">

                        <div class="text-sm text-gray-500">
                            واحدهای سازمانی
                        </div>

                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.departments }}
                        </div>

                    </div>



                    <div class="rounded-lg bg-white p-6 shadow">

                        <div class="text-sm text-gray-500">
                            سمت های شغلی
                        </div>

                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ stats.positions }}
                        </div>

                    </div>


                </div>



                <!-- آخرین کارکنان -->


                <div class="mt-8 overflow-hidden rounded-lg bg-white shadow">


                    <div class="border-b p-6">

                        <h3 class="text-lg font-semibold text-gray-800">
                            آخرین کارکنان ثبت شده
                        </h3>

                    </div>



                    <div class="p-6">


                        <table class="w-full text-right">


                            <thead>

                                <tr class="border-b text-sm text-gray-500">

                                    <th class="pb-3">
                                        کد پرسنلی
                                    </th>

                                    <th class="pb-3">
                                        نام
                                    </th>

                                    <th class="pb-3">
                                        واحد
                                    </th>

                                    <th class="pb-3">
                                        سمت
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


                                </tr>


                            </tbody>


                        </table>


                    </div>


                </div>


            </div>


        </div>


    </AuthenticatedLayout>


</template>