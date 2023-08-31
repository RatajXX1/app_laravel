<script setup>
import { getCurrentInstance, onMounted, provide, ref } from 'vue';
import {closeModal} from "jenesius-vue-modal/dist/jenesius-vue-modal.cjs"
const props = defineProps(['userID'])
const emit = defineEmits(['onReload'])
const { proxy } = getCurrentInstance()

let UserData = ref({
    id: 0,
    name: "",
    surname: "",
    email: "",
    role: 0,
})

function LoadUsers(ID) {
    proxy.$axios
        .get(`/api/users/${ID}`)
        .then(resp => {
            if (resp.status === 200) {
                UserData.value = {
                    ...resp.data
                }
            }
        })
}

function RemoveUser() {
    proxy.$axios
        .delete(`/api/users/${UserData.value.id}`)
        .then(resp => {
            if (resp.status === 200) {
                emit("onReload")
                closeModal()
            }
        })
}

function SaveEditUser() {
    proxy.$axios
        .put(`/api/users/${UserData.value.id}`, {...UserData.value})
        .then(resp => {
            if (resp.status === 200) {
                emit("onReload")
                closeModal()
            }
        })
}


onMounted(() => {
    if (props.userID) LoadUsers(props.userID)
})

</script>

<template>
    <div
        class="fixed w-screen h-screen z-10 top-0 bg-stone-900/80"
    >
        <div 
            class="relative w-1/2 h-1/2 bg-white left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-md"
        >
            <form
                class="w-full p-2 flex flex-col h-full gap-[10px]"
                @submit.prevent
            >
                <h2
                    class="font-bold text-xl mb-5"
                >Edycja uzytkownika</h2>
                <div
                    class="w-full flex felx-row gap-3"
                >
                    <div
                        class="flex flex-col w-full"
                    >
                        <label>Imie</label>
                        <input 
                            class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                            type="text"
                            placeholder="imie"
                            v-model="UserData.name"
                        />
                    </div>
                    <div
                    class="flex flex-col w-full"
                    >
                        <label>Nazwisko</label>
                        <input 
                            type="text"
                            v-model="UserData.surname"
                            class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                            placeholder="Nazwisko"
                        />
                    </div>
                </div>
                <div
                    class="flex flex-col w-full"
                >
                    <label>E-mail</label>
                    <input 
                        v-model="UserData.email"
                        type="text"
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                        placeholder="E-mail"
                    />
                </div>
                <div
                    class="flex flex-col w-full"
                >
                    <label>Rola</label>
                    <select
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                        v-model="UserData.role"
                    >
                        <option value="1">Użytkownik</option>
                        <option value="2">Administrator</option>
                    </select>
                </div>
                <div
                    class="flex flex-row justify-end items-center mt-auto gap-1"
                >
                    <button
                        class="text-red-400 w-fit h-fit px-2 py- rounded-md select-none cursor-pointer"
                        @click="RemoveUser()"
                    >
                        Usuń
                    </button>
                    <button
                        class="text-sky-400 w-fit h-fit px-2 py-1 rounded-md select-none cursor-pointer"
                        @click="closeModal()"
                    >
                        Zamknij
                    </button>
                    <button 
                        class="bg-sky-400 w-fit h-fit px-2 py-1 text-white rounded-md select-none cursor-pointer"
                        @click="SaveEditUser()"
                    >
                        Zapisz
                    </button>
                </div>                
            </form>
        </div>
    </div>
</template>