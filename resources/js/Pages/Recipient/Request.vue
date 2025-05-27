<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { useForm, usePage } from '@inertiajs/vue3';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import SessionMessage from '../../Components/SessionMessage.vue';

const props = defineProps({
    hospitals: Array,
    status: String,
})

const user = usePage().props.auth.user;

const form = useForm({
    hospital_id: '',
    blood_type: '',
    quantity: '',
    urgency: '',
})

const submit = () => {
    form.post(route("requests.store"), {
        onFinish: () => form.reset(),
    });
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Request Blood" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class=" p-8">
        <Container class="">
        <div class="text-center mb-8">
            <Title>Request Blood</Title>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            
            <SessionMessage :status="status" v-if="form.recentlySuccessful" />

            <div class="flex gap-8">
                <div class="space-y-6 w-full">
            <!-- Hospital Select -->
            <div>
                <label for="hospital_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Hospital
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-city"></i>
                        </span>
                    </div>
                <select
                    id="hospital_id"
                    name="hospital_id"
                    v-model="form.hospital_id"
                    :class="{
                        'border-red-500 ring-1 ring-red-500': form.errors.hospital_id
                    }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"                        >
                    <option value="" disabled selected>Select an Hospital</option>
                    <option v-for="hospital in props.hospitals" :key="hospital.id" :value="hospital.id">
                        {{ hospital.name }}
                    </option>
                </select>
            </div>
                <p v-if="form.errors.hospital_id" class="text-red-500 text-xs mt-2">{{ form.errors.hospital_id }}</p>
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

        <div class="space-y-6 w-full">

            <!-- Quantity Input -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Quantity
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        v-model="form.quantity"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.quantity
                        }"
                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <p v-if="form.errors.quantity" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.quantity }}</p>

            </div>

            <!-- Urgency Select -->
            <div class="mb-4">
                <label for="blood_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Urgency
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-droplet"></i>
                        </span>
                    </div>
                    <select
                        id="urgency"
                        name="urgency"
                        v-model="form.urgency"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.urgency
                        }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    >
                        <option></option>
                        <option value="emergence">Emergence</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <p v-if="form.errors.urgency" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.urgency }}</p>

            </div>

        </div>
            
        </div>

            <PrimaryBtn :disabled="form.processing">Request</PrimaryBtn>
        </form>
    </Container>
    </div>
    </div>
</template>

