<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const menu = [
    {
        title: 'داشبورد',
        route: 'dashboard',
        icon: '🏠',
    },
    {
        title: 'مدیریت پرسنل',
        route: 'employees.index',
        icon: '👥',
    },
    {
        title: 'واحدهای سازمانی',
        route: 'departments.index',
        icon: '🏢',
    },
    {
        title: 'سمت‌های شغلی',
        route: 'positions.index',
        icon: '💼',
    },
    {
        title: 'مدیریت کاربران',
        route: 'users.index',
        icon: '👤',
    },
    {
        title: 'نقش‌ها',
        route: 'roles.index',
        icon: '🛡️',
    },
    {
        title: 'حضور و غیاب',
        route: 'attendances.index',
        icon: '🕒',
    },
    {
        title: 'گزارش حضور و غیاب',
        route: 'attendance.reports',
        icon: '📊',
    },
    {
        title: 'مرخصی‌ها',
        route: 'leaves.index',
        icon: '📅',
    },
];

function isActive(routeName: string) {
    return route().current(routeName);
}
</script>

<template>
    <aside
        class="fixed right-0 top-0 h-screen w-72 bg-gray-900 text-white shadow-xl"
        dir="rtl"
    >
        <div
            class="flex h-16 items-center justify-center border-b border-gray-700"
        >
            <h1 class="text-2xl font-bold">
                هستما
            </h1>
        </div>

        <nav class="mt-6 px-4">

            <Link
                v-for="item in menu"
                :key="item.route"
                :href="route(item.route)"
                :class="[
                    'mb-2 flex items-center gap-3 rounded-lg px-4 py-3 transition',
                    isActive(item.route)
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-300 hover:bg-gray-700'
                ]"
            >
                <span>{{ item.icon }}</span>
                <span>{{ item.title }}</span>
            </Link>

        </nav>
    </aside>
</template>