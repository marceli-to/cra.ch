export default {
  methods: {
    getSource(image, template, size = 1500) {
      let coords = '';

      if (template == 'thumbnail') {
        return `/img/${template}/${image.name}`;
      }

      if (template == 'original') {
        return `/img/${template}/${image.name}`;
      }

      if (image.coords_w && image.coords_h) {
        coords = `/${size}/${image.coords_w},${image.coords_h},${image.coords_x},${image.coords_y}`;
      }
      return `/img/${template}/${image.name}${coords}`;
    },
  }
};
