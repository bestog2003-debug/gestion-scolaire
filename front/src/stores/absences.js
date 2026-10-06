// Store des absences : ajout, changement de justification, suppression.
import { defineStore } from 'pinia'
import { apiRequest } from '../services/api'
import { useReportStore } from './report'

export const useAbsencesStore = defineStore('absences', () => {
  async function addAbsence(absenceForm) {
    await apiRequest('/absences', { method: 'POST', body: absenceForm })
    await useReportStore().fetchStudentReport(absenceForm.student_id)
  }
  async function toggleJustified(absence, studentId) {
    await apiRequest(`/absences/${absence.id}`, { method: 'PUT', body: { justified: !absence.justified, reason: absence.reason } })
    await useReportStore().fetchStudentReport(studentId)
  }
  async function removeAbsence(absenceId, studentId) {
    await apiRequest(`/absences/${absenceId}`, { method: 'DELETE' })
    await useReportStore().fetchStudentReport(studentId)
  }

  return { addAbsence, toggleJustified, removeAbsence }
})
