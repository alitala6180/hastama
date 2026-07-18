<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';



interface Department {

    id:number;

    name:string;

}



interface Position {

    id:number;

    name:string;

}



interface Shift {

    id:number;

    name:string;

    start_time:string;

    end_time:string;

}



const props = defineProps<{

    departments:Department[];

    positions:Position[];

    shifts:Shift[];

}>();





const form = useForm({


    employee_code:'',

    first_name:'',

    last_name:'',

    national_code:'',

    mobile:'',

    department_id:'',

    position_id:'',

    shift_id:'',

    hire_date:'',

    status:'active',


});





function submit()
{

    form.post(
        route('employees.store')
    );

}



</script>




<template>


<Head title="افزودن پرسنل" />



<AuthenticatedLayout>



<template #header>

<h2 class="text-xl font-semibold text-gray-800">

افزودن پرسنل جدید

</h2>

</template>





<div class="p-6">



<div class="rounded-lg bg-white p-6 shadow">



<form

@submit.prevent="submit"

class="space-y-6"

>




<div class="grid grid-cols-1 gap-6 md:grid-cols-2">





<div>

<label class="mb-2 block">

کد پرسنلی

</label>


<input

v-model="form.employee_code"

type="text"

class="w-full rounded border-gray-300"

/>


<div
v-if="form.errors.employee_code"
class="mt-1 text-sm text-red-600"
>

{{ form.errors.employee_code }}

</div>


</div>






<div>

<label class="mb-2 block">

نام

</label>


<input

v-model="form.first_name"

type="text"

class="w-full rounded border-gray-300"

/>


</div>






<div>

<label class="mb-2 block">

نام خانوادگی

</label>


<input

v-model="form.last_name"

type="text"

class="w-full rounded border-gray-300"

/>


</div>






<div>

<label class="mb-2 block">

کد ملی

</label>


<input

v-model="form.national_code"

type="text"

class="w-full rounded border-gray-300"

/>


</div>






<div>

<label class="mb-2 block">

موبایل

</label>


<input

v-model="form.mobile"

type="text"

class="w-full rounded border-gray-300"

/>


</div>






<div>

<label class="mb-2 block">

تاریخ استخدام

</label>


<input

v-model="form.hire_date"

type="date"

class="w-full rounded border-gray-300"

/>


</div>







<div>

<label class="mb-2 block">

واحد سازمانی

</label>


<select

v-model="form.department_id"

class="w-full rounded border-gray-300"

>


<option value="">

انتخاب کنید

</option>


<option

v-for="department in departments"

:key="department.id"

:value="department.id"

>

{{ department.name }}

</option>


</select>


</div>







<div>

<label class="mb-2 block">

سمت شغلی

</label>


<select

v-model="form.position_id"

class="w-full rounded border-gray-300"

>


<option value="">

انتخاب کنید

</option>


<option

v-for="position in positions"

:key="position.id"

:value="position.id"

>

{{ position.name }}

</option>


</select>


</div>








<!-- Shift -->


<div>


<label class="mb-2 block">

شیفت کاری

</label>



<select

v-model="form.shift_id"

class="w-full rounded border-gray-300"

>


<option value="">

انتخاب کنید

</option>



<option

v-for="shift in shifts"

:key="shift.id"

:value="shift.id"

>


{{ shift.name }}

-

{{ shift.start_time }}

تا

{{ shift.end_time }}


</option>



</select>



<div

v-if="form.errors.shift_id"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.shift_id }}

</div>



</div>






</div>







<div class="flex justify-end">


<button

type="submit"

:disabled="form.processing"

class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 disabled:opacity-50"

>

ذخیره

</button>



</div>






</form>




</div>



</div>




</AuthenticatedLayout>


</template>