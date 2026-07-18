<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps<{ user: { id:number; name:string; email:string; employee_id:number|null; is_active:boolean; roles:{name:string}[] }; employees:{id:number;first_name:string;last_name:string}[]; roles:{id:number;name:string}[] }>();
const form = useForm({ name: props.user.name, email: props.user.email, password: '', password_confirmation: '', employee_id: props.user.employee_id ?? '', role: props.user.roles[0]?.name ?? '', is_active: props.user.is_active });
const submit = () => form.put(route('users.update', props.user.id));
</script>
<template>
  <Head title="ویرایش کاربر" />
  <AuthenticatedLayout><template #header><h2 class="text-xl font-semibold">ویرایش کاربر</h2></template>
    <form class="mx-auto max-w-2xl space-y-5 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
      <div><label class="mb-1 block">نام</label><input v-model="form.name" class="w-full rounded border p-2" /><p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p></div>
      <div><label class="mb-1 block">ایمیل</label><input v-model="form.email" type="email" class="w-full rounded border p-2" /><p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p></div>
      <div><label class="mb-1 block">رمز عبور جدید (اختیاری)</label><input v-model="form.password" type="password" class="w-full rounded border p-2" /></div>
      <div><label class="mb-1 block">تکرار رمز جدید</label><input v-model="form.password_confirmation" type="password" class="w-full rounded border p-2" /><p v-if="form.errors.password" class="text-sm text-red-600">{{ form.errors.password }}</p></div>
      <div><label class="mb-1 block">پرسنل</label><select v-model="form.employee_id" class="w-full rounded border p-2"><option value="">بدون اتصال</option><option v-for="employee in props.employees" :key="employee.id" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option></select></div>
      <div><label class="mb-1 block">نقش</label><select v-model="form.role" class="w-full rounded border p-2"><option v-for="role in props.roles" :key="role.id" :value="role.name">{{ role.name }}</option></select><p v-if="form.errors.role" class="text-sm text-red-600">{{ form.errors.role }}</p></div>
      <label class="flex items-center gap-2"><input v-model="form.is_active" type="checkbox" /> حساب کاربری فعال باشد</label>
      <button :disabled="form.processing" class="rounded bg-blue-600 px-5 py-2 text-white">ذخیره تغییرات</button>
    </form>
  </AuthenticatedLayout>
</template>
