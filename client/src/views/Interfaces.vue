<script setup>
import { inject,onBeforeMount,ref,computed } from 'vue'
import { useRouterStore } from "../stores/router.js"

const routerStore = useRouterStore()
const axiosRouter = inject('axiosRouter')
const routerUrl = inject('routerUrl')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

onBeforeMount(() => {
   loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Interfaces{{routers}}</h2>
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
                <select class="custom-select custom-select-lg ">
                    <option selected>Type</option>
                    <option value="1"></option>
                </select>

                 <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Actual MTU</th>
                                        <th>L2 MTU</th>
                                        <th>TX</th>
                                        <th>RX</th>
                                        <th class="text-center" style="width: 20%">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                   
                                        <td>a</td>
                                        <td>a</td>
                                        <td>a</td>
                                        <td>a</td>
                                        <td>a</td>
                                        <td>a</td>
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
