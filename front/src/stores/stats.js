// Store des statistiques du tableau de bord.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { apiRequest } from '../services/api'

export const useStatsStore = defineStore('stats', () => {
  const stats = ref(null)

  async function fetchStats() {
    stats.value = await apiRequest('/stats')
  }

  return { stats, fetchStats }
})
