<!-- Page de connexion : redirige vers l'espace correspondant au rôle. -->
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const errorMessage = ref('')

async function submitLogin() {
  try {
    await auth.login(email.value, password.value)
    router.push(auth.homePath)
  } catch (error) {
    errorMessage.value = error.message
  }
}
</script>

<template>
  <div class="mx-auto mt-16 max-w-sm space-y-3 rounded-lg border border-board/15 bg-white p-6">
    <h1 class="text-xl font-semibold text-board">Se connecter</h1>
    <input v-model="email" type="email" placeholder="Email" class="w-full rounded border p-2" @keyup.enter="submitLogin" />
    <input v-model="password" type="password" placeholder="Mot de passe" class="w-full rounded border p-2" @keyup.enter="submitLogin" />
    <p v-if="errorMessage" class="text-sm text-alert">{{ errorMessage }}</p>
    <button class="w-full rounded bg-board p-2 text-paper" @click="submitLogin">Se connecter</button>
  </div>
</template>
