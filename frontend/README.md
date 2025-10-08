# Vue 3 CRUD - Frontend

Aplicación frontend en Vue 3 para gestión de usuarios con Laravel API.

## Requisitos Previos

- **Node.js** (versión 18 o superior)
- **npm** o **yarn**
- **Visual Studio Code** (recomendado)
- **API Laravel** corriendo en `http://localhost:8000`

## Configuración en VS Code

### 1. Extensiones Recomendadas

Instala estas extensiones en VS Code para una mejor experiencia de desarrollo:

- **Vue - Official** (Vue.volar) - Soporte para Vue 3
- **ESLint** - Linter de código
- **Prettier** - Formateador de código
- **Tailwind CSS IntelliSense** - Autocompletado para Tailwind

Para instalar, abre VS Code y ve a la pestaña de Extensiones (Ctrl+Shift+X) y busca cada una.

### 2. Configuración de VS Code (Opcional)

Crea un archivo `.vscode/settings.json` en la raíz del proyecto con:

```json
{
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "[vue]": {
    "editor.defaultFormatter": "Vue.volar"
  }
}
```

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/mangotinita/frontend.git
cd frontend
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Configurar variables de entorno

El archivo `.env` ya está incluido con la configuración por defecto:

```env
VITE_API_URL=http://localhost:8000/api
```

Si tu API Laravel está en otro puerto o URL, modifica este archivo.

## Ejecutar el Proyecto

### Modo Desarrollo

```bash
npm run dev
```

La aplicación estará disponible en: `http://localhost:5173`

### Construir para Producción

```bash
npm run build
```

### Vista Previa de Producción

```bash
npm run preview
```

## Estructura del Proyecto

```
src/
├── components/
│   ├── Alert.vue          # Componente para mostrar mensajes
│   ├── UserForm.vue       # Formulario de crear/editar usuario
│   └── UserList.vue       # Lista de usuarios con tabla
├── services/
│   └── userService.js     # Servicio para llamadas a la API
├── App.vue                # Componente principal
├── main.js                # Punto de entrada
└── style.css              # Estilos globales con Tailwind
```

## Configuración del Backend

Asegúrate de que tu API Laravel tenga CORS configurado correctamente:

```php
// config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173', 'http://localhost:3000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

## Funcionalidades

- Listar usuarios con paginación
- Crear nuevos usuarios
- Editar usuarios existentes
- Eliminar usuarios
- Validación de formularios
- Mensajes de éxito/error
- Interfaz responsive con Tailwind CSS

## Tecnologías Utilizadas

- **Vue 3** - Framework JavaScript
- **Vite** - Build tool y dev server
- **Tailwind CSS 4** - Framework CSS
- **Axios** - Cliente HTTP
- **Vue 3 Composition API** - Para componentes reactivos

## Solución de Problemas

### La aplicación no se conecta a la API

1. Verifica que Laravel esté corriendo en `http://localhost:8000`
2. Revisa la consola del navegador (F12) para ver errores
3. Verifica que CORS esté configurado correctamente en Laravel
4. Asegúrate de que la URL en `.env` sea correcta

### Error de CORS

Si ves errores de CORS en la consola:

1. Verifica que Laravel tenga el paquete `fruitcake/laravel-cors` instalado
2. Asegura que `http://localhost:5173` esté en la lista de `allowed_origins`
3. Limpia la caché de Laravel: `php artisan config:cache`

### No se cargan los estilos

1. Asegúrate de que Tailwind esté instalado: `npm install`
2. Verifica que `postcss.config.js` esté presente
3. Reinicia el servidor de desarrollo

## Contacto

Para dudas o sugerencias, abre un issue en el repositorio.
