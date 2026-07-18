<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; import { Head, Link } from '@inertiajs/vue3';
const props = defineProps<{ department: { id:number; name:string; code:string; description:string|null; is_active:boolean } }>();
</script>
<template><Head title="جزئیات واحد"/><AuthenticatedLayout><template #header><h2 class="text-xl font-semibold">جزئیات واحد سازمانی</h2></template><div class="max-w-2xl rounded-lg bg-white p-6 shadow"><dl class="grid grid-cols-2 gap-5"><dt>نام</dt><dd>{{props.department.name}}</dd><dt>کد</dt><dd>{{props.department.code}}</dd><dt>وضعیت</dt><dd>{{props.department.is_active ? 'فعال' : 'غیرفعال'}}</dd><dt>توضیحات</dt><dd>{{props.department.description ?? '-'}}</dd></dl><Link :href="route('departments.edit',props.department.id)" class="mt-6 inline-block rounded bg-blue-600 px-4 py-2 text-white">ویرایش</Link></div></AuthenticatedLayout></template>
