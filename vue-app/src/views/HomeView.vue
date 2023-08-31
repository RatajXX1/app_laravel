<script setup>
import { getCurrentInstance, provide, ref } from 'vue';
import { RouterView } from 'vue-router';
import { openModal } from 'jenesius-vue-modal/dist/jenesius-vue-modal.cjs';
import Serach from '../components/Serach.vue';

const {proxy} = getCurrentInstance()

async function OpenUserEdit() {
    const modal = await openModal(Serach)
    // modal.onclose = function() {
    //     LoadUsers(ListData.value.ActualPage)
    // }
}

</script>

<template>
  <div
    class="w-screen h-screen bg-gray-200"
  >
    <div
      class="container mx-auto w-3/4 h-full"
    >
      <div class="flex flex-row gap-[10px] h-[60px] items-center">
        <a
          class="bg-sky-400 w-fit h-fit px-2 py-1 text-white rounded-md select-none cursor-pointer"
          @click="proxy.$router.push('/main/users')"
        >Użytkownicy</a>
        <a
          class="bg-sky-400 w-fit h-fit px-2 py-1 text-white rounded-md select-none cursor-pointer"
          @click="proxy.$router.push('/main/posts')"
        >Wpisy</a>
        <a
          class="bg-sky-400 w-fit h-fit px-2 py-1 text-white rounded-md select-none cursor-pointer"
          @click="OpenUserEdit()"
        >Szukaj</a>
      </div>
      <div>
        <RouterView></RouterView>
      </div>
    </div>
    <widget-container-modal/>
  </div>
</template> 
