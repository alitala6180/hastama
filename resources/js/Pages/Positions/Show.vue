<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; import { Head, Link } from '@inertiajs/vue3';
const props = defineProps<{ position: { id:number; name:string; description:string|null; is_active:boolean; department:{name:string} } }>();
</script>
<template><Head title="جزئیات سمت"/><AuthenticatedLayout><template #header><h2 class="text-xl font-semibold">جزئیات سمت شغلی</h2></template><div class="max-w-2xl rounded-lg bg-white p-6 shadow"><dl class="grid grid-cols-2 gap-5"><dt>نام سمت</dt><dd>{{props.position.name}}</dd><dt>واحد</dt><dd>{{props.position.department.name}}</dd><dt>وضعیت</dt><dd>{{props.position.is_active ? 'فعال' : 'غیرفعال'}}</dd><dt>توضیحات</dt><dd>{{props.position.description ?? '-'}}</dd></dl><Link :href="route('positions.edit',props.position.id)" class="mt-6 inline-block rounded bg-blue-600 px-4 py-2 text-white">ویرایش</Link></div></AuthenticatedLayout></template>
