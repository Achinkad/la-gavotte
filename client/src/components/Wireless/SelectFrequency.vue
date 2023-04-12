<script setup>
const props = defineProps({
    network: { type: Object }
})

const arrayBuilder = ((minValue, maxValue, increment) => {
    let a = []
    for (let i = minValue; i <= maxValue; i += increment) a.push(i)
    return a
})

// 2.4Ghz Bands
const band2GHz = [ '2GHz-b', '2GHz-only-g', '2GHz-b/g', '2GHz-only-n', '2GHz-b/g/n', '2GHz-g/n' ]

// 5Ghz Bands
const band5GHz = [ '5GHz-a', '5GHz-only-n', '5GHz-a/n', '5GHz-a/n/ac', '5GHz-only-ac', '5GHz-n/ac' ]

// 2.4GHz Frequencies
const frequency20Mhz = arrayBuilder(2412, 2472, 5)
const frequency2040MhzeC = arrayBuilder(2432, 2472, 5)
const frequency2040MhzCeXX = arrayBuilder(2412, 2452, 5)

// 5GHz Frequencies
const frequency20Mhz5G = arrayBuilder(5180, 5700, 5)

// Channel Width Options
const channelWidthOptions2G = [ '20mhz', '10mhz', '5mhz', '20/40mhz-eC', '20/40mhz-Ce', '20/40mhz-XX' ]
const channelWidthOptions5G = [ '20mhz', '10mhz', '5mhz', '20/40mhz-eC', '20/40mhz-Ce', '20/40mhz-XX', '20/40/80mhz-Ceee', '20/40/80mhz-eCee', '20/40/80mhz-eeCe', '20/40/80mhz-eeeC', '20/40/80mhz-XXXX' ]
</script>

<template>
    <div class="col-3">
        <label for="selectBand" class="form-label">Band <span class="text-danger">*</span></label>
        <select class="form-select" id="selectBand" v-model="network.band">
            <option value="-" hidden disabled>Select a band</option>
            <option :value='network.band' selected hidden disabled>{{ network.band }}</option>
            <option v-for="i in band2GHz" :key="i" :value="i"
                v-if="network['.id'] == '*1'">{{ i }}</option>
            <option v-for="i in band5GHz" :key="i" :value="i"
                v-if="network['.id'] == '*2'">{{ i }}</option>
        </select>
    </div>

    <div class="col-3">
        <label for="username" class="form-label">Channel width <span class="text-danger">*</span></label>
        <select class="form-select" v-model="network['channel-width']">
            <option :value='network["channel-width"]' hidden selected>{{ network['channel-width'] }}</option>
            <option v-for="i in channelWidthOptions2G" :key="i" :value="i"
                v-if="network['.id'] == '*1'">{{ i }}</option>
            <option v-for="i in channelWidthOptions5G" :key="i" :value="i"
                v-if="network['.id'] == '*2'">{{ i }}</option>
        </select>
    </div>

    <div class="col-3">
        <label for="frequency" class="form-label">Frequency <span class="text-danger">*</span></label>
        <input class="form-control" list="frequency" id="frequencyInput" placeholder="Insert a frequency" v-model='network.frequency'>
        <datalist id="frequency">
            <option value="auto">auto</option>
            <option v-for="i in frequency20Mhz" :key="i" :value="i" v-if="network['channel-width'] == '20mhz' && network['.id'] == '*1'">{{ i }}</option>
            <option v-for="i in frequency2040MhzeC" :key="i" :value="i" v-if="network['channel-width'] == '20/40mhz-eC'">{{ i }}</option>
            <option v-for="i in frequency2040MhzCeXX" :key="i" :value="i" v-if="network['channel-width'] == '20/40mhz-Ce' || network['channel-width'] == '20/40mhz-XX'">{{ i }}</option>
            <option v-for="i in frequency20Mhz5G" :key="i" :value="i" v-if="network['channel-width'] == '20mhz' && network['.id'] == '*2'">{{ i }}</option>
        </datalist>
    </div>
</template>
