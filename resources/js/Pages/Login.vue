<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../Components/Container.vue';
import PrimaryBtn from '../Components/PrimaryBtn.vue';
import TextLink from '../Components/TextLink.vue';
import Title from '../Components/Title.vue';
import SessionMessage from '../Components/SessionMessage.vue';

defineProps({ status: String });

const form = useForm({
    email: "",
    password: "",
    remember: null,
});

// Method to handle form submission
const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="| Login" />
    <Container class="w-full">
        <div class="text-center mb-8">
            <Title>Login to your account</Title>
            <p>Don't have an account? <TextLink neno="Register" routeName="register" /></p>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <SessionMessage :status="status" />
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
                <!-- Displaying error for email -->
                <p v-if="form.errors.email" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.email }}</p>
            </div>

            <!-- Password Input -->
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
                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <!-- Displaying error for password -->
                <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        v-model="form.remember"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label for="remember" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <TextLink neno="Forgot your password?" routeName="password.request" />
                </div>
            </div>

            <PrimaryBtn :disabled="form.processing">Login</PrimaryBtn>
        </form>
    </Container>
</template>
