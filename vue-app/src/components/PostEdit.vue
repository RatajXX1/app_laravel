<script setup>
import { getCurrentInstance, onMounted, provide, ref } from 'vue';
import {closeModal} from "jenesius-vue-modal/dist/jenesius-vue-modal.cjs"
const props = defineProps(['postID'])
// const emit = defineEmits(['onReload'])
const { proxy } = getCurrentInstance()

let PostData = ref({
    Tittle: "",
    Data: "",
    Tags: "",
    id: 0
})

function ParseTags() {
    const tables = []
    for(const str of PostData.value.Tags.split("#")) {
        if (str.trim().length !== 0) tables.push(str.trim())
    }
    return tables
}

function JoinTags(tagstable) {
    let dat = ""
    for(const str of tagstable) {
        dat += "#" + str.name + " "
    }
    return dat 
}

function CreatePost() {
    if (
        PostData.value.Tittle.trim().length != 0 && 
        PostData.value.Data.trim().length != 0 &&
        PostData.value.Tags.trim().length != 0
        )

    proxy.$axios
        .put(`/api/posts/${PostData.value.id}`, {...PostData.value, Tags: [...ParseTags()] })
        .then(resp => {
            if (resp.status === 200 && resp.data.Status == "OK") {
                closeModal()
            }
        })
}

function Remove() {
    proxy.$axios
        .delete(`/api/posts/${PostData.value.id}`)
        .then(resp => {
            if (resp.status === 200) {
                closeModal()
            }
        })
}

function LoadPost(id) {
    proxy.$axios
        .get(`/api/posts/${id}`)
        .then(resp => {
            if (resp.status === 200 && resp.data.Status == "OK") {
                PostData.value = {
                    Data: resp.data.Data,
                    Tittle: resp.data.Tittle,
                    Tags: JoinTags(resp.data.Tags),
                    id: id
                }
            }
        })
}


onMounted(() => {
    if (props.postID) LoadPost(props.postID)
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
                >Edycja wpisu</h2>
                <div
                    class="flex flex-col w-full"
                >
                    <label>Tytuł</label>
                    <input 
                        v-model="PostData.Tittle"
                        type="text"
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                        placeholder="tagi"
                    />
                </div>
                <div
                    class="flex flex-col w-full h-full"
                >
                    <label>Zawartośc</label>
                    <textarea
                        v-model="PostData.Data"
                        type="text"
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm resize-none h-full"
                        
                    >
                    </textarea>
                </div>
                <div
                    class="flex flex-col w-full mb-5"
                >
                    <label>Tagi (Kazdy tag zacznin po '#')</label>
                    <input 
                        v-model="PostData.Tags"
                        type="text"
                        class="bg-gray-200 px-1 py-2 outline-none rounded-sm"
                        placeholder="tagi"
                    />
                </div>
                <div
                    class="flex flex-row justify-end items-center mt-auto gap-1"
                >
                    <button
                        class="text-red-400 w-fit h-fit px-2 py- rounded-md select-none cursor-pointer"
                        @click="Remove()"
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
                        @click="CreatePost()"
                    >
                        Dodaj
                    </button>
                </div>                
            </form>
        </div>
    </div>
</template>