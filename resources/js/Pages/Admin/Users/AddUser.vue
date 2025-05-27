<script setup>
import { useSidebar } from '../../../composables/useSidebar';
import DemoHeader from '../../../Layouts/DemoHeader.vue';
import Sidebar from '../../../Layouts/Sidebar.vue';
import UserCreate from './Sections/UserCreate.vue';

defineProps({
    success: String,
    regions: Array,
    districts: Array,
    wards: Array,
});

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Add User "/>
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class=" p-8">
        <UserCreate :status="success" :regions="regions" :districts="districts" :wards="wards"/>
    </div>
    </div>

</template>
