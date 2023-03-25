<script setup>
import { RouterView } from 'vue-router'
import { useConfigStore } from './stores/config.js'

import Navbar from '@/components/layout/Navbar.vue'
import Sidebar from '@/components/layout/Sidebar.vue'

const configStore = useConfigStore()
</script>

<template>
    <div class="wrapper">
        <!-- Sidebar & Navbar -->
        <Sidebar v-if="configStore.showSidebar"/>
        <Navbar v-if="configStore.showNavbar"/>

        <!-- Page Content -->
        <main v-if="configStore.showMain">
            <div class="content">
                <div class="container-fluid">
                    <RouterView/>
                </div>
            </div>
        </main>

        <!-- Login & Register Page -->
        <section v-if="!configStore.showMain">
            <RouterView/>
        </section>
    </div>
</template>
