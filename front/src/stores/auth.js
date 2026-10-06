// Store d'authentification : utilisateur connecté, connexion, déconnexion.
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { apiRequest } from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  let sessionChecked = false

  const isAdmin = computed(() => user.value?.role === 'admin')
  const homePath = computed(() => (isAdmin.value ? '/admin' : '/mon-bulletin'))

  // Vérifie une seule fois si une session PHP est déjà ouverte (rechargement de page)
  async function loadSession() {
    if (sessionChecked) return
    sessionChecked = true
    try { user.value = await apiRequest('/me') } catch { user.value = null }
  }
  async function login(email, password) {
    user.value = await apiRequest('/login', { method: 'POST', body: { email, password } })
  }
  async function logout() {
    await apiRequest('/logout', { method: 'POST' })
    user.value = null
  }

  return { user, isAdmin, homePath, loadSession, login, logout }
})
