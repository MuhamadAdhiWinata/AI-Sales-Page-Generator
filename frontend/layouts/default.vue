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
            <NuxtLink to="/generate" class="text-sm font-medium hover:text-indigo-400 transition-colors">Generate</NuxtLink>
            <NuxtLink to="/history" class="text-sm font-medium hover:text-indigo-400 transition-colors">All Pages</NuxtLink>
            <div class="h-4 w-px bg-slate-800"></div>
            <span class="text-sm text-slate-400">{{ user.name }}</span>
            <button @click="logout" class="text-sm font-medium text-rose-400 hover:text-rose-300 transition-colors">
              Logout
            </button>
          </template>
          <template v-else>
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
