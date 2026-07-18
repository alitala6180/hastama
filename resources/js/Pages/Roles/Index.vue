<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { permissionLabel } from '@/lib/permissions';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

type Role = { id: number; name: string; permissions: { id: number; name: string }[] };

defineProps<{ roles: Role[] }>();
const selectedRole = ref<Role | null>(null);
</script>

<template>
    <Head title="نقش‌ها و دسترسی‌ها" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-semibold text-gray-800">نقش‌ها و دسترسی‌ها</h2></template>
        <section class="space-y-6" dir="rtl">
            <div class="flex flex-col justify-between gap-4 rounded-2xl bg-gradient-to-l from-indigo-700 to-blue-600 p-6 text-white shadow-lg sm:flex-row sm:items-center">
                <div><p class="mb-1 text-sm text-blue-100">کنترل سطح دسترسی</p><h3 class="text-2xl font-bold">نقش‌ها و دسترسی‌های سامانه</h3><p class="mt-2 text-sm text-blue-100">برای هر نقش، دسترسی ماژول‌های مختلف را دقیق تعیین کنید.</p></div>
                <Link :href="route('roles.create')" class="rounded-xl bg-white px-5 py-3 font-semibold text-blue-700 shadow transition hover:bg-blue-50">+ ایجاد نقش جدید</Link>
            </div>
            <div v-if="!roles.length" class="rounded-2xl bg-white p-12 text-center text-gray-500 shadow">هنوز نقشی تعریف نشده است.</div>
            <div v-else class="grid gap-5 lg:grid-cols-2">
                <article v-for="role in roles" :key="role.id" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="mb-5 flex items-start justify-between"><div><p class="text-sm text-gray-500">نام نقش</p><h3 class="mt-1 text-xl font-bold text-gray-800">{{ role.name }}</h3></div><span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">{{ role.permissions.length }} دسترسی</span></div>
                    <div v-if="role.permissions.length" class="flex min-h-14 flex-wrap gap-2">
                        <span v-for="permission in role.permissions.slice(0, 5)" :key="permission.id" class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs text-gray-700">{{ permissionLabel(permission.name) }}</span>
                        <button v-if="role.permissions.length > 5" type="button" class="rounded-lg border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100" @click="selectedRole = role">+{{ role.permissions.length - 5 }} مورد بیشتر</button>
                    </div>
                    <p v-else class="min-h-14 text-sm text-gray-400">دسترسی‌ای انتخاب نشده است.</p>
                    <div class="mt-5 flex items-center justify-between border-t pt-4"><button v-if="role.permissions.length" type="button" class="text-sm font-medium text-gray-500 hover:text-blue-700" @click="selectedRole = role">مشاهده همه</button><span v-else></span><Link :href="route('roles.edit', role.id)" class="font-semibold text-blue-600 hover:text-blue-800">ویرایش دسترسی‌ها ←</Link></div>
                </article>
            </div>

            <div v-if="selectedRole" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/45 p-4" @click.self="selectedRole = null">
                <div class="max-h-[80vh] w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b px-6 py-5"><div><p class="text-sm text-gray-500">فهرست دسترسی‌ها</p><h3 class="text-xl font-bold text-gray-800">{{ selectedRole.name }}</h3></div><button type="button" class="rounded-lg p-2 text-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700" aria-label="بستن" @click="selectedRole = null">×</button></div>
                    <div class="max-h-[55vh] overflow-y-auto p-6"><div class="grid gap-3 sm:grid-cols-2"><div v-for="permission in selectedRole.permissions" :key="permission.id" class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-700">{{ permissionLabel(permission.name) }}</div></div></div>
                    <div class="border-t bg-gray-50 px-6 py-4 text-left"><button type="button" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700" @click="selectedRole = null">بستن</button></div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
