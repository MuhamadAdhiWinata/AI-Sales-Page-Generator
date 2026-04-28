<template>
  <div class="w-full h-screen bg-[#0b0b0c]">
    <iframe
      v-if="content"
      ref="iframeRef"
      :srcdoc="content"
      class="w-full h-full border-none"
      @load="loading = false"
    ></iframe>
    
    <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-[#0b0b0c] text-white z-50">
      <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="animate-pulse">Menyiapkan Preview...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { token } = useAuth()
const config = useRuntimeConfig()
const content = ref('')
const loading = ref(true)

onMounted(async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    content.value = data.content_html
  } catch (e) {
    console.error('Failed to load preview', e)
    loading.value = false
  }
})

definePageMeta({
  layout: false
})
</script>
