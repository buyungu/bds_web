<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Reports" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="ml-72 p-8">
        <p>Manage Reports</p>
    </div>
    </div>
</template>
