# 🔥 Sistema de Control de Botellas de Gas Butano

Sistema completo para gestionar y controlar el uso de botellas de butano en cocina y calentador, con seguimiento de consumo, historial de compras y estadísticas detalladas.

## 🎯 Características Principales

### ✅ Funcionalidades Implementadas

1. **Dashboard Inteligente**
   - Vista general con botellas activas
   - Estadísticas por ubicación (cocina/calentador)
   - Duración promedio de botellas
   - Estimación de uso restante
   - Últimas compras registradas

2. **Gestión de Botellas**
   - Registro de instalación con fecha/hora
   - Cambio automático (finaliza la anterior al instalar nueva)
   - Cálculo automático de duración
   - Estimación de uso diario (kg/día)
   - Indicador visual de progreso

3. **Historial Completo**
   - Filtrado por ubicación
   - Ordenamiento flexible
   - Detalles de cada botella
   - Vinculación con compras

4. **Control de Compras**
   - Registro de precios
   - Seguimiento de proveedores
   - Evolución de precios mensuales
   - Precio por kg calculado automáticamente
   - Estadísticas anuales

5. **Estadísticas Avanzadas**
   - Comparación cocina vs calentador
   - Promedios, mínimos y máximos
   - Gráficos de evolución de precios
   - Total gastado por período

### 📱 Optimizado para Móvil
- Interfaz responsive con Tailwind CSS
- Componentes táctiles grandes
- Navegación con tabs
- Diseño mobile-first

## 🚀 Instalación

### 1. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará las tablas:
- `gas_bottles` - Registro de botellas instaladas
- `gas_purchases` - Historial de compras

### 2. Cargar Datos de Prueba (Opcional)

```bash
php artisan db:seed --class=GasDataSeeder
```

Esto generará:
- Datos de los últimos 6 meses
- Botellas de cocina (duración 45-60 días)
- Botellas de calentador (duración 25-40 días)
- Precios realistas (14-18€)
- Proveedores variados

### 3. Iniciar el Servidor

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite (para assets)
npm run dev
```

## 📍 Rutas Disponibles

Una vez autenticado, puedes acceder a:

- `/gas` o `/dashboard` - Dashboard principal
- `/gas/install` - Instalar nueva botella
- `/gas/history` - Historial completo
- `/gas/purchases` - Análisis de compras

## 💡 Uso Recomendado

### Flujo Típico:

1. **Primera Vez**
   - Registra la botella actual de cocina
   - Registra la botella actual de calentador
   - Añade el precio si la acabas de comprar

2. **Al Cambiar Botella**
   - Ve a "Instalar Nueva Botella"
   - Selecciona ubicación (cocina/calentador)
   - Confirma fecha/hora de instalación
   - Añade precio de compra
   - El sistema automáticamente:
     * Marca la anterior como terminada
     * Calcula su duración en días
     * Calcula el uso diario (kg/día)

3. **Consulta Estadísticas**
   - Dashboard muestra resumen actual
   - Historial para ver patrones
   - Compras para analizar precios

## 📊 Estructura de Datos

### Tabla: gas_bottles

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| user_id | bigint | Usuario propietario |
| location | enum | 'cocina' o 'calentador' |
| weight_kg | decimal | Peso de la botella (default: 12.5kg) |
| installed_at | datetime | Fecha/hora de instalación |
| finished_at | datetime | Fecha/hora de finalización (null si activa) |
| duration_days | int | Días que duró (calculado) |
| estimated_daily_usage | decimal | Kg/día de consumo (calculado) |
| notes | text | Notas opcionales |

### Tabla: gas_purchases

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| user_id | bigint | Usuario |
| gas_bottle_id | bigint | Botella asociada |
| price | decimal | Precio de compra en € |
| purchase_date | date | Fecha de compra |
| supplier | string | Proveedor/tienda |
| weight_kg | decimal | Peso (default: 12.5kg) |
| bottle_type | enum | 'nueva' o 'recarga' |
| notes | text | Notas opcionales |

## 🎨 Componentes Livewire

### Dashboard (`App\Livewire\Gas\Dashboard`)
- Componente principal con tabs
- Estadísticas en tiempo real
- Vista de botellas activas

### InstallBottle (`App\Livewire\Gas\InstallBottle`)
- Formulario de instalación
- Validación de campos
- Registro opcional de compra
- Finalización automática de botella anterior

### History (`App\Livewire\Gas\History`)
- Listado paginado de botellas
- Filtros y ordenamiento
- Acciones (eliminar, marcar terminada)

### Purchases (`App\Livewire\Gas\Purchases`)
- Historial de compras
- Gráficos de evolución de precios
- Filtros por año y proveedor
- Estadísticas de gasto

## 🔧 Modelos Eloquent

### GasBottle
- `active()` - Scope para botellas activas
- `finished()` - Scope para botellas terminadas
- `byLocation()` - Filtrar por ubicación
- `markAsFinished()` - Finalizar botella con cálculos
- Atributos calculados:
  - `days_elapsed` - Días desde instalación
  - `status` - Estado (activa/terminada)
  - `estimated_usage_percentage` - % estimado de uso

### GasPurchase
- `recent()` - Scope ordenado por fecha desc
- Atributo calculado:
  - `price_per_kg` - Precio por kilogramo

## 💰 Estadísticas Disponibles

- **Duración promedio** por ubicación
- **Uso diario promedio** (kg/día)
- **Rango de duración** (min-max)
- **Total gastado** (histórico y anual)
- **Precio medio** de compras
- **Evolución de precios** mensual
- **Comparación** cocina vs calentador

## 📱 Diseño Mobile-First

- **Colores por sección:**
  - Dashboard: Azul
  - Instalar: Verde
  - Historial: Púrpura
  - Compras: Índigo

- **Componentes táctiles:**
  - Botones grandes (py-4)
  - Áreas de click amplias
  - Navegación por tabs

- **Feedback visual:**
  - Barras de progreso con gradientes
  - Estados con colores (activa=verde, terminada=gris)
  - Iconos descriptivos (🍳 cocina, 🚿 calentador)

## 🔮 Funcionalidades Avanzadas

### Estimación Inteligente
El sistema calcula automáticamente cuánto puede durar una botella activa basándose en el histórico de botellas anteriores en la misma ubicación.

### Alertas Visuales
- Barra de progreso amarilla-verde cuando está por debajo del 80%
- Barra roja cuando supera el 80% estimado

### Análisis de Precios
- Gráfico de evolución mensual
- Comparación entre proveedores
- Precio por kg calculado

## 🎯 Mejoras Futuras (Sugerencias)

1. **Notificaciones**
   - Alerta cuando una botella esté cerca de agotarse
   - Recordatorio para comprar basado en promedio

2. **Exportación**
   - PDF con historial
   - CSV de compras para contabilidad

3. **Comparativas**
   - Consumo vs temperatura exterior
   - Impacto de cambios de hábitos

4. **Multi-hogar**
   - Gestionar botellas de varias viviendas
   - Comparar consumos entre hogares

5. **API/PWA**
   - Convertir en Progressive Web App
   - Funcionar offline

## 📄 Licencia

Este sistema está diseñado para uso personal. Siéntete libre de adaptarlo a tus necesidades.

---

**¿Preguntas?** Revisa el código en:
- `app/Models/GasBottle.php` - Lógica de botellas
- `app/Livewire/Gas/` - Componentes de la interfaz
- `resources/views/livewire/gas/` - Vistas Blade
