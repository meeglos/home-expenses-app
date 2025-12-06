# 🔄 Funcionalidad de Movimiento de Botellas de Gas

## Descripción

Esta funcionalidad permite mover botellas de gas activas entre diferentes ubicaciones (cocina ↔ calentador). Es útil en situaciones donde:

- Se olvidó comprar una botella nueva y necesitas usar temporalmente la de otra ubicación
- Hay una emergencia y necesitas gas en una ubicación específica
- Quieres probar el consumo en diferentes ubicaciones
- Cambio de prioridades en el uso del gas

## Características Implementadas

### 1. **Base de Datos**
- **Nueva tabla**: `gas_bottle_moves`
  - Registra cada movimiento de botella
  - Almacena ubicación de origen y destino
  - Fecha y hora del movimiento
  - Razón del movimiento (opcional)

### 2. **Modelo `GasBottle`**
- **Nuevo método**: `moveTo($newLocation, $reason = null, $movedAt = null)`
  - Valida que la ubicación sea diferente a la actual
  - Valida que la ubicación sea válida ('cocina' o 'calentador')
  - Registra el movimiento en el historial
  - Actualiza la ubicación de la botella

- **Nueva relación**: `moves()`
  - Relación hasMany con `GasBottleMove`
  - Permite consultar el historial de movimientos de una botella

### 3. **Componente Livewire `MoveBottle`**
- Modal interactivo para mover botellas
- Selección de botella activa desde un dropdown
- Selección de ubicación destino con iconos visuales
- Campo opcional para describir la razón del movimiento
- Validación en tiempo real
- Sugerencia automática de ubicación opuesta

### 4. **Interfaz de Usuario**
- **Dashboard principal**:
  - Nuevo botón "🔄 Mover Botella" junto a "Instalar Botella"
  - Abre un modal elegante para realizar el movimiento

- **Modal de movimiento**:
  - Selección visual de ubicación con iconos (🍳 Cocina / 🚿 Calentador)
  - Información de la botella seleccionada
  - Campo opcional para razón del movimiento
  - Botón deshabilitado hasta completar campos requeridos

- **Vista de botellas activas**:
  - Muestra historial de movimientos de cada botella
  - Formato: "origen → destino (fecha)"
  - Hasta 3 movimientos más recientes

## Uso

### Desde el Dashboard

1. Haz clic en el botón "🔄 Mover Botella"
2. Selecciona la botella activa que deseas mover
3. El sistema sugerirá automáticamente la ubicación opuesta
4. (Opcional) Ingresa la razón del movimiento
5. Haz clic en "🔄 Mover Botella"

### Desde Código

```php
use App\Models\GasBottle;

// Obtener una botella activa
$bottle = GasBottle::active()->where('location', 'cocina')->first();

// Moverla a otra ubicación
$bottle->moveTo('calentador', 'Emergencia - se agotó la del calentador');

// Ver historial de movimientos
$movements = $bottle->moves()->latest()->get();
foreach ($movements as $move) {
    echo "{$move->from_location} → {$move->to_location} en {$move->moved_at}";
}
```

## Validaciones

- ✅ Solo se pueden mover botellas activas
- ✅ No se puede mover una botella a su ubicación actual
- ✅ Las ubicaciones válidas son: 'cocina' o 'calentador'
- ✅ La razón del movimiento es opcional (máximo 500 caracteres)

## Historial y Seguimiento

Cada movimiento queda registrado permanentemente en la base de datos, incluyendo:
- ID de la botella
- Ubicación de origen
- Ubicación de destino
- Fecha y hora exacta del movimiento
- Razón del movimiento (si se proporcionó)

Este historial permite:
- Analizar patrones de consumo
- Justificar movimientos ante auditorías
- Entender mejor el uso del gas en el hogar

## Eventos y Refrescado

El componente emite el evento `bottleMoved` después de un movimiento exitoso, lo que provoca:
- Refresco automático del dashboard
- Actualización de estadísticas
- Actualización de la lista de botellas activas

## Archivos Modificados/Creados

### Nuevos Archivos
- `database/migrations/2025_12_02_013858_create_gas_bottle_moves_table.php`
- `app/Models/GasBottleMove.php`
- `app/Livewire/Gas/MoveBottle.php`
- `resources/views/livewire/gas/move-bottle.blade.php`
- `MOVIMIENTO_BOTELLAS.md` (este archivo)

### Archivos Modificados
- `app/Models/GasBottle.php`
  - Añadido método `moveTo()`
  - Añadida relación `moves()`
  - Corregido cast de `days_elapsed`

- `app/Livewire/Gas/Dashboard.php`
  - Añadido listener `bottleMoved`
  - Añadida carga de relación `moves`

- `resources/views/livewire/gas/dashboard.blade.php`
  - Añadido botón "Mover Botella"
  - Incluido componente `<livewire:gas.move-bottle />`
  - Añadida visualización de historial de movimientos en botellas activas

## Consideraciones Futuras

Posibles mejoras a considerar:
- [ ] Notificaciones cuando una botella ha sido movida múltiples veces
- [ ] Estadísticas de movimientos por periodo
- [ ] Restricciones por roles de usuario
- [ ] Deshacer movimiento (mover de vuelta)
- [ ] Exportar historial de movimientos
- [ ] Gráficos de análisis de patrones de movimiento
