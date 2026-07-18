<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { permissionGroups, permissionLabel } from '@/lib/permissions';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps<{ permissions: { id: number; name: string }[] }>();
const form = useForm({ name: '', permissions: [] as string[] });
const groupPermissions = (keys: string[]) => props.permissions.filter((permission) => keys.includes(permission.name));
const toggleGroup = (keys: string[], checked: boolean) => { const names = groupPermissions(keys).map((permission) => permission.name); form.permissions = checked ? [...new Set([...form.permissions, ...names])] : form.permissions.filter((name) => !names.includes(name)); };
</script>
<template>
    <Head title="ایجاد نقش" /><AuthenticatedLayout><template #header><h2 class="text-xl font-semibold">ایجاد نقش جدید</h2></template>
    <form class="mx-auto max-w-4xl space-y-6" dir="rtl" @submit.prevent="form.post(route('roles.store'))">
        <div class="rounded-2xl bg-white p-6 shadow-sm"><div class="mb-5 flex items-center justify-between"><div><h3 class="text-lg font-bold">مشخصات نقش</h3><p class="mt-1 text-sm text-gray-500">مثلاً مدیر منابع انسانی یا سرپرست شیفت</p></div><Link :href="route('roles.index')" class="text-sm text-gray-500 hover:text-gray-800">بازگشت</Link></div><label class="mb-2 block text-sm font-medium">نام نقش</label><input v-model="form.name" placeholder="مثلاً سرپرست شیفت" class="w-full rounded-xl border-gray-200 p-3 focus:border-blue-500 focus:ring-blue-500"/><p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p></div>
        <div class="rounded-2xl bg-white p-6 shadow-sm"><div class="mb-6"><h3 class="text-lg font-bold">انتخاب دسترسی‌ها</h3><p class="mt-1 text-sm text-gray-500">هر دسترسی فقط امکان انجام همان عملیات را به کاربر می‌دهد.</p></div><div class="space-y-6"><section v-for="group in permissionGroups" :key="group.title" class="rounded-xl border border-gray-100 p-5"><label class="mb-4 flex cursor-pointer items-center justify-between"><span class="font-bold">{{ group.icon }} {{ group.title }}</span><span class="flex items-center gap-2 text-sm text-blue-700"><input type="checkbox" :checked="groupPermissions(group.keys).length > 0 && groupPermissions(group.keys).every((permission) => form.permissions.includes(permission.name))" @change="toggleGroup(group.keys, ($event.target as HTMLInputElement).checked)"/> انتخاب همه</span></label><div class="grid gap-3 sm:grid-cols-2"><label v-for="permission in groupPermissions(group.keys)" :key="permission.id" class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition hover:border-blue-300 hover:bg-blue-50"><input v-model="form.permissions" :value="permission.name" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/><span class="text-sm">{{ permissionLabel(permission.name) }}</span></label></div></section></div></div>
        <button :disabled="form.processing" class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow transition hover:bg-blue-700 disabled:opacity-50">ایجاد نقش</button>
    </form></AuthenticatedLayout>
</template>
