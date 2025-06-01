<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import Card from '../../Components/Card.vue';

defineProps({
    activeRequests: Number,
    upcomingEvents: Number,
    totalDonations:Number,
    enrolledEvents:Number,
    // notifications: Number,
});

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Donor Dashboard" />
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">
        <div class="grid grid-cols-4 gap-4">
            <Card :number="activeRequests" routeName="donor.requests" name="Blood Requests" icon="warehouse"  />
            <Card :number="totalDonations" routeName="donor.donations" name="Total Donations" icon="tint" />
            <Card :number="upcomingEvents" routeName="donor.events" name="Upcoming Events" icon="calendar" />
            <Card :number="enrolledEvents" routeName="donor.events" name="Enrolled Events" icon="calendar" />
        </div>
    </div>
    </div>
</template>
