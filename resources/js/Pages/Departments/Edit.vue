<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';



interface Department {

    id:number;
    name:string;
    code:string;
    description:string|null;
    is_active:boolean;

}



const props = defineProps<{

    department:Department;

}>();





const form = useForm({

    name: props.department.name,

    code: props.department.code,

    description: props.department.description ?? '',

    is_active: props.department.is_active,

});





function submit()
{

    form.put(

        route(
            'departments.update',
            props.department.id
        )

    );

}



</script>





<template>


<Head title="ویرایش واحد سازمانی"/>


<AuthenticatedLayout>


<template #header>

<h2 class="text-xl font-semibold text-gray-800">

ویرایش واحد سازمانی

</h2>

</template>





<div class="p-6">



<form

@submit.prevent="submit"

class="space-y-5 rounded-lg bg-white p-6 shadow"

>





<div>


<label class="mb-2 block">

نام واحد

</label>


<input

v-model="form.name"

class="w-full rounded border p-2"

/>



<div

v-if="form.errors.name"

class="mt-1 text-sm text-red-600"

>

{{form.errors.name}}

</div>


</div>







<div>


<label class="mb-2 block">

کد واحد

</label>


<input

v-model="form.code"

class="w-full rounded border p-2"

/>




<div

v-if="form.errors.code"

class="mt-1 text-sm text-red-600"

>

{{form.errors.code}}

</div>



</div>







<div>


<label class="mb-2 block">

توضیحات

</label>



<textarea

v-model="form.description"

class="w-full rounded border p-2"

rows="4"

></textarea>



</div>







<div class="flex items-center gap-3">


<input

type="checkbox"

v-model="form.is_active"

/>


<label>

واحد فعال باشد

</label>


</div>








<button

type="submit"

:disabled="form.processing"

class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"

>

ذخیره تغییرات

</button>





</form>



</div>



</AuthenticatedLayout>



</template>