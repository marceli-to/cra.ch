<template>
<div>
  <form @submit.prevent="register()">
    
    <div v-if="hasErrors" class="alert alert--danger">
      <h3>Bitte Eingaben prüfen!</h3>
    </div>
    <div v-if="isDone" class="alert alert--success">
      <h3>Vielen Dank für Ihre Anmeldung!</h3>
    </div>

    <div class="form-group grid-cols-12">
      <div class="md:span-6">
        <input type="text" name="name" placeholder="Name"  v-model="form.name" :class="[errors.name ? 'is-invalid' : '']">
      </div>
      <div class="md:span-6">
        <input type="text" name="firstname" placeholder="Vorname" v-model="form.firstname" :class="[errors.firstname ? 'is-invalid' : '']">
      </div>
    </div>
    <div class="form-group grid-cols-12">
      <div class="md:span-6">
        <input type="text" name="address" placeholder="Adresse" v-model="form.address" :class="[errors.address ? 'is-invalid' : '']">
      </div>
      <div class="md:span-6">
        <div class="sm:grid-cols-12">
          <div class="span-4 md:span-3">
            <input type="text" name="zip" placeholder="PLZ" v-model="form.zip" :class="[errors.zip ? 'is-invalid' : '']">
          </div>
          <div class="span-8 md:span-9">
            <input type="text" name="city" placeholder="Wohnort" v-model="form.city" :class="[errors.city ? 'is-invalid' : '']">
          </div>
        </div>
      </div>
    </div>
    <div class="form-group grid-cols-12">
      <div class="md:span-6">
        <input type="text" name="phone" placeholder="Telefon" v-model="form.phone" :class="[errors.phone ? 'is-invalid' : '']">
      </div>
      <div class="md:span-6">
        <input type="email" name="email" placeholder="E-Mail" v-model="form.email" :class="[errors.email ? 'is-invalid' : '']">
      </div>
    </div>
    <div class="grid-cols-12">
      <div class="md:span-6">
        <div class="form-group">
          <div class="flex items-start">
            <input type="radio" id="member" name="member_type" required value="mitglied" v-model="form.type">
            <label for="member">Ich trete dem Cristina Rutz Architekten als Mitglied bei. Der Mitgliederbeitrag für das laufende Vereinsjahr von CHF 60.– wird in den nächsten Tagen auf das PostFinance Konto CH85 0900 0000 8400 4044 1 überwiesen.</label>
          </div>
        </div>
        <div class="form-group">
          <div class="flex items-start">
            <input type="radio" id="junior_member" name="member_type" required value="jungmitglied" v-model="form.type">
            <label for="junior_member">Ich trete dem Cristina Rutz Architekten als Jungmitglied (für Studierende und Personen in Ausbildung) bei und sende die Ausbildungsbestätigung an info@cristinarutz.ch. Der Mitgliederbeitrag für das laufende Vereinsjahr von CHF 30.– wird in den nächsten Tagen auf das PostFinance Konto CH85 0900 0000 8400 4044 1 überwiesen.</label>
          </div>
        </div>
      </div>
      <div class="md:span-6">
        <div class="form-group">
          <div class="flex items-start">
            <input type="radio" id="backer" name="member_type" required value="goenner" v-model="form.type">
            <label for="backer">Ich trete dem Cristina Rutz Architekten als Gönner:in bei. Der Beitrag für das laufende Vereinsjahr von CHF 600.– wird in den nächsten Tagen auf das PostFinance Konto CH85 0900 0000 8400 4044 1 überwiesen.</label>
          </div>
        </div>
        <div class="form-group">
          <div class="flex items-start">
            <input type="radio" id="sponsor" name="member_type" required value="sponsor" v-model="form.type">
            <label for="sponsor">Ich bin interessiert das Cristina Rutz Architekten als Sponsor:in mit jährlich CHF 2'750.– zu unterstützen. Bitte nehmt mit mir Kontakt auf.</label>
          </div>
        </div>
      </div>
    </div>
    <div>
      <input type="submit" value="Anmeldung Absenden" name="submit" class="btn-submit" v-if="!this.isLoading">
      <input type="submit" value="Daten werden übermittelt..." name="submit" class="btn-submit" disabled v-if="this.isLoading">
    </div>
  </form>
</div>
</template>
<script>
import ErrorHandling from "@member/mixins/ErrorHandling";

export default {

  mixins: [ErrorHandling],

  data() {
    return {

      form: {
        name: null,
        firstname: null,
        address: null,
        zip: null,
        city: null,
        phone: null,
        email: null,
        type: null,
      },

      errors: {
        name: false,
        firstname: false,
        address: false,
        zip: false,
        city: false,
        phone: false,
        email: false,
        type: false,
      },

      isLoading: false,
      isDone: false,
      hasErrors: false,

      errorMessage: '',
    };
  },

  methods: {

    register() {
      this.hasErrors = false;
      this.errorMessage = '';
      this.isLoading = true;
      this.axios.post(`/mitglied-werden`, this.form).then(response => {
        this.isLoading = false;
        this.isDone = true;
        this.reset();
      })
      .catch(error => {
        this.hasErrors = true;
        this.isLoading = false;
        const errorData = error.response.data.errors;
        if (errorData.name) {
          this.errors.name = true;
        }
        if (errorData.firstname) {
          this.errors.firstname = true;
        }
        if (errorData.address) {
          this.errors.address = true;
        }
        if (errorData.zip) {
          this.errors.zip = true;
        }
        if (errorData.city) {
          this.errors.city = true;
        }
        if (errorData.phone) {
          this.errors.phone = true;
        }
        if (errorData.email) {
          this.errors.email = true;
        }

        const form = document.querySelector('form');
        form.scrollIntoView({ block: 'start', behavior: 'smooth' });
      });
    },

    cancel() {
      this.reset();
    },

    reset() {
      this.errorMessage = '';
      this.hasErrors = false;

      this.form = {
        name: null,
        firstname: null,
        address: null,
        zip: null,
        city: null,
        phone: null,
        email: null,
        type: null,
      };

      this.errors = {
        name: false,
        firstname: false,
        address: false,
        zip: false,
        city: false,
        phone: false,
        email: false,
        type: false,
      };
      
    },
  },
}
</script>
