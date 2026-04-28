<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center p-4">
    <div class="w-full max-w-md p-8 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-md">
      <h2 class="text-3xl font-bold mb-2 text-center">Create Account</h2>
      <p class="text-slate-400 text-center mb-8 text-sm">Join us and start generating sales pages</p>

      <form @submit.prevent="handleRegister" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-slate-400 mb-2">Full Name</label>
          <input 
            v-model="form.name"
            type="text" 
            required
            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
            placeholder="John Doe"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
          <input 
            v-model="form.email"
            type="email" 
            required
            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
            placeholder="name@company.com"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-400 mb-2">Password</label>
          <input 
            v-model="form.password"
            type="password" 
            required
            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
            placeholder="••••••••"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-400 mb-2">Confirm Password</label>
          <input 
            v-model="form.password_confirmation"
            type="password" 
            required
            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
            placeholder="••••••••"
          />
        </div>

        <div v-if="error" class="p-3 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
          {{ error }}
        </div>

        <button 
          :disabled="loading"
          type="submit"
          class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Creating Account...' : 'Create Account' }}
        </button>
      </form>

      <p class="text-center mt-8 text-sm text-slate-400">
        Already have an account? 
        <NuxtLink to="/login" class="text-indigo-400 font-semibold hover:underline">Sign In</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup>
const { setToken, setUser } = useAuth()
const config = useRuntimeConfig()
const loading = ref(false)
const error = ref('')

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  try {
    const data = await $fetch(`${config.public.apiUrl}/register`, {
      method: 'POST',
      body: form
    })
    setToken(data.access_token)
    setUser(data.user)
    navigateTo('/generate')
  } catch (e) {
    error.value = e.data?.message || 'Registration failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
