<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

interface Employee {

    id: number;

    first_name: string;

    last_name: string;

    employee_code: string;

}

const props = defineProps<{

    employees: Employee[];

}>();


const form = useForm({

    employee_id: '',

    type: 'annual',

    from_date: '',

    to_date: '',

    days: 1,

    reason: '',

});


watch(

    () => [

        form.from_date,

        form.to_date,

    ],

    () => {

        if (
            form.from_date &&
            form.to_date
        ) {

            const from = new Date(form.from_date);

            const to = new Date(form.to_date);

            const diff = Math.ceil(

                (

                    to.getTime() -

                    from.getTime()

                )

                /

                (1000 * 60 * 60 * 24)

            ) + 1;

            form.days = diff > 0
                ? diff
                : 1;

        }

    }

);


function submit() {

    form.post(

        route('leaves.store')

    );

}

</script>

<template>

<Head title="ثبت مرخصی" />

<AuthenticatedLayout>

<template #header>

<h2 class="text-xl font-semibold">

ثبت مرخصی جدید

</h2>

</template>

<div class="p-6">

<form

    @submit.prevent="submit"

    class="space-y-6 rounded-lg bg-white p-6 shadow"

>

<div>

<label class="mb-2 block">

کارمند

</label>

<select

    v-model="form.employee_id"

    class="w-full rounded-lg border px-4 py-2"

>

<option value="">

انتخاب کنید

</option>

<option

    v-for="employee in employees"

    :key="employee.id"

    :value="employee.id"

>

{{ employee.employee_code }}

-

{{ employee.first_name }}

{{ employee.last_name }}

</option>

</select>

<div

    v-if="form.errors.employee_id"

    class="mt-1 text-sm text-red-600"

>

{{ form.errors.employee_id }}

</div>

</div>



<div>

<label class="mb-2 block">

نوع مرخصی

</label>

<select

    v-model="form.type"

    class="w-full rounded-lg border px-4 py-2"

>

<option value="annual">

استحقاقی

</option>

<option value="sick">

استعلاجی

</option>

<option value="mission">

ماموریت

</option>

<option value="without_salary">

بدون حقوق

</option>

</select>

<div

    v-if="form.errors.type"

    class="mt-1 text-sm text-red-600"

>

{{ form.errors.type }}

</div>

</div>



<div class="grid grid-cols-2 gap-5">

<div>

<label class="mb-2 block">

از تاریخ

</label>

<input

    type="date"

    v-model="form.from_date"

    class="w-full rounded-lg border px-4 py-2"

/>

<div

    v-if="form.errors.from_date"

    class="mt-1 text-sm text-red-600"

>

{{ form.errors.from_date }}

</div>

</div>



<div>

<label class="mb-2 block">

تا تاریخ

</label>

<input

    type="date"

    v-model="form.to_date"

    class="w-full rounded-lg border px-4 py-2"

/>

<div

    v-if="form.errors.to_date"

    class="mt-1 text-sm text-red-600"

>

{{ form.errors.to_date }}

</div>

</div>

</div>



<div>

<label class="mb-2 block">

تعداد روز

</label>

<input

    readonly

    v-model="form.days"

    class="w-full rounded-lg border bg-gray-100 px-4 py-2"

/>

</div>



<div>

<label class="mb-2 block">

دلیل مرخصی

</label>

<textarea

    rows="5"

    v-model="form.reason"

    class="w-full rounded-lg border px-4 py-2"

/>

<div

    v-if="form.errors.reason"

    class="mt-1 text-sm text-red-600"

>

{{ form.errors.reason }}

</div>

</div>



<div class="flex justify-end">

<button

    :disabled="form.processing"

    class="rounded-lg bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700 disabled:opacity-50"

>

ثبت مرخصی

</button>

</div>

</form>

</div>

</AuthenticatedLayout>

</template>