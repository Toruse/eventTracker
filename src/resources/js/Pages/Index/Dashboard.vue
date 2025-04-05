<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import ControlPanelResource from "@/Pages/Index/Partials/ControlPanelResource.vue";

defineProps({
    resources: {
        type: Object,
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <ControlPanelResource :resources="resources"/>
                </div>


                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="text-gray-900">You're logged in!</div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
