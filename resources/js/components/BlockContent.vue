<script setup lang="ts">
import type { Block } from "../types";
defineProps<{ blocks: Block[] | null }>();
</script>
<template>
    <div class="prose">
        <template v-for="(block, index) in blocks || []" :key="index"
            ><h2 v-if="block.type === 'heading'">{{ block.text }}</h2>
            <p v-else-if="block.type === 'paragraph'" class="preserve-lines">
                {{ block.text }}
            </p>
            <ul v-else-if="block.type === 'bullets'">
                <li v-for="(item, i) in block.items" :key="i">{{ item }}</li>
            </ul>
            <ol v-else-if="block.type === 'numbered'">
                <li v-for="(item, i) in block.items" :key="i">{{ item }}</li>
            </ol>
            <div
                v-else-if="block.type === 'table'"
                class="table-wrap"
                tabindex="0"
                role="region"
                aria-label="Detalles de la obra"
            >
                <table>
                    <tbody>
                        <tr v-for="(row, i) in block.rows" :key="i">
                            <th
                                v-if="i === 0"
                                v-for="(cell, j) in row"
                                :key="j"
                                scope="col"
                            >
                                {{ cell }}
                            </th>
                            <template v-else
                                ><td v-for="(cell, j) in row" :key="j">
                                    {{ cell }}
                                </td></template
                            >
                        </tr>
                    </tbody>
                </table>
            </div></template
        >
    </div>
</template>
