<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()
</script>

<template>
    <nav class="navbar navbar-expand-md flex-md-nowrap">
        <div class="container-fluid">
            <button id="buttonSidebarExpandId" ref="buttonSidebarExpand" class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
            aria-expanded="false" aria-label="Toggle navigation" style="position:absolute;top:15px;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="logo-alt">
            <router-link class="nav-link" :to="{ name: 'Dashboard' }">
                <span style="vertical-align">La Gavotte.</span>
            </router-link>
        </div>

        <div class="d-flex justify-content-between w-100">
            <div class="d-flex align-items-center">
                <form class="navbar-search form-inline">
                    <div class="input-group input-group-merge search-bar">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </form>
            </div>

            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown nav-user">
                        <a href="#" class="nav-link dropdown px-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <img src="../../assets/profile.jpg" alt="Profile Picture" class="avatar">
                                <div class="ms-2 text-dark align-items-center">
                                    <span class="mb-0 font-small text-gray-900" style="font-weight:600;">Alberto Ramusino</span>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 py-1">
                            <li> <a class="dropdown-item" href="#">Profile</a> </li>
                            <li> <a class="dropdown-item" href="#">Settings</a> </li>
                            <li> <a class="dropdown-item" href="#">Support</a> </li>
                            <li> <hr class="dropdown-divider"/> </li>
                            <li> <a class="dropdown-item" href="#">Logout</a> </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
</template>

<style scoped>
#buttonSidebarExpandId,
#buttonSidebarExpandId:focus,
#buttonSidebarExpandId:active {
    border: 0;
}

.avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
}

.nav-link {
    padding: 0;
    min-width: 32px;
    display: block;
    text-align: center;
    margin: 0 7px;
    margin-right: 7px;
    position: relative;
}

.navbar {
    height: 4.375rem;
    padding: 0 12px;
    min-height: 70px;
    position: fixed;
    left: 260px;
    top: 0;
    right: 0;
    z-index: 1000;
    /* box-shadow: 0 0 35px 0 rgba(154, 161, 171, .15) !important;
    background-color: #fff; */
}

.navbar-brand {
    background-color: transparent !important;
    box-shadow: none !important;
}

.topbar-divider {
    width: 0;
    border-right: 1px solid #e3e6f0;
    height: calc(4.375rem - 2rem);
    margin: auto 1rem;
}

.avatar-text {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 38px;
}

.logo-alt {
    height: 4.375rem;
    position: fixed;
    top: 0;
    color: #212529 !important;
    text-decoration: none !important;
    display: none;
}

.logo-alt .nav-link {
    height: 4.375rem;
    font-size: 1.313rem;
    font-weight: 800;
    margin: 0;
    color: #212529 !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-menu {
    padding-top: .5rem !important;
    padding-bottom: .5rem !important;
    margin-top: 1rem !important;
    background-color: #fff;
    border: .0625rem solid #e5e7eb;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);
}

.dropdown-menu, .dropdown-item {
    transition: all .2s ease;
    font-weight: 400;
}

.dropdown-item {
    padding-top: .375rem;
}

.dropdown-item:hover {
    color: #090d15;
    background-color: #f2f4f6;
}

.input-group {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    width: 100%;
}

.input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    margin-left: -.0625rem;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.search-bar .form-control {
    width: 280px;
    transition: width .2s ease-in-out;
}

.form-control {
    border: .0625rem solid #d1d5db !important;
    font-size: 0.875rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .07);
    padding-top: 0.2rem !important;
    padding-bottom: 0.2rem !important;
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
}

.form-control:focus {
    color: #6b7280;
    background-color: #fff;
    border-color: #4d6689;
    outline: 0;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.07),0 0 0 .18rem rgba(31,41,55,.25);
}

.input-group-text {
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.07);
}

.input-group-text {
    display: flex;
    align-items: center;
    padding: .5rem .625rem;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6b7280;
    text-align: center;
    white-space: nowrap;
    background-color: #fff;
    border: .0625rem solid #d1d5db;
    border-radius: .5rem;
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}
</style>
