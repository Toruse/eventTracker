<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const resource = usePage().props.resource;

const form = useForm({
    domain: resource.data.domain
});

const submitEdit = () => {
    form.put(route("resources.update", resource.data.id), {
        preserveScroll: true,
    });
};

</script>

<template>
    <Head title="Edit resource" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit resource {{ resource.data.code }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="submitEdit">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Domain</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">To track events on a site, please specify its domain name.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="col-span-full">
                                        <div class="mt-2">
                                            <input
                                                v-model="form.domain"
                                                type="text"
                                                id="domain"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                :class="{'text-red-900 focus:ring-red-500 focus:border-red-500 border-red-300':form.errors.domain,}"
                                            />
                                        </div>
                                        <InputError :message="form.errors.domain" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <Link :href="route('dashboard')" class="text-sm/6 font-semibold text-gray-900">Cancel</Link>
                            <PrimaryButton :disabled="form.processing">Update</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Update.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
