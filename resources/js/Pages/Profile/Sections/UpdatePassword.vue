<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../../../Components/Container.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import Title from '../../../Components/Title.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';

defineProps({
    message: String
});
const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.put(route("profile.password"), {
        onSuccess: () => form.reset(),
        onError: () => form.reset(),
        preserveScroll: true,
    });
}

</script>

<template>
    <Container class="mb-8">
        <div class="mb-6">
            <Title>Update Password</Title>
            <p>
                Ensure you are using a long, Random Password to stay secure
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <SessionMessage :status="message" v-if="form.recentlySuccessful" class="w-1/2"/>
            <!-- Email Input -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Current Password
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        v-model="form.current_password"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.current_password
                        }"
                        class="block w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <!-- Displaying error for current_password -->
                <p v-if="form.errors.current_password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.current_password }}</p>
            </div>

            <!-- Email Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Password
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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
                        class="block w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <!-- Displaying error for password -->
                <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>
            </div>
            <!-- New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Confirm New Password
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        v-model="form.password_confirmation"
                        class="block w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <!-- Displaying error for password_confirmation -->
                <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password_confirmation }}</p>
            </div>


            <PrimaryBtn :disabled="form.processing">Update Password</PrimaryBtn>
        </form>
    </Container>
</template>
