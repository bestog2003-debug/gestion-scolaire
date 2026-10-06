<!-- Espace élève : ses notes, sa moyenne générale et son relevé d'absences (lecture seule). -->
<script setup>
import { onMounted } from 'vue'
import { useReportStore } from '../stores/report'
import StudentReport from '../components/StudentReport.vue'
import AbsenceList from '../components/AbsenceList.vue'

const reportStore = useReportStore()
onMounted(reportStore.fetchMyReport)
</script>

<template>
  <div v-if="reportStore.report" class="space-y-6">
    <h1 class="text-xl font-semibold text-board">Bonjour {{ reportStore.report.student.first_name }}</h1>
    <StudentReport :report="reportStore.report" />
    <section class="space-y-3">
      <h2 class="font-semibold">Mes absences</h2>
      <AbsenceList :absences="reportStore.report.absences" />
    </section>
  </div>
</template>
