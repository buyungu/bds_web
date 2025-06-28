<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { router, useForm } from '@inertiajs/vue3';
import InputField from '../../Components/InputField.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import UserCreate from './Users/Sections/UserCreate.vue';
import UserIndex from './Users/Sections/UserIndex.vue';
import SessionMessage from '../../Components/SessionMessage.vue';

defineProps({
    users:Object,
    status: String,
    success: String,
    
});

const params = route().params;
const form = useForm({
    search: params.search,
    role: params.role,
});

const search = () => {
    router.get(route("admin.users"), {
        search: form.search,
        role: params.role
    })
}

const selectRole = () => {
    router.get(route("admin.users"), {
        role: form.role,
        search: params.search
    })
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Manage User "/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0 ', 'transition-all duration-300 ']">
        <DemoHeader />
        <Sidebar />
        <div class="p-4 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2 gap-y-2">
                <h1 class="text-2xl font-bold">User Management</h1>
            </div>

            <SessionMessage :status="status"/>

            <!-- Responsive filter/action bar -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full sm:w-auto">
                    <PrimaryBtn class="my-2 !bg-blue-600 w-full sm:w-auto">
                        <Link :href="route('admin.addusers')">Add User</Link>
                    </PrimaryBtn>
                    <div class=" my-2 relative w-full sm:w-48 rounded-lg">
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
                <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full sm:w-auto">
                    <Link
                        class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                        v-if="params.search"
                        :href="route('admin.users', { ...params, search: null, page: null })"
                    >
                        {{ params.search }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                    <Link 
                        class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                        v-if="params.role"
                        :href="route('admin.users', { ...params, role: null, page: null })"
                    >
                        {{ params.role }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
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
            <UserCreate :status="success" :regions="regions" :districts="districts" :wards="wards"/>
        </div>
    </div>
</template>
