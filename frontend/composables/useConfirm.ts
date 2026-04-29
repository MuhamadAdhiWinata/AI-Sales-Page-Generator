import { ref } from 'vue'

export interface ConfirmOptions {
  title?: string
  message: string
  confirmText?: string
  cancelText?: string
  type?: 'danger' | 'warning' | 'info'
}

const isOpen = ref(false)
const options = ref<ConfirmOptions>({ message: '' })
const resolvePromise = ref<((value: boolean) => void) | null>(null)

export const useConfirm = () => {
  const requireConfirm = (opts: ConfirmOptions): Promise<boolean> => {
    options.value = {
      title: opts.title || 'Konfirmasi',
      confirmText: opts.confirmText || 'Ya, Lanjutkan',
      cancelText: opts.cancelText || 'Batal',
      type: opts.type || 'warning',
      ...opts
    }
    isOpen.value = true
    return new Promise((resolve) => {
      resolvePromise.value = resolve
    })
  }

  const confirm = () => {
    isOpen.value = false
    if (resolvePromise.value) resolvePromise.value(true)
  }

  const cancel = () => {
    isOpen.value = false
    if (resolvePromise.value) resolvePromise.value(false)
  }

  return {
    isOpen,
    options,
    requireConfirm,
    confirm,
    cancel
  }
}
