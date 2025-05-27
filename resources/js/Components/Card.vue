<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    number: Number,
    routeName: String,
    name: String,
    icon: String,
    label: String,
    quantity: String || null,
    function: String || null,
})

</script>

<template>

      <div
        class="  rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-white/[0.03] "
      >
        <div
          class="flex items-center justify-center w-12 h-12 bg-slate-100 rounded-xl dark:bg-slate-800"
        >
          <i 
            v-if="icon"
            :class="`fa-solid fa-${icon} fill-slate-800 dark:fill-white/90 text-2xl text-slate-600 dark:text-slate-50`" 
          ></i>
          <div v-else
            class="cursor-pointer font-extrabold text-xl text-slate-600 dark:text-slate-50">
            {{ label }}
          </div>
        </div>
  
        <div class="flex items-end justify-between mt-5">
          <div>
            <span class="text-sm text-slate-500 dark:text-slate-400">{{ name }}</span>
            <h4 class="mt-2 font-bold text-slate-800 text-3xl dark:text-white/90">{{ number }} <span class="text-sm">{{ quantity }}</span></h4>
          </div>
  
          <Link
            :href="route(routeName)"
            class="flex items-center gap-1 rounded-full bg-blue-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-300"
          >
  
            View
            <i class="fa-solid fa-arrow-right" ></i>

          </Link>
        </div>
      </div>
  
  </template>
  