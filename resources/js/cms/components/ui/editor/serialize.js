/**
 * The editor's HTML in the shape TinyMCE stored and the site's CSS expects:
 * list items without the paragraph Tiptap wraps their text in
 * (<li>Text</li>, not <li><p>Text</p></li>) and no empty trailing
 * paragraphs. An empty editor gives null.
 */
export function serialize(editor) {
  if (editor.isEmpty) {
    return null;
  }

  const template = document.createElement('template');
  template.innerHTML = editor.getHTML();
  const root = template.content;

  root.querySelectorAll('li').forEach(item => {
    const paragraphs = [...item.children].filter(child => child.tagName === 'P');
    // Keep a paragraph that carries attributes
    if (paragraphs.length === 1 && !paragraphs[0].attributes.length) {
      paragraphs[0].replaceWith(...paragraphs[0].childNodes);
    }
  });

  while (root.lastElementChild?.tagName === 'P' && !root.lastElementChild.innerHTML.trim()) {
    root.lastElementChild.remove();
  }

  return template.innerHTML;
}
