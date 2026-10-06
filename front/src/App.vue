<!-- Gabarit global : barre de navigation (si connecté) + page courante. -->
<script setup>
import AppNavbar from './components/AppNavbar.vue'
import { useAuthStore } from './stores/auth'
const auth = useAuthStore()
</script>

<template>
  <div class="min-h-screen bg-paper text-slate-800">
    <AppNavbar v-if="auth.user" />
    <main class="mx-auto max-w-5xl p-4 sm:p-6"><router-view /></main>
  </div>
</template>
