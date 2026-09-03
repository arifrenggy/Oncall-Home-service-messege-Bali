#!/bin/sh
# Entry point: arahkan assets/images ke volume persisten Railway bila tersedia.
# Tanpa volume (dev lokal), perilaku tetap seperti biasa.
set -e

HTDOCS=/var/www/localhost/htdocs

if [ -d /data ]; then
    mkdir -p /data/images

    # Isi volume dengan gambar bawaan repo saat pertama kali (tanpa menimpa yang sudah ada)
    if [ -d "$HTDOCS/assets/images" ] && [ ! -L "$HTDOCS/assets/images" ]; then
        cp -an "$HTDOCS/assets/images/." /data/images/ 2>/dev/null || true
        rm -rf "$HTDOCS/assets/images"
    fi

    ln -sfn /data/images "$HTDOCS/assets/images"
    chown -R apache:apache /data/images 2>/dev/null || true
    echo "[entrypoint] assets/images dialihkan ke volume persisten /data/images"
fi

exec "$@"
