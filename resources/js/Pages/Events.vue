<script setup>
import Index from './Events/Index.vue';
import InputField from '../Components/InputField.vue'
import { router, useForm } from '@inertiajs/vue3'; 
import SessionMessage from '../Components/SessionMessage.vue';

const props = defineProps({
    events: Object,
    status: String,
    searchTerm: String,
});

const params = route().params;

const userName = params.user_id ? props.events.data.find(i => i.created_by === Number(params.user_id)).user.name : null;

const form = useForm({
    search: props.searchTerm,
});

const search = () => {
    router.get(route("events"), { 
        search: form.search,
        user_id: params.user_id
     });
};

</script>

<template>
    <Head title=" | Latest Events "/>
    <h1 class="text-2xl font-bold mb-4">Latest Events</h1>
    <SessionMessage :status="status" />
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <Link 
                class="px-2 py-1 rounded-md bg-blue-500 text-white flex items-center gap-2"
                v-if="params.search"
                :href="route('events', { ...params, search: null, page: null })"
            >
                {{ params.search }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link 
                class="px-2 py-1 rounded-md bg-blue-500 text-white flex items-center gap-2"
                v-if="params.user_id"
                :href="route('events', { ...params, user_id: null, page: null })"
            >
                {{ userName }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
        </div>

        <div class="w-1/4">
            <form @submit.prevent="search">
                <InputField 
                    type="search"
                    label=""
                    icon="magnifying-glass"
                    placeholder="Search..."
                    v-model="form.search"
                />
            </form>
        </div>
    </div>
    <Index :events="events" :status="status"/>

</template>
