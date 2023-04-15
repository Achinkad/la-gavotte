<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useFirewallStore } from "../../../stores/firewall.js"
import { useRouterStore } from "../../../stores/router.js"

const firewallStore = useFirewallStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const routerIdentification = ref("all")
const protocolNegation = ref('false')



const rule = ref({
    action: 'accept',
    chain: 'forward',

})

const createRule = () => {

    let active_routers = []

    let formData = new FormData()

    formData.append('action', rule.value.action)
    formData.append('chain', rule.value.chain)
    if(rule.value['src-address']!=undefined){
        formData.append('src_address', rule.value['src-address'])
    }
    if(rule.value['dst-address']!=undefined){
        formData.append('dst_address', rule.value['dst-address'])
    }
    
    if(rule.value['src-port']!=undefined){
        formData.append('src_port', rule.value['src-port'])
    }
    
    if(rule.value['dst-port']){
        formData.append('dst_port', rule.value['dst-port'])
    }
    

    if(rule.value.protocol!=undefined){
        formData.append('protocol', rule.value.protocol)
    }
    

    routers.value.forEach(element => {
   
        if(!element.disabled){
            active_routers.push(element.id)
           
        }
    })

    formData.append('active_routers', active_routers)
    formData.append('identity', routerIdentification.value)

    firewallStore.createRules(formData)

}

const editProtocol = () => {
    protocolNegation.value = protocolNegation.value =='true' ? 'false' : 'true';

}

onBeforeMount(() => {
   
    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create Rule</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <div class="px-1">
                            <router-link :to="{ name: 'Firewall'}"
                                                    :title="`Firewall`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createRule">
                            <div class="col-12 mt-3">
                            <label>Select Router</label>
                                <select class="form-select" v-model="routerIdentification">
                                    <option value="all" v-if="routers.length > 0">All</option>
                                    <option value="all" selected hidden disabled v-else>Loading routers...</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            

                                <div class="col-6 mt-3">
                                    <label>Select Action</label>
                                    <select class="form-select" v-model="rule.action">
                                        <option value="accept">accept</option>
                                        <option value="add-dst-to-address-list">add-dst-to-address-list</option>
                                        <option value="add-src-to-address-list">add-src-to-address-list</option>
                                        <option value="drop">drop</option>
                                        <option value="fasttrack-connection">fasttrack-connection</option>
                                        <option value="jump">jump</option>
                                        <option value="log">log</option>
                                        <option value="passthrough">passthrough</option>
                                        <option value="reject">reject</option>
                                        <option value="return">return</option>
                                        <option value="tarpit">tarpit</option>
                                    </select>
                                </div>

                                <div class="col-6 mt-3">
                                    <label>Select Chain</label>
                                    <select class="form-select" v-model="rule.chain">
                                        <option value="forward">forward</option>
                                        <option value="input">input</option>
                                        <option value="output">output</option>
                                    </select>
                                </div>


                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Src-Address</label>
                                    <input type="text" class="form-control" id="src_address" placeholder="Enter the Src Address"
                                    pattern="^!?\b(?:\d{1,3}\.){3}\d{1,3}\b$"
                                    v-model="rule['src-address']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="dst_address" class="form-label">Dst-Address</label>
                                    <input type="text" class="form-control" id="dst_address" placeholder="Enter the Dst Address"
                                    pattern="^!?\b(?:\d{1,3}\.){3}\d{1,3}\b$"
                                    v-model="rule['dst-address']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_port" class="form-label">Src-Port</label>
                                    <input type="text" class="form-control" id="src_port" placeholder="Enter the Src Port" v-model="rule['src-port']" :disabled="protocolNegation=='true' || (rule.protocol!='udp'  && rule.protocol!='tcp' && rule.protocol!=undefined) ">
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="dst_port" class="form-label">Dst-Port</label>
                                    <input type="text" class="form-control" id="dst_port" placeholder="Enter the Dst Port" v-model="rule['dst-port']" :disabled="protocolNegation=='true' || (rule.protocol!='udp'  && rule.protocol!='tcp' && rule.protocol!=undefined)">
                                </div>

                                <div class="col-2 mt-3">
                                    <label>Negate Protocol</label>
                                    <input type="checkbox" class="custom-control-input" @click="editProtocol()">
                                </div>


                               
                                <div class="col-10 mt-3" v-if="protocolNegation=='false'">
                                   <label>Select Protocol</label>
                                    <select class="form-select" v-model="rule.protocol">
                                    
                                        <option value="icmp">icmp</option>
                                        <option value="igmp">igmp</option>
                                        <option value="ggp">ggp</option>
                                        <option value="ip-encap">ip-encap</option>
                                        <option value="st">st</option>
                                        <option value="tcp">tcp</option>
                                        <option value="egp">egp</option>
                                        <option value="pup">pup</option>
                                        <option value="udp">udp</option>
                                        <option value="hmp">hmp</option>
                                        <option value="xns-idp">xns-idp</option>
                                        <option value="rdp">rdp</option>
                                        <option value="iso-tp4">iso-tp4</option>
                                        <option value="dccp">dccp</option>
                                        <option value="xtp">xtp</option>
                                        <option value="ddp">ddp</option>
                                        <option value="idpr-cmtp">idpr-cmtp</option>
                                        <option value="ipv6-encap">ipv6-encap</option>
                                        <option value="rsvp">rsvp</option>
                                        <option value="gre">gre</option>
                                        <option value="ipsec-esp">ipsec-esp</option>
                                        <option value="ipsec-ah">ipsec-ah</option>
                                        <option value="rspf">rspf</option>
                                        <option value="vmtp">vmtp</option>
                                        <option value="ospf">ospf</option>
                                        <option value="ipip">ipip</option>
                                        <option value="etherip">etherip</option>
                                        <option value="encap">encap</option>
                                        <option value="pim">pim</option>
                                        <option value="vrrp">vrrp</option>
                                        <option value="l2tp">l2tp</option>
                                        <option value="sctp">sctp</option>
                                        <option value="udp-lite">udp-lite</option>
                                    </select>
                                </div>

                                <div class="col-10 mt-3" v-if="protocolNegation=='true'">
                                     <label>Select Protocol</label>
                                    <select class="form-select" v-model="rule.protocol">
                                        <option value="!icmp">!icmp</option>
                                        <option value="!igmp">!igmp</option>
                                        <option value="!ggp">!ggp</option>
                                        <option value="!ip-encap">!ip-encap</option>
                                        <option value="!st">!st</option>
                                        <option value="!tcp">!tcp</option>
                                        <option value="!egp">!egp</option>
                                        <option value="!pup">!pup</option>
                                        <option value="!udp">!udp</option>
                                        <option value="!hmp">!hmp</option>
                                        <option value="!xns-idp">!xns-idp</option>
                                        <option value="!rdp">!rdp</option>
                                        <option value="!iso-tp4">!iso-tp4</option>
                                        <option value="!dccp">!dccp</option>
                                        <option value="!xtp">!xtp</option>
                                        <option value="!ddp">!ddp</option>
                                        <option value="!idpr-cmtp">!idpr-cmtp</option>
                                        <option value="!ipv6-encap">!ipv6-encap</option>
                                        <option value="!rsvp">!rsvp</option>
                                        <option value="!gre">!gre</option>
                                        <option value="!ipsec-esp">!ipsec-esp</option>
                                        <option value="!ipsec-ah">!ipsec-ah</option>
                                        <option value="!rspf">!rspf</option>
                                        <option value="!vmtp">!vmtp</option>
                                        <option value="!ospf">!ospf</option>
                                        <option value="!ipip">!ipip</option>
                                        <option value="!etherip">!etherip</option>
                                        <option value="!encap">!encap</option>
                                        <option value="!pim">!pim</option>
                                        <option value="!vrrp">!vrrp</option>
                                        <option value="!l2tp">!l2tp</option>
                                        <option value="!sctp">!sctp</option>
                                        <option value="!udp-lite">!udp-lite</option>
                                    </select>     
                              
                                </div>
                            
                            <div class="col-12 mt-4 d-flex justify-content-end">
                            <div class="px-1">
                                <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                            </div>
                            <div class="px-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
