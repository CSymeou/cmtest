<template>
  <v-list>
    <v-list-item
      v-for="member in members"
      :key="member.id"
    >
      <v-list-item-avatar>
        <v-icon class="grey lighten-1 white--text">mdi-account</v-icon>
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title><strong>{{member.Name}}</strong> ({{member.EmailAddress}})</v-list-item-title>
      </v-list-item-content>
      <v-list-item-action>
        <v-btn icon>
          <v-icon color="grey lighten-1" @click="dialog = true; emailToDelete = member.EmailAddress">
            mdi-delete
          </v-icon>
        </v-btn>
      </v-list-item-action>
    </v-list-item>
    <v-dialog
      v-model="dialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">
          Remove member?
        </v-card-title>
        <v-card-text>
          Are you sure you want to remove this individual from the mailing list? This action cannot be reversed.
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
            @click="handleDeletion()"
          >
            <strong>
              Delete
            </strong>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-list>
</template>

<script>
import axios from 'axios';

export default {
  name: "MembersList",
  props: ['members'],
  data: () => ({
    dialog: false,
    emailToDelete: '',
    loading: false
  }),
  methods: {
    handleDeletion(){
      this.loading = true;
      axios.delete('/members', { data: {
          EmailAddress: this.emailToDelete
        }
      })
      .then(() => {
        this.loading = false;
        this.dialog = false;
        this.$emit('memberDeleted', this.emailToDelete);
      })
    },
  }
};
</script>