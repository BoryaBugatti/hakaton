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
      path: '/profile/:client_id',
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
    },
  ],
})
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('user');
  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ path: '/login' });
  } else {
    next();
  }
});
export default router
