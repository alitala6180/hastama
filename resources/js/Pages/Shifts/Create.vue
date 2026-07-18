<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';



const form = useForm({

    name:'',

    start_time:'',

    end_time:'',

    description:'',

    is_active:true,

});






function submit()
{

    form.post(

        route('shifts.store')

    );

}



</script>





<template>


<Head title="افزودن شیفت"/>




<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

افزودن شیفت کاری

</h2>


</template>







<div class="p-6">



<div class="rounded-lg bg-white p-6 shadow">






<form

@submit.prevent="submit"

class="space-y-6"

>







<div>


<label class="mb-2 block">

نام شیفت

</label>



<input

v-model="form.name"

type="text"

class="w-full rounded border-gray-300"

/>



<div

v-if="form.errors.name"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.name }}

</div>


</div>









<div class="grid grid-cols-2 gap-5">






<div>


<label class="mb-2 block">

ساعت شروع

</label>



<input

v-model="form.start_time"

type="time"

class="w-full rounded border-gray-300"

/>



</div>








<div>


<label class="mb-2 block">

ساعت پایان

</label>



<input

v-model="form.end_time"

type="time"

class="w-full rounded border-gray-300"

/>



</div>






</div>









<div>


<label class="mb-2 block">

توضیحات

</label>



<textarea

v-model="form.description"

class="w-full rounded border-gray-300"

rows="4"

></textarea>


</div>









<div>


<label class="flex items-center gap-2">


<input

type="checkbox"

v-model="form.is_active"

/>



<span>

فعال باشد

</span>


</label>


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

ذخیره شیفت

</span>



</button>




</div>








</form>








</div>





</div>







</AuthenticatedLayout>






</template>