import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import MakeApp from '@/views/MakeApp.vue'
import Profile from '@/views/Profile.vue'
import Register from '@/views/Register.vue'
import Login from '@/views/Login.vue'
import Projects from '@/views/Projects.vue'
import ConfirmApps from '@/views/ConfirmApps.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/graph',
      name: 'graph',
      component: Register
    },
    {
      path: '/make-app',
      name: 'make-app',
      component: MakeApp
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/profile',
      component: Profile,
      name: 'Profile',
      props: true
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/projects-tracking',
      name: 'projects',
      component: Projects
    },
    {
      path: '/confirm-apps',
      name: 'confirm-apps',
      component: ConfirmApps
    }
  ],
})

export default router
