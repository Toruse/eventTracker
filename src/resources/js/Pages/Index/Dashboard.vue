<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import { createTypedChart, Line } from 'vue-chartjs'
import { ChoroplethController, GeoFeature, ColorScale, ProjectionScale, topojson } from 'chartjs-chart-geo'
import ControlPanelResource from "@/Pages/Index/Partials/ControlPanelResource.vue";
import countriesTopoJson from "world-atlas/countries-50m.json";
const countries = topojson.feature(
    countriesTopoJson,
    countriesTopoJson.objects.countries
).features;

const props = defineProps({
    resources: Object,
    selectedResource: Object,
    selectedTab: Number
});

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ChoroplethController,
    GeoFeature,
    ColorScale,
    ProjectionScale
)

const Choropleth = createTypedChart("choropleth", "choropleth");

const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'Data One',
            backgroundColor: '#f87979',
            data: [40, 39, 10, 40, 39, 80, 40]
        }
    ]
}

const options = {
    responsive: true,
    // maintainAspectRatio: false
}

const chartData = {
    labels: countries.map(d => d.properties.name),
    datasets: [
        {
            label: "Countries",
            backgroundColor: context => {
                if (context.dataIndex == null) {
                    return null;
                }
                const value = context.dataset.data[context.dataIndex];
                return `rgb(0, 0, ${value.value * 255})`;
            },
            data: countries.map(d => ({ feature: d, value: Math.random() }))
        }
    ]
}

const chartOptions = {
    showOutline: true,
    showGraticule: true,
    plugins: {
        legend: {
            display: false
        },
    },
    scales: {
        projection: {
            axis: 'x',
            projection: 'equirectangular',
        },
        color: {
            axis: 'x',
            interpolate: (v) => (v < 0.5 ? 'green' : 'red'),
            legend: {
                position: 'bottom-right',
                align: 'right',
            },
        },
    },
}

    // | GeoProjection
    // | 'azimuthalEqualArea'
    // | 'azimuthalEquidistant'
    // | 'gnomonic'
    // | 'orthographic'
    // | 'stereographic'
    // | 'equalEarth'
    // | 'albers'
    // | 'albersUsa'
    // | 'conicConformal'
    // | 'conicEqualArea'
    // | 'conicEquidistant'
    // | 'equirectangular'
    // | 'mercator'
    // | 'transverseMercator'
    // | 'naturalEarth1';

let selectedResourceId = ref(props.selectedResource.data.id)

const changeTab = (index) => {
    router.visit(
        route('dashboard', {
            'selectedTab': index,
            'selectedResource': selectedResourceId.value
        }),
        {
            only: [
                'selectedTab',
                'selectedResource'
            ],
        },
        {
            preserveState: true
        }
    )
}
const eventChangeTab = (index) => {
    changeTab(index)
};

const eventChangeResource = (value) => {
    selectedResourceId.value = value.id
}

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
                    <ControlPanelResource
                        :resources="resources"
                        :selectedResource="selectedResource"
                        @update:modelValue="eventChangeResource"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <TabGroup :selectedIndex="selectedTab" @change="changeTab">
                        <TabList class="flex border-b border-solid border-gray-300">
                            <Tab as="template" v-slot="{ selected }">
                                <botton
                                    :class="[
                                        'px-1 py-4 mr-8 outline-none text-sm/2 font-medium border-b-2 text-gray-500 cursor-pointer',
                                        selected
                                            ? 'text-indigo-500 border-indigo-500'
                                            : 'border-transparent hover:text-gray-700 hover:border-b-2 hover:border-gray-300',
                                ]"
                                >Summary</botton>
                            </Tab>
                            <Tab as="template" v-slot="{ selected }">
                                <botton
                                    :class="[
                                        'px-1 py-4 mr-8 outline-none text-sm/2 font-medium border-b-2 text-gray-500 cursor-pointer',
                                        selected
                                            ? 'text-indigo-500 border-indigo-500'
                                            : 'border-transparent hover:text-gray-700 hover:border-b-2 hover:border-gray-300',
                                ]"
                                >Interaction</botton>
                            </Tab>
                            <Tab as="template" v-slot="{ selected }">
                                <botton
                                    :class="[
                                        'px-1 py-4 mr-8 outline-none text-sm/2 font-medium border-b-2 text-gray-500 cursor-pointer',
                                        selected
                                            ? 'text-indigo-500 border-indigo-500'
                                            : 'border-transparent hover:text-gray-700 hover:border-b-2 hover:border-gray-300',
                                ]"
                                >Events</botton>
                            </Tab>
                        </TabList>
                        <TabPanels class="mt-2">
                            <TabPanel>Content 1</TabPanel>
                            <TabPanel>Content 2</TabPanel>
                            <TabPanel>Content 3</TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="text-gray-900">
                        You're logged in!
                        <Line :data="data" :options="options" />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="text-gray-900">
                        <Choropleth :data="chartData" :options="chartOptions"/>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
