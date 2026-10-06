<!-- Barre de navigation : liens selon le rôle + bouton de déconnexion. -->
<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

async function logoutAndGoToLogin() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <header class="bg-board text-paper">
    <nav class="mx-auto flex max-w-5xl flex-wrap items-center gap-x-5 gap-y-2 p-4">
      <span class="font-semibold">Gestion scolaire</span>
      <template v-if="auth.isAdmin">
        <router-link to="/admin" class="hover:underline">Tableau de bord</router-link>
        <router-link to="/admin/eleves" class="hover:underline">Élèves</router-link>
        <router-link to="/admin/referentiel" class="hover:underline">Classes et matières</router-link>
      </template>
      <router-link v-else to="/mon-bulletin" class="hover:underline">Mon bulletin</router-link>
      <button class="ml-auto rounded bg-paper/15 px-3 py-1 hover:bg-paper/25" @click="logoutAndGoToLogin">Se déconnecter</button>
    </nav>
  </header>
</template>
