<script setup>
    import { useForm } from '@inertiajs/vue3';
    import Container from '../Components/Container.vue';
    import PrimaryBtn from '../Components/PrimaryBtn.vue';
    import TextLink from '../Components/TextLink.vue';
    import Title from '../Components/Title.vue';
    import { computed } from 'vue';


    const form = useForm({
        name: "",
        email: "",
        blood_type: "",
        role: "",
        password: "",
        password_confirmation: "",
        phone: "",
        location: "",
        avatar: null,
        preview: null,
    });

    const change = (e) => {
        form.avatar = e.target.files[0];
        form.preview = URL.createObjectURL(e.target.files[0]);
    };

    const submit = () => {
        form.post(route("register"), {
            onFinish: () => form.reset("password", "password_confirmation"),
        });
    };

    const setPlace = (place) => {
        console.log('setPlace', place);
    }
</script>

<template>
    <Head title="| Register" />
    <Container class="w-full">
        <div class="text-center mb-8">
            <Title>Register a new account</Title>
            <p>Already have an account? <TextLink neno="Login" routeName="login" /></p>
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
                    :src="form.preview ?? 'storage/avatars/default.jpg'"
                />
                </div>

                <p class="error mt-2">{{ form.errors.avatar }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
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

            <!-- Phone Input -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Phone
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
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

            <!-- Location Input -->
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
                        @place_changed="setPlace"
                        id="location"
                        name="location"
                        v-model="form.location"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.location
                        }"
                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    >
                    </GMapAutocomplete> 
                </div>
                <p v-if="form.errors.location" class="text-red-500 text-xs mt-2">{{ form.errors.location }}</p>
            </div>
            </div>

            <div class="space-y-6">
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
                        <option value="">role</option>
                        <option value="hospital">Hospital</option>
                        <option value="organization">organization</option>
                        <option value="user">user</option>
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
                <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>

            </div>

            <!-- Confirm Password Input -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Confirm Password
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
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.password
                        }"
                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
            </div>
            </div>
            </div>

            <p class="text-slate-500 dark:text-slate-400">
                By creating an account you agree to our Terms of service and Privacy policy.
            </p>

            <PrimaryBtn :disabled="form.processing">Register</PrimaryBtn>
        </form>
    </Container>
</template>
