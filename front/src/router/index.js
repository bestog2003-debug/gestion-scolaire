// Routes de l'application et garde de navigation selon la connexion et le rôle.
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: () => import('../views/LoginView.vue'), meta: { public: true } },
  { path: '/admin', component: () => import('../views/DashboardView.vue'), meta: { role: 'admin' } },
  { path: '/admin/eleves', component: () => import('../views/StudentsView.vue'), meta: { role: 'admin' } },
  { path: '/admin/eleves/:id', component: () => import('../views/StudentDetailView.vue'), meta: { role: 'admin' } },
  { path: '/admin/referentiel', component: () => import('../views/CatalogView.vue'), meta: { role: 'admin' } },
  { path: '/mon-bulletin', component: () => import('../views/MyReportView.vue'), meta: { role: 'student' } },
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  await auth.loadSession()
  if (to.meta.public) return auth.user ? auth.homePath : true
  if (!auth.user) return '/login'
  if (to.meta.role !== auth.user.role) return auth.homePath
})

export default router
