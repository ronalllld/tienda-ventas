import api, { obtenerCookieCsrf } from './api';

export async function iniciarSesion(correo, contrasena) {
    await obtenerCookieCsrf();
    const { data } = await api.post('/api/admin/login', { correo, contrasena });
    return data.administrador;
}

export async function cerrarSesion() {
    await api.post('/api/admin/logout');
}

export async function obtenerAdministradorActual() {
    const { data } = await api.get('/api/admin/yo');
    return data.administrador;
}

// Categorías
export async function obtenerCategoriasAdmin() {
    const { data } = await api.get('/api/admin/categorias');
    return data.data;
}

export async function crearCategoria(payload) {
    const { data } = await api.post('/api/admin/categorias', payload);
    return data.data;
}

export async function actualizarCategoria(id, payload) {
    const { data } = await api.put(`/api/admin/categorias/${id}`, payload);
    return data.data;
}

export async function eliminarCategoria(id) {
    await api.delete(`/api/admin/categorias/${id}`);
}

// Productos
export async function obtenerProductosAdmin() {
    const { data } = await api.get('/api/admin/productos');
    return data.data;
}

export async function obtenerProductoAdmin(id) {
    const { data } = await api.get(`/api/admin/productos/${id}`);
    return data.data;
}

export async function crearProducto(payload) {
    const { data } = await api.post('/api/admin/productos', payload);
    return data.data;
}

export async function actualizarProducto(id, payload) {
    const { data } = await api.put(`/api/admin/productos/${id}`, payload);
    return data.data;
}

export async function eliminarProducto(id) {
    await api.delete(`/api/admin/productos/${id}`);
}

// Variantes
export async function crearVariante(productoId, payload) {
    const { data } = await api.post(`/api/admin/productos/${productoId}/variantes`, payload);
    return data.data;
}

export async function actualizarVariante(varianteId, payload) {
    const { data } = await api.put(`/api/admin/variantes/${varianteId}`, payload);
    return data.data;
}

export async function eliminarVariante(varianteId) {
    await api.delete(`/api/admin/variantes/${varianteId}`);
}

// Ventas
export async function obtenerVentas() {
    const { data } = await api.get('/api/admin/ventas');
    return { ventas: data.data, resumen: data.resumen };
}

// Imágenes
export async function subirImagen(productoId, archivo, orden) {
    const formData = new FormData();
    formData.append('imagen', archivo);
    if (orden !== undefined) {
        formData.append('orden', orden);
    }

    const { data } = await api.post(`/api/admin/productos/${productoId}/imagenes`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });
    return data.data;
}

export async function eliminarImagen(imagenId) {
    await api.delete(`/api/admin/imagenes/${imagenId}`);
}
