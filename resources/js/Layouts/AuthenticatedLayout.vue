<script setup lang="ts">

import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';

</script>


<template>

<div
    class="min-h-screen bg-gray-100"
    dir="rtl"
>


    <Sidebar />


    <Header />


    <main class="mr-72 pt-16">


        <header
            v-if="$slots.header"
            class="bg-white shadow"
        >

            <div class="px-6 py-5">

                <slot name="header" />

            </div>


        </header>



        <div class="p-6">

            <slot />

        </div>


    </main>


</div>


</template>