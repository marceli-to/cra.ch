import * as imprint from './modules/imprint.js';
import * as lazy from './modules/lazy.js';
import * as lightbox from './modules/lightbox.js';
import * as menu from './modules/menu.js';
import * as project from './modules/project.js';
import * as teaser from './modules/teaser.js';
import * as truncate from './modules/truncate.js';

// Module scripts run after the document is parsed
[imprint, lazy, lightbox, menu, project, teaser, truncate].forEach((module) => module.init());
