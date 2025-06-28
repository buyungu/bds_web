<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import Container from '../../../Components/Container.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import Title from '../../../Components/Title.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';

const props = defineProps({
    user: Object,
    status: String,

})
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: null,
    preview: props.user.avatar ? `/storage/${props.user.avatar}` : null,  // If there's an avatar, use it
    _method: 'PATCH',
});

// Change avatar logic stays the same
const change = (e) => {
    form.avatar = e.target.files[0];
    form.preview = URL.createObjectURL(e.target.files[0]);
};

const submit = () => {
    form.post(route("profile.info"));
}

const resendEmail = (e) => {
    router.post(route('verification.send'), {}, {
        onStart: () => e.target.disabled = true,
        onFinish: () => e.target.disabled = false,
    })
}
</script>

<template>
    <Container class="mb-8">
        <div class="mb-6">
            <Title>Update Information</Title>
            <p>
                Update Your Accounts Information and email address.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-4 items-center">
                <div class="space-y-6 order-2 md:order-1">
                    <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-full"/>

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
                        <p v-if="form.errors.name" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.name }}</p>
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
                        <p v-if="form.errors.email" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.email }}</p>
                    </div>

                    <div v-if="user.email_verified_at === null" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <p class="text-sm">
                            Your Email Password is unverified
                        </p>
                        <button
                            @click="resendEmail"
                            type="button"
                            class="font-medium text-blue-500
                            hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500 underline disabled:text-slate-400 disabled:cursor-wait text-left sm:text-center"
                        >
                            Click here to resend the verification email
                        </button>
                    </div>
                </div>

                <div class="order-1 md:order-2 flex justify-center md:py-0">
                    <div
                        class="relative w-[200px] h-[200px] sm:w-[250px] sm:h-[250px] md:w-[300px] md:h-[300px] rounded-full overflow-hidden border border-slate-300 lg:-my-16"
                    >
                        <label for="avatar" class="absolute inset-0 grid content-end cursor-pointer">
                            <span class="bg-white/70 pb-2 text-center text-sm">Avatar</span>
                        </label>
                        <input type="file" @input="change" id="avatar" hidden />

                        <img
                            class="object-cover w-full h-full"
                            :src="form.preview ?? '/storage/avatars/default.jpg'"
                        />
                    </div>
                    <p class="error mt-2 text-red-500 text-sm font-medium text-center">{{ form.errors.avatar }}</p>
                </div>
            </div>

            <PrimaryBtn :disabled="form.processing" class="w-full sm:w-auto">Save</PrimaryBtn>
        </form>
    </Container>
</template>