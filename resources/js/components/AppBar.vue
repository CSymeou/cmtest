<template>
  <v-app-bar app flat>
    <v-toolbar-title class="text-uppercase">
      Campaign monitor API test
    </v-toolbar-title>
    <v-spacer />
    <v-btn text @click="openDialog">
      New member
    </v-btn>
    <v-dialog
      v-model="dialog"
      max-width="640"
    >
      <v-card>
        <v-card-title class="headline">
          Add new member to mailing list
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
          >
            <v-row>
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="member.Name"
                  label="Name"
                  required
                  :rules="rules.nameRules"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
              >
                <v-text-field
                  v-model="member.EmailAddress"
                  label="Email"
                  required
                  :rules="rules.emailRules"
                ></v-text-field>
                <v-alert v-if="emailAlert" color="red lighten-2" dark>A user with this email address already exists.</v-alert>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="pink darken-1"
            text
            @click="dialog = false"
          >
            <strong>
              Cancel
            </strong>
          </v-btn>
          <v-btn
            color="pink darken-1"
            text
            :loading="loading"
            @click="handleStore"
          >
            <strong>
              Save
            </strong>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app-bar>
</template>

<script>
import axios from 'axios';

export default {
  name: "AppBar",
  props: ['emails'],
  data: () => ({
    dialog: false,
    emailAlert: false,
    loading: false,
    member: {
      Name: '',
      EmailAddress: ''
    },
    rules: {
      nameRules: [
        v => !!v || 'Name is required',
        v => v.length <= 255 || 'Name must be less than 10 characters',
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => v.length <= 255 || 'Email must be less than 10 characters',
        v => /.+@.+/.test(v) || 'E-mail must be valid',
      ],
    },
    valid: true
  }),
  methods: {
    openDialog(){
      this.dialog = true;
      this.emailAlert = false;
      this.member.Name = '';
      this.member.EmailAddress = '';
    },
    handleStore(){
      if(this.$refs.form.validate()){
        if(this.emails.includes(this.member.EmailAddress)){
          this.emailAlert = true;
        } else {
          this.loading = true;
          axios.post(
              '/members', 
              { Name: this.member.Name, EmailAddress: this.member.EmailAddress})
            .then(response => {
              this.dialog = false;
              this.loading = false;
              this.$emit('memberAdded', this.member)
            });
        }
      }
    }
  }
};
</script>