const NUMERO_WHATSAPP = import.meta.env.VITE_NUMERO_WHATSAPP;

export function formatearPrecio(valor) {
    return `Bs ${Number(valor).toLocaleString('es-BO')}`;
}

export function construirMensajePedido(items) {
    const lineas = items.map((item, indice) => {
        const subtotal = item.precio * item.cantidad;
        return `${indice + 1}. ${item.nombre} - Talla: ${item.talla} - Color: ${item.color} - Cantidad: ${item.cantidad} - Subtotal: ${formatearPrecio(subtotal)}`;
    });

    const total = items.reduce((acum, item) => acum + item.precio * item.cantidad, 0);

    return [
        'Hola, quiero hacer el siguiente pedido:',
        '',
        ...lineas,
        '',
        `Total estimado: ${formatearPrecio(total)}`,
        '',
        'Quedo atento para coordinar el pago.',
    ].join('\n');
}

export function construirLinkWhatsApp(items) {
    const mensaje = construirMensajePedido(items);
    return `https://wa.me/${NUMERO_WHATSAPP}?text=${encodeURIComponent(mensaje)}`;
}
