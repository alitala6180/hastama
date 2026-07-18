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

    department_id:null as number|null,

    position_id:null as number|null,

    shift_id:null as number|null,

    hire_date:'',

    status:'active',


});







function submit()
{

    form.post(

        route('employees.store'),

        {

            preserveScroll:true,

        }

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

class="w-full rounded border-gray-300"

/>


<p

v-if="form.errors.employee_code"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.employee_code }}

</p>


</div>







<div>

<label class="mb-2 block">

نام

</label>


<input

v-model="form.first_name"

class="w-full rounded border-gray-300"

/>


<p

v-if="form.errors.first_name"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.first_name }}

</p>


</div>








<div>

<label class="mb-2 block">

نام خانوادگی

</label>


<input

v-model="form.last_name"

class="w-full rounded border-gray-300"

/>


<p

v-if="form.errors.last_name"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.last_name }}

</p>


</div>








<div>

<label class="mb-2 block">

کد ملی

</label>


<input

v-model="form.national_code"

class="w-full rounded border-gray-300"

/>


</div>








<div>

<label class="mb-2 block">

موبایل

</label>


<input

v-model="form.mobile"

class="w-full rounded border-gray-300"

/>


</div>








<div>

<label class="mb-2 block">

تاریخ استخدام

</label>


<input

type="date"

v-model="form.hire_date"

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


<option :value="null">

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


<option :value="null">

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









<div>

<label class="mb-2 block">

شیفت کاری

</label>



<select

v-model="form.shift_id"

class="w-full rounded border-gray-300"

>


<option :value="null">

بدون شیفت

</option>



<option

v-for="shift in shifts"

:key="shift.id"

:value="shift.id"

>

{{ shift.name }}

({{ shift.start_time }} - {{ shift.end_time }})

</option>



</select>



<p

v-if="form.errors.shift_id"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.shift_id }}

</p>



</div>








<div>

<label class="mb-2 block">

وضعیت

</label>


<select

v-model="form.status"

class="w-full rounded border-gray-300"

>


<option value="active">

فعال

</option>



<option value="inactive">

غیرفعال

</option>



</select>


</div>






</div>







<div class="flex justify-end">



<button

type="submit"

:disabled="form.processing"

class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 disabled:opacity-50"

>


<span v-if="form.processing">

در حال ذخیره...

</span>


<span v-else>

ذخیره

</span>



</button>




</div>





</form>





</div>



</div>





</AuthenticatedLayout>


</template>