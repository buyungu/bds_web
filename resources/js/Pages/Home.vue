<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ErrorMessage from '../Components/ErrorMessage.vue';
import SessionMessage from '../Components/SessionMessage.vue';
import Title from '../Components/Title.vue';
import Index from './Events/Index.vue';

const props = defineProps({
    events: Object,
});

const error = usePage().props.flash.error;
const status = usePage().props.flash.status;
const user = usePage().props.auth.user;

</script>

<template>
    <Head title="| Home"/>
      <div class="mx-auto max-w-screen-lg p-2">
    <ErrorMessage :error="error" />
    <SessionMessage :status="status" />
    <div class="text-center mt-14 pb-14 px-4 border-b-4">
        <h1 class="text-5xl font-semibold tracking-tight text-balance text-slate-900 dark:text-slate-100 sm:text-7xl">
            Connecting Lives Through Blood Donation
        </h1>
        <p class="mt-8 text-lg font-medium text-pretty text-slate-500 dark:text-slate-300 sm:text-xl/8">
            Our platform bridges the gap between donors and patients. Whether you're in need or ready to give, we make the process simple, fast, and safe — because every second counts, and every pint matters.
        </p>
        <div v-if="user" class="mt-10 flex items-center justify-center gap-x-6 ">
            <Link :href="route('events')" class="rounded-md bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:outline outline-blue-500 outline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
            Explore Events
            </Link>
            <Link :href="route('home')" class="rounded-md bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:outline outline-green-500 outline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">
            Learn more <i class="fa-solid fa-arrow-right"></i>
            </Link>
        </div>
        <div v-else class="mt-10 flex items-center justify-center gap-x-6 ">
            <Link :href="route('login')" class="rounded-md bg-blue-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:outline outline-blue-500 outline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
            Become a Donor
            </Link>
            <Link :href="route('login')" class="rounded-md bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:outline outline-green-500 outline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            Need Blood? <i class="fa-solid fa-arrow-right"></i>
            </Link>
        </div>
    </div>

    <div class="my-14">
        <Title class=" text-center">Lartest Blood Donation Events</Title>

        <Index class="my-10" :events="events" :status="status"/>
    </div>

</div>
</template>
