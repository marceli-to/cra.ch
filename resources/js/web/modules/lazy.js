import LazyLoad from 'vanilla-lazyload';

// <x-image> renders `img.lazy` with data-src and <source data-srcset>
export function init() {
  new LazyLoad();
}
