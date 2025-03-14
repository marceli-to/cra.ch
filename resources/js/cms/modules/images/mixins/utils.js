export default {
  methods: {
    getSource(image, template, size = 1500) {
      let coords = '';
      if (image.coords_w && image.coords_h) {
        coords = `/${size}/${image.coords_x},${image.coords_y},${image.coords_w},${image.coords_h}`;
      }
      else {
        // If no coords, still add the size
        coords = `/${size}`;
      }
      return `/img/${template}/${image.name}${coords}`;
    },
  }
};
