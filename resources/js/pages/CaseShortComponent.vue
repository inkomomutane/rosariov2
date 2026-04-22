<script setup lang="ts">
import { ref, h } from 'vue'
import { Badge } from '@/components/ui/badge';
import { MessageSquare ,Bookmark  } from 'lucide-vue-next'

const dark = ref(false)

const post = {
    paragraphs: [
         "Lorem ipsum dolor sit amet, consectetur adipiscing elit. #Ecography Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
         "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. #Ultrasound Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
         "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. #Radiology",
    ],
}


function renderParagraph(text: string): string {
    return text.replace(/#(\w+)/g, '<span class="text-blue-500 dark:text-blue-400 cursor-pointer hover:underline">#$1</span>')
}


const actions = [
    { label: 'Review', icon: MessageSquare  },
    { label: 'Save', icon: Bookmark  },
]
</script>

<template>
    <div class=" dark:bg-zinc-950 flex items-center justify-center  transition-colors duration-300">


        <div class="w-full max-w-2xl px-6">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800  overflow-hidden">

                <!-- Header -->
                <div class="flex items-start justify-between px-4 pt-4 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-zinc-700 overflow-hidden shrink-0 flex items-center justify-center text-white font-medium text-sm">
                            NM
                        </div>
                        <div>
                            <div class="flex items-center gap-1 flex-wrap">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 leading-tight">
                                   Nadia Solis
                                </span>
                                <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">•</span>
                            </div>
                            <div class="flex items-center gap-1 flex-wrap">
                                <p class="text-xs text-red-500 dark:text-zinc-400 leading-tight">
                                    Urgent
                                </p>
                                 <span class="text-xs p-0.5" variant="outline" size="sm">
                                     Ecography
                                 </span>
                            </div>

                            <div class="flex items-center gap-1 mt-0.5">
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">
                                    2h ago
                                </span>
                            </div>
                        </div>
                    </div>
                    <button class="text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300 p-1 -mr-1 rounded transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>
                </div>

                <!-- Post Body -->
                <div class="px-4 pb-3 space-y-2">
                    <p
                        v-for="(para, i) in post.paragraphs"
                        :key="i"
                        class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed"
                        v-html="renderParagraph(para)"
                    />
                </div>

                <!-- Reactions summary -->
                <div class="flex items-center justify-between px-4 pb-2.5">
                    <div class="flex items-center gap-1.5">

                        <span class="text-xs text-zinc-500 dark:text-zinc-400">10 Reviews</span>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-zinc-100 dark:border-zinc-800 mx-4" />

                <!-- Reactions section -->
                <div class="px-4 py-2.5">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-2">Status</p>
                     <Badge variant="outline">
                          {{ 'Submitted'}}
                     </Badge>
                </div>

                <!-- Divider -->
                <div class="border-t border-zinc-100 dark:border-zinc-800 mx-4" />

                <!-- Action Bar -->
                <div class="flex items-center px-2 py-1">
                    <button
                        v-for="action in actions"
                        :key="action.label"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                    >
                        <component :is="action.icon" class="w-4 h-4" />
                        {{ action.label }}
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>
