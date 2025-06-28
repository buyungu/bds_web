<script setup>
import { useSidebar } from '../../../composables/useSidebar';
import { useForm, usePage } from '@inertiajs/vue3';
import Container from '../../../Components/Container.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import Title from '../../../Components/Title.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';
import DemoHeader from '../../../Layouts/DemoHeader.vue';
import SideBar from '../../../Layouts/SideBar.vue';
import { computed } from 'vue'; // You were importing this but not using it, keeping it in case it's used elsewhere

const props = defineProps({
    status: String,
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    // Initialize location as the full object if it exists, otherwise null
    location: props.user.location || null,
    blood_type: props.user.blood_type,
    role: props.user.role,
    password: "",
    password_confirmation: "",
    avatar: null,
    // If there's an avatar, use it; otherwise, use the default.
    // Ensure the path is correct if 'props.user.avatar' already includes '/storage/'.
    preview: props.user.avatar ? `/storage/${props.user.avatar}` : '/storage/avatars/default.jpg',
    _method: 'PATCH',
});

// Change avatar logic stays the same
const change = (e) => {
    form.avatar = e.target.files[0];
    form.preview = URL.createObjectURL(e.target.files[0]);
};

const setLocation = (e) => {
    console.log('setLocation', e);
    // When a place is selected from GMapAutocomplete, update the location object
    form.location = {
        lat: e.geometry.location.lat(),
        lng: e.geometry.location.lng(),
        address: e.formatted_address,
        name: e.name,
        url: e.url,
        district: e.address_components.find(c => c.types.includes('administrative_area_level_2'))?.long_name || '',
        region: e.address_components.find(c => c.types.includes('administrative_area_level_1'))?.long_name || '',
        country: e.address_components.find(c => c.types.includes('country'))?.long_name || '',
    };
}

const submit = () => {
    // When submitting, use the correct Inertia.js method for PATCH requests
    form.post(route("users.update", props.user.id), {
        onSuccess: () => {
            form.reset("password", "password_confirmation");
            // If avatar was updated, update the preview as well to reflect the new image
            if (form.avatar) {
                // Assuming the backend returns the new avatar path on successful update
                // You might need to refetch user data or get the path from the response
                // For now, we'll keep the client-side preview which is good for immediate feedback.
                // If you want the *actual* new permanent URL, you'd get it from a backend response.
            }
        },
        preserveScroll: true,
    });
};

const { isSidebarOpen } = useSidebar();
</script>

<template>
    <Head title=" | Edit User "/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 min-h-screen flex flex-col']">

        <DemoHeader />
        <SideBar />
        <div class="p-4 sm:p-8 flex-grow"> <Container>
                <div class="mb-8">
                    <Title class="text-center">Edit User, {{ user.name }}</Title>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid place-items-center mb-4">
                        <div class="relative w-28 h-28 rounded-full overflow-hidden border border-slate-300">
                            <label for="avatar" class="absolute inset-0 grid content-end cursor-pointer">
                                <span class="bg-white/70 pb-2 text-center text-sm">Avatar</span>
                            </label>
                            <input type="file" @input="change" id="avatar" hidden />

                            <img
                                class="object-cover w-full h-full"
                                :src="form.preview"
                                alt="User Avatar"
                            />
                        </div>
                        <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-2">{{ form.errors.avatar }}</p>
                    </div>

                    <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-full"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Name
                                </label>
                                <div class="relative mt-1 rounded-md">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        v-model="form.name"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.name
                                        }"
                                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    />
                                </div>
                                <p v-if="form.errors.name" class="text-red-500 text-xs mt-2">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Email
                                </label>
                                <div class="relative mt-1 rounded-md">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-at"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        id="email"
                                        name="email"
                                        v-model="form.email"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.email
                                        }"
                                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="text-red-500 mt-2 text-sm font-medium">{{ form.errors.email }}</p>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Phone
                                </label>
                                <div class="relative mt-1 rounded-md">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-phone"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        v-model="form.phone"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.phone
                                        }"
                                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    />
                                </div>
                                <p v-if="form.errors.phone" class="text-red-500 text-xs mt-2">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Location
                                </label>
                                <div class="relative mt-1 rounded-md">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-map-marker-alt"></i>
                                        </span>
                                    </div>
                                    <GMapAutocomplete
                                        @place_changed="setLocation"
                                        id="location"
                                        name="location"
                                        :value="form.location ? form.location.address : ''"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.location
                                        }"
                                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    />
                                </div>
                                <p v-if="form.errors.location" class="text-red-500 text-xs mt-2">{{ form.errors.location }}</p>
                            </div>

                        </div>

                        <div class="space-y-6">
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Role
                                </label>
                                <div class="relative mt-1 rounded-lg">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </span>
                                    </div>
                                    <select
                                        id="role"
                                        name="role"
                                        v-model="form.role"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.role
                                        }"
                                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    >
                                        <option value="" disabled>Select a role</option>
                                        <option value="user">User</option>
                                        <option value="hospital">Hospital</option>
                                        <option value="organization">Organization</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <p v-if="form.errors.role" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.role }}</p>
                            </div>

                            <div>
                                <label for="blood_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Blood Type
                                </label>
                                <div class="relative mt-1 rounded-lg">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="grid place-content-center text-sm text-slate-400">
                                            <i class="fa-solid fa-droplet"></i>
                                        </span>
                                    </div>
                                    <select
                                        id="blood_type"
                                        name="blood_type"
                                        v-model="form.blood_type"
                                        :class="{
                                            'border-red-500 ring-1 ring-red-500': form.errors.blood_type
                                        }"
                                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    >
                                        <option value="" disabled>Select blood type</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                                <p v-if="form.errors.blood_type" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.blood_type }}</p>
                            </div>

                            <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Password (Leave blank to keep current)
                            </label>
                            <div class="relative mt-1 rounded-md">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="grid place-content-center text-sm text-slate-400">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    v-model="form.password"
                                    :class="{
                                        'border-red-500 ring-1 ring-red-500': form.errors.password
                                    }"
                                    class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    placeholder="••••••••"
                                />
                            </div>
                            <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Confirm Password
                            </label>
                            <div class="relative mt-1 rounded-md">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="grid place-content-center text-sm text-slate-400">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                </div>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :class="{
                                        'border-red-500 ring-1 ring-red-500': form.errors.password_confirmation
                                    }"
                                    class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                    placeholder="••••••••"
                                />
                            </div>
                            <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password_confirmation }}</p>
                        </div>
                        </div>
                    </div>

                        
                    <PrimaryBtn :disabled="form.processing" class="w-full sm:w-auto mt-6">Update User</PrimaryBtn>
                </form>
            </Container>
        </div>
    </div>
</template>