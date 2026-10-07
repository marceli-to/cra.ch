<template>
  <div :class="['editor', { 'editor--error': hasError }]">
    <Toolbar v-if="editor" :editor="editor" />
    <EditorContent :editor="editor" class="editor__content" />
  </div>
</template>
<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Superscript from '@tiptap/extension-superscript';
import Toolbar from './Toolbar.vue';
import { serialize } from './serialize';
import { NoWordBreak } from './marks';

// Replaces TinyMCE 7, with its toolbar: undo/redo, bold, bullet list, link,
// superscript, remove formatting and the style formats (Überschrift 1/2,
// Trennung verhindern; "Unterstrichen" had no CSS on the site and is gone,
// as is the source view). From oxid's editor; see
// .rewrite/tools/tiptap-roundtrip.mjs for the check that stored content
// survives it.

defineProps({
  hasError: { type: Boolean, default: false },
});

const model = defineModel({ type: String, default: '' });

const editor = useEditor({
  content: model.value ?? '',
  extensions: [
    StarterKit.configure({
      heading: { levels: [1, 2] },
      blockquote: false,
      code: false,
      codeBlock: false,
      horizontalRule: false,
      orderedList: false,
      italic: false,
      strike: false,
      underline: false,
      link: false,
    }),
    Link.configure({
      openOnClick: false,
      autolink: false,
      HTMLAttributes: { target: null, rel: null },
    }),
    Superscript,
    NoWordBreak,
  ],
  // TinyMCE pasted as plain text; keep pasted Word/web formatting out
  editorProps: {
    transformPastedHTML: html => html.replace(/ style="[^"]*"/gi, '').replace(/ class="[^"]*"/gi, ''),
  },
  onUpdate: ({ editor }) => {
    model.value = serialize(editor);
  },
});

// Content set from outside (e.g. after loading the record)
watch(model, value => {
  if (!editor.value || value === serialize(editor.value)) {
    return;
  }
  editor.value.commands.setContent(value ?? '', { emitUpdate: false });
});

onBeforeUnmount(() => editor.value?.destroy());
</script>
