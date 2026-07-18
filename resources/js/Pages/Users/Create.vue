<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{ employees: { id: number; first_name: string; last_name: string }[]; roles: { id: number; name: string }[] }>();
const form = useForm({ name: '', email: '', password: '', password_confirmation: '', employee_id: '', role: '', is_active: true });
const submit = () => form.post(route('users.store'));
</script>

<template>
  <Head title="کاربر جدید" />
  <AuthenticatedLayout>
    <template #header><h2 class="text-xl font-semibold">کاربر جدید</h2></template>
    <form class="mx-auto max-w-2xl space-y-5 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
      <div v-for="field in ['name', 'email', 'password', 'password_confirmation']" :key="field">
        <label class="mb-1 block">{{ field === 'name' ? 'نام' : field === 'email' ? 'ایمیل' : field === 'password' ? 'رمز عبور' : 'تکرار رمز عبور' }}</label>
        <input v-model="(form as any)[field]" :type="field.includes('password') ? 'password' : field === 'email' ? 'email' : 'text'" class="w-full rounded border p-2" />
        <p v-if="(form.errors as any)[field]" class="mt-1 text-sm text-red-600">{{ (form.errors as any)[field] }}</p>
      </div>
      <div><label class="mb-1 block">پرسنل (اختیاری)</label><select v-model="form.employee_id" class="w-full rounded border p-2"><option value="">بدون اتصال</option><option v-for="employee in props.employees" :key="employee.id" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option></select></div>
      <div><label class="mb-1 block">نقش</label><select v-model="form.role" class="w-full rounded border p-2"><option value="">انتخاب نقش</option><option v-for="role in props.roles" :key="role.id" :value="role.name">{{ role.name }}</option></select><p v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</p></div>
      <label class="flex items-center gap-2"><input v-model="form.is_active" type="checkbox" /> حساب کاربری فعال باشد</label>
      <button :disabled="form.processing" class="rounded bg-blue-600 px-5 py-2 text-white disabled:opacity-50">ایجاد کاربر</button>
    </form>
  </AuthenticatedLayout>
</template>
