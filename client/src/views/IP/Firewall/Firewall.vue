<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'

import { useFirewallStore } from '../../../stores/firewall.js'
import { useRouterStore } from '../../../stores/router.js'

import { useRouter } from 'vue-router'
import FirewallEdit from './FirewallEdit.vue'

const firewallStore = useFirewallStore()
const routerStore = useRouterStore()

const axiosApi = inject('axiosApi')
const router = useRouter()

var router_rules = ref("all")
var selected_rule= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadRules = ((active_routers) => { firewallStore.loadRules(router_rules,active_routers) })
const rules = computed(() => { return firewallStore.getRules() })

const deleteRule = (rule) => {

    firewallStore.deleteRules(rule)

}


const showRule = (rule) => {


    selected_rule.value = rule

}

watch(router_rules, () => {

    let active_routers = []


    if(router_rules.value=='all'){
        routers.value.forEach(element => {

            if(!element.disabled){
                active_routers.push(element.id)

            }
        })
    }
    loadRules(active_routers)
})

onBeforeMount(() => {

    loadRouters()

    let active_routers = []

    routers.value.forEach(element => {

        if(!element.disabled){
            active_routers.push(element.id)

        }
    })

    loadRules(active_routers)

})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <select class="form-select" v-model="router_rules">
                        <option value="all" v-if="routers.length > 0">All</option>
                        <option value="all" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">Firewall</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Firewall Rules</h4>
                            <div class="px-1">
                                <router-link :to="{ name: 'FirewallCreate'}"
                                :title="`Create Rule`">
                                <button class="btn btn-primary">Add Rule</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <table class="table table-responsive align-middle" >
                            <thead class="table-light">

                                <tr>
                                    <th style="width:7%;">#ID</th>
                                    <th>Router</th>
                                    <th>Action</th>
                                    <th>Chain</th>
                                    <th>Src-address</th>
                                    <th>Dst-address</th>
                                    <th>Src-port</th>
                                    <th>Dst-port</th>
                                    <th>Protocol</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-if="rules.length==0">
                                    <td colspan="11" class="text-center" style="height:55px!important;">There are no rules.</td>
                                </tr>
                                <tr v-for="rule in rules">

                                    <td v-if="rule['.id']==undefined">-</td>
                                    <td v-else>{{rule['.id'].substring(1)}}</td>

                                    <td v-if="rule.router==undefined">-</td>
                                    <td v-else>#{{rule.router}}</td>

                                    <td v-if="rule.action==undefined">-</td>
                                    <td v-else>{{rule.action}}</td>

                                    <td v-if="rule.chain==undefined">-</td>
                                    <td v-else>{{rule.chain}}</td>

                                    <td v-if="rule['src-address']==undefined">-</td>
                                    <td v-else>{{rule['src-address']}}</td>

                                    <td v-if="rule['dst-address']==undefined">-</td>
                                    <td v-else>{{rule['dst-address']}}</td>

                                    <td v-if="rule['src-port']==undefined">-</td>
                                    <td v-else>{{rule['src-port']}}</td>

                                    <td v-if="rule['dst-port']==undefined">-</td>
                                    <td v-else>{{rule['dst-port']}}</td>

                                    <td v-if="rule.protocol==undefined">-</td>
                                    <td v-else>{{rule.protocol}}</td>

                                    <td class="text-center" v-if="rule.disabled==undefined">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="rule.disabled=='false'">
                                        <span class="badge badge-success-lighten">Active</span>
                                    </td>
                                    <td class="text-center" v-if="rule.disabled=='true'">
                                        <span class="badge badge-danger-lighten">Disabled</span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-xs btn-light table-button" title="Edit" @click="showRule(rule)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteRule(rule)">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <firewall-edit :rule="selected_rule" v-if="selected_rule"></firewall-edit>
            </div>
        </div>
    </div>
</div>
</template>
