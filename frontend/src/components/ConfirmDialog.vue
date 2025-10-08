<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Overlay -->
    <div 
      class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
      @click="$emit('cancel')"
    ></div>

    <!-- Dialog -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <!-- Icono de advertencia -->
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>

        <!-- Título -->
        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
          {{ title }}
        </h3>

        <!-- Mensaje -->
        <p class="text-sm text-gray-500 text-center mb-6">
          {{ message }}
        </p>

        <!-- Botones -->
        <div class="flex gap-3">
          <button
            type="button"
            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
            @click="$emit('cancel')"
          >
            {{ cancelText }}
          </button>
          <button
            type="button"
            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
            @click="$emit('confirm')"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConfirmDialog',
  props: {
    title: {
      type: String,
      default: 'Confirmar acción'
    },
    message: {
      type: String,
      required: true
    },
    confirmText: {
      type: String,
      default: 'Confirmar'
    },
    cancelText: {
      type: String,
      default: 'Cancelar'
    }
  },
  emits: ['confirm', 'cancel']
};
</script>