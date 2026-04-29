<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
      <div class="fixed inset-0" @click="cancel"></div>
      <div class="relative w-full max-w-md bg-slate-900 border border-slate-700/50 shadow-2xl rounded-2xl p-6 transform transition-all">
        <!-- Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4 shadow-inner" :class="iconBgClass">
          <svg v-if="options.type === 'danger'" class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <svg v-else class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        
        <h3 class="text-xl font-bold text-center text-white mb-2">{{ options.title }}</h3>
        <p class="text-center text-slate-400 text-sm mb-8 leading-relaxed">{{ options.message }}</p>
        
        <div class="flex gap-3 w-full">
          <button @click="cancel" type="button" class="flex-1 px-4 py-3 rounded-xl border border-slate-700 text-slate-300 font-medium hover:bg-slate-800 transition-all focus:ring-2 focus:ring-slate-600 focus:outline-none">
            {{ options.cancelText }}
          </button>
          <button @click="confirm" type="button" class="flex-1 px-4 py-3 rounded-xl text-white font-bold shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:outline-none hover:-translate-y-0.5 active:translate-y-0" :class="btnClass">
            {{ options.confirmText }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'

const { isOpen, options, confirm, cancel } = useConfirm()

const iconBgClass = computed(() => {
  return options.value.type === 'danger' ? 'bg-rose-500/10 border border-rose-500/20' : 'bg-amber-500/10 border border-amber-500/20'
})

const btnClass = computed(() => {
  return options.value.type === 'danger' 
    ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-500/20 focus:ring-rose-500' 
    : 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-500/20 focus:ring-indigo-500'
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.fade-enter-active > div:nth-child(2),
.fade-leave-active > div:nth-child(2) {
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fade-enter-from > div:nth-child(2),
.fade-leave-to > div:nth-child(2) {
  transform: scale(0.95) translateY(10px);
}
</style>
