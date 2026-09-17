const ANCHO_MAXIMO = 1600;
const CALIDAD = 0.82;

function cargarImagen(archivo) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve(img);
        img.onerror = reject;
        img.src = URL.createObjectURL(archivo);
    });
}

/**
 * Redimensiona y comprime una foto en el navegador antes de subirla, para no
 * depender del peso original (las fotos de celular suelen pesar varios MB).
 * Si algo falla (formato raro, navegador viejo) devuelve el archivo original.
 */
export async function comprimirImagen(archivo) {
    if (!archivo.type.startsWith('image/') || archivo.type === 'image/svg+xml') {
        return archivo;
    }

    try {
        const img = await cargarImagen(archivo);
        const escala = Math.min(1, ANCHO_MAXIMO / img.width);

        if (escala === 1 && archivo.size < 1024 * 1024) {
            URL.revokeObjectURL(img.src);
            return archivo;
        }

        const canvas = document.createElement('canvas');
        canvas.width = Math.round(img.width * escala);
        canvas.height = Math.round(img.height * escala);

        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        URL.revokeObjectURL(img.src);

        const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', CALIDAD));
        if (!blob || blob.size >= archivo.size) {
            return archivo;
        }

        const nombre = archivo.name.replace(/\.\w+$/, '') + '.jpg';
        return new File([blob], nombre, { type: 'image/jpeg' });
    } catch {
        return archivo;
    }
}
