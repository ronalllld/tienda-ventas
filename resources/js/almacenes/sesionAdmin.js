import { defineStore } from 'pinia';
import { iniciarSesion, cerrarSesion, obtenerAdministradorActual } from '../servicios/adminServicio';

export const useSesionAdminStore = defineStore('sesionAdmin', {
    state: () => ({
        administrador: null,
        verificado: false,
    }),
    getters: {
        estaAutenticado: (state) => !!state.administrador,
    },
    actions: {
        async iniciarSesion(correo, contrasena) {
            this.administrador = await iniciarSesion(correo, contrasena);
        },
        async cerrarSesion() {
            await cerrarSesion();
            this.administrador = null;
        },
        async verificarSesion() {
            if (this.verificado) {
                return;
            }

            try {
                this.administrador = await obtenerAdministradorActual();
            } catch {
                this.administrador = null;
            } finally {
                this.verificado = true;
            }
        },
    },
});
