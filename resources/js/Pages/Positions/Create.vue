<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';


interface Department {

    id:number;
    name:string;

}


const props = defineProps<{

    departments:Department[];

}>();



const form = useForm({

    department_id:'',

    name:'',

    description:'',

    is_active:true,

});




function submit(){

    form.post(
        route('positions.store')
    );

}


</script>




<template>


<Head title="افزودن سمت شغلی"/>



<AuthenticatedLayout>



<template #header>

<h2 class="text-xl font-semibold text-gray-800">

افزودن سمت شغلی

</h2>

</template>





<div class="p-6">



<form

@submit.prevent="submit"

class="space-y-5 rounded-lg bg-white p-6 shadow"

>



<div class="grid grid-cols-2 gap-5">



<div>


<label class="mb-2 block">

واحد سازمانی

</label>



<select

v-model="form.department_id"

class="w-full rounded border"

>


<option value="">

انتخاب واحد

</option>



<option

v-for="department in props.departments"

:key="department.id"

:value="department.id"

>

{{ department.name }}

</option>



</select>


</div>






<div>


<label class="mb-2 block">

نام سمت

</label>



<input

v-model="form.name"

class="w-full rounded border"

placeholder="مثلا مدیر سیستم"

/>


</div>






<div class="col-span-2">


<label class="mb-2 block">

توضیحات

</label>



<textarea

v-model="form.description"

class="w-full rounded border"

rows="4"

placeholder="توضیحات سمت"

></textarea>



</div>






<div class="flex items-center gap-2">


<input

id="active"

type="checkbox"

v-model="form.is_active"

/>


<label for="active">

فعال

</label>



</div>



</div>






<div>


<button

:disabled="form.processing"

class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700"

>

ثبت سمت

</button>

</div>






</form>



</div>




</AuthenticatedLayout>



</template>