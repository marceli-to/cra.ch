export default {

  data() {
    return { 
      errors: null,
    }
  },
  
  mounted() {

    window.intercepted.$on('response:401', data => {
      this.unauthorized(data);
    });

    window.intercepted.$on('response:403', data => {
      this.forbiddenError(data);
    });

    window.intercepted.$on('response:404', data => {
      this.notFoundError(data);
    });

    window.intercepted.$on('response:405', data => {
      this.notAllowed(data);
    });

    window.intercepted.$on('response:419', data => {
      this.expired(data);
    });

    window.intercepted.$on('response:422', data => {
      this.validationError(data);
    });

    window.intercepted.$on('response:500', data => {
      this.serverError(data);
    });

  },

  beforeDestroy(){
    window.intercepted.$off('response:401', this.listener);
    window.intercepted.$off('response:403', this.listener);
    window.intercepted.$off('response:404', this.listener);
    window.intercepted.$off('response:405', this.listener);
    window.intercepted.$off('response:419', this.listener);
    window.intercepted.$off('response:422', this.listener);
    window.intercepted.$off('response:500', this.listener);
  },

  methods: {

    validationError(data) {
      this.errors = data.body;
      let message = 'Es sind Fehler aufgetreten:';

      if (this.errors.length > 0) {
        message += '<ul>';
        let _that = this;
        this.errors.forEach(function(error){
          message += '<li>'+error+'</li>';
        });
        message += '</ul>';
      }

      this.isLoading = false;
      this.$notify({ type: "error", text: message, duration: -1});
    },

    serverError(data) {
      this.isLoading = false;
      this.$notify({ type: "error", text: `${data.status} ${data.code}<br>${data.body.message}`});
    },

    notFoundError(data) {
      this.$notify({ type: "error", text: `${data.status} ${data.code}`});
      this.$router.push({ name: 'not-found' });
    },

    notAllowed(data) {
      this.isLoading = false;
      this.$notify({ type: "error", text: `${data.status} ${data.code}`});
    },

    forbiddenError(data) {
      this.isLoading = false;
      this.$notify({ type: "error", text: `${data.status} - Zugriff verweigert!`});
      this.$router.push({ name: 'forbidden' });
    },

    unauthorized(data) {
      document.location.href = '/login';
    },

    expired(data) {

    }
  },

};
