#!/bin/sh
# Entry point: arahkan assets/images ke volume persisten Railway bila tersedia.
# Tanpa volume (dev lokal), perilaku tetap seperti biasa.
set -e

HTDOCS=/var/www/localhost/htdocs

if [ -d /data ]; then
    mkdir -p /data/images
    SRC="$HTDOCS/assets/images"

    # Seed gambar bawaan repo ke volume saat boot pertama dengan volume.
    # Per-file dengan pengecekan ada/belum, agar upload-an yang sudah ada
    # di volume tidak pernah tertimpa. Log eksplisit untuk kemudahan debug.
    if [ ! -L "$SRC" ] && [ -d "$SRC" ]; then
        echo "[entrypoint] seeding gambar bawaan ke /data/images ..."
        for f in "$SRC"/*; do
            [ -e "$f" ] || continue
            base=$(basename "$f")
            if [ ! -e "/data/images/$base" ]; then
                if cp "$f" "/data/images/$base"; then
                    echo "[entrypoint] seeded: $base"
                else
                    echo "[entrypoint] GAGAL copy: $base"
                fi
            fi
        done
        rm -rf "$SRC"
        ln -sfn /data/images "$SRC"
        echo "[entrypoint] symlink: $SRC -> /data/images"
    else
        echo "[entrypoint] symlink sudah ada, seeding dilewati"
    fi

    chown -R apache:apache /data/images 2>/dev/null || true
fi

exec "$@"
