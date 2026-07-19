<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';




interface Employee {

    id:number;

    first_name:string;

    last_name:string;

    employee_code:string;

}





interface Leave {

    id:number;

    employee_id:number;

    type:string;

    from_date:string;

    to_date:string;

    reason:string|null;

}






const props = defineProps<{

    leave:Leave;

    employees:Employee[];

}>();









const form = useForm({


    employee_id:props.leave.employee_id,


    type:props.leave.type,


    from_date:props.leave.from_date,


    to_date:props.leave.to_date,


    reason:props.leave.reason ?? '',



});









function submit()
{


    form.put(

        route(

            'leaves.update',

            props.leave.id

        ),

        {

            preserveScroll:true,

        }

    );


}





</script>








<template>


<Head title="ویرایش مرخصی"/>





<AuthenticatedLayout>




<template #header>


<h2 class="text-xl font-semibold text-gray-800">

ویرایش درخواست مرخصی

</h2>


</template>








<div class="p-6">



<div class="rounded-lg bg-white p-6 shadow">







<div

v-if="Object.keys(form.errors).length"

class="mb-5 rounded bg-red-100 p-4 text-red-700"

>


<ul>


<li

v-for="error in form.errors"

:key="error"

>

{{error}}

</li>


</ul>


</div>









<form

@submit.prevent="submit"

class="space-y-6"

>









<div>


<label class="mb-2 block">

پرسنل

</label>



<select

v-model="form.employee_id"

class="w-full rounded border-gray-300"

>


<option

v-for="employee in employees"

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


<label class="mb-2 block">

نوع مرخصی

</label>



<select

v-model="form.type"

class="w-full rounded border-gray-300"

>


<option value="annual">

استحقاقی

</option>



<option value="sick">

استعلاجی

</option>



<option value="hourly">

ساعتی

</option>



<option value="unpaid">

بدون حقوق

</option>



</select>


</div>









<div class="grid grid-cols-2 gap-5">





<div>


<label class="mb-2 block">

از تاریخ

</label>



<input

type="date"

v-model="form.from_date"

class="w-full rounded border-gray-300"

/>



</div>









<div>


<label class="mb-2 block">

تا تاریخ

</label>



<input

type="date"

v-model="form.to_date"

class="w-full rounded border-gray-300"

/>



</div>





</div>









<div>


<label class="mb-2 block">

دلیل

</label>



<textarea

v-model="form.reason"

rows="4"

class="w-full rounded border-gray-300"

></textarea>


</div>









<div class="flex justify-end">





<button

type="submit"

:disabled="form.processing"

class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700"

>


<span v-if="form.processing">

در حال ذخیره...

</span>


<span v-else>

ذخیره تغییرات

</span>



</button>






</div>









</form>








</div>






</div>






</AuthenticatedLayout>






</template>