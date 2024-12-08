import { boot } from 'quasar/wrappers'
import axios from 'axios'
import { useRouter } from 'vue-router'

// Cria a instância do axios
const api = axios.create({ baseURL: 'http://localhost/api' })

export default boot(({ app }) => {
  const router = useRouter()
  
  // Configura o cabeçalho Authorization se o token existir no localStorage
  api.interceptors.request.use(config => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  }, error => {
    return Promise.reject(error)
  })

  // Interceptor para tratar respostas com erros (como 401 Unauthorized)
  api.interceptors.response.use(response => {
    return response
  }, error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token') // Remove o token inválido
      router.push('/Login-1') // Redireciona para a página de login
    }
    return Promise.reject(error)
  })

  // Configuração para uso global dentro dos componentes
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { api }
