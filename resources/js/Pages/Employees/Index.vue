<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';



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

    national_code: string;

    mobile?: string | null;

    status: string;

    department_id: number;

    position_id: number;

    department?: Department;

    position?: Position;

}



interface PaginationLink {

    url: string | null;

    label: string;

    active: boolean;

}



interface EmployeesData {

    data: Employee[];

    links: PaginationLink[];

}



const props = defineProps<{

    employees: EmployeesData;

}>();





function deleteEmployee(id: number) {


    if (!confirm('آیا از حذف این پرسنل مطمئن هستید؟')) {

        return;

    }



    router.delete(
        route('employees.destroy', id),
        {
            preserveScroll: true,
        }
    );

}



</script>



<template>


<Head title="مدیریت پرسنل" />



<AuthenticatedLayout>



    <template #header>


        <h2 class="text-xl font-semibold text-gray-800">

            مدیریت پرسنل

        </h2>


    </template>





    <div class="p-6">



        <div class="rounded-lg bg-white shadow">



            <div class="flex items-center justify-between border-b p-6">


                <h3 class="text-lg font-semibold">

                    لیست کارکنان

                </h3>




                <Link

                    :href="route('employees.create')"

                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"

                >

                    افزودن پرسنل

                </Link>


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

                                کد ملی

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




                            <th class="pb-3">

                                عملیات

                            </th>



                        </tr>


                    </thead>






                    <tbody>



                        <tr

                            v-for="employee in employees.data"

                            :key="employee.id"

                            class="border-b hover:bg-gray-50"

                        >




                            <td class="py-4">

                                {{ employee.employee_code }}

                            </td>





                            <td class="py-4">


                                {{ employee.first_name }}

                                {{ employee.last_name }}


                            </td>





                            <td class="py-4">

                                {{ employee.national_code }}

                            </td>





                            <td class="py-4">


                                {{ employee.department?.name ?? '-' }}


                            </td>





                            <td class="py-4">


                                {{ employee.position?.name ?? '-' }}


                            </td>






                            <td class="py-4">



                                <span

                                    v-if="employee.status === 'active'"

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







                            <td class="py-4 space-x-2">



                                <Link

                                    :href="route('employees.edit', employee.id)"

                                    class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600"

                                >

                                    ویرایش

                                </Link>





                                <button

                                    @click="deleteEmployee(employee.id)"

                                    class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700"

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

                    v-for="link in employees.links"

                    :key="link.label"

                    :href="link.url ?? ''"

                    v-html="link.label"

                    class="rounded px-3 py-2 text-sm"

                    :class="{

                        'bg-blue-600 text-white': link.active,

                        'bg-gray-100': !link.active

                    }"

                />



            </div>




        </div>



    </div>




</AuthenticatedLayout>



</template>