<script setup lang="ts">

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

</script>


<template>

<header
    class="fixed right-72 left-0 top-0 h-16 bg-white shadow"
    dir="rtl"
>

<div class="flex h-full items-center justify-between px-6">


    <h2 class="font-semibold text-gray-700">

        سامانه جامع مدیریت هستما

    </h2>



    <Dropdown align="left" width="48">

        <template #trigger>

            <button
                class="rounded px-3 py-2 text-gray-700"
            >

                {{ $page.props.auth.user.name }}

            </button>

        </template>


        <template #content>

            <DropdownLink
                :href="route('profile.edit')"
            >
                پروفایل
            </DropdownLink>


            <DropdownLink
                :href="route('logout')"
                method="post"
                as="button"
            >
                خروج
            </DropdownLink>


        </template>


    </Dropdown>


</div>

</header>

</template>