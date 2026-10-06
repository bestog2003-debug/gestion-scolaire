// Store des élèves : liste, recherche, ajout, modification, suppression.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { apiRequest } from '../services/api'

export const useStudentsStore = defineStore('students', () => {
  const students = ref([])

  async function fetchStudents(searchTerm = '') {
    students.value = await apiRequest(`/students?search=${encodeURIComponent(searchTerm)}`)
  }
  async function saveStudent(studentForm) {
    if (studentForm.id) await apiRequest(`/students/${studentForm.id}`, { method: 'PUT', body: studentForm })
    else await apiRequest('/students', { method: 'POST', body: studentForm })
  }
  async function removeStudent(studentId) {
    await apiRequest(`/students/${studentId}`, { method: 'DELETE' })
  }

  return { students, fetchStudents, saveStudent, removeStudent }
})
