<script setup>
import { openModal } from 'jenesius-vue-modal/dist/jenesius-vue-modal.cjs';
import { getCurrentInstance, inject, onMounted, ref } from 'vue';
import UserEdit from './UserEdit.vue';

const { proxy } = getCurrentInstance()
// const OpenUserModal = inject("ModalObjects")

const UserRoles = {
    1: "Użytkownik",
    2: "Administrator",
}

const ListData = ref({
    hasPages: false,
    ActualPage: 1,
    Users: [],
    LastPage: 1
})

function LoadUsers(page) {
    proxy.$axios
        .get(`/api/users?page=${page}`)
        .then(resp => {
            if (resp.status === 200) {
                ListData.value = {
                    hasPages: resp.data.hasPages,
                    ActualPage: page,
                    Users: resp.data.Users,
                    LastPage: resp.data.lastPage
                }
            }
        })
}

async function OpenUserEdit(id) {
    const modal = await openModal(UserEdit, {userID: id})
    modal.onclose = function() {
        LoadUsers(ListData.value.ActualPage)
    }
}

onMounted(() => {
    LoadUsers(1)
    // console.log(proxy.$refs)
})

</script>

<template>
    <div
        class="w-full h-full flex flex-col"
    >
        <div
            class="flex flex-row justify-start w-full items-center mb-5 px-3 mt-4"
        >
            <a
                class="font-bold w-full"
                style="flex-basis: 50px; flex-shrink: 0;"
            >ID</a>
            <a
                class="font-bold w-full"
                style=""
            >Imie i nazwisko</a>
            <a
                class="font-bold w-full"
                
            >e-mail</a>
            <a
                class="font-bold w-full"
                style="flex-basis: 150px; flex-shrink: 0;"
            >Rola</a>
        </div>
        <div
            class="bg-white flex flex-row items-center rounded-md py-2 justify-start px-3 mb-[10px]"
            v-for="user in ListData.Users"
            @click="OpenUserEdit(user.id)"
        >
            <a
                class="w-full"
                style="flex-basis: 50px; flex-shrink: 0;"
            >
                {{ user.id }}
            </a>
            <a
                class="w-full"
            >
                {{ user.name + " " + user.surname }}
            </a>
            <a
            class="w-full"
            >
                {{ user.email }}
            </a>
            <a
                style="flex-basis: 150px; flex-shrink: 0;"
            >
                {{ UserRoles[user.role] }}
            </a>
        </div>
        <div
            class="w-full flex flex-row gap-[10px] h-[40px] items-center"
            v-if="ListData.hasPages"
        >
            <span
                class="w-auto h-3/4 relative bg-sky-400 p-2 rounded-md cursor-pointer mr-2"
                @click="ListData.ActualPage > 1 && LoadUsers(--ListData.ActualPage)"
            >
                <svg fill="#000000"  class="w-full h-full fill-white -rotate-180" version="1.1" id="XMLID_287_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                    viewBox="0 0 24 24" xml:space="preserve">
                    <g id="next">
                        <g>
                            <polygon points="6.8,23.7 5.4,22.3 15.7,12 5.4,1.7 6.8,0.3 18.5,12 		"/>
                        </g>
                    </g>
                </svg>
            </span>
            <span
                v-for="n in ListData.LastPage"
                class="text-sky-400 cursor-pointer select-none px-2 rounded-md w-[25px] text-center"
                :class="[ListData.ActualPage == n ? 'bg-sky-400 text-white' : '']"
                @click="ListData.ActualPage != n && LoadUsers(n)"
            >
                {{ n }}
            </span>
            <span
                class="w-auto h-3/4 relative bg-sky-400 p-2 rounded-md cursor-pointer ml-2"
                @click="ListData.ActualPage < ListData.LastPage && LoadUsers(++ListData.ActualPage)"
            >
                <svg fill="#000000"  class="w-full h-full fill-white" version="1.1" id="XMLID_287_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                    viewBox="0 0 24 24" xml:space="preserve">
                    <g id="next">
                        <g>
                            <polygon points="6.8,23.7 5.4,22.3 15.7,12 5.4,1.7 6.8,0.3 18.5,12 		"/>
                        </g>
                    </g>
                </svg>
            </span>
        </div>
    </div>
</template>