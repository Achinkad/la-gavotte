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
import BgpEdit from "./BGPEdit.vue"

var router_bgp = ref("all")
var selected_bgp= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadConnectionsBGP = (() => { routingStore.loadConnectionsBGP(router_bgp) })
const bgpconnections = computed(() => { return routingStore.getConnectionsBGP() })


const deleteConnectionsBGP = (bgpconnection) => {
    

    routingStore.deleteConnectionsBGP(bgpconnection)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
}


const showConnectionsBGP = (bgpconnection) => {

    selected_bgp.value = bgpconnection
    
}

watch(router_bgp, () => {
    loadConnectionsBGP()
})

onBeforeMount(() => {
   
    loadRouters()
    loadConnectionsBGP()

})
</script>

<template>
   <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">BGP</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">BGP Connections</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'BGPCreate'}"
                                                    :title="`Create BGP`">
                                    <button class="btn btn-primary">Add BGP Connection</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                        <select class="custom-select custom-select-lg" v-model="router_bgp">
                            <option value="all" selected>All</option>
                            <option :value="router.id" v-for="router in routers">{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Name</th>
                                        <th>Router ID</th>
                                        <th>Remote Address</th>
                                        <th>Local Address</th>
                                        <th>Remote AS</th>
                                        <th>AS</th>
                                        <th>Remote Port</th>
                                        <th>Local Port</th>
                                        <th>Local Role</th>
                                        <th>Disabled</th>
                                        <th>About</th>
                                        <th>Actions</th>
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="bgpconnections.length==0">
                                        <td colspan="14" class="text-center" style="height:55px!important;">There are no BGP Connections.</td>
                                    </tr>
                                    <tr v-for="bgpconnection in bgpconnections">
                                  
                                        <td>{{bgpconnection['.id'].substring(1)}}</td>
                                        <td>#{{bgpconnection.router}}</td>

                                        <td v-if="bgpconnection.name==undefined">-</td>
                                        <td v-else>{{bgpconnection.name}}</td>

                                        <td v-if="bgpconnection['router-id']==undefined">-</td>
                                        <td v-else>{{bgpconnection['router-id']}}</td>

                                        <td v-if="bgpconnection['remote.address']==undefined">-</td>
                                        <td v-else>{{bgpconnection['remote.address']}}</td>

                                        <td v-if="bgpconnection['local.address']==undefined">-</td>
                                        <td v-else>{{bgpconnection['local.address']}}</td>

                                        <td v-if="bgpconnection['remote.as']==undefined">-</td>
                                        <td v-else>{{bgpconnection['remote.as']}}</td>

                                        <td v-if="bgpconnection['as']==undefined">-</td>
                                        <td v-else>{{bgpconnection['as']}}</td>

                                        <td v-if="bgpconnection['remote.port']==undefined">-</td>
                                        <td v-else>{{bgpconnection['remote.port']}}</td>

                                        <td v-if="bgpconnection['local.port']==undefined">-</td>
                                        <td v-else>{{bgpconnection['local.port']}}</td>

                                        
                                        <td>{{bgpconnection['local.role']}}</td>

                                        <td class="text-success" v-if="bgpconnection.disabled==undefined">false</td>
                                        <td class="text-success" v-if="bgpconnection.disabled=='false'">{{bgpconnection.disabled}}</td>
                                        <td class="text-danger" v-if="bgpconnection.disabled=='true'">{{bgpconnection.disabled}}</td>

                                        <td v-if="bgpconnection['.about']==undefined">Everything OK</td>
                                        <td class="text-danger" v-else>{{bgpconnection['.about']}}</td>

                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteConnectionsBGP(bgpconnection)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showConnectionsBGP(bgpconnection)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        

                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

               <bgp-edit :bgpconnection="selected_bgp" v-if="selected_bgp"></bgp-edit> 
             
        </div>
     </div>
        </div>
        </div>
</template>
