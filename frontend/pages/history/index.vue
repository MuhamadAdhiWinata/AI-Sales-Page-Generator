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
      <div v-for="page in history" :key="page.id" class="p-6 rounded-2xl border border-slate-800 bg-slate-900/50 hover:border-indigo-500/50 transition-all group flex flex-col">
        <div class="flex justify-between items-start mb-4">
          <div class="flex-1 min-w-0">
            <h3 class="text-xl font-bold truncate group-hover:text-indigo-400">{{ page.product_name }}</h3>
            <p class="text-xs text-slate-500 mt-1">{{ new Date(page.created_at).toLocaleDateString() }}</p>
          </div>
          <span :class="[
            'text-[10px] uppercase font-bold px-2 py-1 rounded-md border',
            page.status === 'completed' ? 'border-emerald-500/30 text-emerald-400 bg-emerald-500/5' : 
            page.status === 'failed' ? 'border-rose-500/30 text-rose-400 bg-rose-500/5' : 
            'border-amber-500/30 text-amber-400 bg-amber-500/5 animate-pulse'
          ]">
            {{ page.status }}
          </span>
        </div>

        <p class="text-slate-400 text-sm line-clamp-2 mb-6 flex-1">{{ page.product_description }}</p>
        
        <div class="grid grid-cols-2 gap-2 mb-3">
          <NuxtLink :to="`/s/${page.slug}`" target="_blank" class="py-2 rounded-lg bg-indigo-600 text-white text-center text-xs font-bold hover:bg-indigo-500 transition-colors flex items-center justify-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
            Full Page
          </NuxtLink>
          <button @click="downloadHtml(page)" class="py-2 rounded-lg bg-slate-800 text-white text-center text-xs font-bold hover:bg-slate-700 transition-colors flex items-center justify-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
            Download
          </button>
        </div>

        <div class="flex gap-2">
          <NuxtLink :to="'/history/' + page.id" class="flex-1 py-2 rounded-lg border border-slate-800 text-center text-xs font-semibold hover:bg-slate-800 transition-colors">
            Details
          </NuxtLink>
          <button @click="deletePage(page.id)" class="px-3 py-2 rounded-lg border border-slate-800 text-rose-400 hover:bg-rose-500/10 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
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

const downloadHtml = (page) => {
  if (page.content_html) {
    const fullHtml = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${page.product_name}</title>
    <script src="https://cdn.tailwindcss.com"></` + `script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { opacity: 0; transition: opacity 0.6s ease-in; font-family: 'Inter', sans-serif; }
        .title-font { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-slate-950" onload="document.body.style.opacity='1'">
    ${page.content_html}
</body>
</html>`;

    const blob = new Blob([fullHtml], { type: 'text/html' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `sales-page-${page.product_name.replace(/\s+/g, '-').toLowerCase()}.html`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    URL.revokeObjectURL(url)
  } else {
    alert('Content is not ready yet.')
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
