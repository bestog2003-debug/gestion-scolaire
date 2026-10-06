// Store du référentiel : classes et matières de l'école.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { apiRequest } from '../services/api'

export const useCatalogStore = defineStore('catalog', () => {
  const classes = ref([])
  const subjects = ref([])

  async function fetchCatalog() {
    ;[classes.value, subjects.value] = await Promise.all([apiRequest('/classes'), apiRequest('/subjects')])
  }
  async function addClass(name) {
    await apiRequest('/classes', { method: 'POST', body: { name } })
    await fetchCatalog()
  }
  async function removeClass(classId) {
    await apiRequest(`/classes/${classId}`, { method: 'DELETE' })
    await fetchCatalog()
  }
  async function addSubject(name, coefficient) {
    await apiRequest('/subjects', { method: 'POST', body: { name, coefficient } })
    await fetchCatalog()
  }
  async function removeSubject(subjectId) {
    await apiRequest(`/subjects/${subjectId}`, { method: 'DELETE' })
    await fetchCatalog()
  }

  return { classes, subjects, fetchCatalog, addClass, removeClass, addSubject, removeSubject }
})
