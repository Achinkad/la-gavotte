<script setup>
import { inject,onBeforeMount,ref } from 'vue'
import { useRouterStore } from "../stores/router.js"

const routerStore = useRouterStore()
const axiosRouter = inject('axiosRouter')
const routerUrl = inject('routerUrl')
const routers=ref([])

onBeforeMount(() => {
   routers.value=routerStore.getRouters()
   console.log(routers)
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Interfaces</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Title here</h4>
                     
                </div>
               
                <div class="card-body pt-0">
                    <p>Lorem ipsum.</p>
                    <select class="custom-select custom-select-lg ">
                    <option selected>All</option>
                    <option value="1"></option>
                </select>
                 <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Router SSID</th>
                                        <th>IP Address</th>
                                        <th>MAC Address</th>
                                        <th class="text-center" style="width: 20%">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">#1</td>
                                        <td>BERTOLO</td>
                                        <td>192.168.10.10</td>
                                        <td>C4:AD:34:9F:52:8A</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light" title="View">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</template>
