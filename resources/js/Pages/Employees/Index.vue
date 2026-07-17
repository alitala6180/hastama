<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    employees: {
        data: Array<{
            id: number;
            employee_code: string;
            first_name: string;
            last_name: string;
            national_code: string;
            status: string;
            department?: {
                name: string;
            };
            position?: {
                name: string;
            };
        }>;
    };
}>();
</script>


<template>
    <Head title="کارکنان" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                مدیریت کارکنان
            </h2>
        </template>


        <div class="py-12">

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <table class="w-full border-collapse">

                            <thead>
                                <tr class="border-b">
                                    <th class="p-3 text-right">
                                        کد پرسنلی
                                    </th>

                                    <th class="p-3 text-right">
                                        نام
                                    </th>

                                    <th class="p-3 text-right">
                                        نام خانوادگی
                                    </th>

                                    <th class="p-3 text-right">
                                        واحد
                                    </th>

                                    <th class="p-3 text-right">
                                        سمت
                                    </th>

                                    <th class="p-3 text-right">
                                        وضعیت
                                    </th>
                                </tr>
                            </thead>


                            <tbody>

                                <tr
                                    v-for="employee in employees.data"
                                    :key="employee.id"
                                    class="border-b"
                                >

                                    <td class="p-3">
                                        {{ employee.employee_code }}
                                    </td>


                                    <td class="p-3">
                                        {{ employee.first_name }}
                                    </td>


                                    <td class="p-3">
                                        {{ employee.last_name }}
                                    </td>


                                    <td class="p-3">
                                        {{ employee.department?.name ?? '-' }}
                                    </td>


                                    <td class="p-3">
                                        {{ employee.position?.name ?? '-' }}
                                    </td>


                                    <td class="p-3">
                                        {{ employee.status }}
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