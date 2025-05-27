<script setup>
import { useSidebar } from '../../../composables/useSidebar';
    import { useForm, usePage } from '@inertiajs/vue3';
    import Container from '../../../Components/Container.vue';
    import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
    import Title from '../../../Components/Title.vue';
    import SessionMessage from '../../../Components/SessionMessage.vue';
    import DemoHeader from '../../../Layouts/DemoHeader.vue';
    import SideBar from '../../../Layouts/SideBar.vue';
    import { computed } from 'vue';

    const props = defineProps({ 
        status: String,
        user: Object, 
        regions: Array,
        districts: Array,
        wards: Array,
    });

    const form = useForm({
        name: props.user.name,
        email: props.user.email,
        blood_type: props.user.blood_type,
        role: props.user.role,
        password: "",
        password_confirmation: "",
        region_id: props.user.region_id,
        district_id: props.user.district_id,
        ward_id: props.user.ward_id,
        avatar: null,
        preview: props.user.avatar ? `/storage/${props.user.avatar}` : null,  // If there's an avatar, use it
        _method: 'PATCH',
    });

    // Change avatar logic stays the same
    const change = (e) => {
        form.avatar = e.target.files[0];
        form.preview = URL.createObjectURL(e.target.files[0]);
    };

    // Filter districts based on selected region
    const filteredDistricts = computed(() => {
        return props.districts.filter(district => district.region_id === form.region_id);
    });

    // Filter wards based on selected district
    const filteredWards = computed(() => {
        return props.wards.filter(ward => ward.district_id === form.district_id);
    });

    const submit = () => {
        form.post(route("users.update", props.user.id), {
            onFinish: () => form.reset("password", "password_confirmation"),
            preserveScroll: true,
        });
    };

    const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Edit User "/>
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <SideBar />
    <div class="p-8">
    <Container>
        <div class=" mb-8">
            <Title class="text-center">Edit User, {{ user.name }}</Title>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Upload Avatar -->
            <div class="grid place-items-center">
                <div
                class="relative w-28 h-28 rounded-full overflow-hidden border border-slate-300"
                >
                <label for="avatar" class="absolute inset-0 grid content-end cursor-pointer">
                    <span class="bg-white/70 pb-2 text-center">Avatar</span>
                </label>
                <input type="file" @input="change" id="avatar" hidden />

                <img
                    class="object-cover w-28 h-28"
                    :src="form.preview ?? '/storage/avatars/default.jpg'"
                />
                </div>

                <p class="error mt-2">{{ form.errors.avatar }}</p>
            </div>

            <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-full"/>

            <div class="grid grid-cols-2 gap-4 justify-between">
                <div class="space-y-6">
            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Name
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Email
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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
                <p v-if="form.errors.email" class="text-red-500  mt-2 text-sm font-medium">{{ form.errors.email }}</p>

            </div>

            <!-- Region Select -->
            <div class="mb-4">
                <label for="region_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Region
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-city"></i>
                        </span>
                    </div>
                    <select
                        id="region_id"
                        name="region_id"
                        v-model="form.region_id"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.region_id
                        }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400">
                        <option value="" disabled selected>Select a Region</option>
                        <option v-for="region in props.regions" :key="region.id" :value="region.id">
                            {{ region.name }}
                        </option>
                    </select>
                </div>
                <p v-if="form.errors.region_id" class="text-red-500 text-xs mt-2">{{ form.errors.region_id }}</p>
            </div>

            <!-- District Select -->
            <div>
                <label for="district_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    District
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-tree-city"></i>
                        </span>
                    </div>
                <select
                    id="district_id"
                    name="district_id"
                    v-model="form.district_id"
                    :class="{
                        'border-red-500 ring-1 ring-red-500': form.errors.district_id
                    }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        :disabled="!form.region_id"
                >
                    <option value="" disabled selected>Select a District</option>
                    <option v-for="district in filteredDistricts" :key="district.id" :value="district.id">
                        {{ district.name }}
                    </option>
                </select>
                </div>
                <p v-if="form.errors.district_id" class="text-red-500 text-xs mt-2">{{ form.errors.district_id }}</p>
            </div>
</div>
        <div class="space-y-6">
            <!-- Ward Select -->
            <div>
                <label for="ward_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Ward
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-arrow-right-to-city"></i>
                        </span>
                    </div>
                <select
                    id="ward_id"
                    name="ward_id"
                    v-model="form.ward_id"
                    :class="{
                        'border-red-500 ring-1 ring-red-500': form.errors.ward_id
                    }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        :disabled="!form.district_id"
                >
                    <option value="" disabled selected>Select a Ward</option>
                    <option v-for="ward in filteredWards" :key="ward.id" :value="ward.id">
                        {{ ward.name }}
                    </option>
                </select>
                </div>
                <p v-if="form.errors.ward_id" class="text-red-500 text-xs mt-2">{{ form.errors.ward_id }}</p>
            </div>

            
            <!-- Role Select -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Role
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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
                        <option value="donor">Donor</option>
                        <option value="recipient">Recipient</option>
                        <option value="hospital">Hospital</option>
                        <option value="organization">Organization</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <p v-if="form.errors.role" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.role }}</p>

            </div>

            <!-- Blood Type Select -->
            <div class="mb-4">
                <label for="blood_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Blood Type
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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
                        <option></option>
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

        </div>

        </div>

            

            <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-full"/>


            <PrimaryBtn :disabled="form.processing">Update User</PrimaryBtn>
        </form>
    </Container>
</div>
</div>
</template>
