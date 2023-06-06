<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, computed, onMounted } from "vue";
import store from "../store";

const users = ref([]);
const messages = ref([]);
const userActive = ref(null);
const message = ref("");
const userLogged = computed(() => store.state.user);

async function loadMessages(userId) {
  await axios.get(`api/users/${userId}`).then((response) => {
    userActive.value = response.data.user;
  });

  await axios.get(`api/messages/${userId}`).then((response) => {
    messages.value = response.data.messages;
  });

  scrollToButton();
}

async function sendMessage() {
  await axios
    .post("api/messages/store", {
      content: message.value,
      to: userActive.value.id,
    })
    .then((response) => {
      messages.value.push({
        from: userLogged.id,
        to: userActive.value.id,
        content: message.value,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      });
      message.value = "";
    });

  scrollToButton();
}

function scrollToButton() {
  if (messages.value.length) {
    document.querySelectorAll(".message:last-child")[0].scrollIntoView();
  }
}

onMounted(() => {
  axios.get("api/users").then((response) => {
    users.value = response.data.users;
  });
});
</script>

<template>
  <AppLayout title="Chat">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex"
          style="min-height: 400px; max-height: 400px"
        >
          <div
            class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-y-scroll"
          >
            <ul>
              <li
                v-for="user in users"
                :key="user.id"
                @click="loadMessages(user.id)"
                :class="
                  userActive && userActive.id === user.id
                    ? 'bg-gray-200 bg-opacity-50'
                    : ''
                "
                class="p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-opacity-50 hover:bg-gray-200 hover:cursor-pointer"
              >
                <p class="flex items-center">
                  {{ user.name }}
                  <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                </p>
              </li>
            </ul>
          </div>
          <div class="w-9/12 flex flex-col justify-between">
            <div class="w-full p-6 flex flex-col overflow-y-scroll">
              <div
                v-for="message in messages"
                :key="message.id"
                :class="
                  message.from === $page.props.auth.user.id ? 'text-right' : ''
                "
                class="w-full mb-3 message"
              >
                <p
                  :class="
                    message.from === $page.props.auth.user.id
                      ? 'messageFromMe'
                      : 'messageToMe'
                  "
                  class="inline-block p-2 rounded-md messageFromMe"
                  style="max-width: 75%"
                >
                  {{ message.content }}
                </p>
                <span class="block mt-1 text-xs text-gray-500">
                  {{ $filters.formatDate(message.created_at) }}
                </span>
              </div>
            </div>
            <div
              v-if="userActive"
              class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200"
            >
              <form v-on:submit.prevent="sendMessage">
                <div
                  class="flex rounded-md overflow-hidden border border-gray-300"
                >
                  <input
                    v-model="message"
                    type="text"
                    class="flex-1 px-4 py-2 text-sm focus:outline-none"
                  />
                  <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2"
                  >
                    Enviar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style>
.messageFromMe {
  @apply bg-indigo-300 bg-opacity-25;
}

.messageToMe {
  @apply bg-gray-300 bg-opacity-25;
}
</style>
