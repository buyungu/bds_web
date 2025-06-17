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
        
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 py-10 px-4 flex flex-col items-center">
    <div class="w-full max-w-2xl space-y-8">
      <h2 class="text-3xl font-extrabold text-center text-blue-700 dark:text-blue-200 flex items-center justify-center gap-2">
        Request Progress
      </h2>

      <!-- Recipient Info -->
      <section class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center gap-6">
        <div class="flex-1">
          <h3 class="text-lg font-bold text-blue-700 dark:text-blue-200 mb-2">Recipient Details</h3>
          <div class="space-y-1 text-slate-600 dark:text-slate-300">
            <p><strong>Name:</strong> {{ bloodRequest.recipient?.name }}</p>
            <p>
              <strong>Email:</strong>
              <a :href="`mailto:${bloodRequest.recipient.email}`" class="text-blue-500 hover:underline ml-1">
                {{ bloodRequest.recipient?.email }}
              </a>
            </p>
            <p><strong>Blood Type:</strong> <span class="font-semibold text-red-500">{{ bloodRequest.recipient?.blood_type }}</span></p>
          </div>
        </div>
        <div class="flex-shrink-0 flex items-center justify-center">
          <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center shadow">
            <i class="fa-solid fa-user text-3xl text-red-500"></i>
          </div>
        </div>
      </section>

      <!-- Request Info -->
      <section class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-blue-700 dark:text-blue-200 mb-2">Request Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-slate-600 dark:text-slate-300">
          <p><strong>Blood Type:</strong> <span class="font-semibold text-red-500">{{ bloodRequest.blood_type }}</span></p>
          <p><strong>Status:</strong>
            <span
              :class="{
                'text-yellow-500': bloodRequest.status === 'pending',
                'text-green-500': bloodRequest.status === 'fulfilled',
                'text-red-500': bloodRequest.status === 'cancelled'
              }"
              class="font-semibold"
            >
              {{ bloodRequest.status }}
            </span>
          </p>
          <p><strong>Quantity Remaining:</strong> <span class="font-semibold">{{ bloodRequest.quantity }}</span> pint(s)</p>
          <p><strong>Donors Confirmed:</strong> <span class="font-semibold">{{ bloodRequest.donors?.length ?? 0 }}</span></p>
        </div>
      </section>

      <!-- Hospital Info -->
      <section class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center gap-6">
        <div class="flex-1">
          <h3 class="text-lg font-bold text-blue-700 dark:text-blue-200 mb-2">Hospital Information</h3>
          <div class="space-y-1 text-slate-600 dark:text-slate-300">
            <p><strong>Name:</strong> {{ bloodRequest.hospital?.name }}</p>
            <p>
              <strong>Email:</strong>
              <a :href="`mailto:${bloodRequest.hospital.email}`" class="text-blue-500 hover:underline ml-1">
                {{ bloodRequest.hospital?.email }}
              </a>
            </p>
            <p><strong>Location:</strong> {{ bloodRequest.hospital?.location.address }}</p>
          </div>
        </div>
        <div class="flex-shrink-0 flex items-center justify-center">
          <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center shadow">
            <i class="fa-solid fa-hospital text-3xl text-blue-600"></i>
          </div>
        </div>
      </section>
    </div>
  </div>

    </div>
</template>
  
  
  