<template>
  <transition
    enter-active-class="transform transition ease-out duration-300"
    enter-from-class="translate-y-2 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transition ease-in duration-200"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="visible"
      class="fixed top-4 right-4 z-50 max-w-md w-full shadow-lg rounded-lg overflow-hidden"
      :class="alertClasses"
    >
      <div class="p-4 flex items-start">
        <div class="flex-shrink-0">
          <svg
            v-if="type === 'success'"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <svg
            v-else-if="type === 'error'"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <svg
            v-else-if="type === 'warning'"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            />
          </svg>
          <svg
            v-else
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <div class="ml-3 w-0 flex-1">
          <p class="text-sm font-medium whitespace-pre-line">
            {{ message }}
          </p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
          <button
            @click="close"
            class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="closeButtonClasses"
          >
            <span class="sr-only">Cerrar</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Barra de progreso para auto-cierre (solo si tiene duración) -->
      <div
        v-if="duration > 0"
        class="h-1 transition-all ease-linear"
        :class="progressBarClasses"
        :style="{ width: progressWidth + '%' }"
      ></div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Alert',
  props: {
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'info',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    duration: {
      type: Number,
      default: 5000 // 5 segundos por defecto
    }
  },
  emits: ['close'],
  data() {
    return {
      visible: false,
      timer: null,
      progressTimer: null,
      progressWidth: 100
    };
  },
  computed: {
    alertClasses() {
      const classes = {
        success: 'bg-green-50 text-green-800 border-l-4 border-green-500',
        error: 'bg-red-50 text-red-800 border-l-4 border-red-500',
        warning: 'bg-yellow-50 text-yellow-800 border-l-4 border-yellow-500',
        info: 'bg-blue-50 text-blue-800 border-l-4 border-blue-500'
      };
      return classes[this.type] || classes.info;
    },
    closeButtonClasses() {
      const classes = {
        success: 'text-green-500 hover:text-green-600 focus:ring-green-500',
        error: 'text-red-500 hover:text-red-600 focus:ring-red-500',
        warning: 'text-yellow-500 hover:text-yellow-600 focus:ring-yellow-500',
        info: 'text-blue-500 hover:text-blue-600 focus:ring-blue-500'
      };
      return classes[this.type] || classes.info;
    },
    progressBarClasses() {
      const classes = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
      };
      return classes[this.type] || classes.info;
    }
  },
  mounted() {
    this.show();
  },
  beforeUnmount() {
    this.clearTimers();
  },
  methods: {
    show() {
      this.visible = true;
      
      if (this.duration > 0) {
        // Timer para cerrar la alerta
        this.timer = setTimeout(() => {
          this.close();
        }, this.duration);

        // Timer para la barra de progreso
        this.startProgressBar();
      }
    },
    startProgressBar() {
      const intervalTime = 50; // Actualizar cada 50ms
      const steps = this.duration / intervalTime;
      const decrementAmount = 100 / steps;

      this.progressTimer = setInterval(() => {
        this.progressWidth -= decrementAmount;
        if (this.progressWidth <= 0) {
          this.progressWidth = 0;
          clearInterval(this.progressTimer);
        }
      }, intervalTime);
    },
    clearTimers() {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }
      if (this.progressTimer) {
        clearInterval(this.progressTimer);
        this.progressTimer = null;
      }
    },
    close() {
      this.clearTimers();
      this.visible = false;
      setTimeout(() => {
        this.$emit('close');
      }, 300);
    }
  }
};
</script>