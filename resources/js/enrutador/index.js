import { createRouter, createWebHistory } from 'vue-router';
import { useSesionAdminStore } from '../almacenes/sesionAdmin';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: () => import('../componentes/estructura/DisenoTienda.vue'),
            children: [
                { path: '', name: 'catalogo', component: () => import('../paginas/tienda/CatalogoPagina.vue') },
                { path: 'producto/:slug', name: 'producto', component: () => import('../paginas/tienda/ProductoDetallePagina.vue') },
            ],
        },
        {
            path: '/admin/login',
            name: 'admin.login',
            component: () => import('../paginas/admin/LoginPagina.vue'),
        },
        {
            path: '/admin',
            component: () => import('../componentes/estructura/DisenoAdmin.vue'),
            meta: { requiereAuth: true },
            children: [
                { path: '', redirect: { name: 'admin.productos' } },
                { path: 'productos', name: 'admin.productos', component: () => import('../paginas/admin/ProductosListaPagina.vue') },
                { path: 'productos/nuevo', name: 'admin.productos.nuevo', component: () => import('../paginas/admin/ProductoFormPagina.vue') },
                { path: 'productos/:id/editar', name: 'admin.productos.editar', component: () => import('../paginas/admin/ProductoFormPagina.vue') },
                { path: 'categorias', name: 'admin.categorias', component: () => import('../paginas/admin/CategoriasListaPagina.vue') },
                { path: 'ventas', name: 'admin.ventas', component: () => import('../paginas/admin/VentasPagina.vue') },
            ],
        },
    ],
});

router.beforeEach(async (to) => {
    if (!to.meta.requiereAuth) {
        return true;
    }

    const sesion = useSesionAdminStore();
    await sesion.verificarSesion();

    if (!sesion.estaAutenticado) {
        return { name: 'admin.login', query: { redirigir: to.fullPath } };
    }

    return true;
});

export default router;
