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



interface Employee {

    id:number;

    employee_code:string;

    first_name:string;

    last_name:string;

    national_code:string;

    mobile:string|null;

    department_id:number;

    position_id:number;

    shift_id:number|null;

    hire_date:string|null;

    status:string;

}





const props = defineProps<{

    employee:Employee;

    departments:Department[];

    positions:Position[];

    shifts:Shift[];

}>();







const form = useForm({


    employee_code:props.employee.employee_code,


    first_name:props.employee.first_name,


    last_name:props.employee.last_name,


    national_code:props.employee.national_code,


    mobile:props.employee.mobile ?? '',


    department_id:props.employee.department_id,


    position_id:props.employee.position_id,


    shift_id:props.employee.shift_id ?? '',


    hire_date:props.employee.hire_date ?? '',


    status:props.employee.status,


});








function submit()
{


    form.put(

        route(

            'employees.update',

            props.employee.id

        ),

        {

            preserveScroll:true,

        }

    );


}



</script>





<template>


<Head title="ویرایش پرسنل"/>



<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

ویرایش پرسنل

</h2>


</template>







<div class="p-6">



<div

v-if="Object.keys(form.errors).length"

class="mb-5 rounded-lg bg-red-100 p-4 text-red-700"

>


<ul>


<li

v-for="error in form.errors"

:key="error"

>

{{ error }}

</li>


</ul>


</div>







<form

@submit.prevent="submit"

class="space-y-5 rounded-lg bg-white p-6 shadow"

>





<div class="grid grid-cols-2 gap-5">






<div>

<label>
کد پرسنلی
</label>

<input

v-model="form.employee_code"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
نام
</label>

<input

v-model="form.first_name"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
نام خانوادگی
</label>

<input

v-model="form.last_name"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
کد ملی
</label>

<input

v-model="form.national_code"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
موبایل
</label>

<input

v-model="form.mobile"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
تاریخ استخدام
</label>

<input

type="date"

v-model="form.hire_date"

class="w-full rounded border p-2"

/>

</div>







<div>

<label>
واحد سازمانی
</label>



<select

v-model="form.department_id"

class="w-full rounded border p-2"

>


<option

v-for="item in departments"

:key="item.id"

:value="item.id"

>

{{ item.name }}

</option>


</select>


</div>







<div>

<label>
سمت شغلی
</label>



<select

v-model="form.position_id"

class="w-full rounded border p-2"

>


<option

v-for="item in positions"

:key="item.id"

:value="item.id"

>

{{ item.name }}

</option>


</select>


</div>








<!-- Shift -->

<div>

<label>
شیفت کاری
</label>



<select

v-model="form.shift_id"

class="w-full rounded border p-2"

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









<div>

<label>
وضعیت
</label>



<select

v-model="form.status"

class="w-full rounded border p-2"

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







<button

type="submit"

:disabled="form.processing"

class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700"

>


<span v-if="form.processing">

در حال ذخیره...

</span>


<span v-else>

ذخیره تغییرات

</span>



</button>







</form>





</div>




</AuthenticatedLayout>




</template>