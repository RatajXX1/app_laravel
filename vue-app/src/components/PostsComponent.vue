<script setup>
import { openModal } from 'jenesius-vue-modal/dist/jenesius-vue-modal.cjs';
import { getCurrentInstance, inject, onMounted, ref } from 'vue';
import PostAdd from './PostAdd.vue';
import PostEdit from './PostEdit.vue';

const { proxy } = getCurrentInstance()

const ListData = ref({
    hasPages: false,
    ActualPage: 1,
    Users: [],
    LastPage: 1
})

const SortData = ref({
    SortVal: null,
    SortType: null
})

const FilterData = ref({
    FilterVal: null,
    Tags: []
})

function SetSort(SortVal) {
    if (SortData.value.SortVal == SortVal) {
        SortData.value.SortType = SortData.value.SortType == "desc" ? "asc" : "desc"
    } else {
        SortData.value = {
            SortVal: SortVal,
            SortType: "desc"
        }
    }
    LoadUsers(ListData.value.ActualPage)
}

function SetSFilter(event) {
    if (event.target.value == 0) FilterData.value.FilterVal = null
    else FilterData.value.FilterVal = event.target.value
    LoadUsers(ListData.value.ActualPage)
}

function LoadUsers(page) {
    proxy.$axios
        .get(`/api/posts?page=${page}${SortData.value.SortVal != null ? `&sort_by=${SortData.value.SortVal}&sort_dir=${SortData.value.SortType}` : ""}${FilterData.value.FilterVal != null ? `&tag=${FilterData.value.FilterVal}` : ""}`)
        .then(resp => {
            if (resp.status === 200) {
                ListData.value = {
                    hasPages: resp.data.hasPages,
                    ActualPage: page,
                    Users: resp.data.Posts,
                    LastPage: resp.data.lastPage
                }
            }
        })
}

function GetAllTags() {
    proxy.$axios
        .get(`/api/tags`)
        .then(resp => {
            if (resp.status === 200) {
                FilterData.value.Tags  = resp.data
            }
        })
}

async function OpenUserEdit() {
    const modal = await openModal(PostAdd)
    modal.onclose = function() {
        LoadUsers(ListData.value.ActualPage)
    }
}

async function OpenPostEdit(id) {
    const modal = await openModal(PostEdit, {postID: id})
    modal.onclose = function() {
        LoadUsers(ListData.value.ActualPage)
    }
}

onMounted(() => {
    LoadUsers(1)
    GetAllTags()
})

</script>

<template>
    <div
        class="w-full h-full flex flex-col"
    >
        <div
            class="flex flex-row justify-start w-full items-center mb-5 px-3 mt-4"
        >
            <button
                class="underline text-sky-400 w-fit h-fit px-2 py-1 rounded-md select-none cursor-pointer"
                @click="OpenUserEdit"
            >
                Dodaj wpis
            </button>
            <div
                class="text-sky-400 w-fit h-fit px-2 py-1 rounded-md select-none cursor-pointer"
            >
                Filtruj po tagu: 
                <select
                    class="bg-sky-400 text-white px-3 py-1 rounded-sm ml-2"
                    @change="SetSFilter($event)"
                    
                >
                    <option value="0">-</option>
                    <option
                        v-bind:value="tags.id"
                        v-for="tags in FilterData.Tags"
                    >
                        #{{ tags.name }}
                    </option>
                </select>
            </div>
        </div>
        <div
            class="flex flex-row justify-start w-full items-center mb-5 px-3 mt-4"
        >
            <a
                class="font-bold w-full select-none cursor-pointer"
                style="flex-basis: 50px; flex-shrink: 0;"
                @click="SetSort('id')"
            >
                ID
                <svg
                    v-if="SortData.SortVal == 'id'"
                    class="inline h-full w-[15px]"
                    :class="[SortData.SortType == 'desc' ? 'rotate-180' : '']"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C12.2652 7 12.5196 7.10536 12.7071 7.29289L19.7071 14.2929C20.0976 14.6834 20.0976 15.3166 19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071L12 9.41421L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L11.2929 7.29289C11.4804 7.10536 11.7348 7 12 7Z" fill="#000000"/>
                </svg>
            </a>
            <a
                class="font-bold w-full select-none cursor-pointer"
                style=""
                @click="SetSort('title')"
            >
                Tytuł
                <svg
                    v-if="SortData.SortVal == 'title'"
                    class="inline h-full w-[15px]"
                    :class="[SortData.SortType == 'desc' ? 'rotate-180' : '']"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C12.2652 7 12.5196 7.10536 12.7071 7.29289L19.7071 14.2929C20.0976 14.6834 20.0976 15.3166 19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071L12 9.41421L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L11.2929 7.29289C11.4804 7.10536 11.7348 7 12 7Z" fill="#000000"/>
                </svg>
            </a>
            <a
                class="font-bold w-full select-none cursor-pointer"
                @click="SetSort('created_at')"
            >
                Data dodania
                <svg
                    v-if="SortData.SortVal == 'created_at'"
                    class="inline h-full w-[15px]"
                    :class="[SortData.SortType == 'desc' ? 'rotate-180' : '']"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C12.2652 7 12.5196 7.10536 12.7071 7.29289L19.7071 14.2929C20.0976 14.6834 20.0976 15.3166 19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071L12 9.41421L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L11.2929 7.29289C11.4804 7.10536 11.7348 7 12 7Z" fill="#000000"/>
                </svg>
            </a>
        </div>
        <div
            class="bg-white flex flex-row items-center rounded-md py-2 justify-start px-3 mb-[10px]"
            v-if="ListData.Users.length == 0"
        >
            <h2
                class="w-full text-xl font-bold text-center"
            >
                Brak wpisów!
            </h2>
        </div>
        <div
            class="bg-white flex flex-row items-center rounded-md py-2 justify-start px-3 mb-[10px]"
            v-for="posts in ListData.Users"
            @click="OpenPostEdit(posts.id)"
        >
            <a
                class="w-full"
                style="flex-basis: 50px; flex-shrink: 0;"
            >
                {{ posts.id }}
            </a>
            <a
                class="w-full"
            >
                {{ posts.title }}
            </a>
            <a
            class="w-full"
            >
                {{ posts.created_at.split('T')[0]}}
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