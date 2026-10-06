// Store des notes : saisie, modification, suppression (puis rechargement du relevé).
import { defineStore } from 'pinia'
import { apiRequest } from '../services/api'
import { useReportStore } from './report'

export const useGradesStore = defineStore('grades', () => {
  async function saveGrade(gradeForm) {
    if (gradeForm.id) await apiRequest(`/grades/${gradeForm.id}`, { method: 'PUT', body: gradeForm })
    else await apiRequest('/grades', { method: 'POST', body: gradeForm })
    await useReportStore().fetchStudentReport(gradeForm.student_id)
  }
  async function removeGrade(gradeId, studentId) {
    await apiRequest(`/grades/${gradeId}`, { method: 'DELETE' })
    await useReportStore().fetchStudentReport(studentId)
  }

  return { saveGrade, removeGrade }
})
