<script setup>
import { useUserStore } from '../stores/user.js'

const userStore = useUserStore()
</script>

<template>
    <div class="row" v-if="userStore.user">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Welcome back, {{ userStore.user.name }}!</h2>
                    <p style="margin-top:13px;">Web analytics dashboard for all your Mikrotik devices.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-5 col-lg-12">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-router-fill card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">Routers</h5>
                            <h3 class="mt-3 mb-3">261</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    98%
                                </span>
                                <span class="text-nowrap">Active right now</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-database-fill card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">Backups</h5>
                            <h3 class="mt-3 mb-3">143</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    100%
                                </span>
                                <span class="text-nowrap">Backups done</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-lg-7">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Title here.</h4>
                </div>
                <div class="card-body pt-0">
                    <p>Lorem ipsum.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.widget-flat {
    position: relative;
    overflow: hidden;
}

.card {
    border: none;
    margin-bottom: 24px;
    box-shadow: 0 0 35px 0 rgba(154, 161, 171, .15) !important;
    border-radius: 3px;
}

.card-header {
    margin-top: 0;
    background-color: #fff;
    border: 0;
    margin-bottom: 0;
    padding: 1.5rem;
}

.card-header .header-title {
    text-transform: uppercase;
    letter-spacing: .02em;
    font-size: .9rem;
    margin-top: 0;
}

.card-body {
    padding: 1.5rem;
}

.card-icon {
    color: #f0bc74;
    font-size: 16px;
    background-color: rgba(255, 240, 155, 0.25);
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 40px;
    border-radius: 3px;
    display: inline-block;
}

.text-muted {
    color: #8a969c !important;
}

.mt-3 {
    margin-top: 1.5rem !important;
}

.mb-3 {
    margin-bottom: 1.5rem !important;
}

.me-2 {
    margin-right: .75rem !important;
}

.text-success {
    color: rgb(10, 207, 151) !important;
}

.text-danger {
    color: rgb(250, 92, 124) !important;
}

#arrow-icons {
    font-size: 14.4px;
    margin: 0;
}

.bi {
    margin: 0 !important;
}

.text-nowrap {
    font-size: 14.4px;
    color: rgb(138, 150, 156);
}
</style>
