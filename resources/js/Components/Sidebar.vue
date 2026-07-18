<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const menu = [
    { title: 'داشبورد', route: 'dashboard', icon: '🏠' },
    { title: 'مدیریت کاربران', route: 'users.index', icon: '👤', permission: 'users.view' },
    { title: 'مدیریت پرسنل', route: 'employees.index', icon: '👥', permission: 'employees.view' },
    { title: 'واحدهای سازمانی', route: 'departments.index', icon: '🏢', permission: 'departments.view' },
    { title: 'سمت‌های شغلی', route: 'positions.index', icon: '💼', permission: 'positions.view' },
    { title: 'مرخصی کارکنان', route: 'leaves.index', icon: '📋', permission: 'leave.view' },
    { title: 'حضور و غیاب', route: 'attendances.index', icon: '🕒', permission: 'attendance.view' },
    { title: 'نقش‌ها و دسترسی‌ها', route: 'roles.index', icon: '🔐', permission: 'roles.manage' },
];

const permissions = computed<string[]>(() => (usePage().props.auth as any)?.permissions ?? []);
const visibleMenu = computed(() => menu.filter((item) => !item.permission || permissions.value.includes(item.permission)));
const isActive = (routeName: string) => route().current(routeName);
</script>

<template>
    <aside class="fixed right-0 top-0 flex h-screen w-72 flex-col bg-gray-900 text-white shadow-xl" dir="rtl">
        <div class="flex h-16 items-center justify-center border-b border-gray-700"><h1 class="text-2xl font-bold tracking-wide">هستاما</h1></div>
        <nav class="flex-1 overflow-y-auto px-4 py-6">
            <Link v-for="item in visibleMenu" :key="item.route" :href="route(item.route)" :class="['mb-2 flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200', isActive(item.route) ? 'bg-blue-600 text-white shadow' : 'text-gray-300 hover:bg-gray-700 hover:text-white']">
                <span class="text-xl">{{ item.icon }}</span><span class="font-medium">{{ item.title }}</span>
            </Link>
        </nav>
        <div class="border-t border-gray-700 p-4 text-center text-xs text-gray-400">Hastama ERP v1.0</div>
    </aside>
</template>
