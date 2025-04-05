<script setup>
import {
    AdjustmentsVerticalIcon,
    CheckIcon,
    ChevronDownIcon,
    PlusIcon,
    TrashIcon
} from "@heroicons/vue/20/solid/index.js";
import { ChevronDownIcon as ChevronDownIcon16 } from '@heroicons/vue/16/solid'
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
} from "@headlessui/vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";

defineProps({
    resources: {
        type: Object
    }
});

const res = usePage().props.resources
let selected = ref(res.data[0])

watch(
    () => usePage().props.resources,
    (val) => {
        selected = ref(val.data[0])
    }
);

const deleteForm = useForm({})
const deleteResource = () => {
    if (confirm("Are you sure you want to delete " + selected.value.code + " resource?")) {
        deleteForm.delete(route("resources.destroy", selected.value.id), {
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                <Listbox as="div" v-model="selected" v-if="selected">
                    <div class="relative">
                        <ListboxButton class="grid cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 text-xl">
                                <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
                                  <span class="block truncate">{{ selected.code }}</span>
                                </span>
                            <ChevronDownIcon16 class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" aria-hidden="true" />
                        </ListboxButton>
                    </div>
                    <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                        <ListboxOptions class="absolute z-10 mt-1 max-h-56 overflow-auto rounded-md bg-white py-1 text-base ring-1 shadow-lg ring-black/5 focus:outline-hidden sm:text-sm">
                            <ListboxOption as="template" v-for="resource in resources.data" :key="resource.id" :value="resource" v-slot="{ active, selected }">
                                <li :class="[active ? 'bg-indigo-600 text-white outline-hidden' : 'text-gray-900', 'relative cursor-default py-2 pr-9 pl-3 select-none']">
                                    <div class="flex items-center">
                                        <span :class="[selected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate']">{{ resource.code }}</span>
                                    </div>

                                    <span v-if="selected" :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                            <CheckIcon class="size-5" aria-hidden="true" />
                                          </span>
                                </li>
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </Listbox>
                <div v-else class="text-gray-900">not created</div>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
                <span class="sm:block">
                    <Link :href="route('resources.create')" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <PlusIcon class="mr-1.5 -ml-0.5 size-5" aria-hidden="true"/>
                        Add
                    </Link>
                </span>

                <span class="ml-3 hidden sm:block" v-if="selected">
                    <Link :href="route('resources.edit', selected.id)" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">
                      <AdjustmentsVerticalIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true"/>
                    </Link>
                </span>
                <span class="ml-3 hidden sm:block" v-if="selected">
                    <button @click="deleteResource(selected.id)" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">
                      <TrashIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true"/>
                    </button>
                </span>


            <!-- Dropdown -->
            <Menu as="div" class="relative ml-3 sm:hidden" v-if="selected">
                <MenuButton class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:ring-gray-400">
                    More
                    <ChevronDownIcon class="-mr-1 ml-1.5 size-5 text-gray-400" aria-hidden="true" />
                </MenuButton>

                <transition enter-active-class="transition ease-out duration-200" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems class="absolute right-0 z-10 mt-2 -mr-3 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                        <MenuItem v-slot="{ active }">
                            <Link :href="route('resources.edit', selected.id)" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</Link>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button @click="deleteResource(selected.id)" :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">Delete</button>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
</template>
