export default {
  skin_url: '/assets/js/cms/tinymce/skins/custom',
  branding: false,
  menubar: false,
  statusbar: false,
  external_plugins: {
    link: '/assets/js/cms/tinymce/plugins/link/plugin.min.js',
  },
  plugins: ['lists', 'code', 'link'],
  toolbar: 'undo redo | bold | bullist | link | superscript | removeformat | styleselect | code',
  paste_as_text: true,
  height: "320px",
  style_formats_merge: false,
  style_formats: [{
    title: 'Text',
    items: [
      { title: 'Titel (bold, versal)', inline: 'span', classes : 'text-bold text-uc text-xs' },
      { title: 'Lauftext medium', inline: 'span', classes : 'text-md' },
      { title: 'Liste', block: 'ul', classes : 'list' },
      { title: 'Link (extern)', block: 'p', classes : 'anchor-external' },
      { title: 'Ohne Texteinzug', block: 'p', classes : 'prevent-text-indent' },
      { title: 'Überschrift 1', block : 'h1'},
      { title: 'Überschrift 2', block : 'h2'},
      { title: 'Überschrift 3', block : 'h3'},
    ],
  }],

  link_list: '/filelist',
};
