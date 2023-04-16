<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useFirewallStore } from "../../../stores/firewall.js"
const notyf = inject('notyf')

const firewallStore = useFirewallStore()

const protocolNegation = ref(props.rule.protocol!=undefined && props.rule.protocol[0]=='!' ? 'true' : 'false')

const props = defineProps({
    rule: {
        type: Object,
        required: true,
    }
   
})


const toggleDisabled = () => {

    props.rule.disabled = props.rule.disabled=='true' ? 'false' : 'true';

}

const editProtocol = () => {

    protocolNegation.value = protocolNegation.value =='true' ? 'false' : 'true';

}

const editBridge = () => { 
    
    let formData = new FormData()

    formData.append('rule_identity', props.rule['.id'])
    formData.append('action', props.rule.action)
    formData.append('chain', props.rule.chain)
    if(props.rule['src-address']!=undefined){
        formData.append('src_address', props.rule['src-address'])
    }
    if(props.rule['dst-address']!=undefined){
        formData.append('dst_address', props.rule['dst-address'])
    }
    
    if(props.rule['src-port']!=undefined){
        formData.append('src_port', props.rule['src-port'])
    }
    
    if(props.rule['dst-port']!=undefined){
        formData.append('dst_port', props.rule['dst-port'])
    }
    

    if(props.rule.protocol!=undefined){
        formData.append('protocol', props.rule.protocol)
    }
    
    formData.append('disabled',props.rule.disabled)

    formData.append('router_identity', props.rule.router)

   
    firewallStore.editRules(formData)
   
}

watch(() => props.rule, (newRule) => {
  
    
    if(newRule.protocol!=undefined && newRule.protocol[0]=='!'){
        protocolNegation.value='true'
    }
    if(newRule.protocol==undefined || newRule.protocol[0]!='!'){
        protocolNegation.value='false'
    }
})


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the bridge:{{rule['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editBridge">
         
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
                                    pattern="^!?\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:/(?:3[0-2]|[12]?[0-9]))?$"
                                    v-model="rule['src-address']">
                                    <div id="addressHelp" class="form-text">To negate the address just type "!" before the address</div>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="dst_address" class="form-label">Dst-Address</label>
                                    <input type="text" class="form-control" id="dst_address" placeholder="Enter the Dst Address"
                                    pattern="^!?\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:/(?:3[0-2]|[12]?[0-9]))?$"
                                    v-model="rule['dst-address']">
                                    <div id="addressHelp" class="form-text">To negate the address just type "!" before the address</div>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_port" class="form-label" >Src-Port [0-65535]</label>
                                    <input type="text" class="form-control" id="src_port" placeholder="Enter the Src Port"
                                    pattern="^!?([0-9]|[1-9][0-9]|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="rule['src-port']" :disabled="protocolNegation=='true' || (rule.protocol!='udp'  && rule.protocol!='tcp')">
                                    <div id="portHelp" class="form-text">To negate the port just type "!" before the port <br><u> NOTE: Src-Port only available when protocol is udp or tcp</u></div>
                                </div>
                                <div class="col-6 mt-3">
                                    <label for="dst_port" class="form-label" >Dst-Port [0-65535]</label>
                                    <input type="text" class="form-control" id="dst_port" placeholder="Enter the Dst Port" 
                                    pattern="^!?([0-9]|[1-9][0-9]|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="rule['dst-port']" :disabled="protocolNegation=='true' || (rule.protocol!='udp'  && rule.protocol!='tcp')">
                                    <div id="portHelp" class="form-text">To negate the port just type "!" before the port <br><u> NOTE: Dst-Port only available when protocol is udp or tcp</u></div>
                                </div>


                                <div class="col-2 mt-3">
                                    <label>Negate Protocol</label>
                                    <input type="checkbox" class="custom-control-input" @click="editProtocol()" :checked="protocolNegation=='true'">
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
                                <div class="form-check form-switch col-12 mt-3">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="rule.disabled!=undefined && rule.disabled != 'false'">
                                    <label class="form-check-label" for="flexSwitchCheckDefault"> Disabled</label>
                                    
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
</template>