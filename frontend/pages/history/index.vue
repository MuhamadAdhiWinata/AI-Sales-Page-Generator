<template>
  <div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-12">
      <div>
        <h1 class="text-3xl font-bold mb-2">Generation History</h1>
        <p class="text-slate-400">View and manage your previously generated sales pages.</p>
      </div>
      <NuxtLink to="/generate" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition-all">
        + New Page
      </NuxtLink>
    </div>

    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-12 h-12 border-4 border-slate-800 border-t-indigo-600 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="history.length === 0" class="text-center py-20 border border-dashed border-slate-800 rounded-3xl">
      <p class="text-slate-500 mb-4">No history found yet.</p>
      <NuxtLink to="/generate" class="text-indigo-400 font-bold hover:underline">Generate your first page</NuxtLink>
    </div>

    <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="page in history" :key="page.id" class="p-6 rounded-2xl border border-slate-800 bg-slate-900/50 hover:border-indigo-500/50 transition-all group">
        <h3 class="text-xl font-bold mb-2 truncate group-hover:text-indigo-400">{{ page.product_name }}</h3>
        <p class="text-sm text-slate-500 mb-4">{{ new Date(page.created_at).toLocaleDateString() }}</p>
        <p class="text-slate-400 text-sm line-clamp-3 mb-6">{{ page.product_description }}</p>
        
        <div class="flex gap-3">
          <NuxtLink :to="'/history/' + page.id" class="flex-1 py-2 rounded-lg bg-slate-800 text-center text-sm font-semibold hover:bg-slate-700 transition-colors">
            View
          </NuxtLink>
          <button @click="deletePage(page.id)" class="px-3 py-2 rounded-lg border border-slate-800 text-rose-400 hover:bg-rose-500/10 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { token } = useAuth()
const config = useRuntimeConfig()
const history = ref([])
const loading = ref(true)

const fetchHistory = async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    history.value = data
  } catch (e) {
    console.error('Failed to fetch history', e)
  } finally {
    loading.value = false
  }
}

const deletePage = async (id) => {
  if (!confirm('Are you sure you want to delete this page?')) return
  
  try {
    await $fetch(`${config.public.apiUrl}/sales-pages/${id}`, {
      method: 'DELETE',
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    history.value = history.value.filter(p => p.id !== id)
  } catch (e) {
    alert('Failed to delete page.')
  }
}

onMounted(() => {
  fetchHistory()
})

definePageMeta({
  middleware: (to, from) => {
    const { token } = useAuth()
    if (!token.value) return navigateTo('/login')
  }
})
</script>
