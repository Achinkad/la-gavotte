<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useRouter } from 'vue-router'

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()
import OspfInstanceEdit from "./OSPFInstanceEdit.vue"

var router_ospf = ref("-")
var selected_ospf= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadInstancesOSPF = (() => { routingStore.loadInstancesOSPF(router_ospf) })
const ospfinstances = computed(() => { return routingStore.getInstancesOSPF() })


const deleteInstancesOSPF = (ospfinstance) => {

    routingStore.deleteInstancesOSPF(ospfinstance)

}


const showInstancesOSPF = (ospfinstance) => {
    if(ospfinstance.redistribute!=undefined && !(ospfinstance.redistribute instanceof Array)){
        ospfinstance.redistribute = ospfinstance.redistribute.split(',')
    }


    selected_ospf.value = ospfinstance

}

watch(router_ospf, () => {
    loadInstancesOSPF()
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
                            <h4 class="header-title">OSPF Instances</h4>
                            <div class="px-1">
                                <router-link :to="{ name: 'OSPFInstanceCreate'}"
                                :title="`Create OSPF Instance`">
                                <button class="btn btn-primary">Add OSPF Instance</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <table class="table table-responsive align-middle" >
                            <thead class="table-light">

                                <tr>
                                    <th>#ID</th>
                                    <th>Name</th>
                                    <th>Router ID</th>
                                    <th>Redistribute</th>
                                    <th>Version</th>
                                    <th>About</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>

                                </tr>

                            </thead>
                            <tbody>
                                <tr v-if="ospfinstances.length==0">
                                    <td colspan="8" class="text-center" style="height:55px!important;">There are no OSPF Instances.</td>
                                </tr>
                                <tr v-for="ospfinstance in ospfinstances">

                                    <td v-if="ospfinstance['.id']==undefined">-</td>
                                    <td v-else>{{ospfinstance['.id'].substring(1)}}</td>

                                    <td v-if="ospfinstance.name==undefined">-</td>
                                    <td v-else>{{ospfinstance.name}}</td>

                                    <td v-if="ospfinstance['router-id']==undefined">-</td>
                                    <td v-else>{{ospfinstance['router-id']}}</td>

                                    <td v-if="ospfinstance.redistribute==undefined">-</td>
                                    <td v-else>{{ospfinstance.redistribute}}</td>

                                    <td v-if="ospfinstance.version==undefined">-</td>
                                    <td v-else>{{ospfinstance.version}}</td>

                                    <td v-if="ospfinstance['.about']==undefined">Everything OK</td>
                                    <td class="text-danger" v-else>{{ospfinstance['.about']}}</td>

                                    <td class="text-center" v-if="ospfinstance.disabled==undefined">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="ospfinstance.disabled=='false'">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="ospfinstance.disabled=='true'">
                                        <span class="badge badge-danger-lighten">Disabled</span>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center">

                                            <button class="btn btn-xs btn-light table-button" title="Edit" @click="showInstancesOSPF(ospfinstance)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteInstancesOSPF(ospfinstance)">
                                                <i class="bi bi-trash3"></i>
                                            </button>

                                        </div>

                                    </td>



                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <ospf-instance-edit :ospfinstance="selected_ospf" v-if="selected_ospf"></ospf-instance-edit>

            </div>
        </div>
    </div>
</div>
</template>
