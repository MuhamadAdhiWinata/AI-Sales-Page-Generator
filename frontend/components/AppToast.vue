<template>
  <div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 pointer-events-none">
    <TransitionGroup name="toast">
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        class="pointer-events-auto flex items-center p-4 w-full max-w-xs rounded-2xl shadow-xl shadow-black/40 transform transition-all duration-300 backdrop-blur-xl border"
        :class="getToastClasses(toast.type)"
        role="alert"
      >
        <div class="inline-flex items-center justify-center flex-shrink-0 w-9 h-9 rounded-xl mr-3 shadow-inner" :class="getIconContainerClasses(toast.type)">
          <!-- Success Icon -->
          <svg v-if="toast.type === 'success'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
          </svg>
          <!-- Error Icon -->
          <svg v-else-if="toast.type === 'error'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
          </svg>
          <!-- Warning/Info Icon -->
          <svg v-else class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
          </svg>
        </div>
        <div class="text-sm font-semibold tracking-wide">{{ toast.message }}</div>
        <button @click="removeToast(toast.id)" type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 transition-all focus:ring-2 focus:outline-none" :class="getCloseBtnClasses(toast.type)" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
const { toasts, removeToast } = useToast()

const getToastClasses = (type) => {
  switch (type) {
    case 'success': return 'bg-slate-900/95 border-emerald-500/30 text-emerald-400'
    case 'error': return 'bg-slate-900/95 border-rose-500/30 text-rose-400'
    case 'warning': return 'bg-slate-900/95 border-amber-500/30 text-amber-400'
    case 'info':
    default: return 'bg-slate-900/95 border-blue-500/30 text-blue-400'
  }
}

const getIconContainerClasses = (type) => {
  switch (type) {
    case 'success': return 'text-emerald-500 bg-emerald-500/10 border border-emerald-500/20'
    case 'error': return 'text-rose-500 bg-rose-500/10 border border-rose-500/20'
    case 'warning': return 'text-amber-500 bg-amber-500/10 border border-amber-500/20'
    case 'info':
    default: return 'text-blue-500 bg-blue-500/10 border border-blue-500/20'
  }
}

const getCloseBtnClasses = (type) => {
  switch (type) {
    case 'success': return 'text-emerald-500/70 hover:text-emerald-400 hover:bg-emerald-500/20 focus:ring-emerald-400/50'
    case 'error': return 'text-rose-500/70 hover:text-rose-400 hover:bg-rose-500/20 focus:ring-rose-400/50'
    case 'warning': return 'text-amber-500/70 hover:text-amber-400 hover:bg-amber-500/20 focus:ring-amber-400/50'
    case 'info':
    default: return 'text-blue-500/70 hover:text-blue-400 hover:bg-blue-500/20 focus:ring-blue-400/50'
  }
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(40px) scale(0.95);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(40px) scale(0.95);
}
</style>
