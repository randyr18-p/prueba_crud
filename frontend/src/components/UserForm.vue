<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <div class="p-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ isEditMode ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
          </h2>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600 text-2xl font-bold"
          >
            &times;
          </button>
        </div>

        <!-- Previene envío doble -->
        <form @submit.prevent.stop="handleSubmit">
          <div class="space-y-4">
            <div>
              <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">
                Nombres <span class="text-red-500">*</span>
              </label>
              <input
                id="nombres"
                v-model="formData.nombres"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.nombres }"
              />
              <p v-if="errors.nombres" class="mt-1 text-sm text-red-600">{{ errors.nombres }}</p>
            </div>

            <div>
              <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                Apellidos <span class="text-red-500">*</span>
              </label>
              <input
                id="apellidos"
                v-model="formData.apellidos"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.apellidos }"
              />
              <p v-if="errors.apellidos" class="mt-1 text-sm text-red-600">{{ errors.apellidos }}</p>
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="formData.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.email }"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
            </div>

            <div>
              <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                Teléfono <span class="text-red-500">*</span>
              </label>
              <input
                id="telefono"
                v-model="formData.telefono"
                type="tel"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.telefono }"
              />
              <p v-if="errors.telefono" class="mt-1 text-sm text-red-600">{{ errors.telefono }}</p>
            </div>

            <div v-if="isEditMode" class="bg-gray-50 p-4 rounded-lg">
              <p class="text-sm text-gray-600">
                <strong>Fecha de registro:</strong> {{ formatDate(formData.fecha_registro) }}
              </p>
            </div>
          </div>

          <div class="mt-8 flex justify-end space-x-3">
            <button
              type="button"
              @click="$emit('close')"
              class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition duration-200"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="loading || isSubmitting"
              @click.stop 
              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 disabled:bg-blue-400 disabled:cursor-not-allowed"
            >
              {{ isSubmitting ? 'Guardando...' : (isEditMode ? 'Actualizar' : 'Crear') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserForm',
  props: {
    user: Object,
    loading: Boolean,
    validationErrors: Object
  },
  data() {
    return {
      formData: {
        nombres: '',
        apellidos: '',
        email: '',
        telefono: '',
        fecha_registro: null
      },
      errors: {},
      isSubmitting: false // 🔒 evita envíos dobles
    };
  },
  computed: {
    isEditMode() {
      return !!this.user;
    }
  },
  watch: {
    user: {
      immediate: true,
      handler(newUser) {
        if (newUser) {
          this.formData = { ...newUser };
        } else {
          this.resetForm();
        }
      }
    }
  },
  methods: {
    validateForm() {
      this.errors = {};
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const phoneRegex = /^[0-9+\-\s()]+$/;

      if (!emailRegex.test(this.formData.email)) {
        this.errors.email = 'El formato del email no es válido';
      }
      if (!phoneRegex.test(this.formData.telefono)) {
        this.errors.telefono = 'El teléfono solo puede contener números y símbolos (+, -, espacios, paréntesis)';
      }
      if (this.formData.nombres.trim().length < 2) {
        this.errors.nombres = 'Los nombres deben tener al menos 2 caracteres';
      }
      if (this.formData.apellidos.trim().length < 2) {
        this.errors.apellidos = 'Los apellidos deben tener al menos 2 caracteres';
      }

      return Object.keys(this.errors).length === 0;
    },
    async handleSubmit(event) {
      if (event) event.preventDefault();
      if (this.isSubmitting) return; 
      this.isSubmitting = true;

      try {
        if (!this.validateForm()) {
          this.isSubmitting = false;
          return;
        }

        const submitData = {
          nombres: this.formData.nombres.trim(),
          apellidos: this.formData.apellidos.trim(),
          email: this.formData.email.trim(),
          telefono: this.formData.telefono.trim()
        };

        this.$emit('submit', submitData);
      } finally {
        setTimeout(() => { this.isSubmitting = false; }, 1000); // desbloquea tras 1 seg
      }
    },
    resetForm() {
      this.formData = { nombres: '', apellidos: '', email: '', telefono: '', fecha_registro: null };
      this.errors = {};
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('es-ES', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' });
    }
  }
};
</script>
