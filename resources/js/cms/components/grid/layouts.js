/**
 * The slots of each grid layout: grid area and aspect ratio (classes in
 * sass/cms/modules/_grid.scss), and whether a slot can take an article
 * (home only). Mirrors the site's templates in views/components/galleries.
 */
const slot = (area, ratio, articles = false) => ({ area, ratio, articles });

export const layouts = {
  '1': [slot('a', 'a')],
  '2-1': [slot('a', 'e'), slot('b', 'f')],
  '1-2': [slot('a', 'f'), slot('b', 'e')],
  '1-1-1': [slot('a', 'f'), slot('b', 'f'), slot('c', 'f')],
  '1w-1w-1w': [slot('a', 'g'), slot('b', 'g'), slot('c', 'g')],
  '1-1': [slot('a', 'b'), slot('b', 'b')],
  '1w-1w': [slot('a', 'c'), slot('b', 'c')],
  '1_1-2': [slot('a', 'g', true), slot('b', 'g', true), slot('c', 'e')],
  '2-1_1': [slot('a', 'e'), slot('b', 'c', true), slot('c', 'c', true)],
  '1-1_1': [slot('a', 'b'), slot('b', 'c'), slot('c', 'c')],
  '1_1-1': [slot('a', 'c'), slot('b', 'c'), slot('c', 'b')],
  '1-1-1_1': [slot('a', 'b'), slot('b', 'b'), slot('c', 'c', true), slot('d', 'c', true)],
  '1_1-1-1': [slot('a', 'c', true), slot('b', 'c', true), slot('c', 'b'), slot('d', 'b')],
  '1-1_1-1': [slot('a', 'b'), slot('b', 'c', true), slot('c', 'c', true), slot('d', 'b')],
  '1_1-1-1_1': [slot('a', 'c', true), slot('b', 'c', true), slot('c', 'b'), slot('d', 'c', true), slot('e', 'c', true)],
  '1_1-1_1-1': [slot('a', 'c', true), slot('b', 'c', true), slot('c', 'c', true), slot('d', 'c', true), slot('e', 'b')],
  // Area a holds the home's text (articleContent), not an item
  '1t_1-1_1-1': [slot('b', 'c'), slot('c', 'c'), slot('d', 'c'), slot('e', 'b')],
  '1-1_1-1_1': [slot('a', 'b'), slot('b', 'c', true), slot('c', 'c', true), slot('d', 'c', true), slot('e', 'c', true)],
  '1sq-1sq-1sq': [slot('a', 'h'), slot('b', 'h'), slot('c', 'h')],
  '1sq-1sq': [slot('a', 'h'), slot('b', 'h')],
  '1sq-1': [slot('a', 'h'), slot('b', 'b')],
  '1-1sq': [slot('a', 'b'), slot('b', 'h')],
  '1sq-1sq_1sq': [slot('a', 'h'), slot('b', 'h'), slot('c', 'h')],
  '1sq_1sq-1sq': [slot('a', 'h'), slot('b', 'h'), slot('c', 'h')],
  '1sq-1_1_1': [slot('a', 'h'), slot('b', 'c'), slot('c', 'c', true), slot('d', 'c', true)],
  '1_1_1-1sq': [slot('a', 'c'), slot('b', 'c'), slot('c', 'c', true), slot('d', 'h', true)],
  '1sq-1_1': [slot('a', 'h'), slot('b', 'f'), slot('c', 'c')],
  '1_1-1sq': [slot('a', 'f'), slot('b', 'c'), slot('c', 'h')],
};

/**
 * The layouts each owner offers, in the order of the layout picker
 */
export const choices = {
  Home: ['1t_1-1_1-1', '1w-1w-1w', '1-1-1', '1-1-1_1', '1_1-1-1', '1-1_1-1', '1_1-1_1-1', '1-1_1-1_1', '1_1-1-1_1'],
  Diary: ['1w-1w-1w', '1-1-1', '1-1-1_1', '1_1-1-1', '2-1', '1-2', '1_1-2', '2-1_1', '1w-1w'],
  Project: [
    '1-1_1', '1_1-1', '2-1', '1-2', '1w-1w-1w', '1w-1w', '1_1-2', '2-1_1', '1', '1-1', '1-1-1',
    '1sq-1sq', '1sq-1sq-1sq', '1sq-1', '1-1sq', '1sq-1sq_1sq', '1sq_1sq-1sq', '1sq-1_1_1', '1_1_1-1sq', '1sq-1_1', '1_1-1sq',
  ],
};
