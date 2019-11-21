<template>
  <v-app>
  <div>
    <v-data-table
            :headers="headers"
            :items="beers"
            :options.sync="pagination"
            :server-items-length="totalBeers"
            :loading="loading"
            class="elevation-1"
    >
      <template v-slot:item.details="{ item }">
        <v-btn  icon color="green"
               @click.prevent="openDialogBeer(item)">
          <v-icon>edit</v-icon>
        </v-btn>
      </template>

    </v-data-table>
    <v-row justify="center">
      <v-dialog
              v-model="dialog"
              max-width="290"
      >
        <v-card>
          <v-card-title class="headline">{{ beer.name }}</v-card-title>

          <v-card-text>
            <v-img :src="beer.img" alt="Error"></v-img>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>


            <v-btn
                    color="green darken-1"
                    text
                    @click="dialog = false"
            >
              OK
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
  </v-app>
</template>
<script>
  import axios from 'axios'
  export default {
    data () {
      return {
        totalBeers: 0,
        dialog: false,
        beers: [],
        loading: true,
        pagination: {},
        options: {},
        beer: {
          name: null,
          img: null,
        },
        headers: [
          {
            text: 'Name',
            align: 'left',
            value: 'name',
          },
          { text: 'Brewer', value: 'brewer.name' },
          { text: 'Type', value: 'type' },
          { text: 'Country', value: 'country' },
          { text: 'Price per litre', value: 'price_per_litre' },
          { text: 'Details', value: 'details'}
        ],
      }
    },
    watch: {
      pagination: {
        handler () {
          this.getDataFromApi()
                  .then(data => {
                    this.beers = data.items
                    this.totalBeers = data.total
                  })
        },
        deep: true
      }
    },
    mounted () {
      this.getDataFromApi()
              .then(data => {
                this.beers = data.items
                this.totalBeers = data.total
              })
    },
    methods: {
      openDialogBeer(item)
      {
        console.log(item)
        this.beer.name = item.name;
        this.beer.img = item.img_url;
        this.dialog = true;
      },
      getDataFromApi () {
        this.loading = true
        return new Promise((resolve) => {
          const { sortBy, descending, page, rowsPerPage } = this.pagination
          this.getBeers(page, rowsPerPage, sortBy).then(list => {

            let items = list.items;
            const total = list.total_count;

            if (this.pagination.sortBy) {
              items = items.sort((a, b) => {
                const sortA = a[sortBy]
                const sortB = b[sortBy]

                if (descending) {
                  if (sortA < sortB) return 1
                  if (sortA > sortB) return -1
                  return 0
                } else {
                  if (sortA < sortB) return -1
                  if (sortA > sortB) return 1
                  return 0
                }
              })
            }

            setTimeout(() => {
              this.loading = false
              resolve({
                items,
                total
              })
            }, 1000)
          });

        })
      },
      getBeers (page, rowsPerPage, sortBy) {

        let params = {
          page: page,
          per_page: rowsPerPage,
          sortBy: sortBy
        };

        return new Promise( function(resolve) {
          axios.get('api/beers', {params}).then(response => {
            let data = response.data;
            resolve(data);
          })
        });


      },
    },
  }
</script>
