<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useInterfaceStore } from "../../../stores/interface.js"
import { useRouter } from 'vue-router'

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const interfaceStore = useInterfaceStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()
import OspfTemplateEdit from "./OSPFTemplateEdit.vue"

var router_ospf = ref("-")
var selected_ospf= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const loadTemplatesOSPF = (() => { routingStore.loadTemplatesOSPF(router_ospf) })
const ospftemplates = computed(() => { return routingStore.getTemplatesOSPF() })


const loadAreasOSPF = (() => { routingStore.loadAreasOSPF(router_ospf) })
const ospfareas = computed(() => { return routingStore.getAreasOSPF() })

const loadInterfaces = (() => { interfaceStore.loadInterfaces(router_ospf,type_all) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const type_all = ref("all")


const deleteTemplatesOSPF = (ospftemplate) => {

    routingStore.deleteTemplatesOSPF(ospftemplate)

}


const showTemplatesOSPF = (ospftemplate) => {

    if(ospftemplate.interfaces!=undefined && ospftemplate.interfaces!="" && !(ospftemplate.interfaces instanceof Array)){
        ospftemplate.interfaces = ospftemplate.interfaces.split(',')
    }

    if(ospftemplate.interfaces==undefined ||ospftemplate.interfaces=="" && !(ospftemplate.interfaces instanceof Array)){
        ospftemplate.interfaces = []
    }


    loadAreasOSPF()
    loadInterfaces()
    selected_ospf.value = ospftemplate

}

watch(router_ospf, () => {
    loadTemplatesOSPF()
})

onBeforeMount(() => {

    loadRouters()

})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <select class="form-select" v-model="router_ospf">
                        <option value="-" selected hidden disabled v-if="routers.length > 0">Select a router</option>
                        <option value="-" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">OSPF</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">OSPF Interface-Templates</h4>
                            <div class="px-1">
                                <router-link :to="{ name: 'OSPFTemplateCreate'}"
                                :title="`Create OSPF interface-template`">
                                <button class="btn btn-primary">Add OSPF Interface-Template</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <table class="table table-responsive align-middle" >
                            <thead class="table-light">

                                <tr>
                                    <th>#ID</th>
                                    <th>Interfaces</th>
                                    <th>Area</th>
                                    <th>Networks</th>
                                    <th>Network Type</th>
                                    <th>Cost</th>
                                    <th>Priority</th>
                                    <th>About</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-if="ospftemplates.length==0">
                                    <td colspan="10" class="text-center" style="height:55px!important;">There are no OSPF Interfaces-Templates.</td>
                                </tr>
                                <tr v-for="ospftemplate in ospftemplates">

                                    <td v-if="ospftemplate['.id']==undefined">-</td>
                                    <td v-else>{{ospftemplate['.id'].substring(1)}}</td>

                                    <td v-if="ospftemplate.interfaces==undefined || ospftemplate.interfaces==''">-</td>
                                    <td v-else>{{ospftemplate.interfaces}}</td>

                                    <td v-if="ospftemplate.area==undefined">-</td>
                                    <td v-else>{{ospftemplate.area}}</td>

                                    <td v-if="ospftemplate.networks==undefined">-</td>
                                    <td v-else>{{ospftemplate.networks}}</td>

                                    <td v-if="ospftemplate.type==undefined">-</td>
                                    <td v-else>{{ospftemplate.type}}</td>

                                    <td v-if="ospftemplate.cost==undefined">-</td>
                                    <td v-else>{{ospftemplate.cost}}</td>

                                    <td v-if="ospftemplate.priority==undefined">-</td>
                                    <td v-else>{{ospftemplate.priority}}</td>

                                    <td v-if="ospftemplate['.about']==undefined">Everything OK</td>
                                    <td class="text-danger" v-else>{{ospftemplate['.about']}}</td>

                                    <td class="text-center" v-if="ospftemplate.disabled==undefined">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="ospftemplate.disabled=='false'">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="ospftemplate.disabled=='true'">
                                        <span class="badge badge-danger-lighten">Disabled</span>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center">

                                            <button class="btn btn-xs btn-light table-button" title="Edit" @click="showTemplatesOSPF(ospftemplate)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteTemplatesOSPF(ospftemplate)">
                                                <i class="bi bi-trash3"></i>
                                            </button>

                                        </div>

                                    </td>



                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <ospf-template-edit :ospftemplate="selected_ospf" :ospfareas="ospfareas" :interfaces="interfaces" v-if="selected_ospf"></ospf-template-edit>

            </div>
        </div>
    </div>
</div>
</template>
