<script setup lang="ts">
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { PhPlus } from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import FormErrors from "../../components/FormErrors.vue";
type StudioUser = {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
};
defineProps<{ users: StudioUser[] }>();
const shown = ref(false);
const userId = ref<number | null>(null);
const form = useForm({
    name: "",
    email: "",
    role: "editor",
    is_active: true,
    password: "",
});
const edit = (user?: StudioUser) => {
    userId.value = user?.id || null;
    form.clearErrors();
    form.name = user?.name || "";
    form.email = user?.email || "";
    form.role = user?.role || "editor";
    form.is_active = user?.is_active ?? true;
    form.password = "";
    shown.value = true;
};
const save = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            shown.value = false;
            form.reset();
        },
    };
    if (userId.value) form.put(`/admin/users/${userId.value}`, options);
    else form.post("/admin/users", options);
};
</script>
<template>
    <Head title="Usuarios del estudio" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>El equipo.</h1>
                <p>Controla quién entra al estudio y qué puede gestionar.</p>
            </div>
            <button class="button gold" @click="edit()">
                <PhPlus :size="18" /> Nuevo usuario
            </button>
        </div>
        <section v-if="shown" class="admin-panel user-form-panel">
            <h2>{{ userId ? "Editar usuario" : "Invitar al estudio" }}</h2>
            <form class="studio-form" @submit.prevent="save">
                <FormErrors :errors="form.errors" />
                <div class="form-grid">
                    <label
                        >Nombre<input
                            v-model="form.name"
                            required
                            maxlength="180"
                            autocomplete="off" /></label
                    ><label
                        >Correo electrónico<input
                            v-model="form.email"
                            required
                            type="email"
                            maxlength="255"
                            autocomplete="off" /></label
                    ><label
                        >Rol<select v-model="form.role">
                            <option value="editor">Editor</option>
                            <option value="admin">Administrador</option>
                        </select></label
                    ><label
                        >{{
                            userId
                                ? "Nueva contraseña (opcional)"
                                : "Contraseña inicial"
                        }}<input
                            v-model="form.password"
                            :required="!userId"
                            type="password"
                            minlength="12"
                            maxlength="128"
                            autocomplete="new-password"
                        /><small
                            >12 caracteres o más, mayúsculas, minúsculas y
                            números. El usuario podrá restablecerla desde el
                            acceso.</small
                        ></label
                    >
                </div>
                <p class="muted small-text">
                    Un editor gestiona el catálogo. Un administrador también
                    accede a solicitudes, mensajes, configuración, usuarios y
                    páginas legales. Google sólo acepta el correo autorizado en
                    esta cuenta.
                </p>
                <label class="check-label"
                    ><input v-model="form.is_active" type="checkbox" /><span
                        >Cuenta activa</span
                    ></label
                >
                <div class="inline-actions">
                    <button class="button gold" :disabled="form.processing">
                        {{
                            form.processing ? "Guardando..." : "Guardar usuario"
                        }}</button
                    ><button
                        class="button outline"
                        type="button"
                        @click="
                            shown = false;
                            form.password = '';
                        "
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </section>
        <div class="admin-panel table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acceso</th>
                        <th><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>
                            <strong>{{ user.name }}</strong>
                        </td>
                        <td>{{ user.email }}</td>
                        <td>
                            {{
                                user.role === "admin"
                                    ? "Administrador"
                                    : "Editor"
                            }}
                        </td>
                        <td>
                            <span
                                class="status-tag"
                                :class="{ published: user.is_active }"
                                >{{
                                    user.is_active ? "Activo" : "Desactivado"
                                }}</span
                            >
                        </td>
                        <td>
                            <button class="text-link" @click="edit(user)">
                                Editar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div></AdminLayout
    >
</template>
