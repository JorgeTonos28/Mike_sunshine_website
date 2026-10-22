<script setup lang="ts">
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "../../layouts/AdminLayout.vue";
import Pagination from "../../components/Pagination.vue";
import { orderStatuses, type Pagination as PageData } from "../../types";
type RequestRow = {
    id: number;
    reference: string;
    customer_name: string;
    status: string;
    notification_status: string;
    created_at: string;
};
const props = defineProps<{ orders: PageData<RequestRow>; status: string }>();
const filter = ref(props.status || "");
</script>
<template>
    <Head title="Solicitudes de obras" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>El arte encuentra su lugar.</h1>
                <p>
                    Revisa cada solicitud y conversa con el cliente para
                    confirmar los detalles.
                </p>
            </div>
        </div>
        <label class="admin-filter"
            >Estado<select
                v-model="filter"
                @change="
                    router.get(
                        '/admin/orders',
                        { status: filter },
                        { preserveState: true },
                    )
                "
            >
                <option value="">Todos</option>
                <option
                    v-for="(label, value) in orderStatuses"
                    :key="value"
                    :value="value"
                >
                    {{ label }}
                </option>
            </select></label
        >
        <div v-if="orders.data.length" class="admin-panel table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Aviso por correo</th>
                        <th>Fecha</th>
                        <th><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders.data" :key="order.id">
                        <td>
                            <Link
                                :href="`/admin/orders/${order.id}`"
                                class="gold-text"
                                >{{ order.reference }}</Link
                            >
                        </td>
                        <td>{{ order.customer_name }}</td>
                        <td>
                            <span class="status-tag">{{
                                orderStatuses[order.status]
                            }}</span>
                        </td>
                        <td>
                            {{
                                order.notification_status === "sent"
                                    ? "Enviado"
                                    : order.notification_status === "failed"
                                      ? "Falló el envío"
                                      : "Pendiente"
                            }}
                        </td>
                        <td>
                            {{
                                new Date(order.created_at).toLocaleDateString(
                                    "es-DO",
                                )
                            }}
                        </td>
                        <td>
                            <Link
                                :href="`/admin/orders/${order.id}`"
                                class="text-link"
                                >Abrir</Link
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="empty-state admin-panel">
            <h2>El próximo encuentro está por llegar.</h2>
            <p>Las solicitudes de la web aparecerán aquí.</p>
        </div>
        <Pagination :links="orders.links"
    /></AdminLayout>
</template>
