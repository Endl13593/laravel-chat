<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Conversas de {{ user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex" style="min-height: 600px; max-height: 600px">
                    <!-- list users -->
                    <div class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-y-scroll">
                        <ul>
                            <li
                                v-for="(user, index) in users"
                                :class="(userActive === user.id ? 'userActiveClass' : '')"
                                @click.prevent="loadMessages(user.id)"
                                :key="index"
                                class="p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-gray-200 hover:bg-opacity-50 hover:cursor-pointer">
                                <p class="flex items-center">
                                    {{ user.name }}
                                    <span v-if="user.notification" class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- box message -->
                    <div class="w-9/12 flex flex-col justify-between">

                        <!-- message-->
                        <div class="w-full p-6 flex flex-col overflow-y-scroll">
                            <div
                                v-for="(message, index) in messages" :key="index"
                                :class="(message.from === $page.auth.user.id) ? 'text-right' : ''"
                                class="w-full mb-3 message">
                                <p
                                    :class="(message.from === $page.auth.user.id) ? 'messageForMe' : 'messageToMe'"
                                    class="inline-block p-2 rounded-md" style="max-width: 75%;">
                                    {{ message.content }}
                                </p>
                                <span class="block mt-1 text-xs text-gray-500">{{ message.created_at | formatDate }}</span>
                            </div>
                        </div>

                        <!-- form-->
                        <div
                            v-if="userActive"
                            class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200">
                            <form @submit.prevent="sendMessage">
                                <div class="flex rounded-md overflow-hidden border border-gray-300">
                                    <input
                                        v-model="message"
                                        type="text"
                                        class="flex-1 px-4 py-2 text-sm focus:outline-none"
                                        placeholder="Digite sua mensagem aqui" />
                                    <button
                                        v-if="message !== ''"
                                        type="submit"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2">Enviar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import Vue from 'vue'
    import AppLayout from '@/Layouts/AppLayout'
    import store from "../store";

    export default {
        data() {
            return {
                users: [],
                messages: [],
                message: '',
                userActive: 0
            }
        },
        components: {
            AppLayout
        },
        mounted() {
            axios.get('api/users').then(response => {
                this.users = response.data
            })

            Echo.private(`user.${this.user.id}`).listen('.SendMessage', async (e) => {
                if (this.userActive && this.userActive === e.message.from) {
                    await this.messages.push(e.message)
                    this.scrollToButton()
                } else {
                    const user = this.users.filter((user)=>{
                        if (user.id === e.message.from) {
                            return user
                        }
                    })

                    if (user) {
                        Vue.set(user[0], 'notification', true)
                    }
                }
            })
        },
        methods: {
            scrollToButton() {
                if (this.messages.length) {
                    document.querySelectorAll('.message:last-child')[0].scrollIntoView()
                }
            },
            async loadMessages(userId) {
                this.userActive = userId
                await axios.get(`api/messages/${userId}`).then(response => {
                    this.messages = response.data
                })

                const user = this.users.filter((user)=>{
                    if (user.id === userId) {
                        return user
                    }
                })

                if (user) {
                    Vue.set(user[0], 'notification', false)
                }

                this.scrollToButton();
            },
            async sendMessage() {
                await axios.post('api/messages/store', {
                    'content': this.message,
                    'to': this.userActive
                }).then(response => {
                    this.messages.push({
                        'from': this.user.id,
                        'to' : this.userActive,
                        'content': this.message,
                        'created_at': new Date().toISOString(),
                        'updated_at': new Date().toISOString()
                    })
                    this.message = ''
                })
                this.scrollToButton();
            }
        },
        computed: {
            user() {
                return store.state.user
            }
        }
    }
</script>

<style scoped>
.messageForMe {
    @apply bg-indigo-300 bg-opacity-25
}

.messageToMe {
    @apply bg-gray-300 bg-opacity-25
}
.userActiveClass {
    @apply bg-gray-200 bg-opacity-50
}
</style>
