<script setup>
import { getCurrentInstance, ref } from 'vue';

let {proxy} = getCurrentInstance()
let ErrorData = ref("")

const FormData = {
    email: "",
    password: ""
}

const ProccedLogin = () => {    
    if (FormData.email.trim().length === 0 || FormData.password.trim().length === 0) {
        ErrorData.value = "Pola muszą być uzupełnione!"
    } else 
    proxy.$axios.post("/api/auth", FormData).then(resp => {
        if (resp.status == 200) {
            if (resp.data.Status == "OK") {
                proxy.$router.push("/main")
            } else {
                ErrorData.value = resp.data.Message
            }
        }
    })
}

</script>

<template>
    <div
        class="w-screen h-screen bg-gray-200"
    >
        <div
            class="container mx-auto h-full w-1/2"
        >   
            <form 
                class="flex flex-col gap-[20px] items-center py-5 top-1/3 relative w-[400px] left-1/2 -translate-x-1/2"
                @submit.prevent
            >
                <div
                    v-show="ErrorData != ''"
                    class="absolute w-3/4 -top-20 bg-red-500 rounded-sm py-2"
                >
                    <p
                        class="text-center text-white"
                    >
                        {{ ErrorData }}
                    </p>
                </div>
                <h1
                    class="text-center font-bold text-xl text-sky-400"
                >Logowanie</h1>
                <input
                    class="p-2 w-3/4 rounded-md outline-none"
                    placeholder="Email"
                    type="text"
                    v-model="FormData.email"
                />
                <input
                    class="p-2 w-3/4 rounded-md outline-none"
                    placeholder="Hasło"
                    type="password"
                    v-model="FormData.password"
                />
                <button 
                    class="bg-sky-400 w-3/4 py-1 text-white rounded-md mt-[20px]"
                    @click="ProccedLogin"
                >
                    Zaloguj
                </button>
            </form>
        </div>
    </div>
</template>