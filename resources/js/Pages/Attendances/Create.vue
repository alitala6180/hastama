<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';


interface Employee{

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

}



const props = defineProps<{

    employees:Employee[];

}>();



const form = useForm({

    employee_id:'',

    work_date:'',

    check_in:'',

    check_out:'',

});



function submit(){

    form.post(
        route('attendances.store')
    );

}


</script>



<template>


<Head title="ثبت حضور و غیاب"/>


<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold">

ثبت حضور و غیاب

</h2>

</template>



<div class="p-6">


<form

@submit.prevent="submit"

class="space-y-5 rounded-lg bg-white p-6 shadow"

>


<div>


<label>
پرسنل
</label>


<select

v-model="form.employee_id"

class="mt-2 w-full rounded border"

>


<option value="">

انتخاب پرسنل

</option>


<option

v-for="employee in props.employees"

:key="employee.id"

:value="employee.id"

>

{{employee.first_name}}

{{employee.last_name}}

-

{{employee.employee_code}}

</option>


</select>


</div>



<div>


<label>
تاریخ
</label>


<input

type="date"

v-model="form.work_date"

class="mt-2 w-full rounded border"

/>


</div>



<div class="grid grid-cols-2 gap-5">


<div>


<label>
ساعت ورود
</label>


<input

type="time"

v-model="form.check_in"

class="mt-2 w-full rounded border"

/>


</div>



<div>


<label>
ساعت خروج
</label>


<input

type="time"

v-model="form.check_out"

class="mt-2 w-full rounded border"

/>


</div>


</div>




<button

:disabled="form.processing"

class="rounded bg-blue-600 px-6 py-2 text-white"

>

ثبت حضور

</button>


</form>


</div>


</AuthenticatedLayout>


</template>