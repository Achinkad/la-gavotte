<script setup>
import { ref, inject, onBeforeUnmount, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { useConfigStore } from '../stores/config.js'

const router = useRouter()
const configStore = useConfigStore()
const axiosApi = inject('axiosApi')
const apiUrl = inject('apiUrl')
const notyf = inject('notyf') 

const user = ref({
    name: '',
    email: '',
    password: '',
    confirmpassword: ''
})

const emit = defineEmits(['register'])

const saveUser = () => {
    let formData = new FormData()

    formData.append('name', user.value.name)
    formData.append('email', user.value.email)
    formData.append('password', user.value.password)
    formData.append('confirmpassword', user.value.confirmpassword)

    axiosApi.post(apiUrl + '/api/users', formData)
        .then((response) => {
            user.value = response.data.data
            notyf.success('Registered with success.')
            router.back()
        }).catch(error => {
            notyf.error('Something went wrong while registering, check the fields.')
        })
}

const register = () => {
    saveUser()
    emit('register')
}

onBeforeMount(() => {
    configStore.showNavbar = false
    configStore.showSidebar = false
    configStore.showMain = false
})

onBeforeUnmount(() => {
    configStore.showNavbar = true
    configStore.showSidebar = true
    configStore.showMain = true
})
</script>

<template>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-7 col-lg-7">
                    <div class="card">
                        <div class="card-header text-center">
                            <span>La Gavotte.</span>
                        </div>
                        <div class="card-body">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Free Sign Up</h4>
                                <p class="text-muted">Don't have an account? Create your account, it takes less than a
                                    minute.</p>
                            </div>
                            <form class="row g-3 needs-validation" @submit.prevent="register">
                                <div class="mb-3 col-md-6">
                                    <label for="inputName" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Enter your name"
                                        required v-model="user.name">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="inputUsername" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="inputUsername"
                                        placeholder="Enter your email" required v-model="user.email">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="inputPassword" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="inputPassword"
                                        placeholder="Enter your password" required v-model="user.password">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="inputConfirmPassword" class="form-label">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="inputConfirmPassword"
                                        placeholder="Confirm your password" required v-model="user.confirmpassword">
                                </div>
                                <div class="mb-3 d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary px-3" @click="register">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="signup">
                        <div class="col-12 text-center">
                            <p class="text-muted">Already have an account?
                                <router-link class="text-muted" :to="{ name: 'Login' }">
                                    <b>Log In.</b>
                                </router-link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-alt">
        2023 © Project - Information Technology Laboratory
    </footer>
</template>

<style scoped>
.account-pages {
    align-items: center;
    display: flex;
    min-height: 100vh;
}

.card-header {
    background-color: #313a46;
    color: #fff !important;
    font-weight: 800;
    font-size: 1.5rem;
    padding-top: 2.25rem !important;
    padding-bottom: 2.25rem !important;
    border: 0;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

.card-body {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    padding: 2.25rem !important;
}

.card,
.card-body {
    border: 0;
}

h4 {
    font-size: 1.125rem;
}

.text-muted {
    color: #6c757d !important;
    margin-bottom: 1.5rem !important;
    font-size: 14px;
    font-weight: 400;
}

label {
    color: #6c757d !important;
    font-size: 14.4px;
    font-weight: 600;
}

input::placeholder {
    font-size: 14px;
    opacity: .6;
}

button[type="button"] {
    background-color: #313a46;
    color: #fff;
    border-color: #727cf5;
    border-radius: 0.15rem;
    border: 1px #727cf5;
    font-size: 15px;
    padding: .5rem 0;
}

button[type="button"]:focus {
    color: #fff;
    background-color: #313a46;
    opacity: .9;
    box-shadow: 0 0 0 .15rem rgba(135, 144, 247, 0.5);
}

#signup {
    margin-top: 1.5rem !important;
}

#signup a {
    text-decoration: none;
}

.footer-alt {
    left: 0;
    width: 100% !important;
    border: none;
    text-align: center;
    font-size: 14px;
    position: relative;
}

.footer {
    bottom: 0;
    padding: 19px 24px 20px;
    position: relative;
    right: 0;
    transition: all .2s ease-in-out;
}

.text-danger {
    color: rgb(250, 92, 124) !important;
    font-size: 14px;
    font-weight: 700;
}
</style>
