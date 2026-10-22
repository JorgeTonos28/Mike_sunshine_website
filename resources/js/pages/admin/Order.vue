<script setup lang="ts">
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { PhArrowLeft } from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import FormErrors from "../../components/FormErrors.vue";
import { money, orderStatuses } from "../../types";
type OrderData = {
    id: number;
    reference: string;
    customer_name: string;
    customer_email: string;
    customer_phone: string | null;
    shipping_address: string | null;
    delivery_method: string;
    message: string | null;
    status: string;
    admin_notes: string | null;
    currency: string;
    subtotal_minor: number | null;
    notification_status: string;
    privacy_accepted_at: string;
    terms_version: number;
    created_at: string;
    items: {
        id: number;
        product_id: number | null;
        title: string;
        quantity: number;
        unit_price_minor: number | null;
        currency: string;
    }[];
};
const props = defineProps<{ order: OrderData }>();
const form = useForm({
    status: props.order.status,
    admin_notes: props.order.admin_notes || "",
});
</script>
<template>
    <Head :title="order.reference" /><AdminLayout
        ><Link href="/admin/orders" class="back-link"
            ><PhArrowLeft :size="17" /> Solicitudes</Link
        >
        <div class="admin-heading">
            <div>
                <p class="admin-eyebrow">
                    {{ new Date(order.created_at).toLocaleString("es-DO") }}
                </p>
                <h1>{{ order.reference }}</h1>
                <p>
                    Solicitud manual. Confirma importes, entrega y
                    disponibilidad con el cliente.
                </p>
            </div>
        </div>
        <div class="admin-editor-grid">
            <div class="admin-editor-main">
                <section class="admin-panel">
                    <h2>El cliente</h2>
                    <dl class="customer-data">
                        <div>
                            <dt>Nombre</dt>
                            <dd>{{ order.customer_name }}</dd>
                        </div>
                        <div>
                            <dt>Correo</dt>
                            <dd>
                                <a :href="`mailto:${order.customer_email}`">{{
                                    order.customer_email
                                }}</a>
                            </dd>
                        </div>
                        <div v-if="order.customer_phone">
                            <dt>Teléfono</dt>
                            <dd>{{ order.customer_phone }}</dd>
                        </div>
                        <div>
                            <dt>Entrega</dt>
                            <dd>
                                {{
                                    order.delivery_method === "shipping"
                                        ? "Solicitud de envío"
                                        : "Coordinar recogida"
                                }}
                            </dd>
                        </div>
                        <div v-if="order.shipping_address">
                            <dt>Dirección</dt>
                            <dd class="preserve-lines">
                                {{ order.shipping_address }}
                            </dd>
                        </div>
                    </dl>
                    <div v-if="order.message" class="customer-message">
                        <h3>Mensaje</h3>
                        <p class="preserve-lines">{{ order.message }}</p>
                    </div>
                </section>
                <section class="admin-panel">
                    <h2>Las obras</h2>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Obra</th>
                                    <th>Cantidad</th>
                                    <th>Precio por unidad al solicitar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in order.items" :key="item.id">
                                    <td>
                                        <Link
                                            v-if="item.product_id"
                                            :href="`/admin/products/${item.product_id}/edit`"
                                            class="gold-text"
                                            >{{ item.title }}</Link
                                        ><span v-else>{{ item.title }}</span>
                                    </td>
                                    <td>{{ item.quantity }}</td>
                                    <td>
                                        {{
                                            money(
                                                item.unit_price_minor,
                                                item.currency,
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="summary-row">
                        <span>Importe de las obras al solicitar</span
                        ><strong>{{
                            order.subtotal_minor === null
                                ? "Por cotizar"
                                : money(order.subtotal_minor, order.currency)
                        }}</strong>
                    </div>
                    <p class="muted small-text">
                        El envío se cotiza por separado. Los datos de esta
                        solicitud se conservan aunque se edite la obra.
                    </p>
                </section>
            </div>
            <aside class="admin-editor-side">
                <section class="admin-panel">
                    <h2>Seguimiento</h2>
                    <form
                        class="studio-form"
                        @submit.prevent="
                            form.put(`/admin/orders/${order.id}`, {
                                preserveScroll: true,
                            })
                        "
                    >
                        <FormErrors :errors="form.errors" /><label
                            >Estado<select v-model="form.status">
                                <option
                                    v-for="(label, value) in orderStatuses"
                                    :key="value"
                                    :value="value"
                                >
                                    {{ label }}
                                </option>
                            </select></label
                        ><label
                            >Notas internas<textarea
                                v-model="form.admin_notes"
                                rows="6"
                                maxlength="8000"
                            /><small
                                >Sólo visibles para administradores.</small
                            ></label
                        ><button
                            class="button gold full-width"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? "Guardando..."
                                    : "Guardar seguimiento"
                            }}
                        </button>
                    </form>
                    <p class="muted small-text">
                        Actualizar el estado no envía correos al cliente ni
                        modifica el stock. Gestiona la disponibilidad desde la
                        ficha de la obra.
                    </p>
                </section>
                <section class="admin-panel">
                    <h2>Aviso al estudio</h2>
                    <p>
                        {{
                            order.notification_status === "sent"
                                ? "Enviado"
                                : order.notification_status === "failed"
                                  ? "No se pudo enviar"
                                  : "Pendiente en la cola"
                        }}
                    </p>
                    <button
                        v-if="order.notification_status === 'failed'"
                        class="button outline"
                        @click="
                            router.post(
                                `/admin/orders/${order.id}/retry`,
                                {},
                                { preserveScroll: true },
                            )
                        "
                    >
                        Reintentar aviso
                    </button>
                    <p class="muted small-text">
                        Consentimiento registrado:
                        {{
                            new Date(order.privacy_accepted_at).toLocaleString(
                                "es-DO",
                            )
                        }}. Términos: versión {{ order.terms_version }}.
                    </p>
                </section>
            </aside>
        </div></AdminLayout
    >
</template>
