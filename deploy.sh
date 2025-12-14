#!/bin/bash

# Script de deployment para DigitalOcean
# Uso: ./deploy.sh

set -e

echo "🚀 Iniciando deployment..."

# Conectar al servidor y actualizar
ssh root@178.62.255.203 << 'ENDSSH'
    cd /var/www/home-expenses-app
    
    echo "📥 Descargando últimos cambios..."
    git pull origin development
    
    echo "📦 Instalando dependencias PHP..."
    composer install --no-dev --optimize-autoloader --no-interaction
    
    echo "🎨 Compilando assets..."
    npm install
    npm run build
    
    echo "🔧 Ejecutando migraciones..."
    php artisan migrate --force
    
    echo "🧹 Limpiando cachés..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "🔐 Corrigiendo permisos..."
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
    
    echo "✅ Deployment completado!"
ENDSSH

echo "✨ ¡Aplicación actualizada exitosamente!"
