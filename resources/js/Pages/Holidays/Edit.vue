<script setup lang="ts">

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';




interface Holiday {

    id:number;

    title:string;

    holiday_date:string;

    type:string;

    description:string|null;

    is_active:boolean;

}





const props = defineProps<{

    holiday:Holiday;

}>();






const form = useForm({


    title:props.holiday.title,


    holiday_date:props.holiday.holiday_date,


    type:props.holiday.type,


    description:props.holiday.description ?? '',


    is_active:props.holiday.is_active,


});








function submit()
{


    form.put(


        route(

            'holidays.update',

            props.holiday.id

        ),


        {

            preserveScroll:true

        }


    );


}



</script>






<template>


<Head title="ویرایش تعطیلی"/>






<AuthenticatedLayout>




<template #header>


<h2 class="text-xl font-semibold text-gray-800">

ویرایش تعطیلی

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

class="rounded-lg bg-blue-600 px-6 py-3 text-white"

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