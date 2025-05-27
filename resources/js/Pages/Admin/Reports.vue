<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';

const { isSidebarOpen } = useSidebar()
</script>

<template>
    <Head title=" | Reports"/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
    <DemoHeader />
    <Sidebar />
    <div class=" p-8">
        <p>Admin Manage Reports</p>
    </div>
    </div>
</template>
