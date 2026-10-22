<script setup lang="ts">
import { PhArrowUp, PhArrowDown, PhTrash, PhPlus } from "@phosphor-icons/vue";
import type { Block } from "../types";
const blocks = defineModel<Block[]>({ required: true });
const names: Record<Block["type"], string> = {
    heading: "Título",
    paragraph: "Párrafo",
    bullets: "Viñetas",
    numbered: "Lista numerada",
    table: "Tabla",
};
const add = (type: Block["type"]) => {
    if (blocks.value.length >= 40) return;
    blocks.value.push(
        type === "heading" || type === "paragraph"
            ? { type, text: "" }
            : type === "table"
              ? {
                    type,
                    rows: [
                        ["", ""],
                        ["", ""],
                    ],
                }
              : { type, items: [""] },
    );
};
const move = (i: number, offset: number) => {
    const other = i + offset;
    if (other < 0 || other >= blocks.value.length) return;
    const block = blocks.value.splice(i, 1)[0];
    blocks.value.splice(other, 0, block);
};
const updateItems = (block: Block, event: Event) => {
    block.items = (event.target as HTMLTextAreaElement).value.split("\n");
};
const addColumn = (block: Block) => {
    if ((block.rows?.[0]?.length || 0) < 6)
        block.rows?.forEach((row) => row.push(""));
};
const addRow = (block: Block) => {
    if ((block.rows?.length || 0) < 25)
        block.rows?.push(Array(block.rows?.[0]?.length || 2).fill(""));
};
</script>
<template>
    <div class="block-editor">
        <div class="block-toolbar">
            <button
                v-for="(name, type) in names"
                :key="type"
                type="button"
                @click="add(type)"
                :disabled="blocks.length >= 40"
            >
                <PhPlus :size="14" />{{ name }}
            </button>
        </div>
        <p v-if="!blocks.length" class="block-empty">
            Añade un bloque para contar la historia de la obra. Los títulos,
            listas y tablas tendrán el estilo del sitio.
        </p>
        <section
            v-for="(block, index) in blocks"
            :key="index"
            class="editor-block"
        >
            <header>
                <strong>{{ names[block.type] }}</strong>
                <div>
                    <button
                        class="icon-button"
                        type="button"
                        :disabled="index === 0"
                        :aria-label="`Subir bloque ${index + 1}`"
                        @click="move(index, -1)"
                    >
                        <PhArrowUp :size="16" /></button
                    ><button
                        class="icon-button"
                        type="button"
                        :disabled="index === blocks.length - 1"
                        :aria-label="`Bajar bloque ${index + 1}`"
                        @click="move(index, 1)"
                    >
                        <PhArrowDown :size="16" /></button
                    ><button
                        class="icon-button"
                        type="button"
                        :aria-label="`Eliminar bloque ${index + 1}`"
                        @click="blocks.splice(index, 1)"
                    >
                        <PhTrash :size="16" />
                    </button>
                </div>
            </header>
            <label v-if="block.type === 'heading'"
                >Texto del título<input
                    v-model="block.text"
                    maxlength="200" /></label
            ><label v-else-if="block.type === 'paragraph'"
                >Texto del párrafo<textarea
                    v-model="block.text"
                    maxlength="8000"
                    rows="4"
                /></label
            ><label
                v-else-if="
                    block.type === 'bullets' || block.type === 'numbered'
                "
                >Un elemento por línea<textarea
                    :value="block.items?.join('\n')"
                    @input="updateItems(block, $event)"
                    rows="4"
                /><small
                    >Los números o viñetas se añaden automáticamente.</small
                ></label
            >
            <div v-else-if="block.type === 'table'" class="table-block-editor">
                <p class="muted small-text">
                    La primera fila contiene los encabezados.
                </p>
                <div class="table-wrap">
                    <table>
                        <tbody>
                            <tr
                                v-for="(row, rowIndex) in block.rows"
                                :key="rowIndex"
                            >
                                <td
                                    v-for="(_cell, colIndex) in row"
                                    :key="colIndex"
                                >
                                    <input
                                        v-model="row[colIndex]"
                                        :aria-label="`Fila ${rowIndex + 1}, columna ${colIndex + 1}`"
                                        maxlength="500"
                                    />
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="icon-button"
                                        :disabled="
                                            (block.rows?.length || 0) <= 1
                                        "
                                        :aria-label="`Eliminar fila ${rowIndex + 1}`"
                                        @click="block.rows?.splice(rowIndex, 1)"
                                    >
                                        <PhTrash :size="15" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="editor-table-buttons">
                    <button type="button" @click="addRow(block)">
                        Añadir fila</button
                    ><button type="button" @click="addColumn(block)">
                        Añadir columna</button
                    ><button
                        type="button"
                        :disabled="(block.rows?.[0]?.length || 0) <= 1"
                        @click="block.rows?.forEach((row) => row.pop())"
                    >
                        Quitar última columna
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>
