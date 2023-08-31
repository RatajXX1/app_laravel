<script setup>
import { getCurrentInstance, onMounted, provide, ref, watch } from 'vue';
import {closeModal, openModal} from "jenesius-vue-modal/dist/jenesius-vue-modal.cjs"
import UserEdit from './UserEdit.vue';
import PostEdit from './PostEdit.vue';

const { proxy } = getCurrentInstance()
let PostData = ref([])
let data = ref("")
let timer = undefined

function StartSearch() {
    proxy.$axios
        .post(`/api/search`, {text: data.value})
        .then(resp => {
            if (resp.status === 200 && resp.data.Status == "OK") {
                PostData.value = resp.data.Data
            }
        })
}

function OpenRecord(id, type) {
    closeModal()
    if (type == 1) {
        openModal(UserEdit, {userID: id})
    } else {
        openModal(PostEdit, {postID: id})
    }
}

watch(data, async () => {
    clearTimeout(timer)
    timer = setTimeout(() => {
        StartSearch()
    }, 1000)
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
                >Szukaj</h2>
                <div
                    class="flex flex-col w-full"
                >
                    <input 
                        v-model="data"
                        type="text"
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                        placeholder="Szukaj"
                    />
                </div>
                <div
                    class="w-full h-full overflow-auto"
                >
                    <div
                        class="bg-gray-200 rounded-sm w-full py-2 px-1 mb-2"
                        v-for="Itmes in PostData"
                        @click="OpenRecord(Itmes.id, Itmes.name == Itmes.restdata ? 2 : 1)"
                    >
                        <a>{{ Itmes.name }} {{ Itmes.name == Itmes.restdata ? "" :  Itmes.restdata }}</a>
                    </div>
                </div>
                <div
                    class="flex flex-row justify-end items-center mt-auto gap-1"
                >
                    <button
                        class="text-sky-400 w-fit h-fit px-2 py-1 rounded-md select-none cursor-pointer"
                        @click="closeModal()"
                    >
                        Zamknij
                    </button>
                </div>                
            </form>
        </div>
    </div>
</template>