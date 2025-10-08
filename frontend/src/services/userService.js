import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

/**
 * Clase de error personalizada para errores de validación
 */
class ValidationError extends Error {
  constructor(message, errors, help) {
    super(message);
    this.name = 'ValidationError';
    this.errors = errors;
    this.help = help;
    this.isValidationError = true;
  }

  /**
   * Obtiene todos los mensajes de error como un array
   */
  getErrorMessages() {
    return Object.values(this.errors).flat();
  }

  /**
   * Obtiene todos los mensajes de error como un string
   */
  getErrorMessagesAsString() {
    return this.getErrorMessages().join('\n');
  }

  /**
   * Obtiene el mensaje completo formateado
   */
  getFullMessage() {
    const errors = this.getErrorMessagesAsString();
    return `${this.message}\n\n${errors}${this.help ? `\n\n${this.help}` : ''}`;
  }
}

/**
 * Maneja los errores de las peticiones HTTP
 */
const handleError = (error, defaultMessage) => {
  // Error de validación (422)
  if (error.response?.status === 422 && error.response?.data?.errors) {
    throw new ValidationError(
      error.response.data.message || 'Errores de validación',
      error.response.data.errors,
      error.response.data.help
    );
  }

  // Error de red o servidor
  if (!error.response) {
    throw new Error('Error de conexión. Verifica tu conexión a internet.');
  }

  // Otros errores HTTP
  throw new Error(error.response?.data?.message || defaultMessage);
};

const userService = {
  /**
   * Obtiene todos los usuarios activos
   */
  async getAllUsers() {
    try {
      const response = await axios.get(`${API_BASE_URL}/users`);
      return response.data;
    } catch (error) {
      handleError(error, 'Error al obtener usuarios');
    }
  },

  /**
   * Obtiene un usuario por su ID
   */
  async getUserById(id) {
    try {
      const response = await axios.get(`${API_BASE_URL}/users/${id}`);
      return response.data;
    } catch (error) {
      handleError(error, 'Error al obtener el usuario');
    }
  },

  /**
   * Crea un nuevo usuario o reactiva uno existente
   */
  async createUser(userData) {
    try {
      const response = await axios.post(`${API_BASE_URL}/users`, userData);
      return response.data;
    } catch (error) {
      handleError(error, 'Error al crear el usuario');
    }
  },

  /**
   * Actualiza un usuario existente
   */
  async updateUser(id, userData) {
    try {
      const response = await axios.put(`${API_BASE_URL}/users/${id}`, userData);
      return response.data;
    } catch (error) {
      handleError(error, 'Error al actualizar el usuario');
    }
  },

  /**
   * Elimina (desactiva) un usuario
   */
  async deleteUser(id) {
    try {
      const response = await axios.delete(`${API_BASE_URL}/users/${id}`);
      return response.data;
    } catch (error) {
      handleError(error, 'Error al eliminar el usuario');
    }
  }
};

export default userService;
export { ValidationError };