import api from './api';

export async function obtenerCategorias() {
    const { data } = await api.get('/api/categorias');
    return data.data;
}

export async function obtenerProductos(filtros = {}) {
    const { data } = await api.get('/api/productos', { params: filtros });
    return data.data;
}

export async function obtenerProducto(idOSlug) {
    const { data } = await api.get(`/api/productos/${idOSlug}`);
    return data.data;
}
