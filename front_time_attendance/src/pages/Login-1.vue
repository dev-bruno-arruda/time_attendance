<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex bg-image flex-center">
        <q-card :style="$q.screen.lt.sm ? { 'width': '80%' } : { 'width': '30%' }">
          <q-card-section>
            <q-avatar size="103px" class="absolute-center shadow-10">
              <img src="profile.svg" alt="Profile">
            </q-avatar>
          </q-card-section>
          <q-card-section>
            <div class="text-center q-pt-lg">
              <div class="col text-h6 ellipsis">
                Log in
              </div>
            </div>
          </q-card-section>
          <q-card-section>
            <q-form @submit="handleLogin" autocomplete="on" class="q-gutter-md">
              <q-input
                filled
                v-model="email"
                label="email"
                lazy-rules
                required
                autocomplete="email"
              />

              <q-input
                :type="showPassword ? 'text' : 'password'"
                filled
                v-model="password"
                label="Password"
                lazy-rules
                required
                autocomplete="current-password"
              >
                <template v-slot:append>
                  <q-icon
                    :name="showPassword ? 'visibility_off' : 'visibility'"
                    @click="togglePasswordVisibility"
                    class="cursor-pointer"
                  />
                </template>
              </q-input>

              <div>
                <q-btn label="Login" type="submit" color="primary" :loading="loading" />
              </div>
              <q-banner v-if="errorMessage" class="text-negative q-mt-md">
                {{ errorMessage }}
              </q-banner>
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import UseApi from 'src/composables/UseApi';
import { useRouter } from 'vue-router'

export default defineComponent({
  setup() {
    const router = useRouter()
    const email = ref('')
    const password = ref('')
    const loading = ref(false)
    const errorMessage = ref(null)
    const { login } = UseApi()
    const showPassword = ref(false)

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value
    }

    const handleLogin = async () => {
      loading.value = true
      errorMessage.value = null

      try {
        const response = await login({ email: email.value, password: password.value })
        localStorage.setItem('token', response.token)
        localStorage.setItem('role', JSON.stringify(response.role))
        router.push('/dashboard') // Redireciona
      } catch (error) {
        errorMessage.value = 'Invalid username or password. Please try again.'
      } finally {
        loading.value = false
      }
    }

    return {
      email,
      password,
      handleLogin,
      loading,
      errorMessage,
      showPassword,
      togglePasswordVisibility
    }
  }
})
</script>

<style>
.bg-image {
  background-image: linear-gradient(135deg, #7028e4 0%, #e5b2ca 100%);
}
</style>
