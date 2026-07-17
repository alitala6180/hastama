<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const menu = [
    {
        title: 'داشبورد',
        route: 'dashboard',
        icon: '🏠',
    },
    {
        title: 'مدیریت کاربران',
        route: 'users.index',
        icon: '👤',
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
        title: 'مرخصی کارکنان',
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
        class="fixed right-0 top-0 flex h-screen w-72 flex-col bg-gray-900 text-white shadow-xl"
        dir="rtl"
    >
        <!-- لوگو -->
        <div
            class="flex h-16 items-center justify-center border-b border-gray-700"
        >
            <h1 class="text-2xl font-bold tracking-wide">
                هستما
            </h1>
        </div>

        <!-- منو -->
        <nav class="flex-1 overflow-y-auto px-4 py-6">

            <Link
                v-for="item in menu"
                :key="item.route"
                :href="route(item.route)"
                :class="[
                    'mb-2 flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200',
                    isActive(item.route)
                        ? 'bg-blue-600 text-white shadow'
                        : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                ]"
            >

                <span class="text-xl">
                    {{ item.icon }}
                </span>

                <span class="font-medium">
                    {{ item.title }}
                </span>

            </Link>

        </nav>

        <!-- فوتر -->
        <div
            class="border-t border-gray-700 p-4 text-center text-xs text-gray-400"
        >
            Hastama ERP v1.0
        </div>
    </aside>
</template>