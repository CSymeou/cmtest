<template>
  <v-app>
    <app-bar :emails="memberEmails" @memberAdded="addMember"/>
    <v-main>
      <v-container>
        <v-card>
          <v-card-title class="headline" grey>
            Email list members
          </v-card-title>
          <v-card-subtitle grey>
            {{this.members.length}} members
          </v-card-subtitle>
          <v-card-text>
            <members-list :members="members" @memberDeleted="removeMember" />
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from 'axios'
import AppBar from './AppBar'
import MembersList from './MembersList'

export default {
  name: "App",
  components: {AppBar, MembersList},
  data: () => ({
    members: [],
  }),
  methods: {
    refreshMembers(){
      axios.get('/members').then(response => {
        this.members = response.data.subscribers['Results'];
      });
    },
    addMember(member){
      this.members = [...this.members, {
        Name: member.Name,
        EmailAddress: member.EmailAddress
      }]
    },
    removeMember(EmailAddress){
      this.members = this.members.filter(x => x.EmailAddress !== EmailAddress)
    }
  },
  mounted(){
    this.refreshMembers();
  },
  computed: {
    memberEmails() {
      return this.members.map((x) => x.EmailAddress)
    } 
  }
};
</script>