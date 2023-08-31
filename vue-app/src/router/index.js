import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import UsersComponent from '../components/UsersComponent.vue'
import PostsComponent from '../components/PostsComponent.vue'
import axios from 'axios'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: {Auth: false}
    },
    {
      path: '/main',
      name: 'dashboard',
      component: HomeView,
      meta: {Auth: true},
      redirect: "/main/users",
      children: [
        {
          path: "users",
          component: UsersComponent
        },
        {
          path: "posts",
          component: PostsComponent
        },
      ]
    }
  ]
})

router.beforeResolve(async (to, _, next) => {
  if (to.meta.Auth === true) {
    const resp = await axios.get("/api/authorization", {
      baseURL: import.meta.env.VITE_LOCAL_SERVER_PATH || "",
      withCredentials: true
    }).then(resp => {
      if (resp.status == 200 && resp.data.Auth) {
        window.UserData = resp.data
        return true
      }
      else return false
    }).catch(() => {
      return false
    })
    if (!resp) next("/")
    else next()
    return resp
  } else {
    next()
    return true
  }
})

export default router
