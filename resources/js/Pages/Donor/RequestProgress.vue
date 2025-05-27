<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';

  defineProps({
    bloodRequest: Object
  });

  const { isSidebarOpen } = useSidebar()

  </script>

<template>
    <Head title=" | Request Progress" />
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class=" p-8">
        
        <div class=" space-y-6 bg-slate-100 dark:bg-slate-700 min-h-screen">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">🩸 Request Progress</h2>

            <!-- Recipient Info -->
            <div class="bg-white dark:bg-slate-800 shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-2 text-slate-700 dark:text-slate-200">Recipient Details</h3>
                <p class="text-slate-600 dark:text-slate-300"><strong>Name:</strong> {{ bloodRequest.recipient?.name }}</p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Email:</strong> 
                    <a :href="`mailto:${bloodRequest.recipient.email}`" class="text-blue-500 ml-1">
                        {{ bloodRequest.recipient?.email }}
                    </a>
                </p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Blood Type:</strong> {{ bloodRequest.recipient?.blood_type }}</p>
            </div>

            <!-- Request Info -->
            <div class="bg-white dark:bg-slate-800 shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-2 text-slate-700 dark:text-slate-200">Request Details</h3>
                <p class="text-slate-600 dark:text-slate-300"><strong>Blood Type:</strong> {{ bloodRequest.blood_type }}</p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Status:</strong> {{ bloodRequest.status }}</p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Quantity Remaining:</strong> {{ bloodRequest.quantity }} pint(s)</p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Donors Confirmed:</strong> {{ bloodRequest.donors?.length ?? 0 }}</p>
            </div>

            <!-- Hospital Info -->
            <div class="bg-white dark:bg-slate-800 shadow-md p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-2 text-slate-700 dark:text-slate-200">Hospital Information</h3>
                <p class="text-slate-600 dark:text-slate-300"><strong>Name:</strong> {{ bloodRequest.hospital?.name }}</p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Email:</strong> 
                    <a :href="`mailto:${bloodRequest.hospital.email}`" class="text-blue-500 ml-1">
                        {{ bloodRequest.hospital?.email }}
                    </a>
                </p>
                <p class="text-slate-600 dark:text-slate-300"><strong>Location:</strong> {{ bloodRequest.hospital?.ward?.name }}, {{ bloodRequest.hospital?.ward?.district?.name }}, {{ bloodRequest.hospital?.ward?.district?.region?.name }}</p>
            </div>
        </div>

    </div>
    </div>
</template>
  
  
  