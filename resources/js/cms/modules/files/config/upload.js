export default {
  url: "/api/file/upload",
  method: 'post',
  maxFilesize: 32,
  maxFiles: 1,
  createImageThumbnails: false,
  acceptedFiles: '.pdf, .txt, .zip, .doc',
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token')
  }
}