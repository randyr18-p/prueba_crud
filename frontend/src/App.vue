<template>
  <div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">Sistema de Gestión de Usuarios</h1>
      </div>
    </header>

    <main class="max-w-7xl mx-auto py-6">
      <UserList
        :users="users"
        :loading="loading"
        @create-user="openCreateForm"
        @edit-user="openEditForm"
        @delete-user="confirmDelete"
      />
    </main>

    <UserForm
      v-if="showForm"
      :user="selectedUser"
      :loading="formLoading"
      :validation-errors="validationErrors"
      @submit="handleFormSubmit"
      @close="closeForm"
    />

    <Alert
      v-for="alert in alerts"
      :key="alert.id"
      :message="alert.message"
      :type="alert.type"
      @close="removeAlert(alert.id)"
    />

    <!-- Modal de confirmación de eliminación -->
    <ConfirmDialog
      v-if="showDeleteConfirm"
      title="Confirmar eliminación"
      message="¿Está seguro de que desea eliminar este usuario? Esta acción cambiará su estado a inactivo."
      confirm-text="Eliminar"
      cancel-text="Cancelar"
      @confirm="executeDelete"
      @cancel="cancelDelete"
    />
  </div>
</template>

<script>
import UserList from './components/UserList.vue';
import UserForm from './components/UserForm.vue';
import Alert from './components/Alert.vue';
import ConfirmDialog from './components/ConfirmDialog.vue';
import userService, { ValidationError } from './services/userService.js';

export default {
  name: 'App',
  components: {
    UserList,
    UserForm,
    Alert,
    ConfirmDialog
  },
  data() {
    return {
      users: [],
      loading: false,
      showForm: false,
      selectedUser: null,
      formLoading: false,
      alerts: [],
      alertIdCounter: 0,
      validationErrors: {},
      showDeleteConfirm: false,
      userToDelete: null
    };
  },
  mounted() {
    this.loadUsers();
  },
  methods: {
    /**
     * Carga la lista de usuarios desde la API
     */
    async loadUsers() {
      this.loading = true;
      try {
        const response = await userService.getAllUsers();
        console.log('API Response:', response);

        // Extraer los datos de usuarios de la respuesta
        let userData = response;
        if (response.data) {
          userData = response.data;
        }
        if (response.users) {
          userData = response.users;
        }

        this.users = Array.isArray(userData) ? userData : [];

        // Ordenar usuarios alfabéticamente
        if (this.users.length > 0) {
          this.users.sort((a, b) => {
            const nameA = `${a.nombres || a.name || ''} ${a.apellidos || ''}`.toLowerCase();
            const nameB = `${b.nombres || b.name || ''} ${b.apellidos || ''}`.toLowerCase();
            return nameA.localeCompare(nameB);
          });
        }

        console.log('Users loaded:', this.users.length);
      } catch (error) {
        console.error('Error loading users:', error);
        this.showAlert(error.message, 'error');
      } finally {
        this.loading = false;
      }
    },

    /**
     * Abre el formulario para crear un nuevo usuario
     */
    openCreateForm() {
      this.selectedUser = null;
      this.validationErrors = {};
      this.showForm = true;
    },

    /**
     * Abre el formulario para editar un usuario existente
     */
    async openEditForm(userId) {
      try {
        const response = await userService.getUserById(userId);
        this.selectedUser = response.data || response;
        this.validationErrors = {};
        this.showForm = true;
      } catch (error) {
        this.showAlert(error.message, 'error');
      }
    },

    /**
     * Cierra el formulario y limpia los datos
     */
    closeForm() {
      this.showForm = false;
      this.selectedUser = null;
      this.validationErrors = {};
    },

    /**
     * Maneja el envío del formulario (crear o actualizar usuario)
     */
    async handleFormSubmit(userData) {
      this.formLoading = true;
      this.validationErrors = {}; // Limpiar errores previos

      try {
        let response;
        
        if (this.selectedUser) {
          // Actualizar usuario existente
          response = await userService.updateUser(this.selectedUser.id, userData);
          this.showAlert(response.message || 'Usuario actualizado exitosamente', 'success');
        } else {
          // Crear nuevo usuario (o reactivar si estaba inactivo)
          response = await userService.createUser(userData);
          this.showAlert(response.message || 'Usuario creado exitosamente', 'success');
        }

        this.closeForm();
        await this.loadUsers();

      } catch (error) {
        console.error('Error submitting form:', error);

        // Manejar errores de validación
        if (error.isValidationError) {
          this.validationErrors = error.errors;
          
          // Mostrar alerta con el resumen de errores
          const errorCount = Object.keys(error.errors).length;
          const errorMessage = errorCount === 1 
            ? 'Se encontró 1 error en el formulario' 
            : `Se encontraron ${errorCount} errores en el formulario`;
          
          this.showAlert(`${errorMessage}. Por favor revise los campos marcados.`, 'error');
        } else {
          // Otros tipos de errores
          this.showAlert(error.message, 'error');
        }
      } finally {
        this.formLoading = false;
      }
    },

    /**
     * Muestra el diálogo de confirmación antes de eliminar
     */
    confirmDelete(userId) {
      this.userToDelete = userId;
      this.showDeleteConfirm = true;
    },

    /**
     * Ejecuta la eliminación del usuario
     */
    async executeDelete() {
      if (!this.userToDelete) return;

      try {
        const response = await userService.deleteUser(this.userToDelete);
        this.showAlert(response.message || 'Usuario eliminado exitosamente', 'success');
        await this.loadUsers();
      } catch (error) {
        console.error('Error deleting user:', error);
        this.showAlert(error.message, 'error');
      } finally {
        this.cancelDelete();
      }
    },

    /**
     * Cancela la eliminación del usuario
     */
    cancelDelete() {
      this.showDeleteConfirm = false;
      this.userToDelete = null;
    },

    /**
     * Muestra una alerta al usuario
     */
    showAlert(message, type = 'info') {
      const alert = {
        id: this.alertIdCounter++,
        message,
        type
      };
      this.alerts.push(alert);

      // Auto-cerrar alertas de éxito después de 5 segundos
      if (type === 'success') {
        setTimeout(() => {
          this.removeAlert(alert.id);
        }, 5000);
      }
    },

    /**
     * Elimina una alerta
     */
    removeAlert(alertId) {
      const index = this.alerts.findIndex(a => a.id === alertId);
      if (index !== -1) {
        this.alerts.splice(index, 1);
      }
    }
  }
};
</script>