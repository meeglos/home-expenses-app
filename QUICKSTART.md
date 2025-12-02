# 🚀 Guía de Inicio Rápido - Sistema de Control de Gas

## ⚡ Puesta en Marcha (5 minutos)

### 1️⃣ Ejecutar Migraciones
```bash
php artisan migrate
```

### 2️⃣ Cargar Datos de Prueba (Recomendado)
```bash
php artisan db:seed --class=GasDataSeeder
```

Esto creará:
- ✅ Datos de los últimos 6 meses
- ✅ Botellas de cocina y calentador
- ✅ Compras con precios realistas
- ✅ 1-2 botellas activas actuales

### 3️⃣ Iniciar Servidor
```bash
# Terminal 1
php artisan serve

# Terminal 2  
npm run dev
```

### 4️⃣ Acceder a la Aplicación
1. Ve a: `http://localhost:8000`
2. Inicia sesión o regístrate
3. El dashboard te redirigirá automáticamente a `/gas`

---

## 📱 Acceso desde Móvil (Red Local)

### Opción 1: Usar IP Local
```bash
# 1. Descubre tu IP
ip addr show | grep "inet " | grep -v 127.0.0.1

# 2. Inicia el servidor con --host
php artisan serve --host=0.0.0.0 --port=8000

# 3. Accede desde móvil
# http://TU_IP:8000
```

### Opción 2: Usar Laravel Sail (Docker)
```bash
sail up -d
# Accede: http://localhost
```

---

## 🔧 Comandos Útiles

### Ver Reporte del Estado Actual
```bash
php artisan gas:report
```

Muestra:
- 🟢 Botellas activas con días de uso
- 📊 Barra de progreso estimado
- 📈 Estadísticas por ubicación

Ejemplo de salida:
```
📊 Reporte de Botellas de Gas - Usuario #1

🟢 BOTELLAS ACTIVAS:
  🍳 cocina: 28 días activa
     [████████████] 60% estimado
  🚿 calentador: 15 días activa
     [██████] 30% estimado

🍳 ESTADÍSTICAS DE COCINA:
  • Total de botellas: 5
  • Duración promedio: 52.4 días
  • Uso diario promedio: 0.238 kg/día
  • Rango: 45-60 días
```

### Limpiar y Reiniciar (con precaución)
```bash
# Eliminar TODOS los datos y recrear
php artisan migrate:fresh

# Con datos de prueba
php artisan migrate:fresh --seed --seeder=GasDataSeeder
```

### Verificar Rutas Disponibles
```bash
php artisan route:list --name=gas
```

### Limpiar Cachés
```bash
php artisan optimize:clear
```

---

## 🎯 Primeros Pasos en la App

### Escenario 1: Empezar desde Cero

1. **Instala tu primera botella**
   - Menú: "➕ Instalar Nueva Botella"
   - Selecciona ubicación (cocina/calentador)
   - Confirma fecha de instalación
   - Añade precio si la compraste

2. **Repite para la otra ubicación**
   - Instala también la otra botella (si tienes ambas)

3. **Explora el Dashboard**
   - Verás tus botellas activas
   - Aún no habrá estadísticas (necesitas al menos 1 botella terminada)

### Escenario 2: Con Datos de Prueba

1. **Explora el Dashboard**
   - Tab "📊 Resumen": Vista general
   - Tab "🔥 Activas": Botellas en uso
   - Tab "📈 Estadísticas": Comparativas

2. **Revisa el Historial**
   - Menú: "📋 Historial"
   - Filtra por ubicación
   - Ordena por diferentes criterios

3. **Analiza Compras**
   - Menú: "💶 Compras"
   - Ve evolución de precios
   - Filtra por año/proveedor

4. **Instala una Nueva**
   - Prueba el flujo completo
   - Observa cómo se actualiza todo automáticamente

---

## 🔥 Flujo Típico de Uso

### Día 1: Instalación Inicial
```
Usuario → "Instalar Nueva Botella"
       → Selecciona "Cocina"
       → Fecha: Hoy 10:30
       → Precio: 15.50€
       → Proveedor: Repsol
       → [Guardar]

Sistema → Crea registro de botella activa
       → Asocia compra
       → Muestra en dashboard
```

### Día 47: Cambio de Botella
```
Usuario → "Instalar Nueva Botella"
       → Selecciona "Cocina"
       → Fecha: Hoy 11:00
       → Precio: 16.20€

Sistema → Marca anterior como terminada automáticamente
       → Calcula: Duró 47 días
       → Calcula: Uso diario = 12.5kg ÷ 47 = 0.266 kg/día
       → Crea nueva botella activa
       → Actualiza estadísticas
```

### Cualquier Día: Consulta
```
Usuario → Dashboard
       → Ve: "Cocina activa hace 15 días"
       → Ve: "Estimado 32% usado"
       → Ve: "Promedio: 47 días"
```

---

## 📊 Interpretando las Estadísticas

### Duración Promedio
- **Alta (>60 días)**: Bajo consumo, eficiente
- **Normal (30-60 días)**: Uso estándar
- **Baja (<30 días)**: Alto consumo, revisar

### Uso Diario
- **Cocina típico**: 0.2 - 0.4 kg/día
- **Calentador típico**: 0.3 - 0.5 kg/día
- Varía según: personas, estación, hábitos

### Barra de Progreso (Botellas Activas)
- 🟢 Verde (0-60%): OK
- 🟡 Amarillo (60-80%): Pronto
- 🔴 Rojo (>80%): Comprar ya

---

## 💡 Consejos Prácticos

### 1. Registra Inmediatamente
- Instala la botella en la app justo al cambiarla
- Aprovecha que tienes el ticket con el precio

### 2. Añade Notas Útiles
- Clima: "Ola de frío"
- Eventos: "Casa llena en Navidad"
- Cambios: "Nueva cocina eficiente"

### 3. Compara Precios
- Usa la sección "Compras"
- Filtra por proveedor
- Encuentra el más económico

### 4. Detecta Anomalías
- Si una botella dura mucho menos, investiga:
  - ¿Fuga?
  - ¿Más personas en casa?
  - ¿Cambio de temperatura?

### 5. Planifica Compras
- Mira el promedio de duración
- Compra con antelación
- Aprovecha ofertas

---

## 🐛 Resolución de Problemas

### La página no carga estilos
```bash
# Asegúrate de que Vite está corriendo
npm run dev

# O compila assets
npm run build
```

### Error al ejecutar migraciones
```bash
# Verifica conexión a base de datos
php artisan config:cache
php artisan migrate
```

### No aparecen datos de prueba
```bash
# Verifica que se ejecutó el seeder
php artisan db:seed --class=GasDataSeeder

# Ver usuarios disponibles
php artisan tinker
>>> User::all()
```

### Livewire no responde
```bash
# Limpia cachés
php artisan optimize:clear
php artisan livewire:discover

# Reinicia servidor
php artisan serve
```

---

## 🎨 Personalización Rápida

### Cambiar Colores
Edita las clases de Tailwind en las vistas:
- `resources/views/livewire/gas/dashboard.blade.php`
- Cambia `blue-600` por `green-600`, `purple-600`, etc.

### Ajustar Peso por Defecto
Si usas botellas de 11kg en lugar de 12.5kg:
- Edita las migraciones: `database/migrations/*_create_gas_*`
- Cambia `->default(12.5)` por `->default(11)`
- Ejecuta: `php artisan migrate:fresh`

### Añadir Más Proveedores al Seeder
Edita `database/seeders/GasDataSeeder.php`:
```php
$suppliers = ['Repsol', 'Cepsa', 'Galp', 'TuProveedor'];
```

---

## 📞 Soporte

Para más información revisa:
- `GAS_SYSTEM.md` - Documentación completa
- Código en `app/Models/GasBottle.php`
- Vistas en `resources/views/livewire/gas/`

---

**¡Listo! Ya puedes gestionar tus botellas de gas de forma inteligente.** 🎉
