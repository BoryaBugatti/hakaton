import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import MakeApp from '@/views/MakeApp.vue'
import Profile from '@/views/Profile.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/make-app',
      name: 'make-app',
      component: MakeApp
    },
    {
      path: '/profile',
      name: 'profile',
      component: Profile
    }
  ],
})

export default router
