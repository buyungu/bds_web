<script setup>
import { useForm } from '@inertiajs/vue3';
import PaginationLinks from '../../../../Components/PaginationLinks.vue';

// Props
const props = defineProps({
    users: Object,
    status: String,
});

const form = useForm();
// Delete user function
const deleteUser = (id) => {
    if (confirm("Are you sure?")) {
        form.delete(route('users.destroy', id));
    }
};


</script>

<template>
    <div class="max-w-6xl mx-auto mb-8 dark:text-white">
        
        <table class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg">
            <thead
                class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
            >
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 w-1/5 text-left">Role</th>
                    <th class="p-3 w-1/5 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in props.users.data" :key="user.id" class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600">
                    <td class="p-3">
                        <div class="flex items-center gap-2">
                            <img
                                :src="
                                    user.avatar
                                        ? `/storage/${user.avatar}`
                                        : '/storage/avatars/default.jpg'
                                "
                                alt=""
                                class="h-10 w-10 rounded-full object-cover object-center"
                            />
                            <h4 class="font-bold">
                                {{ user.name }}
                                
                            </h4>
                        </div>
                    </td>
                    <td class="p-3">{{ user.email }}</td>
                    <td class="p-3 w-1/5 text-left">{{ user.role }}</td>
                    <td class="p-3 w-1/5 flex items-center gap-3 ">
                        <Link :href="route('users.edit', user.id)" class="text-blue-500 font-medium text-sm dark:text-blue-300">Edit</Link>
                        <form @submit.prevent="deleteUser(user.id)">
                            <button class="text-red-500 dark:text-red-400">Delete</button>
                        </form>                    
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <PaginationLinks :paginator="users" />
    </div>
</template>
