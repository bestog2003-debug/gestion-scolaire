<!-- Tableau de bord admin : chiffres clés, moyennes par classe, élèves sous 10 de moyenne. -->
<script setup>
import { onMounted } from 'vue'
import { useStatsStore } from '../stores/stats'
import StatCard from '../components/StatCard.vue'

const statsStore = useStatsStore()
onMounted(statsStore.fetchStats)
</script>

<template>
  <div v-if="statsStore.stats" class="space-y-6">
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
      <StatCard label="Élèves" :value="statsStore.stats.studentCount" />
      <StatCard label="Classes" :value="statsStore.stats.classCount" />
      <StatCard label="Moyenne de l'école" :value="statsStore.stats.schoolAverage" />
      <StatCard label="Absences non justifiées" :value="statsStore.stats.unjustifiedAbsenceCount" is-alert />
    </div>
    <section>
      <h2 class="mb-2 font-semibold">Moyenne par classe</h2>
      <ul class="divide-y rounded-lg border border-board/15 bg-white">
        <li v-for="classAverage in statsStore.stats.classAverages" :key="classAverage.class_name" class="flex justify-between p-3">
          <span>{{ classAverage.class_name }}</span><span class="font-semibold">{{ classAverage.average }}</span>
        </li>
      </ul>
    </section>
    <section>
      <h2 class="mb-2 font-semibold text-alert">Élèves en difficulté (moyenne sous 10)</h2>
      <p v-if="!statsStore.stats.atRiskStudents.length" class="text-slate-500">Aucun élève en difficulté.</p>
      <ul class="divide-y rounded-lg border border-alert/30 bg-white">
        <li v-for="student in statsStore.stats.atRiskStudents" :key="student.id" class="flex justify-between p-3">
          <router-link :to="`/admin/eleves/${student.id}`" class="underline">{{ student.first_name }} {{ student.last_name }}</router-link>
          <span class="font-semibold text-alert">{{ student.average }}</span>
        </li>
      </ul>
    </section>
  </div>
</template>
