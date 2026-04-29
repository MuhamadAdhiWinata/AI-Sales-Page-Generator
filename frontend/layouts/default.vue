<template>
  <div class="min-h-screen bg-slate-950 text-slate-200 selection:bg-indigo-500/30">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/80 backdrop-blur-md">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <NuxtLink to="/" class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
          AI Sales Gen
        </NuxtLink>

        <div class="flex items-center gap-6">
          <template v-if="user">
            <NuxtLink id="create-page-btn" to="/generate" class="text-sm font-medium hover:text-indigo-400 transition-colors">Generate</NuxtLink>
            <NuxtLink to="/history" class="text-sm font-medium hover:text-indigo-400 transition-colors">All Pages</NuxtLink>
            <div class="h-4 w-px bg-slate-800"></div>
            <NuxtLink to="/docs" class="text-sm font-medium text-slate-400 hover:text-indigo-400 transition-colors flex items-center gap-1 mr-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              Docs
            </NuxtLink>
            <span class="text-sm text-slate-400">{{ user.name }}</span>
            <button @click="logout" class="text-sm font-medium text-rose-400 hover:text-rose-300 transition-colors">
              Logout
            </button>
          </template>
          <template v-else>
            <NuxtLink to="/docs" class="text-sm font-medium text-slate-400 hover:text-indigo-400 transition-colors flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              Docs
            </NuxtLink>
            <div class="h-4 w-px bg-slate-800"></div>
            <NuxtLink to="/login" class="text-sm font-medium hover:text-indigo-400 transition-colors">Login</NuxtLink>
            <NuxtLink to="/register" class="px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-500/20">
              Get Started
            </NuxtLink>
          </template>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot />
    </main>
    
    <AppConfirm />
    <AppToast />
  </div>
</template>

<script setup>
const { user, logout, fetchUser } = useAuth()

onMounted(() => {
  fetchUser()
})
</script>

<style>
body {
  @apply bg-slate-950;
}
</style>
