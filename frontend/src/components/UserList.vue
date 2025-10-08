<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center sm:text-left">
        Listado de Usuarios
      </h1>
      <button
        @click="$emit('create-user')"
        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
      >
        Crear Nuevo Usuario
      </button>
    </div>

    <!-- Estado: Cargando -->
    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-gray-600">Cargando usuarios...</p>
    </div>

    <!-- Estado: Sin usuarios -->
    <div v-else-if="users.length === 0" class="text-center py-8">
      <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 max-w-2xl mx-auto rounded-lg">
        <div class="flex items-center justify-center gap-2">
          <svg class="h-5 w-5 text-yellow-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path
              fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          <p class="text-sm text-yellow-700">
            No hay usuarios registrados. Verifica que tu API esté corriendo en <strong>http://localhost:8000</strong>.
          </p>
        </div>
      </div>
    </div>

    <!-- Estado: Usuarios cargados -->
    <div v-else>
      <!-- Tabla (desktop) -->
      <div class="hidden md:block bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">ID</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Nombres</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Apellidos</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Email</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Teléfono</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Registro</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Últ. Modif.</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="user in users"
                :key="user.id"
                class="hover:bg-gray-50"
              >
                <td class="px-6 py-4 text-gray-900">{{ user.id }}</td>
                <td class="px-6 py-4 text-gray-900">{{ user.nombres }}</td>
                <td class="px-6 py-4 text-gray-900">{{ user.apellidos }}</td>
                <td class="px-6 py-4 text-gray-700">{{ user.email }}</td>
                <td class="px-6 py-4 text-gray-700">{{ user.telefono }}</td>
                <td class="px-6 py-4 text-gray-600">{{ formatDate(user.fecha_registro) }}</td>
                <td class="px-6 py-4 text-gray-600">{{ formatDate(user.fecha_modificacion) }}</td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <button
                      @click="$emit('edit-user', user.id)"
                      class="text-blue-600 hover:underline"
                    >
                      Editar
                    </button>
                    <button
                      @click="confirmDelete(user)"
                      class="text-red-600 hover:underline"
                    >
                      Eliminar
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista tipo tarjeta (mobile) -->
      <div class="md:hidden space-y-4">
        <div
          v-for="user in users"
          :key="user.id"
          class="bg-white rounded-lg shadow p-4 flex flex-col gap-2"
        >
          <div class="flex justify-between items-center border-b pb-2 mb-2">
            <h2 class="text-lg font-semibold text-gray-800">
              {{ user.nombres }} {{ user.apellidos }}
            </h2>
            <span class="text-xs text-gray-500">#{{ user.id }}</span>
          </div>

          <div class="text-sm text-gray-600 space-y-1">
            <p><strong>Email:</strong> {{ user.email }}</p>
            <p><strong>Teléfono:</strong> {{ user.telefono }}</p>
            <p><strong>Registrado:</strong> {{ formatDate(user.fecha_registro) }}</p>
            <p><strong>Actualizado:</strong> {{ formatDate(user.fecha_modificacion) }}</p>
          </div>

          <div class="flex justify-end gap-3 mt-3">
            <button
              @click="$emit('edit-user', user.id)"
              class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded"
            >
              Editar
            </button>
            <button
              @click="confirmDelete(user)"
              class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de eliminación -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 px-4"
      @click.self="showDeleteModal = false"
    >
      <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-auto">
        <h3 class="text-lg font-bold text-gray-900 mb-3">Confirmar Eliminación</h3>
        <p class="text-gray-600 mb-4">
          ¿Eliminar a <strong>{{ userToDelete?.nombres }} {{ userToDelete?.apellidos }}</strong>?
        </p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded"
          >
            Cancelar
          </button>
          <button
            @click="handleDelete"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserList',
  props: {
    users: Array,
    loading: Boolean
  },
  data() {
    return {
      showDeleteModal: false,
      userToDelete: null
    };
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    confirmDelete(user) {
      this.userToDelete = user;
      this.showDeleteModal = true;
    },
    handleDelete() {
      if (this.userToDelete) {
        this.$emit('delete-user', this.userToDelete.id);
        this.showDeleteModal = false;
        this.userToDelete = null;
      }
    }
  }
};
</script>
