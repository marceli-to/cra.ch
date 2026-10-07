import { Mark } from '@tiptap/vue-3';

/**
 * A <span class="…"> mark, as TinyMCE's style formats wrote them; the site's
 * CSS styles the classes.
 */
function classMark(name, className, command) {
  return Mark.create({
    name,

    parseHTML() {
      return [{ tag: `span.${className}` }];
    },

    renderHTML() {
      return ['span', { class: className }, 0];
    },

    addCommands() {
      return {
        [command]: () => ({ commands }) => commands.toggleMark(this.name),
      };
    },
  });
}

// "Trennung verhindern": keeps words on one line
export const NoWordBreak = classMark('noWordBreak', 'no-word-break', 'toggleNoWordBreak');
