import { ref } from 'vue'
import UseApi from 'src/composables/UseApi'
import { useRouter } from 'vue-router'

export default function useAuthService() {
  const token = ref(null)
  const { post } = UseApi()  // Usa a função `post` do UseApi para enviar as credenciais
  const router = useRouter()

  const login = async (credentials) => {
    try {
      const response = await post('/login', credentials)  // Define a URL de login
      if (response.token) {
        token.value = response.token
        localStorage.setItem('token', token.value)
        api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
        router.push('/dashboard')
      } else {
        throw new Error("Token not found in response")
      }
    } catch (error) {
      console.error("Erro ao fazer login:", error.message)
      throw error  // Para que o erro possa ser tratado no componente que chama `login`
    }
  }

  const logout = () => {
    token.value = null
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
    router.push('/login')
  }

  return {
    login,
    logout,
    token
  }
}
