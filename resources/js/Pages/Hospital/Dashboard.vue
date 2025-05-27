<script setup>
import { useSidebar } from '../../composables/useSidebar';
import Card from '../../Components/Card.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';

defineProps({
    totalBloodStock: Number,
    pendingBloodRequests: Number,
    upcomingEvents: Number,
    registeredDonors: Number,
})

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Hospital Dashboard" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">
        <div class="grid grid-cols-4 gap-4">
            <Card :number="totalBloodStock" routeName="hospital.inventory" name="Total Blood" icon="warehouse" quantity="Unit" />
            <Card :number="pendingBloodRequests" routeName="hospital.requests" name="Pending Requests" icon="tint" />
            <Card :number="upcomingEvents" routeName="hospital.events" name="Events" icon="calendar" />
            <Card :number="registeredDonors" routeName="hospital.donors" name="Available Donors" icon="users" />
        </div>
    </div>
    </div>
</template>
