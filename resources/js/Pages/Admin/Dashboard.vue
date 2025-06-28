<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import Card from '../../Components/Card.vue';
import UserIndex from './Users/Sections/UserIndex.vue';
import { router, useForm } from '@inertiajs/vue3';
import InputField from '../../Components/InputField.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import Title from '../../Components/Title.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

defineProps({
    events: Object,
    users:Object,
    status: String,
    success: String,
    countUsers: Number,
    countEvents: Number,
    countBlood: Number,
    countRequests: Number,
});

const params = route().params;
const form = useForm({
    search: params.search,
    role: params.role,
});

const search = () => {
    router.get(route("admin.dashboard"), {
        search: form.search,
        role: params.role
    })
}

const selectRole = () => {
    router.get(route("admin.dashboard"), {
        role: form.role,
        search: params.search
    })
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Admin Dashboard" />
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300']">
        <DemoHeader />
        <Sidebar />
        <div class="p-4 sm:p-8">
            <!-- Responsive Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-4">
                <Card :number="countUsers" routeName="admin.users" name="Total Users" icon="users"/>
                <Card :number="countEvents" routeName="admin.events" name="Total Events" icon="calendar"/>
                <Card :number="countBlood" routeName="admin.inventory" name="Total Blood" icon="hospital" quantity="Units"/>
                <Card :number="countRequests" routeName="admin.users" name="Total Requests" icon="tint"/>
            </div>

            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-2 gap-2">
                <h1 class="text-2xl font-bold">User Management</h1>
                <SessionMessage :status="status"/>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                <div class="flex flex-col sm:flex-row items-stretch gap-2">
                    <PrimaryBtn class="my-2 !bg-blue-600">
                        <Link :href="route('admin.addusers')">Add User</Link>
                    </PrimaryBtn>
                    <div class="my-2 relative w-full sm:w-48 rounded-lg">
                        <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="grid place-content-center text-sm text-slate-400">
                                <i class="fa-solid fa-user-tie"></i>
                            </span>
                        </div>
                        <select
                            @change="selectRole"
                            id="role"
                            name="role"
                            v-model="form.role"
                            class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        >
                            <option value="user">User</option>
                            <option value="hospital">Hospital</option>
                            <option value="organization">Organization</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch gap-2">
                <div class="flex items-stretch gap-2">
                    <Link
                        class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                        v-if="params.search"
                        :href="route('admin.dashboard', { ...params, search: null, page: null })"
                    >
                        {{ params.search }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                    <Link 
                        class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                        v-if="params.role"
                        :href="route('admin.dashboard', { ...params, role: null, page: null })"
                    >
                        {{ params.role }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                    </div>
                    <form @submit.prevent="search" class="w-full sm:w-auto">
                        <InputField
                            label=""
                            icon="magnifying-glass"
                            placeholder="Search..."
                            v-model="form.search"
                        />
                    </form>
                </div>
            </div>

            <UserIndex :users="users" :status="status"/>

            <Title>Manage Events</Title>

            <div v-if="events.data.length">
                <div class="my-6 overflow-x-auto rounded-md">
                    <!-- Responsive Table -->
                    <table class="min-w-[600px] w-full table-fixed border-collapse overflow-hidden text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg">
                        <thead class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900">
                            <tr>
                                <th class="w-3/4 p-3 text-left">Event Title</th>
                                <th class="w-1/4 py-3 pl-3 text-left">Creator</th>
                                <th class="w-1/5 py-3 pr-3 text-right">View</th>
                                <th class="w-1/5 py-3 pr-3 text-right">Edit</th>
                                <th class="w-1/5 py-3 pr-3 text-right">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="event in events.data"
                                :key="event.id"
                                class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                            >
                                <td class="w-3/4 p-3 text-left">
                                    <div class="flex items-center gap-2">
                                        <img
                                            :src="event.image ? `/storage/${event.image}` : '/storage/images/events/default.png'"
                                            alt=""
                                            class="h-10 w-10 rounded-full object-cover object-center"
                                        />
                                        <h4 class="font-bold">
                                            {{ event.title }}
                                        </h4>
                                    </div>
                                </td>
                                <td class="w-1/4 py-3 pl-3 text-left text-blue-500">
                                    <button @click="selectUser(event.user.id)" class="text-blue-500 font-medium text-sm">
                                        {{ event.user.name ?? 'Unknown' }}
                                    </button>
                                </td>
                                <td class="w-1/5 py-3 pr-3 text-right text-blue-500">
                                    <Link :href="route('events.show', event.id)">View</Link>
                                </td>
                                <td class="w-1/5 py-3 pr-3 text-right text-blue-500">
                                    <Link :href="route('events.edit', event.id)">Edit</Link>
                                </td>
                                <td class="w-1/5 py-3 pr-3 text-right text-red-500">
                                    <button type="button" @click="deleteEvent(event.id)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <PaginationLinks :paginator="events" />
                </div>
            </div>
            <div v-else>You have no events!</div>
        </div>
    </div>
</template>
