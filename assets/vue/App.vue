<template>
  <div>
    <v-data-table
            :headers="headers"
            :items="beers"
            :options.sync="pagination"
            :server-items-length="totalBeers"
            :loading="loading"
            class="elevation-1"
    ></v-data-table>
  </div>
</template>
<script>
  import axios from 'axios'
  export default {
    data () {
      return {
        totalBeers: 0,
        beers: [],
        loading: true,
        pagination: {},
        options: {},
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

        console.log(params)

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
