#!/bin/bash

echo "=== Configurando Apache para usar PHP 8.3 ==="

# Verificar si tenemos permisos de sudo
if ! sudo -n true 2>/dev/null; then
    echo "❌ Se requieren permisos de administrador para cambiar la configuración de Apache"
    echo "Por favor ejecuta los siguientes comandos manualmente:"
    echo ""
    echo "sudo a2dismod php7.4"
    echo "sudo a2enmod php8.3"
    echo "sudo systemctl restart apache2"
    echo ""
    echo "O proporciona la contraseña cuando se solicite."
    exit 1
fi

echo "🔄 Deshabilitando PHP 7.4..."
sudo a2dismod php7.4

echo "🔄 Habilitando PHP 8.3..."
sudo a2enmod php8.3

echo "🔄 Reiniciando Apache..."
sudo systemctl restart apache2

echo "✅ Verificando estado de Apache..."
sudo systemctl status apache2 --no-pager -l

echo ""
echo "✅ Configuración completada!"
echo "PHP 8.3 debería estar ahora activo en Apache."
