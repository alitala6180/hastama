<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';




const form = useForm({

    title:'',

    holiday_date:'',

    type:'official',

    description:'',

    is_active:true,

});






function submit()
{

    form.post(

        route('holidays.store')

    );

}



</script>





<template>


<Head title="افزودن تعطیلی"/>





<AuthenticatedLayout>



<template #header>


<h2 class="text-xl font-semibold text-gray-800">

افزودن تعطیلی جدید

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

عنوان تعطیلی

</label>



<input

v-model="form.title"

type="text"

class="w-full rounded border-gray-300"

/>





<div

v-if="form.errors.title"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.title }}

</div>



</div>









<div class="grid grid-cols-2 gap-5">





<div>


<label class="mb-2 block">

تاریخ تعطیلی

</label>



<input

v-model="form.holiday_date"

type="date"

class="w-full rounded border-gray-300"

/>





<div

v-if="form.errors.holiday_date"

class="mt-1 text-sm text-red-600"

>

{{ form.errors.holiday_date }}

</div>



</div>








<div>


<label class="mb-2 block">

نوع تعطیلی

</label>



<select

v-model="form.type"

class="w-full rounded border-gray-300"

>



<option value="official">

رسمی

</option>



<option value="weekly">

هفتگی

</option>



<option value="company">

سازمانی

</option>



</select>



</div>







</div>









<div>


<label class="mb-2 block">

توضیحات

</label>



<textarea

v-model="form.description"

rows="4"

class="w-full rounded border-gray-300"

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

class="rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 disabled:opacity-50"

>


<span v-if="form.processing">

در حال ذخیره...

</span>


<span v-else>

ذخیره تعطیلی

</span>



</button>



</div>









</form>






</div>





</div>








</AuthenticatedLayout>






</template>