// Store du relevé d'un élève : notes par matière, moyennes et absences.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { apiRequest } from '../services/api'

export const useReportStore = defineStore('report', () => {
  const report = ref(null)

  async function fetchStudentReport(studentId) {
    report.value = await apiRequest(`/students/${studentId}/report`)
  }
  async function fetchMyReport() {
    report.value = await apiRequest('/me/report')
  }

  return { report, fetchStudentReport, fetchMyReport }
})
