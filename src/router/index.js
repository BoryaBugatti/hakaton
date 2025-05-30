import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import MakeApp from '@/views/MakeApp.vue'
import Profile from '@/views/Profile.vue'
import Register from '@/views/Register.vue'
import Login from '@/views/Login.vue'

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
      name: 'profile',
      component: Profile
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    }
  ],
})

export default router
